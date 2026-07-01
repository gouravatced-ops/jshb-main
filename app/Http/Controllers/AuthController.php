<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Models\OtpLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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

        if ($otpStage) {
            if (! $user->login_with_otp) {
                return back()->withInput()->with('error', 'This account does not use OTP login.');
            }

            $otpLog = OtpLog::where('user_id', $user->id)
                ->where('purpose', 'login')
                ->latest()
                ->first();

            if (! $otpLog || $otpLog->otp_code !== $request->otp_code) {
                return back()->withInput()
                    ->with('error', 'Incorrect OTP code.')
                    ->with('otp_required', true)
                    ->with('email', $request->email);
            }

            $otpLog->update(['verified' => true]);

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

            Auth::login($user, $request->boolean('remember'));
            $this->setSessionExpiry($request);

            return redirect()->route($this->dashboardRoute($user))->with('success', 'Welcome back, ' . $user->name);
        }

        if (! Hash::check($request->password, $user->password)) {
            LoginLog::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'failed',
                'action' => 'password_login',
            ]);

            return back()->withInput()->with('error', 'Invalid credentials.');
        }

        if ($user->login_with_otp) {
            $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            OtpLog::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'otp_code' => $otpCode,
                'verified' => false,
                'purpose' => 'login',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->withInput()
                ->with('success', 'OTP generated for your account. Enter it below to complete login.')
                ->with('otp_required', true)
                ->with('email', $request->email);
        }

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

        if (! Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['password' => 'Password is incorrect.'])->withInput();
        }

        $user->update(['is_locked' => false]);

        return redirect()->route($this->dashboardRoute($user));
    }

    private function setSessionExpiry(Request $request)
    {
        if (app()->environment('local')) {
            $request->session()->forget(['session_expires_at_ts', 'session_expires_at', 'session_last_activity']);
            return;
        }

        $minutesOfSession = (int) env('SESSION_LIFETIME', 60);
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

            'managing-director' => 'managing.dashboard',

            'operator' => 'operator.dashboard',

            'allottee' => 'dashboard',
            default => 'staff.dashboard',
        };
    }
}
