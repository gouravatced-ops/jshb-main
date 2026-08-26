<?php

namespace App\Http\Controllers\Shared;

use App\Models\SubDivision;
use App\Models\DocumentMaster;
use App\Models\AllotteeDocument;
use App\Models\DocumentRequest;
use App\Models\ApplicationDocument;
use App\Models\BypassRequest;
use App\Http\Requests\Application\ProcessActionRequest;
use App\Services\ApplicationService;
use App\Http\Requests\Application\UploadDocumentRequest;
use App\Http\Requests\Application\RequestDocumentFormRequest;
use App\Mail\DocumentRequestMail;
use App\Services\NotificationService;

use App\Http\Controllers\Controller;
use App\Models\Workflow;
use App\Models\Application;
use App\Models\WorkflowStep;
use App\Models\ApplicationMovement;
use App\Models\ApplicationAuditTrail;
use App\Models\ApplicationNote;
use App\Models\Allottee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\DocumentUploadTrait;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AllotteeSiteVerification;
use App\Models\AllotteeGeneratedDocument;
use App\Models\OtpLog;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    protected function getViewPrefix()
    {
        $roleSlug = auth()->user()->roleRelation->slug ?? '';
        
        $engineerRoles = [
            'dealing-assistant', 
            'division-officer', 
            'office-superintendent', 
            'estate-officer', 
            'executive-engineer', 
            'assistant-engineer', 
            'junior-engineer',
            'engineer'
        ];

        if (in_array($roleSlug, $engineerRoles)) {
            return 'engineer';
        }

        return match ($roleSlug) {
            'co-assistant' => 'coassistant',
            'operator' => 'operator',
            default => 'admin',
        };
    }
    use DocumentUploadTrait;

    public function index(Request $request)
    {
        $user = Auth::user();

        // Ensure only Dealing Assistant sees this specific list for now
        // Assuming user->roleRelation->slug is 'dealing-assistant'
        // If not, we can either return empty or show a different view.
        $workflowId = Workflow::where('application_type', 'allotment')->value('id') ?? 1;

        $targetUserId = $user->assistant_to_id ?? $user->id;
        $targetRoleId = $user->assistant_to_id ? User::find($user->assistant_to_id)?->role_id : $user->role_id;

        // 1. Get all pending application allottee IDs for this role
        $pendingAllotteeIds = Application::where('current_role_id', $targetRoleId)
            ->whereIn('status', ['pending', 'in_progress', 'forwarded'])
            ->pluck('allottee_id')
            ->unique()
            ->toArray();

        // 2. Filter these allottees by the user's division using the proper DB connection
        $query = Application::with('allottee')
            ->where('current_user_id', $targetUserId)
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
            $allotteeQuery = Allottee::query();

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
        $applications->getCollection()->transform(function ($app) {
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

        $subDivisions = SubDivision::where('status', 1)
            ->where('division_id', $user->division_id)
            ->get();

        return view($this->getViewPrefix() . '.applications.index', compact('applications', 'subDivisions'));
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
            $allotteeQuery = Allottee::query();

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

        $subDivisions = SubDivision::where('status', 1)
            ->where('division_id', $user->division_id)
            ->get();

        return view($this->getViewPrefix() . '.applications.history', compact('applications', 'subDivisions'));
    }

    public function show(Application $application)
    {
        $application->load([
            'allottee',
            'currentStep',
            'movements' => function ($q) {
                $q->orderBy('movement_date', 'asc');
            },
            'notes' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
            'notes.user',
            'notes.user.division',
            'documents',
            'workflow.requiredDocuments',
            'workflow.steps.role',
            'bypassRequests' => function ($q) {
                $q->where('status', 'pending');
            }
        ]);

        $documentMasters = DocumentMaster::where('status', 1)->orderBy('sort_order')->get();
        $allotteeDocuments = $application->allottee_id ? AllotteeDocument::where('allottee_id', $application->allottee_id)
            ->with('document')
            ->get() : collect();
        $documentRequests = DocumentRequest::where('application_id', $application->id)
            ->with(['documentMaster', 'requestedBy', 'uploadedDocument'])
            ->get();

        $requiredDocumentIds = $application->workflow && $application->workflow->requiredDocuments
            ? $application->workflow->requiredDocuments->pluck('id')->toArray()
            : [];

        $excludedDocIds = collect($allotteeDocuments->pluck('document_id'))
            ->merge($documentRequests->where('status', 'pending')->pluck('document_master_id'))
            ->unique()
            ->toArray();

        $isSiteVerificationCompleted = ApplicationDocument::where('application_id', $application->id)
            ->where('document_type', 'Site Verification')
            ->exists();

        return view($this->getViewPrefix() . '.applications.show', compact('application', 'documentMasters', 'allotteeDocuments', 'documentRequests', 'requiredDocumentIds', 'excludedDocIds', 'isSiteVerificationCompleted'));
    }

    public function actionForm(Application $application, $action_type)
    {
        $validActions = ['forward', 'send_back', 'reject', 'approve', 'add_note'];
        if (!in_array($action_type, $validActions)) {
            abort(404);
        }

        $approvedBypass = BypassRequest::where('application_id', $application->id)
            ->where('status', 'approved')
            ->where('is_used', false)
            ->orderBy('id', 'desc')
            ->first();

        $nextStep = null;
        $forwardOptions = [];
        $sendBackOptions = [];

        if ($action_type == 'forward' && $application->currentStep) {
            if ($approvedBypass) {
                $eligibleSteps = WorkflowStep::with('role')
                    ->where('id', $approvedBypass->target_step_id)
                    ->get();
            } else {
                $eligibleSteps = WorkflowStep::with('role')
                    ->where('workflow_id', $application->workflow_id)
                    ->whereIn('action_type', ['verify', 'approve'])
                    ->where('step_order', '>', $application->currentStep->step_order)
                    ->orderBy('step_order', 'asc')
                    ->get();
            }

            $divisionId = $application->allottee->division_id ?? null;

            foreach ($eligibleSteps as $step) {
                // Get engineers with this role_id and division_id
                $engineersQuery = User::on('adms_jshb')->where('role_id', $step->role_id)->where('status', 1);
                if ($divisionId) {
                    $engineersQuery->where(function ($q) use ($divisionId) {
                        $q->where('user_type', 'administration')
                            ->orWhere('division_id', $divisionId);
                    });
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
            $previousMovements = ApplicationMovement::with(['fromUser', 'fromStep', 'fromRole'])
                ->where('application_id', $application->id)
                ->where('action_type', 'forwarded')
                ->where('from_step_id', '!=', $application->currentStep->id)
                ->orderBy('movement_date', 'desc')
                ->get();

            $processedSteps = [];

            foreach ($previousMovements as $movement) {
                if ($movement->fromStep && $movement->fromUser) {
                    $stepId = $movement->from_step_id;
                    $userId = $movement->from_user_id;

                    if (!isset($processedSteps[$stepId])) {
                        $processedSteps[$stepId] = [
                            'step' => $movement->fromStep,
                            'engineers' => collect()
                        ];
                    }

                    if (!$processedSteps[$stepId]['engineers']->contains('id', $userId)) {
                        $processedSteps[$stepId]['engineers']->push($movement->fromUser);
                    }
                }
            }

            $sendBackOptions = array_values($processedSteps);

            usort($sendBackOptions, function ($a, $b) {
                return $b['step']->step_order <=> $a['step']->step_order;
            });
        }

        $application->load([
            'notes' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
            'notes.user.division',
            'notes.role'
        ]);

        $roles = Role::where('id', '!=', Auth::user()->role_id)->get();

        $isSiteVerificationStep = ($application->currentStep && $application->currentStep->action_type == 'site_verification');
        $isSiteVerificationCompleted = false;

        if ($isSiteVerificationStep) {
            $isSiteVerificationCompleted = $application->isSiteVerificationCompleted();
        }

        return view($this->getViewPrefix() . '.applications.actions.' . $action_type, compact('application', 'roles', 'nextStep', 'forwardOptions', 'sendBackOptions', 'isSiteVerificationStep', 'isSiteVerificationCompleted', 'approvedBypass'));
    }

    public function processAction(ProcessActionRequest $request, Application $application, ApplicationService $applicationService)
    {
        return $applicationService->processAction($application, $request, Auth::user());
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

        return redirect()->route($this->getViewPrefix() . '.applications.index')->with('success', 'Application workflow has been completely reset and started over.');
    }

    public function uploadDocument(UploadDocumentRequest $request, Application $application)
    {

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

        ApplicationDocument::create([
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
        $application->load(['notes' => function ($query) {
            $query->orderBy('id', 'asc');
        }, 'notes.user', 'notes.role', 'notes.user.division']);

        $localPath = str_replace('\\', '/', storage_path('app/public/'));
        foreach ($application->notes as $note) {
            if ($note->remarks) {
                $note->remarks = preg_replace('/https?:\/\/[^\/]+\/storage\//i', $localPath, $note->remarks);
            }
        }

        $pdf = \Mccarlosen\LaravelMpdf\Facades\LaravelMpdf::loadView($this->getViewPrefix() . '.applications.notes-pdf', compact('application'), [], [
            'auto_language_detection' => true,
            'temp_dir'         => storage_path('app/temp'),
            'custom_font_dir'  => public_path('font/'),
            'custom_font_data' => [
                'krutidev011' => [
                    'R'  => 'KrutiDev011.ttf',
                ],
                'notosansdevanagari' => [
                    'R' => 'NotoSansDevanagari.ttf',
                    'B' => 'NotoSansDevanagari-Bold.ttf',
                ]
            ]
        ]);

        return $pdf->stream('application_' . $application->application_no . '_notes.pdf');
    }
    public function requestDocument(RequestDocumentFormRequest $request)
    {

        $requestedDocNames = [];

        foreach ($request->document_master_ids as $docId) {
            $existingRequest = DocumentRequest::where('application_id', $request->application_id)
                ->where('document_master_id', $docId)
                ->where('status', 'pending')
                ->first();

            if ($existingRequest) {
                continue; // Skip already requested ones
            }

            DocumentRequest::create([
                'application_id' => $request->application_id,
                'allottee_id' => $request->allottee_id,
                'document_master_id' => $docId,
                'requested_by' => Auth::id(),
                'remarks' => $request->remarks,
                'expires_at' => now()->addDays(2),
                'status' => 'pending'
            ]);

            $documentMaster = DocumentMaster::find($docId);
            if ($documentMaster) {
                $requestedDocNames[] = $documentMaster->document_name;
            }
        }

        if (empty($requestedDocNames)) {
            return back()->with('error', 'All selected documents are already requested and pending.');
        }

        $allottee = Allottee::find($request->allottee_id);
        if ($allottee && $allottee->user_id) {
            $defaultMsg = 'Engineer has requested additional documents for your application. Please upload within 2 days.';
            $message = !empty(trim($request->remarks)) ? trim($request->remarks) : $defaultMsg;

            $docNamesStr = implode(', ', $requestedDocNames);
            $dueDate = now()->addDays(2)->format('d M Y, h:i A');
            $dashboardUrl = url('/'); // Link to allottee dashboard

            $customMailable = new DocumentRequestMail(
                $allottee->user->name ?? 'Allottee',
                $docNamesStr,
                $dueDate,
                $dashboardUrl,
                $message
            );

            app(NotificationService::class)->send([
                'user_id' => $allottee->user_id,
                'is_allottee' => true,
                'application_id' => $request->application_id,
                'notification_type' => 'document_request',
                'subject' => 'Document Request - Action Required',
                'message' => $message . ' (Documents: ' . $docNamesStr . ')',
                'send_email' => true,
                'send_sms' => true,
                'send_whatsapp' => true,
                'link' => '/dashboard',
                'mailable' => $customMailable
            ]);
        }

        return back()->with('success', 'Document requests sent successfully to the allottee.');
    }

}
