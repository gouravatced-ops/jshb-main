<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Models\OtpLog;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required_without:otp_stage|string',
            'otp_code' => 'required_if:otp_stage,1|string',
            'otp_stage' => 'sometimes|boolean',
        ]);

        $otpStage = (bool) $request->input('otp_stage', false);
        $user = User::where('email', $request->email)
            ->orWhere('name', $request->email)
            ->first();

        // If not found in default DB, check adms_allottees DB
        if (! $user) {
            $user = User::on('adms_allottees')
                ->where('email', $request->email)
                ->orWhere('name', $request->email)
                ->first();
        }

        if (! $user) {
            return back()->withInput()->with('error', 'Account not found.');
        }

        if ($user->status === false || $user->status === 0 || $user->status === '0') {
            return back()->withInput()->with('error', 'Your account has been deactivated.');
        }

        // ─── LOCKOUT CHECK ─────────────────────────────
        if ($user->account_blocked_until && $user->account_blocked_until > now()) {
            $diff = $user->account_blocked_until->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE);
            return back()->withInput()->with('error', 'Your account is blocked. Please try again after ' . $diff . '.');
        }

        // ─── OTP VERIFICATION STAGE ─────────────────────────────
        if ($otpStage) {
            if (! $user->login_with_otp) {
                return back()->withInput()->with('error', 'This account does not use OTP login.');
            }

            // Verify OTP from database via OtpService
            $isValid = $this->otpService->verifyOtp($user->id, $request->otp_code, 'login');

            if (! $isValid) {
                // Return immediately without triggering standard lockout for OTP, or trigger it too?
                // The prompt seemed focused on password, but let's increment it here too.
                // It's safer to just let OTP failures count as well.
                return $this->handleFailedLogin($user, $request);
            }

            // Success resets lockout
            $this->resetLockoutState($user);

            LoginLog::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'action' => 'otp_login',
            ]);

            // Set password_created_at if not already set
            if (!$user->password_created_at) {
                $user->update(['password_created_at' => now()]);
            }

            if ($user->is_locked) {
                $user->update(['is_locked' => false]);
            }

            // Set 8-hour OTP login validity
            $this->otpService->setOtpLoginValidity($user->id);

            Auth::login($user, $request->boolean('remember'));
            $this->setSessionExpiry($request);

            return redirect()->route($this->dashboardRoute($user))->with('success', 'Welcome back, ' . $user->name);
        }

        // ─── PASSWORD CHECK ─────────────────────────────────────
        $isValidAuth = false;
        if (Hash::check($request->password, $user->password)) {
            $isValidAuth = true;
        } elseif ($user->secure_pin && Hash::check($request->password, $user->secure_pin)) {
            $isValidAuth = true;
        }

        if (! $isValidAuth) {
            return $this->handleFailedLogin($user, $request);
        }

        // Success resets lockout
        $this->resetLockoutState($user);

        // ─── OTP REQUIRED: CHECK 8HR VALIDITY FIRST ──────────────
        if ($user->login_with_otp) {
            // Check if OTP login is still valid (within 8 hours)
            if ($this->otpService->isOtpLoginValid($user->id)) {
                // Skip OTP — still within 8-hour validity window
                LoginLog::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'success',
                    'action' => 'otp_login_8hr_valid',
                ]);

                if (!$user->password_created_at) {
                    $user->update(['password_created_at' => now()]);
                }

                if ($user->is_locked) {
                    $user->update(['is_locked' => false]);
                }

                Auth::login($user, $request->boolean('remember'));
                $this->setSessionExpiry($request);

                return redirect()->route($this->dashboardRoute($user))->with('success', 'Welcome back, ' . $user->name);
            }

            // OTP validity expired — generate + send new OTP via Job/Queue
            $this->otpService->generateAndSendOtp(
                $user->id,
                $user->email,
                'login',
                'Your OTP for login verification is:',
                $request->ip(),
                $request->userAgent()
            );

            return back()->withInput()
                ->with('success', 'OTP has been sent to your email. Enter it below to complete login.')
                ->with('otp_required', true)
                ->with('email', $request->email);
        }

        // ─── DIRECT LOGIN (NO OTP) ──────────────────────────────
        LoginLog::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'success',
            'action' => 'password_login',
        ]);

        // Set password_created_at if not already set
        if (!$user->password_created_at) {
            $user->update(['password_created_at' => now()]);
        }

        if ($user->is_locked) {
            $user->update(['is_locked' => false]);
        }

        Auth::login($user, $request->boolean('remember'));
        $this->setSessionExpiry($request);

        return redirect()->route($this->dashboardRoute($user))->with('success', 'Welcome back, ' . $user->name);
    }

    /**
     * Resend OTP for login
     */
    public function resendLoginOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
            ->orWhere('name', $request->email)
            ->first();

        if (!$user) {
            return back()->withInput()->with('error', 'Account not found.');
        }

        $result = $this->otpService->resendOtp(
            $user->id,
            $user->email,
            'login',
            $request->ip(),
            $request->userAgent()
        );

        if (!$result['success']) {
            return back()->withInput()
                ->with('error', $result['message'])
                ->with('otp_required', true)
                ->with('email', $request->email);
        }

        return back()->withInput()
            ->with('success', 'New OTP has been sent to your email.')
            ->with('otp_required', true)
            ->with('email', $request->email);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $action = $request->query('auto') ? 'auto_logout' : 'manual_logout';

        if ($user) {
            LoginLog::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'action' => $action,
            ]);

            if ($user->is_locked) {
                $user->update(['is_locked' => false]);
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $message = $action === 'auto_logout'
            ? 'Your session expired after 60 minutes. Please login again.'
            : 'You have been logged out.';

        return redirect()->route('login')->with('success', $message);
    }

    public function lockScreen(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $user->update(['is_locked' => true]);

        return redirect()->route('lock.screen');
    }

    public function showLockScreen()
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->is_locked) {
            return redirect()->route($this->dashboardRoute($user));
        }

        return view('lock-screen', compact('user'));
    }

    public function unlockScreen(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $isValidAuth = false;
        if (Hash::check($request->password, $user->password)) {
            $isValidAuth = true;
        } elseif ($user->secure_pin && Hash::check($request->password, $user->secure_pin)) {
            $isValidAuth = true;
        }

        if (! $isValidAuth) {
            return redirect()->back()->withErrors(['password' => 'Password or PIN is incorrect.'])->withInput();
        }

        $user->update(['is_locked' => false]);

        return redirect()->route($this->dashboardRoute($user));
    }

    private function setSessionExpiry(Request $request)
    {
        $isLocal = in_array($request->getHost(), ['127.0.0.1', 'localhost', '::1']);
        $minutesOfSession = $isLocal ? 240 : 90;
        $expiry = now()->addMinutes($minutesOfSession);
        $request->session()->put('session_expires_at_ts', $expiry->timestamp);
        $request->session()->put('session_expires_at', $expiry->toDateTimeString());
        $request->session()->put('session_last_activity', now()->toDateTimeString());
    }

    private function dashboardRoute(User $user): string
    {
        return match ($user->roleRelation->slug) {

            'admin',
            'super-admin' => 'admin.dashboard',
            'division-officer' => 'division.dashboard',

            'executive-engineer',
            'dealing-assistant',
            'office-superintendent',
            'estate-officer',
            'assistant-engineer',
            'junior-engineer' => 'engineer.dashboard',

            'revenue-officer',
            'chief-accounts-officer',
            'chief-financial-officer' => 'accountant.dashboard',

            'managing-director',
            'secretary-chief-engineer' => 'managing.dashboard',

            'operator' => 'operator.dashboard',

            'allottee' => 'dashboard',
            default => 'staff.dashboard',
        };
    }

    private function handleFailedLogin(User $user, Request $request)
    {
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
            $msg = 'Invalid credentials. Attempts remaining: ' . $remaining;
            $isBlocked = false;
        }

        LoginLog::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'failed',
            'action' => $isBlocked ? 'login_blocked' : 'login_failed',
        ]);

        return back()->withInput()->with('error', $msg);
    }

    private function resetLockoutState(User $user)
    {
        if ($user->failed_login_attempts > 0 || $user->account_blocked_until || $user->has_been_blocked_once) {
            $user->update([
                'failed_login_attempts' => 0,
                'account_blocked_until' => null,
                'has_been_blocked_once' => 0,
            ]);
        }
    }
}
