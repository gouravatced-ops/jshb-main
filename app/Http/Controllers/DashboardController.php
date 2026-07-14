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
use \Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($this->checkSessionExpiry($request)) {
            return redirect()->route('login')->with('error', 'Your session expired after 90 minutes');
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
            return redirect()->route('login')->with('error', 'Your session expired after 90 minutes');
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
            return redirect()->route('login')->with('error', 'Your session expired after 90 minutes');
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
            return redirect()->route('login')->with('error', 'Your session expired after 90 minutes');
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
            return redirect()->route('login')->with('error', 'Your session expired after 90 minutes');
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
            return redirect()->route('login')->with('error', 'Your session expired after 90 minutes');
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
        
        $pendingApplications = collect();
        $workflowId = \App\Models\Workflow::where('application_type', 'allotment')->value('id') ?? 1;
        $admsDb = config('database.connections.adms_allottees.database');

        $pendingApplications = \App\Models\Application::join("$admsDb.allottees as al", 'applications.allottee_id', '=', 'al.id')
            ->leftJoin('application_movements as am', 'applications.id', '=', 'am.application_id')
            ->where('al.division_id', $user->division_id)
            ->where('applications.current_role_id', $user->role_id)
            ->whereIn('applications.status', ['pending', 'in_progress', 'forwarded'])
            ->select(
                'applications.id',
                'applications.application_no',
                'applications.application_type',
                'al.prefix',
                'al.allottee_name',
                'al.allottee_middle_name',
                'al.allottee_surname',
                'al.allottee_prefix_hindi',
                'al.allottee_name_hindi',
                'al.allottee_middle_hindi',
                'al.allottee_surname_hindi',
                'al.property_number',
                'al.allotment_no',
                'applications.created_date',
                DB::raw("DATE_FORMAT(applications.created_date, '%d-%b-%Y %H:%i') as created_date_formatted"),
                DB::raw("DATEDIFF(NOW(), applications.created_date) as days_pending"),
                DB::raw("
                    CASE 
                        WHEN DATEDIFF(NOW(), applications.created_date) <= 3 THEN 'Normal'
                        WHEN DATEDIFF(NOW(), applications.created_date) <= 7 THEN 'Urgent'
                        ELSE 'Overdue'
                    END as priority
                "),
                DB::raw("COUNT(am.id) as total_movements"),
                DB::raw("(SELECT remarks FROM application_notes WHERE application_id = applications.id ORDER BY created_at DESC LIMIT 1) as last_remark")
            )
            ->groupBy(
                'applications.id', 'applications.application_no', 'applications.application_type',
                'al.prefix', 'al.allottee_name', 'al.allottee_middle_name', 'al.allottee_surname',
                'al.allottee_prefix_hindi', 'al.allottee_name_hindi', 'al.allottee_middle_hindi', 'al.allottee_surname_hindi',
                'al.property_number', 'al.allotment_no', 'applications.created_date'
            )
            ->orderBy('applications.created_date', 'ASC')
            ->take(5)
            ->get();

        return view('engineer.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
            'pendingApplications'
        ));
    }

    public function accountant(Request $request)
    {
        if ($this->checkSessionExpiry($request)) {
            return redirect()->route('login')->with('error', 'Your session expired after 90 minutes');
        }

        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        if (! $user || $user->role !== 'accountant') {
            return redirect()->route('dashboard')->with('error', 'Accountant access required.');
        }

        $users = User::with('detail')->orderByDesc('created_at')->get();
        $loginLogs = LoginLog::latest()->take(10)->get();
        $otpLogs = OtpLog::latest()->take(10)->get();    
        $latestLogin = $loginLogs->first();

        return view('accountant.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
        ));
    }

    public function managing(Request $request)
    {
        if ($this->checkSessionExpiry($request)) {
            return redirect()->route('login')->with('error', 'Your session expired after 90 minutes');
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
            return redirect()->route('login')->with('error', 'Your session expired after 90 minutes');
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
