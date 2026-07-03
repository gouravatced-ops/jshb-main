<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class SendOtpEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * Seconds to wait before retrying.
     */
    public $backoff = 30;

    public string $email;
    public $otp;
    public $messageBody;
    public $purpose;
    public $userName;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email, string $otp, string $messageBody, string $purpose = 'login', ?string $userName = null)
    {
        $this->email = $email;
        $this->otp = $otp;
        $this->messageBody = $messageBody;
        $this->purpose = $purpose;
        $this->userName = $userName;

        // Use 'emails' queue for OTP mails
        $this->onQueue('emails');
    }

    /**
     * Mask OTP for logging
     */
    private function maskOtp(string $otp): string
    {
        return substr($otp, 0, 2) . str_repeat('*', strlen($otp) - 2);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Log SMTP config details before sending — OTP is MASKED
        Log::channel('otp_sent')->info('=== OTP EMAIL SENDING START ===', [
            'to_email'        => $this->email,
            'otp_masked'      => $this->maskOtp($this->otp),
            'purpose'         => $this->purpose,
            'smtp_host'       => config('mail.mailers.smtp.host'),
            'smtp_port'       => config('mail.mailers.smtp.port'),
            'smtp_user'       => config('mail.mailers.smtp.username'),
            'smtp_encryption' => config('mail.mailers.smtp.encryption'),
            'mail_from'       => config('mail.from.address'),
            'mail_from_name'  => config('mail.from.name'),
            'mailer'          => config('mail.default'),
            'app_env'         => config('app.env'),
            'timestamp'       => now()->toDateTimeString(),
        ]);

        try {
            Mail::to($this->email)->send(new OtpMail(
                $this->otp,
                $this->messageBody,
                [],
                $this->purpose,
                $this->userName
            ));

            Log::channel('otp_sent')->info('=== OTP EMAIL SENT SUCCESS ===', [
                'to_email'    => $this->email,
                'otp_masked'  => $this->maskOtp($this->otp),
                'purpose'     => $this->purpose,
                'smtp_host'   => config('mail.mailers.smtp.host'),
                'smtp_user'   => config('mail.mailers.smtp.username'),
                'sent_at'     => now()->toDateTimeString(),
            ]);
        } catch (\Exception $e) {
            Log::channel('otp_sent')->error('=== OTP EMAIL SEND FAILED ===', [
                'to_email'   => $this->email,
                'otp_masked' => $this->maskOtp($this->otp),
                'purpose'    => $this->purpose,
                'smtp_host'  => config('mail.mailers.smtp.host'),
                'smtp_port'  => config('mail.mailers.smtp.port'),
                'smtp_user'  => config('mail.mailers.smtp.username'),
                'error'      => $e->getMessage(),
                'error_code' => $e->getCode(),
                'attempt'    => $this->attempts(),
                'max_tries'  => $this->tries,
                'failed_at'  => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure after all retries exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('=== OTP EMAIL PERMANENTLY FAILED (ALL RETRIES EXHAUSTED) ===', [
            'to_email'       => $this->email,
            'otp_masked'     => $this->maskOtp($this->otp),
            'purpose'        => $this->purpose,
            'smtp_host'      => config('mail.mailers.smtp.host'),
            'smtp_user'      => config('mail.mailers.smtp.username'),
            'error'          => $exception->getMessage(),
            'total_attempts' => $this->attempts(),
            'failed_at'      => now()->toDateTimeString(),
        ]);
    }
}