<?php

namespace App\Http\Controllers;

use App\Models\EngineerDetail;
use App\Models\GuestHouseRequisition;
use App\Models\LoginLog;
use App\Models\Organization;
use App\Models\OtpLog;
use App\Models\PostType;
use App\Models\User;
use App\Models\Workflow;
use App\Models\Application;
use App\Models\Allottee;
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
        $workflowId = Workflow::where('application_type', 'allotment')->value('id') ?? 1;
        // 1. Get all pending application allottee IDs for this role
        $pendingAllotteeIds = Application::where('current_role_id', $user->role_id)
            ->whereIn('status', ['pending', 'in_progress', 'forwarded'])
            ->pluck('allottee_id')
            ->unique()
            ->toArray();

        // 2. Filter these allottees by the user's division using the proper DB connection
        $validAllotteeIds = Allottee::whereIn('id', $pendingAllotteeIds)
            ->where('division_id', $user->division_id)
            ->pluck('id')
            ->toArray();

        // 3. Fetch applications matching these valid allottees
        $pendingApplications = Application::with('allottee')
            ->where('current_role_id', $user->role_id)
            ->whereIn('status', ['pending', 'in_progress', 'forwarded'])
            ->whereIn('allottee_id', $validAllotteeIds)
            ->select(
                'applications.id',
                'applications.application_no',
                'applications.application_type',
                'applications.allottee_id',
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
                DB::raw("(SELECT COUNT(id) FROM application_movements WHERE application_id = applications.id) as total_movements"),
                DB::raw("(SELECT remarks FROM application_notes WHERE application_id = applications.id ORDER BY created_at DESC LIMIT 1) as last_remark")
            )
            ->orderBy('applications.created_date', 'ASC')
            ->take(5)
            ->get();
            
        // Map allottee data so views don't break
        $pendingApplications->transform(function($app) {
            if ($app->allottee) {
                $app->prefix = $app->allottee->prefix;
                $app->allottee_name = $app->allottee->allottee_name;
                $app->allottee_middle_name = $app->allottee->allottee_middle_name;
                $app->allottee_surname = $app->allottee->allottee_surname;
                $app->allottee_prefix_hindi = $app->allottee->allottee_prefix_hindi;
                $app->allottee_name_hindi = $app->allottee->allottee_name_hindi;
                $app->allottee_middle_hindi = $app->allottee->allottee_middle_hindi;
                $app->allottee_surname_hindi = $app->allottee->allottee_surname_hindi;
                $app->property_number = $app->allottee->property_number;
                $app->allotment_no = $app->allottee->allotment_no;
            }
            return $app;
        });

        // Stats
        $totalPending = Application::where('current_role_id', $user->role_id)
            ->whereIn('status', ['pending', 'in_progress', 'forwarded'])
            ->whereIn('allottee_id', $validAllotteeIds)
            ->count();
            
        $totalReceived = Application::whereHas('movements', function($q) use ($user) {
                $q->where('to_role_id', $user->role_id);
            })
            ->whereIn('allottee_id', $validAllotteeIds)
            ->count();
            
        // Applications that were with the engineer but are now moved forward/completed
        $totalProcessed = Application::whereHas('movements', function($q) use ($user) {
                $q->where('from_role_id', $user->role_id);
            })
            ->whereIn('allottee_id', $validAllotteeIds)
            ->count();

        return view('engineer.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
            'pendingApplications',
            'totalPending',
            'totalReceived',
            'totalProcessed'
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
