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
        $totalMovements = ApplicationMovement::whereBetween('movement_date', [$startOfYesterday, $endOfYesterday])->count();
        $totalCompleted = Application::where('status', 'completed')
            ->whereBetween('updated_at', [$startOfYesterday, $endOfYesterday])->count();
        $totalDocsGenerated = DocumentGenerationQueue::where('status', 'completed')
            ->whereBetween('completed_at', [$startOfYesterday, $endOfYesterday])->count();

        // 2. Engineer Performance Report
        $movements = ApplicationMovement::with('fromUser.roleRelation')
            ->whereBetween('movement_date', [$startOfYesterday, $endOfYesterday])
            ->whereNotNull('from_user_id')
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

        // Send Email to Admins (Role ID 8) and Super-Admins/Management (Role ID 9)
        $recipients = User::whereIn('role_id', [8, 9])->pluck('email')->filter()->toArray();
        $fallbackEmail = config('gouravatced@gmail.com', 'computered3896@gmail.com');

        if (empty($recipients)) {
            $recipients = [$fallbackEmail];
        }

        $ccEmails = ['system@adms.jshb.computered.co.in'];

        // Create Batch Program Record
        $batch = BatchProgram::create([
            'command_name' => 'SendDailyActivityReport',
            'total_jobs' => count($recipients),
            'unique_engineers_count' => count($recipients), // loosely mapping
            'started_at' => now(),
            'status' => 'running',
        ]);

        $mailable = new DailyActivityReportMail($reportData, $reportDate);
        $mailBody = $mailable->render(); // Capture the HTML body

        $successCount = 0;

        foreach ($recipients as $email) {
            $user = User::where('email', $email)->first();

            $detail = BatchProgramDetail::create([
                'batch_program_id' => $batch->id,
                'user_id' => $user ? $user->id : null,
                'recipient_email' => $email,
                'cc_email' => implode(', ', $ccEmails),
                'mail_body' => $mailBody,
                'status' => 'queued',
                'queued_at' => now(),
            ]);

            try {
                // Send individually to track success/failure accurately per person
                Mail::to($email)
                    ->cc($ccEmails)
                    ->send($mailable);

                $detail->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $detail->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
                $log->error("Failed to send daily activity report to {$email}: " . $e->getMessage());
            }
        }

        $batch->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        if ($successCount > 0) {
            $log->info("Successfully sent daily activity report to {$successCount} recipients.");
            $this->info("Report sent successfully to {$successCount} recipients.");
        } else {
            $this->error("Failed to send reports. Check logs.");
        }
    }
}
