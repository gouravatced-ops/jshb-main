<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use App\Models\Workflow;
use App\Models\Application;
use App\Models\WorkflowStep;
use App\Models\ApplicationMovement;
use App\Models\ApplicationNote;
use App\Models\Allottee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\DocumentUploadTrait;

class ApplicationController extends Controller
{
    use DocumentUploadTrait;

    public function index(Request $request)
    {
        $user = Auth::user();

        // Ensure only Dealing Assistant sees this specific list for now
        // Assuming user->roleRelation->slug is 'dealing-assistant'
        // If not, we can either return empty or show a different view.
        $workflowId = Workflow::where('application_type', 'allotment')->value('id') ?? 1;
        
        // 1. Get all pending application allottee IDs for this role
        $pendingAllotteeIds = Application::where('current_role_id', $user->role_id)
            ->whereIn('status', ['pending', 'in_progress', 'forwarded'])
            ->pluck('allottee_id')
            ->unique()
            ->toArray();

        // 2. Filter these allottees by the user's division using the proper DB connection
        $query = Application::with('allottee')
            ->where('current_user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'approved')
            ->where('status', '!=', 'rejected');

        // Apply Filters
        if ($request->filled('application_no')) {
            $query->where('application_no', 'like', '%' . $request->application_no . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('created_date_from')) {
            $query->whereDate('created_date', '>=', $request->created_date_from);
        }
        if ($request->filled('created_date_to')) {
            $query->whereDate('created_date', '<=', $request->created_date_to);
        }
        if ($request->filled('property_number') || $request->filled('sub_division_id')) {
            $allotteeQuery = \App\Models\Allottee::query();
            
            if ($request->filled('property_number')) {
                $allotteeQuery->where('property_number', 'like', '%' . $request->property_number . '%');
            }
            if ($request->filled('sub_division_id')) {
                $allotteeQuery->where('subdivision_id', $request->sub_division_id);
            }
            
            $matchingAllotteeIds = $allotteeQuery->pluck('id')->toArray();
            $query->whereIn('allottee_id', $matchingAllotteeIds);
        }

        $applications = $query->select(
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
            ->paginate(15)
            ->appends($request->all());
            
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

        $subDivisions = \App\Models\SubDivision::where('status', 1)
            ->where('division_id', $user->division_id)
            ->get();

        return view('engineer.applications.index', compact('applications', 'subDivisions'));
    }

    public function history(Request $request)
    {
        $user = Auth::user();

        // Fetch application IDs that the user has interacted with (took action on)
        $historyApplicationIds = ApplicationMovement::where('from_user_id', $user->id)
            ->pluck('application_id')
            ->unique()
            ->toArray();

        $query = Application::with('allottee')
            ->whereIn('id', $historyApplicationIds);

        // Apply Filters
        if ($request->filled('application_no')) {
            $query->where('application_no', 'like', '%' . $request->application_no . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('created_date_from')) {
            $query->whereDate('created_date', '>=', $request->created_date_from);
        }
        if ($request->filled('created_date_to')) {
            $query->whereDate('created_date', '<=', $request->created_date_to);
        }
        if ($request->filled('property_number') || $request->filled('sub_division_id')) {
            $allotteeQuery = \App\Models\Allottee::query();
            
            if ($request->filled('property_number')) {
                $allotteeQuery->where('property_number', 'like', '%' . $request->property_number . '%');
            }
            if ($request->filled('sub_division_id')) {
                $allotteeQuery->where('subdivision_id', $request->sub_division_id);
            }
            
            $matchingAllotteeIds = $allotteeQuery->pluck('id')->toArray();
            $query->whereIn('allottee_id', $matchingAllotteeIds);
        }

        $applications = $query->select(
                'applications.id',
                'applications.application_no',
                'applications.application_type',
                'applications.allottee_id',
                'applications.status',
                'applications.priority',
                'applications.created_date',
                DB::raw("DATE_FORMAT(applications.created_date, '%d-%b-%Y %H:%i') as created_date_formatted")
            )
            ->orderBy('applications.updated_at', 'desc')
            ->paginate(15)
            ->appends($request->all());

        $subDivisions = \App\Models\SubDivision::where('status', 1)
            ->where('division_id', $user->division_id)
            ->get();

        return view('engineer.applications.history', compact('applications', 'subDivisions'));
    }

    public function show(Application $application)
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
            'notes.user',
            'documents'
        ]);

        return view('engineer.applications.show', compact('application'));
    }

    public function actionForm(Application $application, $action_type)
    {
        $validActions = ['forward', 'send_back', 'reject', 'approve', 'add_note'];
        if(!in_array($action_type, $validActions)) {
            abort(404);
        }

        $nextStep = null;
        $forwardOptions = [];
        $sendBackOptions = [];
        
        if ($action_type == 'forward' && $application->currentStep) {
            $eligibleSteps = WorkflowStep::with('role')
                ->where('workflow_id', $application->workflow_id)
                ->whereIn('action_type', ['verify', 'approve'])
                ->where('step_order', '>', $application->currentStep->step_order)
                ->orderBy('step_order', 'asc')
                ->get();

            $divisionId = $application->allottee->division_id ?? null;

            foreach($eligibleSteps as $step) {
                // Get engineers with this role_id and division_id
                $engineersQuery = User::on('adms_jshb')->where('role_id', $step->role_id)->where('status', 1);
                if ($divisionId) {
                    $engineersQuery->where('division_id', $divisionId);
                }
                $engineers = $engineersQuery->get();

                if ($engineers->count() > 0) {
                    $forwardOptions[] = [
                        'step' => $step,
                        'engineers' => $engineers
                    ];
                }
            }
        } elseif ($action_type == 'send_back' && $application->currentStep) {
            $eligibleSteps = WorkflowStep::with('role')
                ->where('workflow_id', $application->workflow_id)
                ->where('step_order', '<', $application->currentStep->step_order)
                ->orderBy('step_order', 'desc')
                ->get();
            
            $divisionId = $application->allottee->division_id ?? null;

            foreach($eligibleSteps as $step) {
                // Get engineers with this role_id and division_id
                $engineersQuery = User::on('adms_jshb')->where('role_id', $step->role_id)->where('status', 1);
                if ($divisionId) {
                    $engineersQuery->where('division_id', $divisionId);
                }
                $engineers = $engineersQuery->get();

                if ($engineers->count() > 0) {
                    $sendBackOptions[] = [
                        'step' => $step,
                        'engineers' => $engineers
                    ];
                }
            }
        }

        $application->load([
            'notes' => function($q) {
                $q->orderBy('created_at', 'desc');
            },
            'notes.user.division',
            'notes.role'
        ]);

        $roles = Role::where('id', '!=', Auth::user()->role_id)->get();

        return view('engineer.applications.actions.' . $action_type, compact('application', 'roles', 'nextStep', 'forwardOptions', 'sendBackOptions'));
    }

    public function processAction(Request $request, Application $application)
    {
        $request->validate([
            'action_type' => 'required|string',
            'remarks' => 'required|string|max:50000'
        ]);

        $user = Auth::user();

        if ($request->action_type == 'add_note') {
            ApplicationNote::create([
                'application_id' => $application->id,
                'user_id' => $user->id,
                'role_id' => $user->role_id,
                'remarks' => $request->remarks,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return redirect()->route('engineer.applications.show', $application)
                ->with('success', 'Note added successfully.');
        }

        $nextStep = null;
        $newStatus = $application->status;
        $targetUser = null;

        if ($request->action_type == 'forward') {
            $request->validate([
                'forward_to_user' => 'required'
            ]);
            
            $parts = explode('|', $request->forward_to_user);
            $targetUserId = $parts[0] ?? null;
            $nextStepId = $parts[1] ?? null;
            
            $nextStep = WorkflowStep::find($nextStepId);
            $targetUser = User::on('adms_jshb')->find($targetUserId);
            $newStatus = 'forwarded';
        } elseif ($request->action_type == 'send_back') {
            $request->validate([
                'send_back_to_user' => 'required'
            ]);
            
            $parts = explode('|', $request->send_back_to_user);
            $targetUserId = $parts[0] ?? null;
            $nextStepId = $parts[1] ?? null;
            
            $nextStep = WorkflowStep::find($nextStepId);
            $targetUser = User::on('adms_jshb')->find($targetUserId);
            $newStatus = 'in_progress';
        } elseif ($request->action_type == 'reject') {
            $newStatus = 'rejected';
        } elseif ($request->action_type == 'approve') {
            $nextStep = WorkflowStep::where('workflow_id', $application->workflow_id)
                ->where('step_order', '>', $application->currentStep->step_order)
                ->orderBy('step_order', 'asc')
                ->first();
            $newStatus = $nextStep ? 'approved' : 'completed';
        }

        $previousStepId = $application->current_step_id;
        $previousRoleId = $application->current_role_id;

        if ($nextStep) {
            $application->current_step_id = $nextStep->id;
            $application->current_role_id = $nextStep->role_id;
            
            // If it's a forward or send_back action, we already explicitly know the target user
            if (!in_array($request->action_type, ['forward', 'send_back'])) {
                // Find Target User based on division for other actions (approve)
                $divisionId = $application->allottee->division_id ?? null;
                $targetUserQuery = User::on('adms_jshb')->where('role_id', $nextStep->role_id)->where('status', 1);
    
                if ($divisionId) {
                    $targetUser = (clone $targetUserQuery)->where('division_id', $divisionId)->first();
                    if (!$targetUser) {
                        $targetUser = $targetUserQuery->first();
                    }
                } else {
                    $targetUser = $targetUserQuery->first();
                }
            }
                
            $application->current_user_id = $targetUser ? $targetUser->id : null;
        }
        $application->status = $newStatus;
        $application->save();

        if ($newStatus === 'completed') {
            try {
                $allottee = $application->allottee;
                
                // 1. Generate PDF
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.allottee.letters.templates.allotment-pdf', compact('allottee'))
                    ->setOptions([
                        'isRemoteEnabled' => false,
                        'isHtml5ParserEnabled' => true,
                        'chroot' => [public_path(), storage_path(), base_path()]
                    ])
                    ->setPaper('a4', 'portrait');
                    
                $pdfContent = $pdf->output();
                $fileName = 'allotment_letter_' . ($allottee->allotment_no ?? $application->application_no) . '_' . time() . '.pdf';

                // 2. Upload to Document API
                $scheme = $allottee->scheme ?? null;
                $yyyy = date('Y');
                $mm = date('m');
                $dd = date('d');
                
                $extraData = [
                    'application_for' => $application->application_type ?? '',
                    'division_code' => $allottee->division->division_code ?? '',
                    'subdivision_code' => $allottee->subDivision->subdivision_code ?? '',
                    'property_category' => $allottee->propertyCategory->category_code ?? '',
                    'property_type' => $allottee->propertyType->type_code ?? '',
                    'property_income' => $allottee->quarterType->quarter_code ?? '',
                    'username' => $allottee->username ?? ''
                ];

                $uploadResult = $this->uploadContentToDocumentApi(
                    $pdfContent,
                    $fileName,
                    'ALLOTMENT_LETTER',
                    $scheme->scheme_code ?? 'SCH',
                    $allottee->property_number ?? 'PROP',
                    $yyyy,
                    $mm,
                    $dd,
                    $extraData
                );

                // 3. Save to application_documents
                \App\Models\ApplicationDocument::create([
                    'application_id' => $application->id,
                    'movement_id'    => null,
                    'document_type'  => 'ALLOTMENT_LETTER',
                    'document_name'  => 'Allotment Letter (Auto Generated)',
                    'file_name'      => $uploadResult['file_name'],
                    'file_path'      => $uploadResult['file_path'],
                    'file_size'      => strlen($pdfContent),
                    'file_mime_type' => 'application/pdf',
                    'uploaded_by'    => $user->id,
                    'uploader_type'  => 'System',
                    'uploaded_at'    => now(),
                ]);

                // 4. Save to allottee_generated_documents
                \App\Models\AllotteeGeneratedDocument::create([
                    'allottee_id'    => $allottee->id,
                    'document_name'  => 'Allotment Letter',
                    'document_type'  => 'allotment-letter',
                    'file_name'      => $uploadResult['file_name'],
                    'file_path'      => $uploadResult['file_path'],
                    'generated_by'   => $user->id,
                    'generated_at'   => now(),
                    'issue_date'     => now()->format('Y-m-d'),
                    'document_number'=> $allottee->allotment_no ?? $application->application_no
                ]);

            } catch (\Exception $e) {
                Log::error("Failed to auto-generate allotment PDF: " . $e->getMessage());
            }
        }

        // Save the noting/remarks
        ApplicationNote::create([
            'application_id' => $application->id,
            'user_id' => $user->id,
            'role_id' => $user->role_id,
            'remarks' => $request->remarks,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Map action type to DB ENUM values
        $actionEnumMap = [
            'forward' => 'forwarded',
            'send_back' => 'send_back',
            'reject' => 'rejected',
            'approve' => 'approved'
        ];
        
        $dbActionType = $actionEnumMap[$request->action_type] ?? 'forwarded';

        // Generate clean system remarks for the movement log
        $systemRemark = "Application " . str_replace('_', ' ', $dbActionType);
        if ($nextStep) {
            $nextRole = Role::find($nextStep->role_id);
            if ($nextRole) {
                $systemRemark .= " to " . $nextRole->name;
            }
        }

        // Complete application movement tracking
        ApplicationMovement::create([
            'application_id' => $application->id,
            'from_user_id' => $user->id,
            'to_user_id' => $targetUser ? $targetUser->id : null,
            'from_role_id' => $previousRoleId,
            'from_step_id' => $previousStepId,
            'to_role_id' => $nextStep ? $nextStep->role_id : null,
            'to_step_id' => $nextStep ? $nextStep->id : null,
            'action_type' => $dbActionType,
            'remarks' => $systemRemark,
            'movement_date' => now(),
            'status' => 'completed',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Log the action details
        Log::info("Application Action Processed", [
            'application_id' => $application->id,
            'action_type' => $request->action_type,
            'previous_step_id' => $previousStepId,
            'next_step_id' => $nextStep ? $nextStep->id : null,
            'from_user_id' => $user->id,
            'target_user_id' => $targetUser ? $targetUser->id : null,
            'target_user_name' => $targetUser ? $targetUser->name : 'N/A'
        ]);

        return redirect()->route('engineer.applications.show', $application)
                         ->with('success', 'Office noting and action recorded successfully.');
    }

    public function resetWorkflow(Request $request, Application $application)
    {
        // 1. Get the first workflow step
        $firstStep = WorkflowStep::where('workflow_id', $application->workflow_id)
            ->orderBy('step_order', 'asc')
            ->first();

        if (!$firstStep) {
            return redirect()->back()->with('error', 'Workflow steps not found. Cannot reset.');
        }

        // 2. Delete all application movements
        ApplicationMovement::where('application_id', $application->id)->delete();

        // 3. Delete all application notes
        ApplicationNote::where('application_id', $application->id)->delete();

        // 4. Update the application to pending and assign to the first role/step
        $application->status = 'pending';
        $application->current_step_id = $firstStep->id;
        $application->current_role_id = $firstStep->role_id;
        $application->save();

        return redirect()->route('engineer.applications.index')->with('success', 'Application workflow has been completely reset and started over.');
    }

    public function uploadDocument(Request $request, Application $application)
    {
        $request->validate([
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120' // 5MB max
        ]);

        $file = $request->file('document_file');
        
        $allottee = $application->allottee;
        $schemeCode = $allottee->scheme->scheme_code ?? 'SCH';
        $propertyNumber = $allottee->property_number ?? 'PROP';
        $yyyy = date('Y');
        $mm = date('m');
        $dd = date('d');

        $extraData = [
            'application_for' => $application->application_type ?? '',
            'division_code' => $allottee->division->division_code ?? '',
            'subdivision_code' => $allottee->subDivision->subdivision_code ?? '',
            'property_category' => $allottee->propertyCategory->category_code ?? '',
            'property_type' => $allottee->propertyType->type_code ?? '',
            'property_income' => $allottee->quarterType->quarter_code ?? '',
            'username' => $allottee->username ?? ''
        ];

        try {
            $uploadResult = $this->uploadToDocumentApi(
                $file,
                'APPLICATION',
                $schemeCode,
                $propertyNumber,
                $yyyy,
                $mm,
                $dd,
                null,
                $extraData
            );
            
            $path = $uploadResult['file_path'];
            $originalName = $uploadResult['file_name'];
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload document to Document Store: ' . $e->getMessage());
        }

        $userType = Auth::user()->user_type ?? 'engineer';
        $latestMovement = $application->movements()->latest()->first();

        \App\Models\ApplicationDocument::create([
            'application_id' => $application->id,
            'movement_id' => $latestMovement ? $latestMovement->id : null,
            'document_type' => $userType . '_upload',
            'document_name' => ucfirst($userType) . ' Upload',
            'file_name' => $originalName,
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'file_mime_type' => $file->getMimeType(),
            'uploaded_by' => Auth::id(),
            'uploader_type' => $userType,
            'uploaded_at' => now()
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function previewNotesPdf(Application $application)
    {
        $application->load(['notes' => function($query) {
            $query->orderBy('id', 'asc');
        }, 'notes.user', 'notes.role', 'notes.user.division']);

        $localPath = str_replace('\\', '/', storage_path('app/public/'));
        foreach($application->notes as $note) {
            if ($note->remarks) {
                $note->remarks = preg_replace('/https?:\/\/[^\/]+\/storage\//i', $localPath, $note->remarks);
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('engineer.applications.notes-pdf', compact('application'))
            ->setOptions([
                'isRemoteEnabled' => false, 
                'isHtml5ParserEnabled' => true,
                'chroot' => [public_path(), storage_path(), base_path()]
            ])
            ->setPaper('a4', 'portrait');

        return $pdf->stream('application_' . $application->application_no . '_notes.pdf');
    }
}
