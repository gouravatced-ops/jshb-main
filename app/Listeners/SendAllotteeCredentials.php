<?php

namespace App\Listeners;

use App\Events\AllotteeCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\NotificationService;
use App\Mail\AllotteeCredentialMail;
use Illuminate\Support\Facades\Log;

class SendAllotteeCredentials implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(AllotteeCreated $event): void
    {
        try {
            $notificationService = app(NotificationService::class);
            $portalUrl = config('jshb.allottee_portal_url', 'http://localhost/jshb-allottees');

            // 1. Send Credential Email to Allottee
            $notificationService->send([
                'user_id'           => $event->user->id,
                'application_id'    => $event->application ? $event->application->id : null,
                'allottee_id'       => $event->allottee->id,
                'is_allottee'       => true,
                'notification_type' => 'info',
                'subject'           => 'Your JSHB Allottee Portal Login Credentials',
                'message'           => 'Credentials sent automatically after Step 3.',
                'email_id'          => $event->user->email,
                'send_email'        => true,
                'mailable'          => new AllotteeCredentialMail($event->user->username, $event->plainPassword, $portalUrl)
            ]);

            // 2. Send System Email to Admin
            $systemEmail = config('jshb.mail_system_username', 'system@adms.jshb.computered.co.in');
            $adminSubject = "New Allottee Created";
            $adminMessage = "Dear Admin,\n\nA new allottee has been successfully created in the system.\n\nAllottee Name: {$event->user->name}\nUsername/Email: {$event->user->username}\n\nPlease check the JSHB portal for more details.";

            $notificationService->send([
                'user_id'           => 6, // Hardcoded per user request in previous session
                'email_id'          => $systemEmail,
                'subject'           => $adminSubject,
                'message'           => $adminMessage,
                'notification_type' => 'system',
                'send_email'        => true,
                'send_sms'          => false
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send allottee credentials in listener: ' . $e->getMessage());
        }
    }
}
