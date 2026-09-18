<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Stevebauman\Location\Facades\Location;
use App\Mail\SystemFailedLoginAlertMail;

class LogFailedLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        try {
            $emailAttempted = $event->credentials['email'] ?? ($event->credentials['username'] ?? 'Unknown');
            $ipAddress = request()->ip();
            $cacheKey = 'failed_login_attempts_' . $ipAddress;

            // Increment the failed attempts in cache, keep for 1 hour
            $attempts = Cache::increment($cacheKey);
            Cache::put($cacheKey, $attempts, now()->addHours(1));

            Log::warning("Failed login attempt $attempts from IP: $ipAddress for email: $emailAttempted");

            // If attempts cross the threshold of 4 (i.e. 4 or more)
            if ($attempts >= 4) {
                // To prevent spamming if they try 10 times, we only send on exact intervals, e.g. 4, 8, 12...
                // Or simply send when it hits exactly 4, and let them know.
                if ($attempts % 4 === 0) {
                    $location = Location::get($ipAddress);
                    $timestamp = now()->format('d M Y, H:i:s');
                    $adminEmail = config('gouravatced@gmail.com', 'system@adms.jshb.computered.co.in');

                    Mail::to($adminEmail)->queue(new SystemFailedLoginAlertMail($emailAttempted, $ipAddress, $location, $timestamp, $attempts));
                    
                    Log::error("CRITICAL: Sent failed login alert for IP $ipAddress ($attempts attempts).");
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to process LogFailedLogin listener: " . $e->getMessage());
        }
    }
}
