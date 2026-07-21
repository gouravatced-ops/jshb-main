<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericNotificationMail;

class NotificationService
{
    /**
     * Send a notification across requested channels.
     *
     * @param array $params [
     *   'user_id' => int, // The target user's ID
     *   'notification_type' => string, // e.g., 'success', 'warning', 'info'
     *   'subject' => string, // Subject of the notification
     *   'message' => string, // Body of the notification
     *   'email_id' => string|null, // Optional email to send to. Defaults to User's email.
     *   'phone_number' => string|null, // Optional phone to send to. Defaults to User's phone.
     *   'link' => string|null, // Optional call to action link
     *   'send_email' => bool, // Default true
     *   'send_sms' => bool, // Default false
     *   'send_whatsapp' => bool, // Default false
     * ]
     * @return Notification
     */
    public function send(array $params): Notification
    {
        $userId = $params['user_id'] ?? null;
        $subject = $params['subject'] ?? 'Notification';
        $message = $params['message'] ?? '';
        $notificationType = $params['notification_type'] ?? 'info';
        $link = $params['link'] ?? null;

        $sendEmail = $params['send_email'] ?? true;
        $sendSms = $params['send_sms'] ?? false;
        $sendWhatsapp = $params['send_whatsapp'] ?? false;

        $emailId = $params['email_id'] ?? null;
        $phoneNumber = $params['phone_number'] ?? null;

        if ($userId && (!$emailId || !$phoneNumber)) {
            $user = User::find($userId);
            if ($user) {
                if (!$emailId) $emailId = $user->email;
                if (!$phoneNumber) $phoneNumber = $user->phone ?? $user->mobile ?? ''; // Adjust based on DB structure
            }
        }

        // Development mode override
        if (config('app.env') === 'local') {
            $emailId = 'gouravatced@gmail.com';
        }

        // Initialize delivery statuses
        $isEmailSent = false;
        $isSmsSent = false;
        $isWhatsappSent = false;
        
        $emailSentAt = null;
        $smsSentAt = null;
        $whatsappSentAt = null;

        // General Notification Log
        Log::channel('notification_log')->info("Initiating Notification to User ID: {$userId} | Subject: {$subject}");

        // 1. Send Email
        if ($sendEmail && filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
            try {
                $mailable = $params['mailable'] ?? new GenericNotificationMail($subject, $message, $link);
                Mail::to($emailId)->send($mailable);
                $isEmailSent = true;
                $emailSentAt = now();
                Log::channel('send_mail')->info("Email sent to {$emailId} | Subject: {$subject}");
            } catch (\Exception $e) {
                Log::channel('send_mail')->error("Failed to send Email to {$emailId} | Error: " . $e->getMessage());
            }
        } elseif ($sendEmail) {
            Log::channel('send_mail')->warning("Skipped Email sending: Invalid or missing email address for User ID: {$userId}");
        }

        // 2. Send SMS
        if ($sendSms && !empty($phoneNumber)) {
            try {
                // TODO: Integrate actual SMS Gateway API here
                // Example: SmsGateway::send($phoneNumber, $message);
                
                $isSmsSent = true;
                $smsSentAt = now();
                Log::channel('sms')->info("SMS sent to {$phoneNumber} | Message: {$message}");
            } catch (\Exception $e) {
                Log::channel('sms')->error("Failed to send SMS to {$phoneNumber} | Error: " . $e->getMessage());
            }
        } elseif ($sendSms) {
            Log::channel('sms')->warning("Skipped SMS sending: Invalid or missing phone number for User ID: {$userId}");
        }

        // 3. Send WhatsApp
        if ($sendWhatsapp && !empty($phoneNumber)) {
            try {
                // TODO: Integrate actual WhatsApp Gateway API here
                // Example: WhatsappGateway::send($phoneNumber, $message);
                
                $isWhatsappSent = true;
                $whatsappSentAt = now();
                Log::channel('whatsapp')->info("WhatsApp sent to {$phoneNumber} | Message: {$message}");
            } catch (\Exception $e) {
                Log::channel('whatsapp')->error("Failed to send WhatsApp to {$phoneNumber} | Error: " . $e->getMessage());
            }
        } elseif ($sendWhatsapp) {
            Log::channel('whatsapp')->warning("Skipped WhatsApp sending: Invalid or missing phone number for User ID: {$userId}");
        }

        // 4. Save to Database
        $notification = Notification::create([
            'user_id' => $userId,
            'notification_type' => $notificationType,
            'subject' => $subject,
            'message' => $message,
            'link' => $link,
            'is_read' => false,
            'is_email_sent' => $isEmailSent,
            'email_sent_at' => $emailSentAt,
            'is_sms_sent' => $isSmsSent,
            'sms_sent_at' => $smsSentAt,
            'is_push_sent' => $isWhatsappSent, // Using push for WhatsApp
            'push_sent_at' => $whatsappSentAt,
        ]);

        Log::channel('notification_log')->info("Notification saved to DB | Notification ID: {$notification->id}");

        return $notification;
    }
}
