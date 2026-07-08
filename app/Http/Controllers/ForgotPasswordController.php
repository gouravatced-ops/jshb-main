<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ForgotPasswordController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP for password reset via Job/Queue
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withInput()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Use OtpService: generate + store in DB + send via queue
        $this->otpService->generateAndSendOtp(
            $user->id,
            $user->email,
            'password_reset',
            'Your OTP for password reset is:',
            $request->ip(),
            $request->userAgent()
        );

        session([
            'password_reset_email' => $user->email,
            'password_reset_otp_required' => true,
        ]);

        return back()
            ->with('success', 'OTP has been sent to your email. Enter the OTP below to verify and reset your password.')
            ->with('otp_required', true)
            ->with('email', $user->email);
    }

    /**
     * Verify OTP from database via OtpService
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp_code' => ['required', 'digits:6'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withInput()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Verify OTP from database
        $isValid = $this->otpService->verifyOtp($user->id, $request->otp_code, 'password_reset');

        if (! $isValid) {
            return back()
                ->withInput()
                ->with('otp_required', true)
                ->with('email', $request->email)
                ->withErrors(['otp_code' => 'Invalid or expired OTP. Please try again.']);
        }

        session([
            'password_reset_verified_email' => $user->email,
            'password_reset_verified_at' => now()->toDateTimeString(),
        ]);

        return redirect()
            ->route('password.reset')
            ->with('success', 'OTP verified successfully. You can now set a new password.');
    }

    /**
     * Resend OTP for password reset
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withInput()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Resend via OtpService (includes cooldown check)
        $result = $this->otpService->resendOtp(
            $user->id,
            $user->email,
            'password_reset',
            $request->ip(),
            $request->userAgent()
        );

        if (! $result['success']) {
            return back()
                ->withInput()
                ->with('otp_required', true)
                ->with('email', $request->email)
                ->with('error', $result['message']);
        }

        return back()
            ->with('success', 'New OTP has been sent to your email.')
            ->with('otp_required', true)
            ->with('email', $user->email);
    }

    public function showResetForm(Request $request)
    {
        $email = session('password_reset_verified_email');

        if (! $email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Please verify OTP first to reset your password.']);
        }

        return view('auth.reset-password', [
            'email' => $email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $verifiedEmail = session('password_reset_verified_email');

        if (! $verifiedEmail || $verifiedEmail !== $request->email) {
            return redirect()->route('password.request')->withErrors(['email' => 'OTP verification is required before resetting the password.']);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withInput()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'password_created_at' => now(),
        ])->save();

        session()->forget([
            'password_reset_email',
            'password_reset_otp_required',
            'password_reset_verified_email',
            'password_reset_verified_at',
        ]);

        return redirect()->route('login')->with('success', 'Password reset successfully. Please login with your new password.');
    }
}
