<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

use App\Models\BypassRequest;
use App\Models\Notification;
use App\Mail\GenericNotificationMail;
use App\Models\ApplicationDocument;
use App\Models\AllotteeGeneratedDocument;
use App\Models\AllotteeProcessStep;
use App\Models\AllotteePaymentOrder;
use App\Mail\ApplicationForwardedMail;
use App\Services\NotificationService;
use App\Models\AllotteeTransaction;
use App\Models\Workflow;
use App\Models\Application;
use App\Models\WorkflowStep;
use App\Models\ApplicationMovement;
use App\Models\ApplicationAuditTrail;
use App\Models\ApplicationNote;
use App\Models\Allottee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Traits\DocumentUploadTrait;
use App\Http\Requests\Application\ProcessActionRequest;
use Illuminate\Http\Request;

class ApplicationService
{
    use DocumentUploadTrait;

    public function processAction(Application $application, Request $request, User $user)
    {
        // Validation moved to ProcessActionRequest

        if ($request->action_type == 'forward' && $application->currentStep && $application->currentStep->action_type == 'site_verification') {
            $isSiteVerificationCompleted = $application->isSiteVerificationCompleted();
            if (!$isSiteVerificationCompleted) {
                return redirect()->back()->with('error', 'Site Verification is pending. Please complete it from the Site Verification tab before forwarding.');
            }
        }

        $user = Auth::user();

        if ($request->action_type == 'approve') {
            // Validation moved to ProcessActionRequest

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
            // Validation moved to ProcessActionRequest

            $parts = explode('|', $request->forward_to_user);
            $targetUserId = $parts[0] ?? null;
            $nextStepId = $parts[1] ?? null;

            $nextStep = WorkflowStep::find($nextStepId);
            $targetUser = User::on('adms_jshb')->find($targetUserId);

            if ($request->has('is_bypass_request') && $request->is_bypass_request == '1') {
                // Validation moved to ProcessActionRequest

                $bypassRequest = BypassRequest::create([
                    'application_id' => $application->id,
                    'requested_by_user_id' => $user->id,
                    'target_step_id' => $nextStepId,
                    'target_role_id' => $nextStep ? $nextStep->role_id : null,
                    'target_user_id' => $targetUserId,
                    'reason' => $request->bypass_reason,
                    'status' => 'pending',
                ]);

                try {
                    $mailSubject = "Bypass Request Submitted for Application: " . $application->application_no;
                    $mailBody = "Application bypass due to this by pass reason: " . $request->bypass_reason;

                    // Email to system
                    Mail::to('system@adms.jshb.computered.co.in')->send(new \App\Mail\GenericNotificationMail($mailSubject, $mailBody, route('admin.bypass-requests.index')));

                    // Notify Admins
                    $admins = User::on('adms_jshb')->where('role_id', 8)->get();
                    foreach ($admins as $admin) {
                        Notification::create([
                            'application_id' => $application->id,
                            'user_id' => $admin->id,
                            'notification_type' => 'bypass_submitted',
                            'subject' => $mailSubject,
                            'message' => $mailBody,
                            'link' => route('admin.bypass-requests.index'),
                            'is_read' => false
                        ]);

                        if ($admin->email) {
                            Mail::to($admin->email)->send(new \App\Mail\GenericNotificationMail($mailSubject, $mailBody, route('admin.bypass-requests.index')));
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to send bypass submission notifications: " . $e->getMessage());
                }


                // Save Noting only if remarks are provided
                if ($request->filled('remarks')) {
                    ApplicationNote::create([
                        'application_id' => $application->id,
                        'user_id' => $user->id,
                        'role_id' => $user->role_id,
                        'remarks' => $request->remarks,
                        'font_family' => $request->font_family ?? 'english',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                // Notify Admins
                $admins = User::on('adms_jshb')->where('role_id', 1)->get();
                $mailSubject = "Bypass Request Pending for Application: " . $application->application_no;
                $mailBody = "A workflow bypass request has been requested by " . $user->name . " for Application No: " . $application->application_no . ". Reason: " . $request->bypass_reason;
                $link = route('admin.bypass-requests.index');

                foreach ($admins as $admin) {
                    Notification::create([
                        'application_id' => $application->id,
                        'user_id' => $admin->id,
                        'notification_type' => 'bypass_request',
                        'subject' => $mailSubject,
                        'message' => $mailBody,
                        'link' => $link,
                        'is_read' => false
                    ]);

                    if ($admin->email) {
                        try {
                            Mail::to($admin->email)->send(new GenericNotificationMail($mailSubject, $mailBody, $link));
                        } catch (\Exception $e) {
                            Log::error("Failed to send bypass request mail to admin: " . $e->getMessage());
                        }
                    }
                }

                return redirect()->route('engineer.applications.show', $application)
                    ->with('success', 'Bypass Request submitted successfully. Waiting for Admin approval.');
            }

            // Not a new bypass request - Validate if it's a skipped level
            if ($application->currentStep) {
                $immediateNextStep = WorkflowStep::where('workflow_id', $application->workflow_id)
                    ->whereIn('action_type', ['verify', 'approve'])
                    ->where('step_order', '>', $application->currentStep->step_order)
                    ->orderBy('step_order', 'asc')
                    ->first();

                if ($immediateNextStep && $nextStepId != $immediateNextStep->id) {
                    // This is a bypass forward! Verify there's an approved request for this step.
                    $approvedBypass = BypassRequest::where('application_id', $application->id)
                        ->where('target_step_id', $nextStepId)
                        ->where('status', 'approved')
                        ->where('is_used', false)
                        ->first();

                    if (!$approvedBypass) {
                        return redirect()->back()->with('error', 'You cannot forward to this step without an approved bypass request from Admin.');
                    }

                    // Mark as used so it cannot be reused if sent back
                    $approvedBypass->is_used = true;
                    $approvedBypass->save();
                }
            }

            $newStatus = 'forwarded';
        } elseif ($request->action_type == 'send_back') {
            // Validation moved to ProcessActionRequest

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
            Log::info("Agreement Letter generation triggered by MD approval for Application ID: " . $application->id);
        }

        if ($shouldGenerateDocument) {
            if ($application->application_type === 'possession') {
                Log::info("Processing Possession approval for Application ID: {$application->id}. Moving Site Verification docs.");
                $allottee = $application->allottee;

                // Find Site Verification documents
                $docsToMove = ApplicationDocument::where('application_id', $application->id)
                    ->whereIn('document_type', ['Site Verification Map', 'Site Verification'])
                    ->get();

                foreach ($docsToMove as $doc) {
                    $isMap = (stripos($doc->document_type, 'map') !== false);
                    $newDocType = $isMap ? 'approved-site-verification-map' : 'approved-site-verification-pdf';
                    $newDocName = $isMap ? 'Approved Site Verification Map' : 'Approved Site Verification PDF';

                    AllotteeGeneratedDocument::create([
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
                Log::info("Starting document generation for Application ID: {$application->id}, Type: {$application->application_type}");
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
                ApplicationDocument::create([
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
                AllotteeGeneratedDocument::create([
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
                    Log::info("Document generation complete for Possession. Completing step and unlocking next.");

                    // Complete the step in allottee process steps if required
                    // For possession, usually menu_key is allotment-possession-letter, but wait, from the earlier code it seems to be allotment/site-verification or allotment/possession-letter
                    $currentStep = AllotteeProcessStep::where([
                        'allottee_id' => $allottee->id,
                        'menu_key' => 'allotment',
                        'sub_menu_key' => 'allotment-possession-letter'
                    ])->first();

                    if ($currentStep) {
                        AllotteeProcessStep::completeStep(
                            $allottee->id,
                            'allotment',
                            $currentStep->sub_menu_key,
                            $user->id
                        );
                        AllotteeProcessStep::unlockNextStep($allottee->id, $currentStep->step_no);
                    }
                } elseif ($isAgreement) {
                    Log::info("Document generation complete for Agreement. Completing step and unlocking next.");

                    // Mark Agreement step as completed
                    AllotteeProcessStep::completeStep(
                        $allottee->id,
                        'allotment',
                        'agreement-document-letter',
                        $user->id
                    );

                    // Unlock the next step
                    $currentStep = AllotteeProcessStep::where([
                        'allottee_id' => $allottee->id,
                        'menu_key' => 'allotment',
                        'sub_menu_key' => 'agreement-document-letter'
                    ])->first();

                    if ($currentStep) {
                        AllotteeProcessStep::unlockNextStep($allottee->id, $currentStep->step_no);
                    }
                } else {
                    Log::info("Document generation complete. Next step unlocked for allotment (Payment Order).");

                    // 5. Mark Allottee Process Step as completed
                    AllotteeProcessStep::completeStep(
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

                    AllotteePaymentOrder::updateOrCreate(
                        [
                            'allottee_id' => $allottee->id,
                            'order_type'  => 'allotment',
                        ],
                        [
                            'order_no'         => AllotteePaymentOrder::generateOrderNo('ODR-ALT'),
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
                    $currentStep = AllotteeProcessStep::where([
                        'allottee_id' => $allottee->id,
                        'menu_key' => 'allotment',
                        'sub_menu_key' => 'generate-allotment'
                    ])->first();

                    if ($currentStep) {
                        AllotteeProcessStep::unlockNextStep($allottee->id, $currentStep->step_no);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Failed to auto-generate allotment PDF: " . $e->getMessage());
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
            $prevStep = WorkflowStep::find($previousStepId);
            if ($prevStep && $prevStep->action_type == 'site_verification') {
                $siteVerfDocs = ApplicationDocument::where('application_id', $application->id)
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

            $customMailable = new ApplicationForwardedMail(
                $targetUser->name,
                $application->application_no,
                $user->name,
                $request->action_type,
                $dashboardUrl,
                $request->remarks
            );

            app(NotificationService::class)->send([
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

        // Trigger Notification to Allottee on Approve / Reject
        if (in_array($request->action_type, ['approve', 'reject'])) {
            $actionWord = $request->action_type == 'approve' ? 'approved' : 'rejected';
            $subject = "Application {$actionWord}: {$application->application_no}";

            if ($request->action_type == 'approve') {
                $documentName = match ($application->application_type) {
                    'allotment' => 'Allotment Letter',
                    'possession' => 'Possession Letter',
                    'agreement' => 'Agreement',
                    default => ucfirst(str_replace('_', ' ', $application->application_type))
                };
                $message = "Your application ({$application->application_no}) has been approved and your {$documentName} has been generated. Please log in to download your " . strtolower($documentName) . ".";
            } else {
                $message = "Your application ({$application->application_no}) has been rejected.";
            }

            $dashboardUrl = url('/login');

            // Mail to Allottee
            $allotteeUser = User::on('adms_allottees')->find($application->allottee->user_id);
            if ($allotteeUser) {
                $customMailableAllottee = new ApplicationForwardedMail(
                    $allotteeUser->name ?? 'Allottee',
                    $application->application_no,
                    $user->name,
                    $request->action_type,
                    $dashboardUrl,
                    '', // Remarks are hidden for approve/reject
                    $message // Pass custom message
                );

                app(NotificationService::class)->send([
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
        }

        return redirect()->route('engineer.applications.show', $application)
            ->with('success', 'Office noting and action recorded successfully.');
    }

    /**
     * Create a new application (e.g. allotment).
     *
     * @param Allottee $allottee
     * @param string $applicationType
     * @param string $ipAddress
     * @param string $userAgent
     * @return Application|null
     */
    public function createApplication(Allottee $allottee, $applicationType, $ipAddress, $userAgent)
    {
        try {
            DB::beginTransaction();

            // Find Workflow
            $workflow = Workflow::where('application_type', $applicationType)
                ->where('is_active', 1)
                ->first();

            $existingApplication = Application::where('allottee_id', $allottee->id)
                ->where('application_type', $applicationType)
                ->exists();

            $application = null;

            if ($workflow && !$existingApplication) {
                // Get starting step
                $startingStep = WorkflowStep::where('workflow_id', $workflow->id)
                    ->orderBy('step_order', 'asc')
                    ->first();

                // Get next step
                $nextStep = $startingStep ? WorkflowStep::where('workflow_id', $workflow->id)
                    ->where('step_order', '>', $startingStep->step_order)
                    ->orderBy('step_order', 'asc')
                    ->first() : null;

                // Find Target User based on division
                $divisionId = $allottee->division_id;
                $targetUser = $nextStep ? User::where('role_id', $nextStep->role_id)
                    ->when($divisionId, function ($query) use ($divisionId) {
                        return $query->where('division_id', $divisionId);
                    })
                    ->where('status', 1)
                    ->orderByDesc('is_default')
                    ->first() : null;

                $typePrefixMap = [
                    'allotment' => 'ALT',
                    'possession' => 'POS',
                    'agreement' => 'AGR',
                    'divident' => 'DVD',
                    'dividend' => 'DVD',
                    'final_calculation' => 'FCL',
                    'site_verification' => 'SVF',
                    'register' => 'RGT',
                    'name_transfer' => 'NTF',
                    'allotment_cancel' => 'ACL',
                ];

                $prefix = $typePrefixMap[strtolower($applicationType)] ?? 'APP';

                $divCode = $allottee->division ? $allottee->division->division_code : 'XXX';
                $subDivCode = $allottee->subDivision ? $allottee->subDivision->subdivision_code : 'XXX';

                // Ensure codes are available, handle missing codes safely
                $divCode = strtoupper(substr($divCode, 0, 3));
                $subDivCode = strtoupper(substr($subDivCode, 0, 3));

                $rand2Num1 = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
                $month2Digit = date('m');

                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $rand2Alpha1 = $characters[rand(0, 25)] . $characters[rand(0, 25)];
                $rand2Alpha2 = $characters[rand(0, 25)] . $characters[rand(0, 25)];

                $day2Digit = date('d');
                $rand2Num2 = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
                $year2Digit = date('y');

                $applicationNo = "{$prefix}-{$divCode}{$subDivCode}-{$rand2Num1}{$month2Digit}{$rand2Alpha1}{$day2Digit}{$rand2Num2}{$year2Digit}{$rand2Alpha2}";
                // Create Application
                $application = Application::create([
                    'application_no' => $applicationNo,
                    'application_type' => $applicationType,
                    'allottee_id' => $allottee->id,
                    'property_id' => 1, // Provide a default or actual property ID
                    'workflow_id' => $workflow->id,
                    'current_step_id' => $nextStep ? $nextStep->id : ($startingStep ? $startingStep->id : null),
                    'current_user_id' => $targetUser ? $targetUser->id : null,
                    'current_role_id' => $nextStep ? $nextStep->role_id : ($startingStep ? $startingStep->role_id : null),
                    'status' => 'in_progress', // Set directly to in_progress because it is forwarded immediately
                    'priority' => 'normal',
                    'created_date' => now(),
                    'remarks' => 'New ' . $applicationType . ' application for property ' . ($allottee->property_number ?? 'N/A'),
                    'created_by' => auth()->id() ?? 1,
                ]);

                // Notify Allottee
                if ($allottee->user_id) {
                    app(NotificationService::class)->send([
                        'user_id' => $allottee->user_id,
                        'is_allottee' => true,
                        'application_id' => $application->id,
                        'notification_type' => 'success',
                        'subject' => 'Application Created',
                        'message' => "Your application ({$applicationNo}) for {$applicationType} has been successfully created.",
                        'send_email' => false,
                        'send_sms' => true,
                        'send_whatsapp' => true,
                        'link' => null
                    ]);
                }

                // 1. Create Application Movement (System Generation)
                $systemMovement = ApplicationMovement::create([
                    'application_id' => $application->id,
                    'from_user_id' => auth()->id(),
                    'to_user_id' => null,
                    'from_role_id' => auth()->check() ? auth()->user()->role_id : null,
                    'to_role_id' => null,
                    'from_step_id' => $startingStep ? $startingStep->id : null,
                    'to_step_id' => null,
                    'action_type' => 'created',
                    'status' => 'completed',
                    'remarks' => 'Application created by system',
                    'movement_date' => now(),
                ]);

                // Insert Document from step0 lottery payment
                $transaction = AllotteeTransaction::where([
                    'allottee_id'      => $allottee->id,
                    'transaction_type' => 'lottery_payment',
                    'payment_stage'    => 'application',
                ])->first();

                if ($transaction && $transaction->receipt_path) {
                    ApplicationDocument::create([
                        'application_id'  => $application->id,
                        'movement_id'     => $systemMovement->id,
                        'document_type'   => 'lottery_receipt',
                        'document_name'   => 'Lottery Receipt',
                        'file_name'       => $transaction->receipt_file ?? 'lottery_receipt.pdf',
                        'file_path'       => $transaction->receipt_path,
                        'file_size'       => 0,
                        'file_mime_type'  => 'application/pdf',
                        'version'         => 1,
                        'is_original'     => 1,
                        'is_verified'     => 0,
                        'uploaded_by'     => auth()->id() ?? 1,
                        'uploaded_at'     => now(),
                    ]);
                }

                // 3. Insert Audit Trail
                $allotteeFullName = trim(implode(' ', array_filter([
                    $allottee->allottee_name,
                    $allottee->allottee_middle_name,
                    $allottee->allottee_surname,
                ])));

                if (empty($allotteeFullName)) {
                    $allotteeFullName = 'Applicant';
                }

                ApplicationAuditTrail::create([
                    'application_id' => $application->id,
                    'user_id' => auth()->id() ?? 6,
                    'role_id' => auth()->check() ? auth()->user()->role_id : 8,
                    'action' => 'create',
                    'module' => 'application',
                    'description' => ucfirst($applicationType) . " application {$applicationNo} created for {$allotteeFullName}",
                    'new_data' => [
                        'application_id' => $application->id,
                        'application_no' => $applicationNo,
                        'allottee_id' => $allottee->id,
                        'allottee_name' => $allotteeFullName,
                        'property_number' => $allottee->property_number ?? '',
                    ],
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]);

                // 4. Create Application Movement (Forward to Next Step)
                if ($nextStep) {
                    ApplicationMovement::create([
                        'application_id' => $application->id,
                        'from_user_id' => auth()->id() ?? 1,
                        'to_user_id' => $targetUser ? $targetUser->id : null,
                        'from_role_id' => auth()->check() ? auth()->user()->role_id : $startingStep->role_id,
                        'to_role_id' => $nextStep->role_id,
                        'from_step_id' => $startingStep->id,
                        'to_step_id' => $nextStep->id,
                        'action_type' => 'forwarded',
                        'status' => 'in_progress',
                        'remarks' => 'Application automatically forwarded to ' . $nextStep->step_name,
                        'movement_date' => now(),
                    ]);

                    // Send Notification to Allottee
                    if ($allottee->user_id) {
                        app(NotificationService::class)->send([
                            'user_id' => $allottee->user_id,
                            'is_allottee' => true,
                            'application_id' => $application->id,
                            'notification_type' => 'application_created',
                            'subject' => 'New ' . ucfirst($applicationType) . ' Application Created',
                            'message' => "Your " . strtolower($applicationType) . " application {$applicationNo} has been created and forwarded to {$nextStep->step_name} for verification.",
                            'link' => "/dashboard/section/application",
                            'send_email' => true,
                            'send_sms' => true,
                            'send_whatsapp' => true,
                        ]);
                    }

                    // Send Notification to Target User (e.g., Dealing Assistant)
                    if ($targetUser) {
                        app(NotificationService::class)->send([
                            'user_id' => $targetUser->id,
                            'is_allottee' => false,
                            'application_id' => $application->id,
                            'notification_type' => 'application_forwarded',
                            'subject' => 'New ' . ucfirst($applicationType) . ' Application for Verification',
                            'message' => "A new " . strtolower($applicationType) . " application {$applicationNo} has been forwarded to you for document verification.",
                            'link' => "/applications/view/{$application->id}",
                            'send_email' => true,
                            'send_sms' => false,
                            'send_whatsapp' => false,
                        ]);
                    }
                }
            }

            DB::commit();
            return $application;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating application: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete an application and all its related records.
     *
     * @param Application $application
     * @return bool
     */
    public function deleteApplication(Application $application)
    {
        try {
            DB::beginTransaction();

            $allotteeId = $application->allottee_id;

            // Delete Movements
            ApplicationMovement::where('application_id', $application->id)->delete();

            // Delete Documents
            ApplicationDocument::where('application_id', $application->id)->delete();

            // Delete Audit Trails
            ApplicationAuditTrail::where('application_id', $application->id)->delete();

            // Force delete the application if it uses SoftDeletes, or regular delete
            if (method_exists($application, 'forceDelete')) {
                $application->forceDelete();
            } else {
                $application->delete();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting application: ' . $e->getMessage());
            return false;
        }
    }
}
