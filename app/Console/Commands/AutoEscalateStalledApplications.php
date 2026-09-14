<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApplicationMovement;
use App\Models\ApplicationExtensionRequest;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
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
        $daysThreshold = (int) $this->option('days');
        $thresholdDate = now()->subDays($daysThreshold);

        $stalledMovements = ApplicationMovement::with(['application', 'toUser'])
            ->whereIn('status', ['pending','in_progress'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', $thresholdDate)
            ->get();

        $log->info("Found " . $stalledMovements->count() . " potentially stalled movements (Threshold: $daysThreshold days).");

        $escalatedCount = 0;

        $admins = User::where('role_id', 8)->get();

        $systemEmail = config('jshb.mail_system_username', 'system@adms.jshb.computered.co.in');

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
                $notificationService->send([
                    'user_id' => $admin->id,
                    'is_allottee' => false,
                    'notification_type' => 'warning',
                    'subject' => "ESCALATION: Stalled Application #$appNo",
                    'message' => "Application #$appNo has been stalled with Engineer $engineerName for more than $daysThreshold days past its due date. No extension has been requested.",
                    'link' => null, // Dynamic admin link not guaranteed, fallback to null
                    'send_email' => true,
                    'cc' => $systemEmail,
                    'application_id' => $movement->application_id,
                ]);
            }

            // Notify Engineer (Warning)
            $notificationService->send([
                'user_id' => $movement->to_user_id,
                'is_allottee' => false,
                'notification_type' => 'danger',
                'subject' => "WARNING: Application #$appNo Escalated",
                'message' => "Your pending Application #$appNo is overdue by more than $daysThreshold days. This has been automatically escalated to the Admin.",
                'link' => route('engineer.applications.show', $movement->application_id),
                'send_email' => true,
                'application_id' => $movement->application_id,
            ]);

            $escalatedCount++;
            $log->info("Escalated Application #$appNo stalled with $engineerName.");
            $this->info("Escalated Application #$appNo stalled with $engineerName.");
        }

        $log->info("--- Escalation check complete. $escalatedCount applications escalated. ---");
        $this->info("Escalation check complete. $escalatedCount applications escalated.");
    }
}
