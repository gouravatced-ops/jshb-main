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
        
        // 1. Get all pending application allottee IDs for this role
        $pendingAllotteeIds = \App\Models\Application::where('current_role_id', $user->role_id)
            ->whereIn('status', ['pending', 'in_progress', 'forwarded'])
            ->pluck('allottee_id')
            ->unique()
            ->toArray();

        // 2. Filter these allottees by the user's division using the proper DB connection
        $validAllotteeIds = \App\Models\Allottee::whereIn('id', $pendingAllotteeIds)
            ->where('division_id', $user->division_id)
            ->pluck('id')
            ->toArray();

        // 3. Fetch applications matching these valid allottees
        $applications = \App\Models\Application::with('allottee')
            ->where('current_role_id', $user->role_id)
            ->whereIn('status', ['pending', 'in_progress', 'forwarded'])
            ->whereIn('allottee_id', $validAllotteeIds)
            ->select(
                'applications.id',
                'applications.application_no',
                'applications.application_type',
                'applications.allottee_id',
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
                DB::raw("(SELECT COUNT(id) FROM application_movements WHERE application_id = applications.id) as total_movements"),
                DB::raw("(SELECT remarks FROM application_notes WHERE application_id = applications.id ORDER BY created_at DESC LIMIT 1) as last_remark")
            )
            ->orderBy('applications.created_date', 'ASC')
            ->paginate(15);
            
        // Map allottee data so views don't break
        $applications->getCollection()->transform(function($app) {
            if ($app->allottee) {
                $app->prefix = $app->allottee->prefix;
                $app->allottee_name = $app->allottee->allottee_name;
                $app->allottee_middle_name = $app->allottee->allottee_middle_name;
                $app->allottee_surname = $app->allottee->allottee_surname;
                $app->allottee_prefix_hindi = $app->allottee->allottee_prefix_hindi;
                $app->allottee_name_hindi = $app->allottee->allottee_name_hindi;
                $app->allottee_middle_hindi = $app->allottee->allottee_middle_hindi;
                $app->allottee_surname_hindi = $app->allottee->allottee_surname_hindi;
                $app->property_number = $app->allottee->property_number;
                $app->allotment_no = $app->allottee->allotment_no;
            }
            return $app;
        });

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
