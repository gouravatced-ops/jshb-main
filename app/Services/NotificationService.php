<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\AllotteeNotification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\CommunicationTrack;
use App\Mail\GenericNotificationMail;

class NotificationService
{
    /**
     * Send a notification across requested channels.
     *
     * @param array $params [
     *   'user_id' => int, // The target user's ID
     *   'is_allottee' => bool, // Default false. Set true for allottee notifications (adms_allottees DB)
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
     * @return Model
     */
    public function send(array $params): Model
    {
        $userId = $params['user_id'] ?? null;
        $isAllottee = $params['is_allottee'] ?? false;
        $subject = $params['subject'] ?? 'Notification';
        $message = $params['message'] ?? '';
        $notificationType = $params['notification_type'] ?? 'info';
        $link = $params['link'] ?? null;

        $sendEmail = $params['send_email'] ?? true;
        $sendSms = $params['send_sms'] ?? false;
        $sendWhatsapp = $params['send_whatsapp'] ?? false;

        $emailId = $params['email_id'] ?? null;
        $phoneNumber = $params['phone_number'] ?? null;

        $user = null;
        if ($userId) {
            $userQuery = $isAllottee ? User::on('adms_allottees') : User::query();
            $user = $userQuery->find($userId);
            if ($user) {
                if (!$emailId) $emailId = $user->email;
                if (!$phoneNumber) $phoneNumber = $user->phone ?? $user->mobile ?? '';
            }
        }

        // Apply Communication Settings based on user's role
        if ($user && $user->role_id) {
            $commSetting = \App\Models\CommunicationSetting::where('role_id', $user->role_id)->first();
            if ($commSetting) {
                // Only send if BOTH the original request AND the global setting allow it
                $sendEmail = $sendEmail && $commSetting->is_email_enabled;
                $sendSms = $sendSms && $commSetting->is_sms_enabled;
                $sendWhatsapp = $sendWhatsapp && $commSetting->is_whatsapp_enabled;
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
        $targetDb = $isAllottee ? 'adms_allottees' : 'adms_jshb';
        Log::channel('notification_log')->info("Initiating Notification to User ID: {$userId} (DB: {$targetDb}) | Subject: {$subject}");

        // 1. Send Email
        if ($sendEmail && filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
            try {
                $mailable = $params['mailable'] ?? new GenericNotificationMail($subject, $message, $link);
                Mail::to($emailId)->send($mailable);
                $isEmailSent = true;
                $emailSentAt = now();
                Log::channel('send_mail')->info("Email sent to {$emailId} | Subject: {$subject}");

                $this->logCommunication(
                    $isAllottee, $userId, 'email', $subject, $message, 'success', null, $params
                );
            } catch (\Exception $e) {
                Log::channel('send_mail')->error("Failed to send Email to {$emailId} | Error: " . $e->getMessage());

                $this->logCommunication(
                    $isAllottee, $userId, 'email', $subject, $message, 'failed', $e->getMessage(), $params
                );
            }
        } elseif ($sendEmail) {
            Log::channel('send_mail')->warning("Skipped Email sending: Invalid or missing email address for User ID: {$userId}");
            $this->logCommunication(
                $isAllottee, $userId, 'email', $subject, $message, 'failed', 'Invalid or missing email address', $params
            );
        }

        // 2. Send SMS
        if ($sendSms && !empty($phoneNumber)) {
            try {
                $isSmsSent = true;
                $smsSentAt = now();
                Log::channel('sms')->info("SMS sent to {$phoneNumber} | Message: {$message}");

                $this->logCommunication(
                    $isAllottee, $userId, 'sms', $subject, $message, 'success', null, $params
                );
            } catch (\Exception $e) {
                Log::channel('sms')->error("Failed to send SMS to {$phoneNumber} | Error: " . $e->getMessage());

                $this->logCommunication(
                    $isAllottee, $userId, 'sms', $subject, $message, 'failed', $e->getMessage(), $params
                );
            }
        } elseif ($sendSms) {
            Log::channel('sms')->warning("Skipped SMS sending: Invalid or missing phone number for User ID: {$userId}");
            $this->logCommunication(
                $isAllottee, $userId, 'sms', $subject, $message, 'failed', 'Invalid or missing phone number', $params
            );
        }

        // 3. Send WhatsApp
        if ($sendWhatsapp && !empty($phoneNumber)) {
            try {
                $isWhatsappSent = true;
                $whatsappSentAt = now();
                Log::channel('whatsapp')->info("WhatsApp sent to {$phoneNumber} | Message: {$message}");

                $this->logCommunication(
                    $isAllottee, $userId, 'whatsapp', $subject, $message, 'success', null, $params
                );
            } catch (\Exception $e) {
                Log::channel('whatsapp')->error("Failed to send WhatsApp to {$phoneNumber} | Error: " . $e->getMessage());

                $this->logCommunication(
                    $isAllottee, $userId, 'whatsapp', $subject, $message, 'failed', $e->getMessage(), $params
                );
            }
        } elseif ($sendWhatsapp) {
            Log::channel('whatsapp')->warning("Skipped WhatsApp sending: Invalid or missing phone number for User ID: {$userId}");
            $this->logCommunication(
                $isAllottee, $userId, 'whatsapp', $subject, $message, 'failed', 'Invalid or missing phone number', $params
            );
        }

        // 4. Save to Database
        $notificationData = [
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
            'is_push_sent' => $isWhatsappSent,
            'push_sent_at' => $whatsappSentAt,
        ];

        if (isset($params['application_id'])) {
            $notificationData['application_id'] = $params['application_id'];
        }

        if ($isAllottee) {
            $notification = AllotteeNotification::create($notificationData);
        } else {
            $notification = Notification::create($notificationData);
        }

        Log::channel('notification_log')->info("Notification saved to DB ({$targetDb}) | Notification ID: {$notification->id}");

        // Broadcast Real-time Notification if it's for Engineers (jshb)
        if (!$isAllottee && $userId) {
            try {
                \App\Events\EngineerNotificationEvent::dispatch($userId, $notification->toArray());
            } catch (\Exception $e) {
                Log::channel('notification_log')->error("Failed to broadcast EngineerNotificationEvent | Error: " . $e->getMessage());
            }
        }

        return $notification;
    }

    private function logCommunication($isAllottee, $userId, $type, $subject, $content, $status, $error, $params = [])
    {
        $request = request();
        $senderId = \Illuminate\Support\Facades\Auth::id();
        $senderType = $senderId ? 'jshb_user' : 'system';
        
        // If an allottee is doing an action in their portal, how do we know?
        // Usually, internal users have Auth::id(), but allottees might have Auth::guard('allottee')->id() or similar.
        // We must check if the guard is defined first to avoid "Auth guard not defined" errors.
        if (config('auth.guards.allottee') && \Illuminate\Support\Facades\Auth::guard('allottee')->check()) {
            $senderType = 'allottee';
            $senderId = \Illuminate\Support\Facades\Auth::guard('allottee')->id();
        }

        $appId = $params['application_id'] ?? null;
        $allotteeId = $params['allottee_id'] ?? ($isAllottee ? $userId : null);

        $receiverType = $isAllottee ? 'allottee' : 'jshb_user';
        $roleId = null;

        if (!$isAllottee && $userId) {
            $user = User::find($userId);
            $roleId = $user ? $user->role_id : null;
        }

        CommunicationTrack::create([
            'application_id' => $appId,
            'allottee_id' => $allotteeId,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'receiver_type' => $receiverType,
            'receiver_id' => $userId,
            'role_id' => $roleId,
            'communication_type' => $type,
            'subject' => $subject,
            'content' => $content,
            'ip_address' => $request->ip(),
            'browser_agent' => $request->userAgent(),
            'status' => $status,
            'error_message' => $error,
        ]);
    }
}
