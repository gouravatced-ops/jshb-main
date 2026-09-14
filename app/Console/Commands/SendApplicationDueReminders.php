<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApplicationMovement;
use App\Models\User;
use App\Mail\ApplicationDueReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendApplicationDueReminders extends Command
{
    protected $signature = 'app:send-application-due-reminders';
    protected $description = 'Send reminder emails for pending application movements that have 5 days left until due date';

    public function handle()
    {
        $log = Log::build(['driver' => 'single', 'path' => storage_path('logs/application_due_reminders.log')]);
        $log->info('--- Starting Application Due Reminders Command ---');

        $query = ApplicationMovement::with(['toUser', 'application'])
            ->whereIn('status', ['pending','in_progress'])
            ->whereNotNull('due_date');

        // Yahan par hum actual SQL Query log kar rahe hain
        $log->info("Executing SQL Query: " . $query->toSql());

        $movements = $query->get();

        // Yahan par hum Query ka Response (kitne record mile) log kar rahe hain
        $log->info("Query Response: Found " . $movements->count() . " pending movements with a due date in database.");

        $count = 0;

        foreach ($movements as $movement) {
            $dueDate = Carbon::parse($movement->due_date)->startOfDay();
            $today = now()->startOfDay();

            if ($dueDate->isPast()) {
                continue;
            }

            $diffDays = $today->diffInDays($dueDate);

            if ($diffDays <= 5) {
                $user = $movement->toUser;

                if ($user && $user->email) {
                    $this->sendReminderEmail($movement, $user, $diffDays);
                    $log->info("Reminder sent to {$user->email} for Application ID {$movement->application_id} ({$diffDays} days left)");
                    $count++;
                } else {
                    $log->warning("No email found for User ID {$movement->to_user_id} assigned to Application ID {$movement->application_id}");
                }
            }
        }

        $log->info("--- Completed Application Due Reminders Command. Total sent: {$count} ---");
        $this->info("Completed. Total reminders sent: {$count}");
    }

    private function sendReminderEmail($movement, $user, $diffDays)
    {
        $applicationNo = $movement->application ? $movement->application->application_no : 'Unknown';
        $dueDateFormatted = Carbon::parse($movement->due_date)->format('d M Y');

        Mail::to($user->email)->send(new ApplicationDueReminderMail(
            $user->name,
            $applicationNo,
            $dueDateFormatted,
            $diffDays
        ));
    }
}
