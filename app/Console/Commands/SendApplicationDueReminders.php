<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApplicationMovement;
use App\Models\User;
use App\Models\BatchProgram;
use App\Models\BatchProgramDetail;
use App\Mail\ApplicationDueReminderMail;
use App\Jobs\ProcessBatchEmailJob;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendApplicationDueReminders extends Command
{
    protected $signature = 'app:send-application-due-reminders';
    protected $description = 'Send reminder emails for pending application movements that have 5 days left until due date, using custom batch tracking';

    public function handle()
    {
        $log = Log::build(['driver' => 'single', 'path' => storage_path('logs/application_due_reminders.log')]);
        $log->info('--- Starting Application Due Reminders Command (with Batch Tracking) ---');

        $query = ApplicationMovement::with(['toUser', 'application'])
            ->whereIn('status', ['pending','in_progress'])
            ->whereNotNull('due_date');

        $log->info("Executing SQL Query: " . $query->toSql());
        $movements = $query->get();
        $log->info("Query Response: Found " . $movements->count() . " pending movements with a due date in database.");

        if ($movements->isEmpty()) {
            $this->info("No pending movements found. Exiting.");
            return;
        }

        // Create Master Batch Record
        $batchProgram = BatchProgram::create([
            'command_name' => 'SendApplicationDueReminders',
            'started_at' => now(),
            'status' => 'running',
        ]);

        $count = 0;
        $uniqueEngineers = [];

        foreach ($movements as $movement) {
            $dueDate = Carbon::parse($movement->due_date)->startOfDay();
            $today = now()->startOfDay();

            if ($dueDate->isPast()) {
                continue;
            }

            $diffDays = $today->diffInDays($dueDate);

            // Send reminders only when exactly 5, 3, 1, or 0 days are left
            if (in_array($diffDays, [5, 3, 1, 0])) {
                $user = $movement->toUser;

                if ($user && $user->email) {
                    // Track unique engineer
                    $uniqueEngineers[$user->id] = true;

                    // 1. Create Detail Record
                    $detail = BatchProgramDetail::create([
                        'batch_program_id' => $batchProgram->id,
                        'user_id' => $user->id,
                        'application_id' => $movement->application_id,
                        'status' => 'queued',
                        'queued_at' => now(),
                    ]);

                    // 2. Prepare the Mailable
                    $applicationNo = $movement->application ? $movement->application->application_no : 'Unknown';
                    $dueDateFormatted = Carbon::parse($movement->due_date)->format('d M Y');
                    $mailable = new ApplicationDueReminderMail(
                        $user->name,
                        $applicationNo,
                        $dueDateFormatted,
                        $diffDays
                    );

                    // 3. Dispatch Job
                    ProcessBatchEmailJob::dispatch($detail->id, $user->email, $mailable);

                    $log->info("Job Queued (Detail ID {$detail->id}) for {$user->email}, App ID {$movement->application_id}");
                    $count++;
                } else {
                    $log->warning("No email found for User ID {$movement->to_user_id} assigned to Application ID {$movement->application_id}");
                }
            }
        }

        // Update Master Batch Record
        $batchProgram->update([
            'total_jobs' => $count,
            'unique_engineers_count' => count($uniqueEngineers),
            'completed_at' => now(),
            'status' => 'completed', // Command completed dispatching
        ]);

        $log->info("--- Completed Application Due Reminders Command. Total queued: {$count} ---");
        $this->info("Completed. Total reminders queued: {$count}. Batch ID: {$batchProgram->id}");
    }
}
