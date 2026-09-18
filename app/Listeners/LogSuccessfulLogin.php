<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;
use App\Mail\SystemLoginAlertMail;

class LogSuccessfulLogin
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
    public function handle(Login $event): void
    {
        try {
            $user = $event->user;
            $ipAddress = request()->ip();
            $location = Location::get($ipAddress);
            $timestamp = now()->format('d M Y, H:i:s');

            $adminEmail = config('gouravatced@gmail.com', 'system@adms.jshb.computered.co.in');

            Mail::to($adminEmail)->queue(new SystemLoginAlertMail($user, $ipAddress, $location, $timestamp));
            
            Log::info("Successful login by User ID: {$user->id}. Alert queued.");
        } catch (\Exception $e) {
            Log::error("Failed to process LogSuccessfulLogin listener: " . $e->getMessage());
        }
    }
}
