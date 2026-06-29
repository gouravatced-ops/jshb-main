<?php

namespace App\Http\Controllers;

use App\Models\EngineerDetail;
use App\Models\GuestHouseRequisition;
use App\Models\LoginLog;
use App\Models\Organization;
use App\Models\OtpLog;
use App\Models\PostType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($this->checkSessionExpiry($request)) {
            return redirect()->route('login')->with('error', 'Your session expired after 60 minutes.');
        }

        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $recentLogins = $user->loginLogs()->latest()->take(5)->get();
        $otpLogCount = $user->otpLogs()->count();

        return view('user.dashboard', compact('user', 'recentLogins', 'otpLogCount'));
    }

    public function admin(Request $request)
    {
        if ($this->checkSessionExpiry($request)) {
            return redirect()->route('login')->with('error', 'Your session expired after 60 minutes.');
        }

        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        if (! $user || $user->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Admin access required.');
        }

        $users = User::with('detail')->orderByDesc('created_at')->get();
        $loginLogs = LoginLog::latest()->take(10)->get();
        $otpLogs = OtpLog::latest()->take(10)->get();
        $latestLogin = $loginLogs->first();

        return view('admin.module.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
        ));
    }

    public function staff(Request $request)
    {
        if ($this->checkSessionExpiry($request)) {
            return redirect()->route('login')->with('error', 'Your session expired after 60 minutes.');
        }

        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        if (! $user || $user->role !== 'staff') {
            return redirect()->route('dashboard')->with('error', 'Staff access required.');
        }

        $users = User::with('detail')->orderByDesc('created_at')->get();
        $loginLogs = LoginLog::latest()->take(10)->get();
        $otpLogs = OtpLog::latest()->take(10)->get();    
        $latestLogin = $loginLogs->first();

        return view('staff.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
        ));
    }

    public function division(Request $request)
    {
        if ($this->checkSessionExpiry($request)) {
            return redirect()->route('login')->with('error', 'Your session expired after 60 minutes.');
        }

        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        if (! $user || $user->role !== 'division') {
            return redirect()->route('dashboard')->with('error', 'Division access required.');
        }

        $users = User::with('detail')->orderByDesc('created_at')->get();
        $loginLogs = LoginLog::latest()->take(10)->get();
        $otpLogs = OtpLog::latest()->take(10)->get();    
        $latestLogin = $loginLogs->first();

        return view('division.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
        ));
    }

    public function subdivision(Request $request)
    {
        if ($this->checkSessionExpiry($request)) {
            return redirect()->route('login')->with('error', 'Your session expired after 60 minutes.');
        }

        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        if (! $user || $user->role !== 'subdivision') {
            return redirect()->route('dashboard')->with('error', 'Sub Division access required.');
        }

        $users = User::with('detail')->orderByDesc('created_at')->get();
        $loginLogs = LoginLog::latest()->take(10)->get();
        $otpLogs = OtpLog::latest()->take(10)->get();    
        $latestLogin = $loginLogs->first();

        return view('subdivision.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
        ));
    }

    public function engineer(Request $request)
    {
        if ($this->checkSessionExpiry($request)) {
            return redirect()->route('login')->with('error', 'Your session expired after 60 minutes.');
        }

        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        if (! $user || $user->role !== 'engineer') {
            return redirect()->route('dashboard')->with('error', 'Engineer access required.');
        }

        $users = User::with('detail')->orderByDesc('created_at')->get();
        $loginLogs = LoginLog::latest()->take(10)->get();
        $otpLogs = OtpLog::latest()->take(10)->get();    
        $latestLogin = $loginLogs->first();

        return view('engineer.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
        ));
    }

    public function managing(Request $request)
    {
        if ($this->checkSessionExpiry($request)) {
            return redirect()->route('login')->with('error', 'Your session expired after 60 minutes.');
        }

        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        if (! $user || $user->role !== 'managing') {
            return redirect()->route('dashboard')->with('error', 'Managing Director access required.');
        }

        $users = User::with('detail')->orderByDesc('created_at')->get();
        $loginLogs = LoginLog::latest()->take(10)->get();
        $otpLogs = OtpLog::latest()->take(10)->get();    
        $latestLogin = $loginLogs->first();

        return view('managing.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
        ));
    }

    public function operator(Request $request)
    {
        if ($this->checkSessionExpiry($request)) {
            return redirect()->route('login')->with('error', 'Your session expired after 60 minutes.');
        }

        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        if (! $user || $user->role !== 'operator') {
            return redirect()->route('dashboard')->with('error', 'Operator access required.');
        }

        $users = User::with('detail')->orderByDesc('created_at')->get();
        $loginLogs = LoginLog::latest()->take(10)->get();
        $otpLogs = OtpLog::latest()->take(10)->get();    
        $latestLogin = $loginLogs->first();

        return view('operator.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
        ));
    }



    private function checkSessionExpiry(Request $request)
    {
        if (! Auth::check()) {
            return false;
        }

        $expiryTs = $request->session()->get('session_expires_at_ts');

        if ($expiryTs && now()->timestamp >= $expiryTs) {
            $user = Auth::user();

            LoginLog::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'action' => 'auto_logout',
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return true;
        }

        return false;
    }
}
