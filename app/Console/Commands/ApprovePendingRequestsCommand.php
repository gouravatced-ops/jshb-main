<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BypassRequest;
use App\Models\ApplicationExtensionRequest;
use App\Models\ApplicationMovement;
use App\Models\Application;
use App\Models\User;
use App\Models\Notification;
use App\Models\BatchProgram;
use App\Models\BatchProgramDetail;
use App\Mail\BypassAndExtensionApprovalReportMail;
use App\Mail\GenericNotificationMail;
use App\Jobs\ProcessBatchEmailJob;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApprovePendingRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:approve-pending-requests
                            {--days=3 : Default extension days for pending application extension requests}
                            {--no-mail : Skip dispatching the email report}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically approve pending bypass requests and extension requests with default days, and dispatch an email report to management';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $log = Log::build([
            'driver' => 'single',
            'path'   => storage_path('logs/pending_approvals.log'),
        ]);

        $executionTime = now()->format('d M Y, h:i A');
        $this->info("=== Starting Automated Requests Approval at {$executionTime} ===");
        $log->info("=== Starting Automated Requests Approval at {$executionTime} ===");

        $defaultDays = (int) $this->option('days') ?: 3;
        $systemApprover = User::where('role_id', 9)->first() ?: User::whereIn('role_id', [8, 9])->first();
        $approverId = $systemApprover ? $systemApprover->id : 1;

        // System and Super-Admin Recipients (Admin role 8 completely removed)
        $systemEmail = config('jshb.mail_system_username', 'system@adms.jshb.computered.co.in');

        $superAdminEmails = User::where('role_id', 9)
            ->where('status', 1)
            ->pluck('email')
            ->filter(fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->toArray();

        $fallbackEmail = config('gouravatced@gmail.com', 'computered3896@gmail.com');
        if (empty($superAdminEmails)) {
            $superAdminEmails = [$fallbackEmail];
        }

        $ccEmails = array_values(array_diff($superAdminEmails, [$systemEmail]));
        if (empty($ccEmails)) {
            $ccEmails = [$fallbackEmail];
        }

        $bypassReport = [];
        $extensionReport = [];

        // ----------------------------------------------------
        // 1. Process Pending Workflow Bypass Requests
        // ----------------------------------------------------
        $pendingBypasses = BypassRequest::with(['application', 'requestedBy', 'targetUser', 'targetRole'])
            ->where('status', 'pending')
            ->get();

        $this->info("Found " . $pendingBypasses->count() . " pending bypass request(s).");
        $log->info("Found " . $pendingBypasses->count() . " pending bypass request(s).");

        foreach ($pendingBypasses as $bypass) {
            try {
                DB::transaction(function () use ($bypass, $approverId, &$bypassReport, $log, $superAdminEmails) {
                    $bypass->status = 'approved';
                    $bypass->approved_by_user_id = $approverId;
                    $bypass->approved_at = now();
                    $bypass->save();

                    $engineer = $bypass->requestedBy;
                    $appNo = $bypass->application->application_no ?? 'N/A';
                    $sentTo = $bypass->targetUser->name ?? ($bypass->targetRole->name ?? 'Next Assigned Officer');

                    if ($engineer) {
                        $mailSubject = "Bypass Request Approved for Application: " . $appNo;
                        $mailBody = "Your request to bypass workflow for Application No: {$appNo} has been approved automatically. You can now forward the application to {$sentTo}.";
                        $link = route('engineer.applications.show', $bypass->application);

                        Notification::create([
                            'application_id'    => $bypass->application_id,
                            'user_id'           => $engineer->id,
                            'notification_type' => 'bypass_approved',
                            'subject'           => $mailSubject,
                            'message'           => $mailBody,
                            'link'              => $link,
                            'is_read'           => false,
                        ]);

                        if ($engineer->email) {
                            try {
                                $mailable = Mail::to($engineer->email);
                                if (!empty($superAdminEmails)) {
                                    $mailable->cc($superAdminEmails);
                                }
                                $mailable->send(new GenericNotificationMail($mailSubject, $mailBody, $link));
                            } catch (\Exception $e) {
                                $log->error("Failed to send bypass approval mail to engineer [{$engineer->email}]: " . $e->getMessage());
                            }
                        }
                    }

                    $bypassReport[] = [
                        'app_no'       => $appNo,
                        'requested_by' => $engineer->name ?? 'Unknown',
                        'sent_to'      => $sentTo,
                        'reason'       => $bypass->reason ?? 'No reason provided',
                        'approved_at'  => now()->format('d M Y, h:i A'),
                    ];

                    $log->info("Approved bypass request #{$bypass->id} for Application #{$appNo}");
                });
            } catch (\Exception $e) {
                $log->error("Error approving bypass request #{$bypass->id}: " . $e->getMessage());
                $this->error("Failed to approve bypass request #{$bypass->id}: " . $e->getMessage());
            }
        }

        // ----------------------------------------------------
        // 2. Process Pending Application Extension Requests
        // ----------------------------------------------------
        $pendingExtensions = ApplicationExtensionRequest::with(['application', 'requestedBy', 'movement'])
            ->where('status', 'pending')
            ->get();

        $this->info("Found " . $pendingExtensions->count() . " pending extension request(s).");
        $log->info("Found " . $pendingExtensions->count() . " pending extension request(s).");

        $notificationService = new NotificationService();

        foreach ($pendingExtensions as $ext) {
            try {
                DB::transaction(function () use ($ext, $approverId, $defaultDays, &$extensionReport, $notificationService, $log, $superAdminEmails) {
                    $grantedDays = (int) ($ext->extension_days ?: $defaultDays);
                    $ext->extension_days = $grantedDays;
                    $ext->status = 'approved';
                    $ext->approved_at = now();
                    $ext->remarks = $ext->remarks ?: "Auto-approved via automated scheduler with {$grantedDays} days extension";
                    $ext->save();

                    // Resolve related movement to update due_date
                    $movement = $ext->movement;
                    if (!$movement && $ext->movement_id) {
                        $movement = ApplicationMovement::find($ext->movement_id);
                    }
                    if (!$movement && $ext->application_id) {
                        $movement = ApplicationMovement::where('application_id', $ext->application_id)
                            ->where('to_user_id', $ext->requested_by)
                            ->whereIn('status', ['pending', 'in_progress'])
                            ->latest()
                            ->first();
                    }

                    $oldDueFormatted = 'N/A';
                    $newDueFormatted = 'N/A';

                    if ($movement) {
                        $currentDate = $movement->due_date ? Carbon::parse($movement->due_date) : now();
                        $oldDueFormatted = $currentDate->format('d M Y');
                        $newDueDate = (clone $currentDate)->addDays($grantedDays);
                        $movement->due_date = $newDueDate;
                        $movement->save();
                        $newDueFormatted = $newDueDate->format('d M Y');
                    }

                    $appNo = $ext->application->application_no ?? 'N/A';
                    $engineer = $ext->requestedBy;

                    // Notify Engineer
                    if ($ext->requested_by) {
                        try {
                            $notificationService->send([
                                'user_id'           => $ext->requested_by,
                                'is_allottee'       => false,
                                'notification_type' => 'success',
                                'subject'           => "Extension Request Approved - Application #{$appNo}",
                                'message'           => "Your extension request has been approved automatically. You have been granted {$grantedDays} additional days. New Due Date: {$newDueFormatted}.",
                                'link'              => route('engineer.extensions.index'),
                                'send_email'        => true,
                                'send_sms'          => false,
                                'cc'                => $superAdminEmails,
                            ]);
                        } catch (\Exception $e) {
                            $log->error("Failed to send extension approval notification to user #{$ext->requested_by}: " . $e->getMessage());
                        }
                    }

                    $extensionReport[] = [
                        'app_no'         => $appNo,
                        'requested_by'   => $engineer->name ?? 'Unknown',
                        'extension_days' => $grantedDays,
                        'old_due_date'   => $oldDueFormatted,
                        'new_due_date'   => $newDueFormatted,
                        'reason'         => $ext->request_reason ?? 'Extension requested by officer',
                        'approved_at'    => now()->format('d M Y, h:i A'),
                    ];

                    $log->info("Approved extension request #{$ext->id} for Application #{$appNo} with +{$grantedDays} days");
                });
            } catch (\Exception $e) {
                $log->error("Error approving extension request #{$ext->id}: " . $e->getMessage());
                $this->error("Failed to approve extension request #{$ext->id}: " . $e->getMessage());
            }
        }

        // ----------------------------------------------------
        // 3. Package Report & Dispatch Mail to Management
        // ----------------------------------------------------
        $reportData = [
            'bypass_approved'    => $bypassReport,
            'extension_approved' => $extensionReport,
            'default_days'       => $defaultDays,
            'execution_time'     => $executionTime,
        ];

        $this->info("To: {$systemEmail}");
        $this->info("CC: " . implode(', ', $ccEmails));

        if ($this->option('no-mail')) {
            $this->warn("Option --no-mail passed. Email report skipped.");
            $log->info("Option --no-mail passed. Email report skipped.");
            return Command::SUCCESS;
        }

        // Create Batch Program Record (Single Job)
        $batch = BatchProgram::create([
            'command_name'           => 'ApprovePendingRequestsCommand',
            'total_jobs'             => 1,
            'unique_engineers_count' => 1,
            'started_at'             => now(),
            'status'                 => 'running',
        ]);

        $mailable = new BypassAndExtensionApprovalReportMail($reportData, $executionTime);
        $mailBody = $mailable->render();

        $systemUser = User::where('email', $systemEmail)->first();

        // Create a SINGLE detail row with system as recipient and super-admins in CC
        $detail = BatchProgramDetail::create([
            'batch_program_id' => $batch->id,
            'user_id'          => $systemUser ? $systemUser->id : null,
            'recipient_email'  => $systemEmail,
            'cc_email'         => implode(', ', $ccEmails),
            'mail_body'        => $mailBody,
            'status'           => 'queued',
            'queued_at'        => now(),
        ]);

        $success = false;
        try {
            $freshMailable = new BypassAndExtensionApprovalReportMail($reportData, $executionTime);
            ProcessBatchEmailJob::dispatch($detail->id, $systemEmail, $freshMailable);
            $success = true;
        } catch (\Exception $e) {
            $detail->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            $log->error("Failed to queue approvals report: " . $e->getMessage());
        }

        $batch->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        $this->info("Processed: " . count($bypassReport) . " bypass request(s) and " . count($extensionReport) . " extension request(s).");
        $this->info($success ? "Report dispatched to {$systemEmail} (CC: " . implode(', ', $ccEmails) . ")." : "Failed to dispatch report.");
        $log->info("Completed automated approvals. Bypass: " . count($bypassReport) . ", Extension: " . count($extensionReport) . ", Report sent: " . ($success ? 'yes' : 'no'));

        return Command::SUCCESS;
    }
}
