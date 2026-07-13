<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use App\Models\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ensure only Dealing Assistant sees this specific list for now
        // Assuming user->roleRelation->slug is 'dealing-assistant'
        // If not, we can either return empty or show a different view.
        $workflowId = Workflow::where('application_type', 'allotment')->value('id') ?? 1;
        
        $admsDb = config('database.connections.adms_allottees.database');

        $applications = \App\Models\Application::join("$admsDb.allottees as al", 'applications.allottee_id', '=', 'al.id')
            ->leftJoin('application_movements as am', 'applications.id', '=', 'am.application_id')
            ->where('al.division_id', $user->division_id)
            ->where('applications.current_role_id', $user->role_id)
            ->whereIn('applications.status', ['pending', 'in_progress', 'forwarded'])
            ->select(
                'applications.id',
                'applications.application_no',
                'applications.application_type',
                'al.prefix',
                'al.allottee_name',
                'al.allottee_middle_name',
                'al.allottee_surname',
                'al.allottee_prefix_hindi',
                'al.allottee_name_hindi',
                'al.allottee_middle_hindi',
                'al.allottee_surname_hindi',
                'al.property_number',
                'al.allotment_no',
                'applications.created_date',
                DB::raw("DATE_FORMAT(applications.created_date, '%d-%b-%Y %H:%i') as created_date_formatted"),
                DB::raw("DATEDIFF(NOW(), applications.created_date) as days_pending"),
                DB::raw("
                    CASE 
                        WHEN DATEDIFF(NOW(), applications.created_date) <= 3 THEN 'Normal'
                        WHEN DATEDIFF(NOW(), applications.created_date) <= 7 THEN 'Urgent'
                        ELSE 'Overdue'
                    END as priority
                "),
                DB::raw("COUNT(am.id) as total_movements"),
                DB::raw("(SELECT remarks FROM application_notes WHERE application_id = applications.id ORDER BY created_at DESC LIMIT 1) as last_remark")
            )
            ->groupBy(
                'applications.id', 'applications.application_no', 'applications.application_type',
                'al.prefix', 'al.allottee_name', 'al.allottee_middle_name', 'al.allottee_surname',
                'al.allottee_prefix_hindi', 'al.allottee_name_hindi', 'al.allottee_middle_hindi', 'al.allottee_surname_hindi',
                'al.property_number', 'al.allotment_no', 'applications.created_date'
            )
            ->orderBy('applications.created_date', 'ASC')
            ->paginate(15);

        return view('engineer.applications.index', compact('applications'));
    }

    public function show(\App\Models\Application $application)
    {
        $application->load([
            'allottee',
            'currentStep',
            'movements' => function($q) {
                $q->orderBy('movement_date', 'asc');
            },
            'notes' => function($q) {
                $q->orderBy('created_at', 'desc');
            },
            'notes.createdBy',
            'documents'
        ]);

        return view('engineer.applications.show', compact('application'));
    }

    public function actionForm(\App\Models\Application $application, $action_type)
    {
        $validActions = ['forward', 'send_back', 'reject', 'verify', 'approve', 'add_note'];
        if(!in_array($action_type, $validActions)) {
            abort(404);
        }

        $nextStep = null;
        if ($action_type == 'forward' && $application->currentStep) {
            $nextStep = \App\Models\WorkflowStep::with('role')
                ->where('workflow_id', $application->workflow_id)
                ->where('step_order', '>', $application->currentStep->step_order)
                ->orderBy('step_order', 'asc')
                ->first();
        }

        $roles = \App\Models\Role::where('id', '!=', Auth::user()->role_id)->get();

        return view('engineer.applications.actions.' . $action_type, compact('application', 'roles', 'nextStep'));
    }

    public function processAction(Request $request, \App\Models\Application $application)
    {
        $request->validate([
            'action_type' => 'required|string',
            'remarks' => 'required|string|max:1000'
        ]);

        $user = Auth::user();

        $nextStep = null;
        $newStatus = $application->status;

        if ($request->action_type == 'forward') {
            $nextStep = \App\Models\WorkflowStep::where('workflow_id', $application->workflow_id)
                ->where('step_order', '>', $application->currentStep->step_order)
                ->orderBy('step_order', 'asc')
                ->first();
            $newStatus = 'forwarded';
        } elseif ($request->action_type == 'send_back') {
            $nextStep = \App\Models\WorkflowStep::where('workflow_id', $application->workflow_id)
                ->where('step_order', '<', $application->currentStep->step_order)
                ->orderBy('step_order', 'desc')
                ->first();
            $newStatus = 'in_progress';
        } elseif ($request->action_type == 'reject') {
            $newStatus = 'rejected';
        } elseif ($request->action_type == 'approve') {
            $nextStep = \App\Models\WorkflowStep::where('workflow_id', $application->workflow_id)
                ->where('step_order', '>', $application->currentStep->step_order)
                ->orderBy('step_order', 'asc')
                ->first();
            $newStatus = $nextStep ? 'approved' : 'completed';
        }

        if ($nextStep) {
            $application->current_step_id = $nextStep->id;
            $application->current_role_id = $nextStep->role_id;
        }
        $application->status = $newStatus;
        $application->save();

        // Save the noting/remarks
        \App\Models\ApplicationNote::create([
            'application_id' => $application->id,
            'user_id' => $user->id,
            'remarks' => $request->remarks,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Optional: you can insert an application_movement here to track that an action happened, 
        // even if it stays with the same user, or transition it to the next step.
        \App\Models\ApplicationMovement::create([
            'application_id' => $application->id,
            'from_user_id' => $user->id,
            'from_role_id' => $user->role_id,
            'action_type' => $request->action_type,
            'remarks' => $request->remarks,
            'movement_date' => now(),
            'status' => 'completed'
        ]);

        return redirect()->route('engineer.applications.show', $application)
                         ->with('success', 'Office noting and action recorded successfully.');
    }
}
