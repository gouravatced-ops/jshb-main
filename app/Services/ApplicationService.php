<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApplicationMovement;
use App\Models\ApplicationDocument;
use App\Models\ApplicationAuditTrail;
use App\Models\Allottee;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use App\Models\User;
use App\Models\AllotteeTransaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplicationService
{
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
                
                $applicationNo = 'APL-' . date('Y') . '-' . rand(12345678, 99999999);
                
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
                    app(\App\Services\NotificationService::class)->send([
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
                        app(\App\Services\NotificationService::class)->send([
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
                        app(\App\Services\NotificationService::class)->send([
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
