<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;
use Thenetbotz\EmailValidator\Facades\EmailValidator;

class ValidateEmailRecipientListener
{
    /**
     * Map validation reason code to human-readable explanation.
     */
    public static function getReadableReason(string $reason): string
    {
        return match ($reason) {
            'invalid_syntax'     => 'Email format/syntax is invalid (RFC non-compliant)',
            'no_mx_record'       => 'Domain does not exist or has no active MX/mail server records (Un-mailable domain)',
            'disposable_domain'  => 'Domain is a temporary/disposable/fake email provider (e.g. Mailinator, Yopmail)',
            'smtp_rejected'      => 'Recipient address was rejected by the destination mail server',
            'smtp_unreachable'   => 'Destination mail server is unreachable or timed out',
            default              => 'Email validation failed (' . $reason . ')',
        };
    }

    /**
     * Handle the event.
     * Intercepts EVERY email before it leaves the application.
     * Validates RFC syntax, DNS domain, MX records, and detects disposable domains.
     *
     * @param MessageSending $event
     * @return bool|void Returns false to cancel sending if no valid 'To' recipient exists.
     */
    public function handle(MessageSending $event)
    {
        $message = $event->message;
        $subject = $message->getSubject() ?? '(No Subject)';

        // 1. Validate primary 'To' recipients
        $validTo = [];
        $invalidTo = [];

        foreach ($message->getTo() as $address) {
            $email = strtolower(trim($address->getAddress()));
            $result = EmailValidator::validate($email);

            if ($result->valid) {
                $validTo[] = $address;
            } else {
                $explanation = self::getReadableReason($result->reason);
                $invalidTo[] = [
                    'email'       => $email,
                    'reason'      => $result->reason,
                    'explanation' => $explanation,
                ];

                $logMsg = "[MAIL BLOCKED] Primary recipient '{$email}' failed validation | Reason: {$explanation} ({$result->reason}) | Subject: {$subject}";
                Log::warning($logMsg, [
                    'email'            => $email,
                    'subject'          => $subject,
                    'validation_error' => $result->reason,
                    'explanation'      => $explanation,
                ]);

                if (config()->has('logging.channels.send_mail')) {
                    Log::channel('send_mail')->warning($logMsg);
                }
            }
        }

        // If no valid 'To' recipient exists, cancel the email
        if (empty($validTo)) {
            Log::warning("[MAIL CANCELLED] No valid primary recipient remaining. Email not sent.", [
                'subject'   => $subject,
                'invalidTo' => $invalidTo,
            ]);

            return false;
        }

        // If some 'To' were valid and some invalid, update 'To' with only valid ones
        if (!empty($invalidTo)) {
            $message->to(...$validTo);
        }

        // 2. Validate and filter 'Cc' recipients
        $validCc = [];
        $invalidCc = [];

        foreach ($message->getCc() as $address) {
            $email = strtolower(trim($address->getAddress()));
            $result = EmailValidator::validate($email);

            if ($result->valid) {
                $validCc[] = $address;
            } else {
                $explanation = self::getReadableReason($result->reason);
                $invalidCc[] = [
                    'email'       => $email,
                    'reason'      => $result->reason,
                    'explanation' => $explanation,
                ];

                $logMsg = "[MAIL CC STRIPPED] CC recipient '{$email}' removed | Reason: {$explanation} ({$result->reason}) | Subject: {$subject}";
                Log::warning($logMsg, [
                    'email'            => $email,
                    'subject'          => $subject,
                    'validation_error' => $result->reason,
                    'explanation'      => $explanation,
                ]);

                if (config()->has('logging.channels.send_mail')) {
                    Log::channel('send_mail')->warning($logMsg);
                }
            }
        }

        if (!empty($invalidCc)) {
            $message->cc(...$validCc);
        }

        // 3. Validate and filter 'Bcc' recipients
        $validBcc = [];
        $invalidBcc = [];

        foreach ($message->getBcc() as $address) {
            $email = strtolower(trim($address->getAddress()));
            $result = EmailValidator::validate($email);

            if ($result->valid) {
                $validBcc[] = $address;
            } else {
                $explanation = self::getReadableReason($result->reason);
                $invalidBcc[] = [
                    'email'       => $email,
                    'reason'      => $result->reason,
                    'explanation' => $explanation,
                ];

                $logMsg = "[MAIL BCC STRIPPED] BCC recipient '{$email}' removed | Reason: {$explanation} ({$result->reason}) | Subject: {$subject}";
                Log::warning($logMsg, [
                    'email'            => $email,
                    'subject'          => $subject,
                    'validation_error' => $result->reason,
                    'explanation'      => $explanation,
                ]);

                if (config()->has('logging.channels.send_mail')) {
                    Log::channel('send_mail')->warning($logMsg);
                }
            }
        }

        if (!empty($invalidBcc)) {
            $message->bcc(...$validBcc);
        }

        return true;
    }
}
