<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtpLog;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class GlobalOtpController extends Controller
{
    /**
     * Send a global OTP for a specific purpose.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'purpose' => 'required|string|max:100',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 401);
        }

        $purpose = $request->purpose;
        $messageBody = "You have requested to perform a secure operation ({$purpose}). Please use this OTP to verify your action.";

        // Inject OtpService
        $otpService = app(\App\Services\OtpService::class);
        
        try {
            // Generate, encrypt, store, and dispatch email automatically
            $otpService->generateAndSendOtp(
                $user->id,
                $user->email,
                $purpose,
                $messageBody,
                $request->ip(),
                $request->userAgent()
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send Global OTP: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send OTP email.'], 500);
        }

        return response()->json([
            'success' => true, 
            'message' => 'OTP sent successfully to your registered email address.' . (config('app.env') === 'local' ? ' (Dev mode)' : '')
        ]);
    }

    /**
     * Verify a global OTP for a specific purpose.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'purpose' => 'required|string|max:100',
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 401);
        }

        $purpose = $request->purpose;
        $otp = $request->otp;

        $otpService = app(\App\Services\OtpService::class);
        $isValid = $otpService->verifyOtp($user->id, $otp, $purpose);

        if (!$isValid) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.']);
        }

        // Set a session flag indicating this purpose has been verified
        // We will suffix it to keep it unique per purpose
        session(['global_otp_verified_' . $purpose => true]);

        return response()->json([
            'success' => true, 
            'message' => 'OTP verified successfully.'
        ]);
    }
}
