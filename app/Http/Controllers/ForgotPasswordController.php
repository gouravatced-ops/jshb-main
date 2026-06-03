<?php

namespace App\Http\Controllers;

use App\Models\OtpLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withInput()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpLog::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'otp_code' => $otpCode,
            'verified' => false,
            'purpose' => 'password_reset',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        session([
            'password_reset_email' => $user->email,
            'password_reset_otp_required' => true,
        ]);

        /*
        $status = Password::sendResetLink(
            $request->only('email')
        );
        */

        return back()
            ->with('success', 'OTP generated successfully. Enter the OTP below to verify and reset your password.')
            ->with('otp_required', true)
            ->with('email', $user->email);
    }

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

        $otpLog = OtpLog::where('user_id', $user->id)
            ->where('purpose', 'password_reset')
            ->latest()
            ->first();

        if (! $otpLog || $otpLog->otp_code !== $request->otp_code) {
            return back()
                ->withInput()
                ->with('otp_required', true)
                ->with('email', $request->email)
                ->withErrors(['otp_code' => 'Invalid OTP. Please try again.']);
        }

        $otpLog->update(['verified' => true]);

        session([
            'password_reset_verified_email' => $user->email,
            'password_reset_verified_at' => now()->toDateTimeString(),
        ]);

        return redirect()
            ->route('password.reset')
            ->with('success', 'OTP verified successfully. You can now set a new password.');
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
