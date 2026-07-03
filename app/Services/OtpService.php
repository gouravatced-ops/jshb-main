<?php

namespace App\Services;

use App\Jobs\SendOtpEmail;
use App\Models\OtpLog;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Generate a random OTP code
     */
    public function generateOtp($length = 6): string
    {
        return str_pad((string) random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    /**
     * Mask OTP for logging (show only first 2 chars + ****)
     */
    public function maskOtp(string $otp): string
    {
        return substr($otp, 0, 2) . str_repeat('*', strlen($otp) - 2);
    }

    /**
     * Encrypt OTP code for database storage using APP_KEY
     */
    public function encryptOtp(string $otp): string
    {
        return Crypt::encryptString($otp);
    }

    /**
     * Decrypt OTP code from database using APP_KEY
     */
    public function decryptOtp(string $encryptedOtp): string
    {
        return Crypt::decryptString($encryptedOtp);
    }

    /**
     * Get the email address to send OTP to.
     * In development (local), all emails go to OTP_DEV_EMAIL.
     * In production, emails go to the user's actual email.
     */
    public function getRecipientEmail(string $userEmail): string
    {
        if (app()->environment('local')) {
            $devEmail = env('OTP_DEV_EMAIL', 'gouravatced@gmail.com');
            Log::info("OTP Dev Mode: Redirecting email from {$userEmail} to {$devEmail}");
            return $devEmail;
        }

        return $userEmail;
    }

    /**
     * Store OTP in database (otp_logs table) — ENCRYPTED
     */
    public function storeOtp(
        ?int $userId,
        string $email,
        string $otp,
        string $purpose = 'login',
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): OtpLog {
        $expiryMinutes = (int) env('OTP_EXPIRY_MINUTES', 10);

        $otpLog = OtpLog::create([
            'user_id'    => $userId,
            'email'      => $email,
            'otp_code'   => $this->encryptOtp($otp), // Encrypted in DB
            'verified'   => false,
            'purpose'    => $purpose,
            'expires_at' => now()->addMinutes($expiryMinutes),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);

        Log::info("OTP stored in DB (encrypted) for: {$email} (user_id: {$userId}), purpose: {$purpose}, otp: {$this->maskOtp($otp)}, expires in: {$expiryMinutes} min");

        return $otpLog;
    }

    /**
     * Verify OTP from database
     * Decrypts stored OTP and compares with input
     */
    public function verifyOtp(?int $userId, string $otpCode, string $purpose = 'login'): bool
    {
        $otpLog = OtpLog::where('user_id', $userId)
            ->where('purpose', $purpose)
            ->where('verified', false)
            ->latest()
            ->first();

        if (!$otpLog) {
            Log::warning("OTP verify failed: No OTP found for user_id: {$userId}, purpose: {$purpose}");
            return false;
        }

        // Check expiry
        if ($otpLog->isExpired()) {
            Log::warning("OTP verify failed: OTP expired for user_id: {$userId}");
            return false;
        }

        // Decrypt stored OTP and compare
        try {
            $storedOtp = $this->decryptOtp($otpLog->otp_code);
        } catch (\Exception $e) {
            Log::error("OTP decrypt failed for user_id: {$userId}, error: {$e->getMessage()}");
            return false;
        }

        if ($storedOtp !== $otpCode) {
            Log::warning("OTP verify failed: Invalid OTP code for user_id: {$userId}, entered: {$this->maskOtp($otpCode)}");
            return false;
        }

        // Mark as verified
        $otpLog->update(['verified' => true]);
        Log::info("OTP verified successfully for user_id: {$userId}, purpose: {$purpose}");

        return true;
    }

    /**
     * Send OTP email via Job (Queue)
     * In dev mode, email goes to OTP_DEV_EMAIL
     * Uses SendOtpEmail job for async processing
     * OTP value is masked in logs
     */
    public function sendOtpEmail(string $userEmail, string $otp, string $messageBody, string $purpose = 'login', ?string $userName = null): void
    {
        $recipientEmail = $this->getRecipientEmail($userEmail);

        Log::channel('otp_dispatch')->info('=== OTP EMAIL DISPATCH ===', [
            'original_user_email' => $userEmail,
            'actual_recipient'    => $recipientEmail,
            'is_dev_override'     => ($recipientEmail !== $userEmail) ? 'YES → Dev mode active' : 'NO → Production mode',
            'otp_masked'          => $this->maskOtp($otp),
            'purpose'             => $purpose,
            'user_name'           => $userName,
            'queue'               => 'emails',
            'queue_connection'    => config('queue.default'),
            'smtp_host'           => config('mail.mailers.smtp.host'),
            'smtp_port'           => config('mail.mailers.smtp.port'),
            'smtp_from'           => config('mail.from.address'),
            'dispatched_at'       => now()->toDateTimeString(),
        ]);

        // Dispatch job to queue — OTP is passed in plain text to job (for email body)
        SendOtpEmail::dispatch($recipientEmail, $otp, $messageBody, $purpose, $userName);

        Log::channel('otp_dispatch')->info("OTP email job dispatched successfully to queue for: {$recipientEmail}");
    }

    /**
     * Generate + Store + Send OTP (all-in-one)
     * If valid OTP already exists → re-send SAME OTP (no new generation)
     * If no valid OTP → generate new, store, send
     */
    public function generateAndSendOtp(
        ?int $userId,
        string $email,
        string $purpose = 'login',
        ?string $messageBody = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): OtpLog {
        // Default message based on purpose
        if (!$messageBody) {
            $messageBody = match ($purpose) {
                'login'          => 'Your OTP for login verification is:',
                'password_reset' => 'Your OTP for password reset is:',
                default          => 'Your OTP verification code is:',
            };
        }

        $userName = null;
        if ($userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                $userName = $user->name;
            }
        }

        // Check if a valid (non-expired, non-verified) OTP already exists
        $existingOtp = OtpLog::where('user_id', $userId)
            ->where('purpose', $purpose)
            ->where('verified', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if ($existingOtp) {
            // Decrypt existing OTP to resend
            try {
                $decryptedOtp = $this->decryptOtp($existingOtp->otp_code);
            } catch (\Exception $e) {
                Log::error("Failed to decrypt existing OTP for user_id: {$userId}, generating new one");
                // Fall through to generate new OTP
                $decryptedOtp = null;
            }

            if ($decryptedOtp) {
                Log::info("OTP reused (existing valid OTP found) for user_id: {$userId}, otp_masked: {$this->maskOtp($decryptedOtp)}, purpose: {$purpose}, expires_at: {$existingOtp->expires_at}");
                $this->sendOtpEmail($email, $decryptedOtp, $messageBody, $purpose, $userName);
                return $existingOtp;
            }
        }

        // No valid OTP exists — generate new one
        $otp = $this->generateOtp();

        // Store in database (encrypted)
        $otpLog = $this->storeOtp($userId, $email, $otp, $purpose, $ipAddress, $userAgent);

        // Send email via queue (plain text OTP for email body)
        $this->sendOtpEmail($email, $otp, $messageBody, $purpose, $userName);

        return $otpLog;
    }

    /**
     * Resend OTP
     * - Checks cooldown (minimum 60 seconds between resends)
     * - Generates new OTP + stores in DB + dispatches email job
     */
    public function resendOtp(
        ?int $userId,
        string $email,
        string $purpose = 'login',
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): array {
        // Check last OTP sent time (cooldown: 60 seconds)
        $lastOtp = OtpLog::where('user_id', $userId)
            ->where('purpose', $purpose)
            ->latest()
            ->first();

        if ($lastOtp && $lastOtp->created_at->diffInSeconds(now()) < 60) {
            $remainingSeconds = 60 - $lastOtp->created_at->diffInSeconds(now());
            Log::warning("OTP resend blocked: Cooldown active for user_id: {$userId}, wait: {$remainingSeconds}s");
            return [
                'success'  => false,
                'message'  => "Please wait {$remainingSeconds} seconds before requesting a new OTP.",
                'cooldown' => $remainingSeconds,
            ];
        }

        // Generate and send new OTP
        $messageBody = match ($purpose) {
            'login'          => 'Your new OTP for login verification is:',
            'password_reset' => 'Your new OTP for password reset is:',
            default          => 'Your new OTP verification code is:',
        };

        $otpLog = $this->generateAndSendOtp($userId, $email, $purpose, $messageBody, $ipAddress, $userAgent);

        Log::info("OTP resent for user_id: {$userId}, purpose: {$purpose}");

        return [
            'success' => true,
            'message' => 'New OTP has been sent to your email.',
            'otp_log' => $otpLog,
        ];
    }

    /**
     * Check if user has a valid OTP login session (within 8 hours)
     */
    public function isOtpLoginValid(?int $userId): bool
    {
        $user = \App\Models\User::find($userId);

        if (!$user || !$user->otp_login_valid_until) {
            return false;
        }

        return now()->lessThan($user->otp_login_valid_until);
    }

    /**
     * Set OTP login validity for 8 hours after successful OTP login
     */
    public function setOtpLoginValidity(?int $userId): void
    {
        $user = \App\Models\User::find($userId);
        if ($user) {
            $validUntil = now()->addHours(8);
            $user->update(['otp_login_valid_until' => $validUntil]);
            Log::info("OTP login validity set for user_id: {$userId}, valid until: {$validUntil->toDateTimeString()}");
        }
    }

    /**
     * Clear OTP login validity (e.g., on password change)
     */
    public function clearOtpLoginValidity(?int $userId): void
    {
        $user = \App\Models\User::find($userId);
        if ($user) {
            $user->update(['otp_login_valid_until' => null]);
            Log::info("OTP login validity cleared for user_id: {$userId}");
        }
    }

    /**
     * Check if a valid (non-expired, non-verified) OTP exists
     */
    public function hasValidOtp(?int $userId, string $purpose = 'login'): bool
    {
        return OtpLog::where('user_id', $userId)
            ->where('purpose', $purpose)
            ->where('verified', false)
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Clear/invalidate all pending OTPs for a user+purpose
     */
    public function clearOtp(?int $userId, string $purpose = 'login'): void
    {
        OtpLog::where('user_id', $userId)
            ->where('purpose', $purpose)
            ->where('verified', false)
            ->update(['verified' => true]);

        Log::info("All pending OTPs cleared for user_id: {$userId}, purpose: {$purpose}");
    }
}