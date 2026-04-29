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
            return redirect()->route('login')->with('error', 'Your session expired after 30 minutes.');
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

        $employeeDetail = $user->engineerDetail()
            ->with(['currentOrganization', 'department', 'district', 'block', 'postType'])
            ->first();
        $recentLogins = $user->loginLogs()->latest()->take(5)->get();
        $otpLogCount = $user->otpLogs()->count();
        $recentRequisitions = GuestHouseRequisition::query()
            ->with(['district', 'block'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('user', 'employeeDetail', 'recentLogins', 'otpLogCount', 'recentRequisitions'));
    }

    public function admin(Request $request)
    {
        if ($this->checkSessionExpiry($request)) {
            return redirect()->route('login')->with('error', 'Your session expired after 30 minutes.');
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

        $totalEngineers = EngineerDetail::count();
        $districtCoverage = EngineerDetail::distinct('district_id')->count('district_id');
        $totalOrganizations = Organization::count();
        $totalPostTypes = PostType::count();
        $pendingRequisitions = GuestHouseRequisition::where('status', 'pending')->count();

        $recentEngineers = EngineerDetail::with([
            'user',
            'currentOrganization',
            'district',
            'block',
            'postType',
        ])
            ->latest()
            ->take(8)
            ->get();

        $recentRequisitions = GuestHouseRequisition::query()
            ->with(['user', 'district', 'block'])
            ->latest()
            ->take(6)
            ->get();

        $districtEngineerCounts = EngineerDetail::query()
            ->selectRaw('districts.name_en as district_name, COUNT(engineer_details.id) as engineer_count')
            ->join('districts', 'districts.id', '=', 'engineer_details.district_id')
            ->where('districts.state_id', 15)
            ->groupBy('districts.id', 'districts.name_en')
            ->orderByDesc('engineer_count')
            ->orderBy('districts.name_en')
            ->take(10)
            ->get();

        $latestEngineer = $recentEngineers->first();
        $latestLogin = $loginLogs->first();
        $topDistrict = $districtEngineerCounts->first();

        return view('admin.module.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'totalEngineers',
            'districtCoverage',
            'totalOrganizations',
            'totalPostTypes',
            'pendingRequisitions',
            'recentEngineers',
            'recentRequisitions',
            'districtEngineerCounts',
            'latestEngineer',
            'latestLogin',
            'topDistrict'
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
