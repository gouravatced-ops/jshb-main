<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Application;
use App\Models\ApplicationMovement;
use App\Models\ApplicationCorrespondence;
use App\Models\BypassRequest;
use App\Models\DocumentGenerationQueue;
use App\Models\User;
use App\Models\BatchProgram;
use App\Models\BatchProgramDetail;
use App\Mail\DailyActivityReportMail;
use App\Jobs\ProcessBatchEmailJob;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendDailyActivityReport extends Command
{
    protected $signature = 'app:send-daily-activity-report';
    protected $description = 'Generate and send a daily activity report of the previous day to the admin';

    public function handle()
    {
        $log = Log::build(['driver' => 'single', 'path' => storage_path('logs/daily_activity_report.log')]);
        $log->info('--- Starting Daily Activity Report Cron Job ---');

        $startOfYesterday = Carbon::yesterday()->startOfDay();
        $endOfYesterday = Carbon::yesterday()->endOfDay();
        $reportDate = Carbon::yesterday()->format('d M Y');

        $log->info("Generating report for date: $reportDate");

        // 1. Application Stats
        $totalCreated = Application::whereBetween('created_at', [$startOfYesterday, $endOfYesterday])->count();
        $totalMovements = ApplicationMovement::whereNotIn('from_role_id', [8, 9])->whereBetween('movement_date', [$startOfYesterday, $endOfYesterday])->count();
        $totalCompleted = Application::where('status', 'completed')
            ->whereBetween('updated_at', [$startOfYesterday, $endOfYesterday])->count();
        $totalDocsGenerated = DocumentGenerationQueue::where('status', 'completed')
            ->whereBetween('completed_at', [$startOfYesterday, $endOfYesterday])->count();

        // 2. Engineer Performance Report
        $movements = ApplicationMovement::with('fromUser.roleRelation')
            ->whereNotIn('from_role_id', [8, 9])
            ->whereNotNull('from_user_id')
            ->whereBetween('movement_date', [$startOfYesterday, $endOfYesterday])
            ->get();

        $correspondences = ApplicationCorrespondence::with('generatedBy')
            ->whereBetween('created_at', [$startOfYesterday, $endOfYesterday])
            ->whereNotNull('generated_by_user_id')
            ->get();

        $engineersData = [];

        // Count Movements per user
        foreach ($movements as $mov) {
            $userId = $mov->from_user_id;
            if (!isset($engineersData[$userId])) {
                $engineersData[$userId] = [
                    'name' => $mov->fromUser->name ?? 'Unknown',
                    'role' => $mov->fromUser->roleRelation->name ?? 'User',
                    'movements' => 0,
                    'letters' => 0,
                ];
            }
            $engineersData[$userId]['movements']++;
        }

        // Count Letters per user
        foreach ($correspondences as $corr) {
            $userId = $corr->generated_by_user_id;
            if (!isset($engineersData[$userId])) {
                $engineersData[$userId] = [
                    'name' => $corr->generatedBy->name ?? 'Unknown',
                    'role' => $corr->generatedBy->roleRelation->name ?? 'User',
                    'movements' => 0,
                    'letters' => 0,
                ];
            }
            $engineersData[$userId]['letters']++;
        }

        $engineerReport = array_values($engineersData);
        usort($engineerReport, function($a, $b) {
            return $b['movements'] <=> $a['movements']; // Sort by highest movements
        });

        // 3. Bypass Report
        $bypassRequests = BypassRequest::with(['application', 'requestedBy', 'targetUser', 'targetRole'])
            ->whereBetween('created_at', [$startOfYesterday, $endOfYesterday])
            ->get();

        $bypassReport = [];
        foreach ($bypassRequests as $bypass) {
            $sentTo = $bypass->targetUser->name ?? ($bypass->targetRole->name ?? 'Unknown');
            $bypassReport[] = [
                'app_no' => $bypass->application->application_no ?? 'Unknown',
                'requested_by' => $bypass->requestedBy->name ?? 'Unknown',
                'sent_to' => $sentTo,
                'reason' => $bypass->reason ?? 'No reason provided',
            ];
        }

        // Package Data
        $reportData = [
            'total_created' => $totalCreated,
            'total_movements' => $totalMovements,
            'total_completed' => $totalCompleted,
            'total_docs_generated' => $totalDocsGenerated,
            'engineer_report' => $engineerReport,
            'bypass_report' => $bypassReport,
        ];

        // 1. Primary recipient is system email
        $systemEmail = config('jshb.mail_system_username', 'system@adms.jshb.computered.co.in');

        // 2. CC recipients are ONLY Super-Admin accounts (Role ID 9) - Admin role (Role ID 8) is excluded
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

        // Remove system email from CC if it's already there to prevent duplicate sending
        $ccEmails = array_values(array_diff($superAdminEmails, [$systemEmail]));
        if (empty($ccEmails)) {
            $ccEmails = [$fallbackEmail];
        }

        // Create Batch Program Record (Single Job)
        $batch = BatchProgram::create([
            'command_name'           => 'SendDailyActivityReport',
            'total_jobs'             => 1,
            'unique_engineers_count' => 1,
            'started_at'             => now(),
            'status'                 => 'running',
        ]);

        $mailable = new DailyActivityReportMail($reportData, $reportDate);
        $mailBody = $mailable->render(); // Capture the HTML body

        // Find system user if available
        $systemUser = User::where('email', $systemEmail)->first();

        // Create a SINGLE detail row with system as recipient and multiple super-admins in CC
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
            $freshMailable = new DailyActivityReportMail($reportData, $reportDate);

            // Dispatch single job to queue
            ProcessBatchEmailJob::dispatch($detail->id, $systemEmail, $freshMailable);
            $success = true;
        } catch (\Exception $e) {
            $detail->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            $log->error("Failed to queue daily activity report: " . $e->getMessage());
        }

        $batch->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        if ($success) {
            $log->info("Successfully queued single daily activity report to {$systemEmail} with CC to super-admins: " . implode(', ', $ccEmails));
            $this->info("Daily activity report queued successfully (To: {$systemEmail}, CC: " . implode(', ', $ccEmails) . ").");
        } else {
            $this->error("Failed to send report. Check logs.");
        }
    }
}
