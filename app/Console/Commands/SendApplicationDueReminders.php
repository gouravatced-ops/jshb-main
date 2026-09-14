<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApplicationMovement;
use App\Models\User;
use App\Mail\GenericNotificationMail;
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

        $movements = ApplicationMovement::with(['toUser', 'application'])
            ->where('status', 'pending')
            ->whereNotNull('due_date')
            ->get();

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
        
        $subject = "Application Lock Warning: Due Date Approaching - {$applicationNo}";
        
        $mailBody = "<p>Dear {$user->name},</p>";
        $mailBody .= "<p>This is a reminder regarding a pending application assigned to you.</p>";
        $mailBody .= "<ul>";
        $mailBody .= "<li><strong>Application No:</strong> {$applicationNo}</li>";
        $mailBody .= "<li><strong>Action Required:</strong> Please review and process this application.</li>";
        $mailBody .= "<li><strong>Due Date:</strong> {$dueDateFormatted}</li>";
        $mailBody .= "</ul>";

        if ($diffDays == 0) {
            $mailBody .= "<p><strong style='color:red; font-size: 16px;'>TODAY is the last day to process this application!</strong></p>";
        } else {
            $mailBody .= "<p>You have <strong style='color:red;'>{$diffDays} days remaining</strong> to process this application before the due date.</p>";
        }

        $mailBody .= "<hr><p style='color: #777; font-size: 13px;'><strong>Note:</strong> If the application is not processed by the due date, it will be locked and you will not be able to view or process it. After locking, you must submit a formal request to the administration with a valid reason to unlock it.</p>";

        Mail::to($user->email)->send(new GenericNotificationMail($subject, $mailBody, null));
    }
}
