<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApplicationMovement;
use App\Models\ApplicationExtensionRequest;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use App\Models\BatchProgram;
use App\Models\BatchProgramDetail;
use App\Jobs\ProcessBatchEmailJob;
use App\Mail\GenericNotificationMail;
use Illuminate\Support\Facades\Log;
class AutoEscalateStalledApplications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-escalate-stalled-applications {--days=3 : The number of days past due to consider stalled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically escalate applications that are stalled with an engineer beyond their due date.';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $log = Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/task_log.log'),
        ]);

        $log->info('--- AutoEscalateStalledApplications Command Started ---');
        
        $batchProgram = BatchProgram::create([
            'command_name' => 'AutoEscalateStalledApplications',
            'started_at' => now(),
            'status' => 'running',
        ]);
        
        $daysThreshold = (int) $this->option('days');
        $thresholdDate = now()->subDays($daysThreshold);

        $stalledMovements = ApplicationMovement::with(['application', 'toUser'])
            ->whereIn('status', ['pending','in_progress'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', $thresholdDate)
            ->where('is_escalated', false)
            ->get();

        $log->info("Found " . $stalledMovements->count() . " potentially stalled movements (Threshold: $daysThreshold days).");

        $escalatedCount = 0;

        $admins = User::where('role_id', 8)->get();

        $systemEmail = config('jshb.mail_system_username', 'system@adms.jshb.computered.co.in');

        $totalJobs = 0;

        foreach ($stalledMovements as $movement) {
            $appNo = $movement->application->application_no ?? 'Unknown';
            $engineerName = $movement->toUser->name ?? 'Unknown Engineer';

            // Check if there is already a pending extension request
            $hasPendingExtension = ApplicationExtensionRequest::where('application_id', $movement->application_id)
                ->where('requested_by', $movement->to_user_id)
                ->where('status', 'pending')
                ->exists();

            if ($hasPendingExtension) {
                $log->info("Skipped Application #$appNo - Pending extension request exists.");
                continue; // Skip escalation if they already asked for an extension and are waiting
            }

            // Notify Admins
            foreach ($admins as $admin) {
                $subject = "ESCALATION: Stalled Application #$appNo";
                $message = "Application #$appNo has been stalled with Engineer $engineerName for more than $daysThreshold days past its due date. No extension has been requested.";
                
                $mailable = new GenericNotificationMail($subject, $message, null, false);
                $detail = BatchProgramDetail::create([
                    'batch_program_id' => $batchProgram->id,
                    'user_id' => $admin->id,
                    'application_id' => $movement->application_id,
                    'recipient_email' => $admin->email,
                    'cc_email' => $systemEmail,
                    'mail_body' => $mailable->render(),
                    'status' => 'queued',
                    'queued_at' => now(),
                ]);
                ProcessBatchEmailJob::dispatch($detail->id, $admin->email, $mailable);
                $totalJobs++;

                $notificationService->send([
                    'user_id' => $admin->id,
                    'is_allottee' => false,
                    'notification_type' => 'warning',
                    'subject' => $subject,
                    'message' => $message,
                    'link' => null, 
                    'send_email' => false,
                    'application_id' => $movement->application_id,
                ]);
            }

            // Notify Engineer (Warning)
            $subjectEng = "WARNING: Application #$appNo Escalated";
            $messageEng = "Your pending Application #$appNo is overdue by more than $daysThreshold days. This has been automatically escalated to the Admin.";
            $linkEng = route('engineer.applications.show', $movement->application_id);
            
            if ($movement->toUser && $movement->toUser->email) {
                $mailableEng = new GenericNotificationMail($subjectEng, $messageEng, $linkEng, false);
                $detailEng = BatchProgramDetail::create([
                    'batch_program_id' => $batchProgram->id,
                    'user_id' => $movement->to_user_id,
                    'application_id' => $movement->application_id,
                    'recipient_email' => $movement->toUser->email,
                    'cc_email' => $systemEmail,
                    'mail_body' => $mailableEng->render(),
                    'status' => 'queued',
                    'queued_at' => now(),
                ]);
                ProcessBatchEmailJob::dispatch($detailEng->id, $movement->toUser->email, $mailableEng);
                $totalJobs++;
            }

            $notificationService->send([
                'user_id' => $movement->to_user_id,
                'is_allottee' => false,
                'notification_type' => 'danger',
                'subject' => $subjectEng,
                'message' => $messageEng,
                'link' => $linkEng,
                'send_email' => false,
                'application_id' => $movement->application_id,
            ]);

            $escalatedCount++;
            
            // Mark as escalated so we don't notify again tomorrow for the same movement
            $movement->update([
                'is_escalated' => true,
                'escalated_at' => now(),
            ]);

            $log->info("Escalated Application #$appNo stalled with $engineerName.");
            $this->info("Escalated Application #$appNo stalled with $engineerName.");
        }

        $batchProgram->update([
            'total_jobs' => $totalJobs,
            'completed_at' => now(),
            'status' => 'completed',
        ]);

        $log->info("--- Escalation check complete. $escalatedCount applications escalated. ---");
        $this->info("Escalation check complete. $escalatedCount applications escalated. Batch ID: {$batchProgram->id}");
    }
}
