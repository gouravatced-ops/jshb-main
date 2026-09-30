<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA setup page with QR Code.
     */
    public function setup()
    {
        $user = Auth::user();

        $google2fa = app('pragmarx.google2fa');

        // Generate a new secret if the user doesn't have one
        if (!$user->google2fa_secret) {
            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        // Generate the QR code URL
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );

        // Generate the QR code SVG
        $renderer = new ImageRenderer(
            new RendererStyle(250),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        return view('2fa.setup', compact('qrCodeSvg', 'user'));
    }

    /**
     * Enable 2FA after verifying the OTP for the first time.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'totp' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');

        $isValid = $google2fa->verifyKey($user->google2fa_secret, $request->totp);

        if ($isValid) {
            $user->google2fa_enabled = true;
            $user->save();
            return redirect()->route('admin.dashboard')->with('success', 'Two-Factor Authentication is now enabled!');
        }

        return back()->with('error', 'Invalid Authenticator Code. Please try again.');
    }

    /**
     * Disable 2FA.
     */
    public function disable()
    {
        $user = Auth::user();
        $user->google2fa_enabled = false;
        $user->google2fa_secret = null;
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Two-Factor Authentication has been disabled.');
    }

    /**
     * Show the form to enter OTP during login.
     */
    public function showVerifyForm(Request $request, $token)
    {
        if (!$request->session()->has('2fa:user:id') || $request->session()->get('2fa:token') !== $token) {
            $request->session()->forget(['2fa:user:id', '2fa:user:remember', '2fa:token', '2fa:expires_at']);
            return redirect()->route('login')->with('error', 'Invalid or expired 2FA session.');
        }

        if (now()->timestamp > $request->session()->get('2fa:expires_at')) {
            $request->session()->forget(['2fa:user:id', '2fa:user:remember', '2fa:token', '2fa:expires_at']);
            return redirect()->route('login')->with('error', '2FA session expired. Please login again.');
        }

        $expiresAt = $request->session()->get('2fa:expires_at');
        return view('2fa.verify', compact('token', 'expiresAt'));
    }

    /**
     * Verify the OTP during login.
     */
    public function verify(Request $request, $token)
    {
        $request->validate([
            'totp' => 'required|string|size:6'
        ]);

        if (!$request->session()->has('2fa:user:id') || $request->session()->get('2fa:token') !== $token) {
            $request->session()->forget(['2fa:user:id', '2fa:user:remember', '2fa:token', '2fa:expires_at']);
            return redirect()->route('login')->with('error', 'Invalid or expired 2FA session.');
        }

        if (now()->timestamp > $request->session()->get('2fa:expires_at')) {
            $request->session()->forget(['2fa:user:id', '2fa:user:remember', '2fa:token', '2fa:expires_at']);
            return redirect()->route('login')->with('error', '2FA session expired. Please login again.');
        }

        $userId = $request->session()->get('2fa:user:id');
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        // ─── LOCKOUT CHECK ─────────────────────────────
        if ($user->account_blocked_until && $user->account_blocked_until > now()) {
            $diff = $user->account_blocked_until->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE);
            return back()->with('error', 'Your account is blocked. Please try again after ' . $diff . '.');
        }

        $google2fa = app('pragmarx.google2fa');
        $isValid = $google2fa->verifyKey($user->google2fa_secret, $request->totp);

        if ($isValid) {
            // Success resets lockout
            if ($user->failed_login_attempts > 0 || $user->account_blocked_until || $user->has_been_blocked_once) {
                $user->update([
                    'failed_login_attempts' => 0,
                    'account_blocked_until' => null,
                    'has_been_blocked_once' => 0,
                ]);
            }

            // Login the user
            $request->session()->forget(['2fa:user:id', '2fa:user:remember', '2fa:token', '2fa:expires_at']);
            Auth::login($user, $request->session()->get('2fa:user:remember', false));

            // Set session expiry as per existing logic
            $isLocal = in_array($request->getHost(), ['127.0.0.1', 'localhost', '::1']);
            $minutesOfSession = $isLocal ? 240 : 90;
            $expiry = now()->addMinutes($minutesOfSession);
            $request->session()->put('session_expires_at_ts', $expiry->timestamp);
            $request->session()->put('session_expires_at', $expiry->toDateTimeString());
            $request->session()->put('session_last_activity', now()->toDateTimeString());

            // Use the same dashboard routing logic
            $app = app(\App\Http\Controllers\AuthController::class);
            $method = new \ReflectionMethod($app, 'dashboardRoute');
            $method->setAccessible(true);
            $route = $method->invoke($app, $user);

            return redirect()->route($route)->with('success', 'Welcome back, ' . $user->name);
        }

        // ─── FAILED ATTEMPT LOGIC ─────────────────────────────
        $user->increment('failed_login_attempts');

        if ($user->has_been_blocked_once && $user->failed_login_attempts >= 1) {
            $user->update([
                'account_blocked_until' => now()->addHours(24),
                'failed_login_attempts' => 0,
            ]);
            $msg = 'Your account has been blocked for 24 hours due to another failed login attempt.';
            $isBlocked = true;
        } elseif ($user->failed_login_attempts >= 5) {
            $user->update([
                'account_blocked_until' => now()->addHours(1),
                'has_been_blocked_once' => 1,
                'failed_login_attempts' => 0,
            ]);
            $msg = 'Your account has been blocked for 1 hour due to 5 failed login attempts.';
            $isBlocked = true;
        } else {
            $remaining = $user->has_been_blocked_once ? (1 - $user->failed_login_attempts) : (5 - $user->failed_login_attempts);
            $msg = 'Invalid Authenticator Code. Attempts remaining: ' . $remaining;
            $isBlocked = false;
        }

        \App\Models\LoginLog::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'failed',
            'action' => $isBlocked ? 'login_blocked' : 'login_failed',
        ]);

        // Fire Laravel's Failed event to trigger the LogFailedLogin listener for email alerts
        event(new \Illuminate\Auth\Events\Failed(config('auth.defaults.guard'), $user, [
            'email' => $user->email,
            'totp' => $request->totp
        ]));

        return back()->with('error', $msg);
    }
}
