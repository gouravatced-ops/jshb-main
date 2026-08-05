<?php

namespace App\Http\Controllers\Engineer;

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
            'workflow.steps.role'
        ]);

        $documentMasters = \App\Models\DocumentMaster::where('status', 1)->orderBy('sort_order')->get();
        $allotteeDocuments = $application->allottee_id ? \App\Models\AllotteeDocument::where('allottee_id', $application->allottee_id)
            ->with('document')
            ->get() : collect();
        $documentRequests = \App\Models\DocumentRequest::where('application_id', $application->id)
            ->with(['documentMaster', 'requestedBy', 'uploadedDocument'])
            ->get();

        $requiredDocumentIds = $application->workflow && $application->workflow->requiredDocuments
            ? $application->workflow->requiredDocuments->pluck('id')->toArray()
            : [];

        $excludedDocIds = collect($allotteeDocuments->pluck('document_id'))
            ->merge($documentRequests->where('status', 'pending')->pluck('document_master_id'))
            ->unique()
            ->toArray();

        $isSiteVerificationCompleted = \App\Models\ApplicationDocument::where('application_id', $application->id)
            ->where('document_type', 'Site Verification')
            ->exists();

        return view('engineer.applications.show', compact('application', 'documentMasters', 'allotteeDocuments', 'documentRequests', 'requiredDocumentIds', 'excludedDocIds', 'isSiteVerificationCompleted'));
    }

    public function actionForm(Application $application, $action_type)
    {
        $validActions = ['forward', 'send_back', 'reject', 'approve', 'add_note'];
        if (!in_array($action_type, $validActions)) {
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
            $isSiteVerificationCompleted = \App\Models\ApplicationDocument::where('application_id', $application->id)
                ->where('document_type', 'Site Verification')
                ->exists();
        }

        return view('engineer.applications.actions.' . $action_type, compact('application', 'roles', 'nextStep', 'forwardOptions', 'sendBackOptions', 'isSiteVerificationStep', 'isSiteVerificationCompleted'));
    }

    public function processAction(Request $request, Application $application)
    {
        $request->validate([
            'action_type' => 'required|string',
            'remarks' => 'required|string'
        ]);

        if ($request->action_type == 'forward' && $application->currentStep && $application->currentStep->action_type == 'site_verification') {
            $isSiteVerificationCompleted = \App\Models\ApplicationDocument::where('application_id', $application->id)
                ->where('document_type', 'Site Verification')
                ->exists();
            if (!$isSiteVerificationCompleted) {
                return redirect()->back()->with('error', 'Site Verification is pending. Please complete it from the Site Verification tab before forwarding.');
            }
        }

        $user = Auth::user();

        if ($request->action_type == 'approve') {
            $request->validate([
                'internal_password' => 'required'
            ]);

            if (!Hash::check($request->internal_password, $user->internal_password)) {
                return back()->with('error', 'Incorrect internal password. Approval failed.');
            }
        }

        if ($request->action_type == 'add_note') {
            ApplicationNote::create([
                'application_id' => $application->id,
                'user_id' => $user->id,
                'role_id' => $user->role_id,
                'note_type' => 'user_note',
                'remarks' => $request->remarks,
                'font_family' => $request->font_family ?? 'english',
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

        // Determine if we should generate the document
        $shouldGenerateDocument = ($newStatus === 'completed');

        // If it's an agreement application and was just approved by the MD (Role 4), generate the document even if not fully completed yet
        $isMDApproval = ($previousRoleId == 4 && $request->action_type == 'approve');
        if ($application->application_type === 'agreement' && $isMDApproval) {
            $shouldGenerateDocument = true;
            \Illuminate\Support\Facades\Log::info("Agreement Letter generation triggered by MD approval for Application ID: " . $application->id);
        }

        if ($shouldGenerateDocument) {
            if ($application->application_type === 'possession') {
                \Illuminate\Support\Facades\Log::info("Processing Possession approval for Application ID: {$application->id}. Moving Site Verification docs.");
                $allottee = $application->allottee;

                // Find Site Verification documents
                $docsToMove = \App\Models\ApplicationDocument::where('application_id', $application->id)
                    ->whereIn('document_type', ['Site Verification Map', 'Site Verification'])
                    ->get();

                foreach ($docsToMove as $doc) {
                    $isMap = (stripos($doc->document_type, 'map') !== false);
                    $newDocType = $isMap ? 'approved-site-verification-map' : 'approved-site-verification-pdf';
                    $newDocName = $isMap ? 'Approved Site Verification Map' : 'Approved Site Verification PDF';

                    \App\Models\AllotteeGeneratedDocument::create([
                        'allottee_id'    => $allottee->id,
                        'document_name'  => $newDocName,
                        'document_type'  => $newDocType,
                        'file_name'      => $doc->file_name,
                        'file_path'      => $doc->file_path,
                        'generated_by'   => $user->id,
                        'generated_at'   => now(),
                        'issue_date'     => now()->format('Y-m-d'),
                        'document_number' => $application->application_no
                    ]);

                    // Delete the original row
                    // $doc->delete();
                }
            }

            try {
                \Illuminate\Support\Facades\Log::info("Starting document generation for Application ID: {$application->id}, Type: {$application->application_type}");
                $allottee = $application->allottee;

                // Determine template and document info based on application type
                $isAgreement = ($application->application_type === 'agreement');
                $isPossession = ($application->application_type === 'possession');

                if ($isAgreement) {
                    $pdfTemplate = 'admin.allottee.letters.templates.agreement-pdf';
                    $documentType = 'agreement-letter';
                    $documentName = 'Agreement Letter';
                    $dbDocType = 'AGREEMENT_LETTER';
                    $docPrefix = 'agreement_letter_';
                } elseif ($isPossession) {
                    $pdfTemplate = 'admin.allottee.letters.templates.possession-pdf';
                    $documentType = 'possession-letter';
                    $documentName = 'Possession Letter';
                    $dbDocType = 'POSSESSION_LETTER';
                    $docPrefix = 'possession_letter_';
                } else {
                    $pdfTemplate = 'admin.allottee.letters.templates.allotment-pdf';
                    $documentType = 'allotment-letter';
                    $documentName = 'Allotment Letter';
                    $dbDocType = 'ALLOTMENT_LETTER';
                    $docPrefix = 'allotment_letter_';
                }


                // 1. Generate PDF
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($pdfTemplate, compact('allottee'))
                    ->setOptions([
                        'isRemoteEnabled' => false,
                        'isHtml5ParserEnabled' => true,
                        'chroot' => [public_path(), storage_path(), base_path()]
                    ])
                    ->setPaper('a4', 'portrait');

                $pdfContent = $pdf->output();
                $allotmentNo = $allottee->allotment_no ?? $application->application_no;
                $safeAllotmentNo = str_replace(['/', '\\'], '-', $allotmentNo);
                $fileName = $docPrefix . $safeAllotmentNo . '_' . time() . '.pdf';

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
                    'FINAL',
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
                    'document_type'  => $dbDocType,
                    'document_name'  => $documentName . ' (Auto Generated)',
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
                    'document_name'  => $documentName,
                    'document_type'  => $documentType,
                    'file_name'      => $uploadResult['file_name'],
                    'file_path'      => $uploadResult['file_path'],
                    'generated_by'   => $user->id,
                    'generated_at'   => now(),
                    'issue_date'     => now()->format('Y-m-d'),
                    'document_number' => $allottee->allotment_no ?? $application->application_no
                ]);

                if ($isPossession) {
                    \Illuminate\Support\Facades\Log::info("Document generation complete for Possession. Completing step and unlocking next.");

                    // Complete the step in allottee process steps if required
                    // For possession, usually menu_key is allotment-possession-letter, but wait, from the earlier code it seems to be allotment/site-verification or allotment/possession-letter
                    $currentStep = \App\Models\AllotteeProcessStep::where([
                        'allottee_id' => $allottee->id,
                        'menu_key' => 'allotment',
                        'sub_menu_key' => 'site-verification'
                    ])->first();

                    if ($currentStep) {
                        \App\Models\AllotteeProcessStep::completeStep(
                            $allottee->id,
                            'allotment',
                            $currentStep->sub_menu_key,
                            $user->id
                        );
                        \App\Models\AllotteeProcessStep::unlockNextStep($allottee->id, $currentStep->step_no);
                    }
                } elseif ($isAgreement) {
                    \Illuminate\Support\Facades\Log::info("Document generation complete for Agreement. Completing step and unlocking next.");

                    // Mark Agreement step as completed
                    \App\Models\AllotteeProcessStep::completeStep(
                        $allottee->id,
                        'allotment',
                        'agreement-document-letter',
                        $user->id
                    );

                    // Unlock the next step
                    $currentStep = \App\Models\AllotteeProcessStep::where([
                        'allottee_id' => $allottee->id,
                        'menu_key' => 'allotment',
                        'sub_menu_key' => 'agreement-document-letter'
                    ])->first();

                    if ($currentStep) {
                        \App\Models\AllotteeProcessStep::unlockNextStep($allottee->id, $currentStep->step_no);
                    }
                } else {
                    \Illuminate\Support\Facades\Log::info("Document generation complete. Next step unlocked for allotment (Payment Order).");

                    // 5. Mark Allottee Process Step as completed
                    \App\Models\AllotteeProcessStep::completeStep(
                        $allottee->id,
                        'allotment',
                        'generate-allotment',
                        $user->id
                    );

                    // 6. Generate 15% Allotment Payment Order
                    $finance = $allottee->scheme->schemeFinance ?? null;
                    $propertyAmount = $finance ? (float) ($finance->property_total_cost ?? 0) : 0;
                    $allotmentPercentage = $finance ? (float) ($finance->allotment_percentage ?? 15) : 15;
                    $baseAmount = $finance ? (float) ($finance->allotment_amount ?? 0) : 0;

                    if ($baseAmount == 0 && $propertyAmount > 0) {
                        $baseAmount = ($propertyAmount * $allotmentPercentage) / 100;
                    }

                    \App\Models\AllotteePaymentOrder::updateOrCreate(
                        [
                            'allottee_id' => $allottee->id,
                            'order_type'  => 'allotment',
                        ],
                        [
                            'order_no'         => \App\Models\AllotteePaymentOrder::generateOrderNo('ODR-ALT'),
                            'title'            => "{$allotmentPercentage}% Allotment Payment Order",
                            'property_amount'  => $propertyAmount,
                            'percentage'       => $allotmentPercentage,
                            'base_amount'      => $baseAmount,
                            'penalty_amount'   => 0,
                            'admin_charge'     => 0,
                            'total_payable'    => $baseAmount,
                            'paid_amount'      => 0,
                            'remaining_amount' => $baseAmount,
                            'due_date'         => now()->addDays(30)->format('Y-m-d'),
                            'issued_at'        => now(),
                            'order_status'     => 'issued',
                            'remarks'          => 'Auto generated ' . $allotmentPercentage . '% allotment payment order',
                            'created_by'       => $user->id,
                        ]
                    );


                    // Unlock the next step (15% Demand Note) assuming it's the next logical step
                    // Find the step number for 'generate-allotment' to unlock the next one
                    $currentStep = \App\Models\AllotteeProcessStep::where([
                        'allottee_id' => $allottee->id,
                        'menu_key' => 'allotment',
                        'sub_menu_key' => 'generate-allotment'
                    ])->first();

                    if ($currentStep) {
                        \App\Models\AllotteeProcessStep::unlockNextStep($allottee->id, $currentStep->step_no);
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to auto-generate allotment PDF: " . $e->getMessage());
            }
        }

        $targetUserId = $user->assistant_to_id ?? $user->id;
        $targetRoleId = $user->assistant_to_id ? User::find($user->assistant_to_id)?->role_id : $user->role_id;

        // Save the noting/remarks
        ApplicationNote::create([
            'application_id' => $application->id,
            'user_id' => $targetUserId,
            'role_id' => $targetRoleId,
            'remarks' => $request->remarks,
            'font_family' => $request->font_family ?? 'english',
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

        if ($user->assistant_to_id) {
            $systemRemark .= " (Approved by MD, Operation done by Co-Assistant: {$user->name})";
        }

        // Complete application movement tracking
        $movement = ApplicationMovement::create([
            'application_id' => $application->id,
            'from_user_id' => $targetUserId,
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

        // Link movement ID to Site Verification document if applicable
        if ($request->action_type == 'forward' && $previousStepId) {
            $prevStep = \App\Models\WorkflowStep::find($previousStepId);
            if ($prevStep && $prevStep->action_type == 'site_verification') {
                $siteVerfDocs = \App\Models\ApplicationDocument::where('application_id', $application->id)
                    ->whereIn('document_type', ['Site Verification', 'Site Verification Map'])
                    ->whereNull('movement_id')
                    ->get();
                foreach ($siteVerfDocs as $doc) {
                    $doc->update(['movement_id' => $movement->id]);
                }
            }
        }

        // Integrate ApplicationAuditTrail for movement
        ApplicationAuditTrail::create([
            'application_id' => $application->id,
            'user_id' => $targetUserId,
            'role_id' => $previousRoleId,
            'action' => 'movement_created',
            'module' => 'Application Workflow',
            'description' => $systemRemark,
            'old_data' => [
                'step_id' => $previousStepId,
                'role_id' => $previousRoleId,
            ],
            'new_data' => [
                'step_id' => $nextStep ? $nextStep->id : null,
                'role_id' => $nextStep ? $nextStep->role_id : null,
                'action_type' => $dbActionType,
            ],
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

        // Trigger Notification to target engineer if applicable
        if ($targetUser && in_array($request->action_type, ['forward', 'send_back'])) {
            $actionWord = $request->action_type == 'forward' ? 'forwarded' : 'sent back';
            $subject = "Application {$actionWord} to you: {$application->application_no}";
            $message = "An application ({$application->application_no}) has been {$actionWord} to you by {$user->name}.";

            $dashboardUrl = url('/login');

            $customMailable = new \App\Mail\ApplicationForwardedMail(
                $targetUser->name,
                $application->application_no,
                $user->name,
                $request->action_type,
                $dashboardUrl,
                $request->remarks
            );

            app(\App\Services\NotificationService::class)->send([
                'user_id' => $targetUser->id,
                'is_allottee' => false,
                'application_id' => $application->id,
                'notification_type' => 'application_movement',
                'subject' => $subject,
                'message' => $message,
                'send_email' => true,
                'send_sms' => false,
                'send_whatsapp' => false,
                'link' => '/login',
                'mailable' => $customMailable
            ]);
        }

        // Trigger Notification to Allottee and Estate Officer on Approve / Reject
        if (in_array($request->action_type, ['approve', 'reject'])) {
            $actionWord = $request->action_type == 'approve' ? 'approved' : 'rejected';
            $subject = "Application {$actionWord}: {$application->application_no}";

            if ($request->action_type == 'approve') {
                $message = "Your application ({$application->application_no}) has been approved and your Allotment Letter has been generated. Please log in to download your allotment letter.";
            } else {
                $message = "Your application ({$application->application_no}) has been rejected.";
            }

            $dashboardUrl = url('/login');

            // Mail to Allottee
            $allotteeUser = \App\Models\User::on('adms_allottees')->find($application->allottee->user_id);
            if ($allotteeUser) {
                $customMailableAllottee = new \App\Mail\ApplicationForwardedMail(
                    $allotteeUser->name ?? 'Allottee',
                    $application->application_no,
                    $user->name,
                    $request->action_type,
                    $dashboardUrl,
                    '', // Remarks are hidden for approve/reject
                    $message // Pass custom message
                );

                app(\App\Services\NotificationService::class)->send([
                    'user_id' => $allotteeUser->id,
                    'is_allottee' => true,
                    'application_id' => $application->id,
                    'notification_type' => 'application_movement',
                    'subject' => $subject,
                    'message' => $message,
                    'send_email' => true,
                    'send_sms' => true,
                    'send_whatsapp' => true,
                    'link' => '/login',
                    'mailable' => $customMailableAllottee
                ]);
            }

            // Mail to Estate Officer
            $divisionId = $application->allottee->division_id ?? null;
            $estateOfficerRole = \App\Models\Role::where('slug', 'estate-officer')->first();
            if ($estateOfficerRole && $divisionId) {
                $estateOfficer = User::on('adms_jshb')
                    ->where('role_id', $estateOfficerRole->id)
                    ->where('division_id', $divisionId)
                    ->where('status', 1)
                    ->first();

                if ($estateOfficer) {
                    $customMailableEstate = new \App\Mail\ApplicationForwardedMail(
                        $estateOfficer->name,
                        $application->application_no,
                        $user->name,
                        $request->action_type,
                        $dashboardUrl,
                        '', // Remarks are hidden for approve/reject
                        $message // Pass custom message
                    );

                    app(\App\Services\NotificationService::class)->send([
                        'user_id' => $estateOfficer->id,
                        'is_allottee' => false,
                        'application_id' => $application->id,
                        'notification_type' => 'application_movement',
                        'subject' => $subject,
                        'message' => $message,
                        'send_email' => true,
                        'send_sms' => false,
                        'send_whatsapp' => false,
                        'link' => '/login',
                        'mailable' => $customMailableEstate
                    ]);
                }
            }
        }

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
        $application->load(['notes' => function ($query) {
            $query->orderBy('id', 'asc');
        }, 'notes.user', 'notes.role', 'notes.user.division']);

        $localPath = str_replace('\\', '/', storage_path('app/public/'));
        foreach ($application->notes as $note) {
            if ($note->remarks) {
                $note->remarks = preg_replace('/https?:\/\/[^\/]+\/storage\//i', $localPath, $note->remarks);
            }
        }

        $pdf = \Mccarlosen\LaravelMpdf\Facades\LaravelMpdf::loadView('engineer.applications.notes-pdf', compact('application'), [], [
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
    public function requestDocument(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'allottee_id' => 'required',
            'document_master_ids' => 'required|array|min:1',
        ]);

        $requestedDocNames = [];

        foreach ($request->document_master_ids as $docId) {
            $existingRequest = \App\Models\DocumentRequest::where('application_id', $request->application_id)
                ->where('document_master_id', $docId)
                ->where('status', 'pending')
                ->first();

            if ($existingRequest) {
                continue; // Skip already requested ones
            }

            \App\Models\DocumentRequest::create([
                'application_id' => $request->application_id,
                'allottee_id' => $request->allottee_id,
                'document_master_id' => $docId,
                'requested_by' => \Illuminate\Support\Facades\Auth::id(),
                'remarks' => $request->remarks,
                'expires_at' => now()->addDays(2),
                'status' => 'pending'
            ]);

            $documentMaster = \App\Models\DocumentMaster::find($docId);
            if ($documentMaster) {
                $requestedDocNames[] = $documentMaster->document_name;
            }
        }

        if (empty($requestedDocNames)) {
            return back()->with('error', 'All selected documents are already requested and pending.');
        }

        $allottee = \App\Models\Allottee::find($request->allottee_id);
        if ($allottee && $allottee->user_id) {
            $defaultMsg = 'Engineer has requested additional documents for your application. Please upload within 2 days.';
            $message = !empty(trim($request->remarks)) ? trim($request->remarks) : $defaultMsg;

            $docNamesStr = implode(', ', $requestedDocNames);
            $dueDate = now()->addDays(2)->format('d M Y, h:i A');
            $dashboardUrl = url('/'); // Link to allottee dashboard

            $customMailable = new \App\Mail\DocumentRequestMail(
                $allottee->user->name ?? 'Allottee',
                $docNamesStr,
                $dueDate,
                $dashboardUrl,
                $message
            );

            app(\App\Services\NotificationService::class)->send([
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
