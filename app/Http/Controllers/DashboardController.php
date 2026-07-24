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

        $dashboardNotices = $this->getNoticesForUser($user);
        $dashboardNotifications = $this->getNotificationsForUser($user);

        return view('admin.module.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
            'dashboardNotices',
            'dashboardNotifications'
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

        $dashboardNotices = $this->getNoticesForUser($user);
        $dashboardNotifications = $this->getNotificationsForUser($user);

        return view('staff.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
            'dashboardNotices',
            'dashboardNotifications'
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

        $dashboardNotices = $this->getNoticesForUser($user);
        $dashboardNotifications = $this->getNotificationsForUser($user);

        return view('division.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
            'dashboardNotices',
            'dashboardNotifications'
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

        $dashboardNotices = $this->getNoticesForUser($user);
        $dashboardNotifications = $this->getNotificationsForUser($user);

        return view('subdivision.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
            'dashboardNotices',
            'dashboardNotifications'
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
        $pendingApplications->transform(function ($app) {
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

        $totalReceived = Application::whereHas('movements', function ($q) use ($user) {
            $q->where('to_role_id', $user->role_id);
        })
            ->whereIn('allottee_id', $validAllotteeIds)
            ->count();

        // Applications that were with the engineer but are now moved forward/completed
        $totalProcessed = Application::whereHas('movements', function ($q) use ($user) {
            $q->where('from_role_id', $user->role_id);
        })
            ->whereIn('allottee_id', $validAllotteeIds)
            ->count();
            
        $totalSentBack = Application::where('current_role_id', $user->role_id)
            ->where('status', 'send_back')
            ->whereIn('allottee_id', $validAllotteeIds)
            ->count();
            
        $totalRejected = Application::where('current_role_id', $user->role_id)
            ->where('status', 'rejected')
            ->whereIn('allottee_id', $validAllotteeIds)
            ->count();

        $currentMonthReceivedCount = Application::whereHas('movements', function ($q) use ($user) {
            $q->where('to_role_id', $user->role_id);
        })
            ->whereIn('allottee_id', $validAllotteeIds)
            ->whereYear('created_date', now()->year)
            ->whereMonth('created_date', now()->month)
            ->count();

        $dashboardNotices = $this->getNoticesForUser($user);
        $dashboardNotifications = $this->getNotificationsForUser($user);

        // Fetch all pending applications for the calendar and use their latest movement date to the user
        $dashboardForwardedApps = Application::with('allottee')
            ->where('current_role_id', $user->role_id)
            ->whereIn('status', ['pending', 'in_progress', 'forwarded'])
            ->whereIn('allottee_id', $validAllotteeIds)
            ->whereHas('movements', function($q) use ($user) {
                $q->where('to_user_id', $user->id);
            })
            ->with(['movements' => function($q) use ($user) {
                $q->where('to_user_id', $user->id)->orderBy('id', 'desc');
            }])
            ->get();

        $dashboardForwardedApps->transform(function ($app) {
            $latestMovement = $app->movements->first();
            $app->created_date = $latestMovement ? $latestMovement->movement_date : $app->created_date;
            
            if ($app->allottee) {
                $app->allottee_name = trim("{$app->allottee->prefix} {$app->allottee->allottee_name} {$app->allottee->allottee_surname}");
            }
            return $app;
        });

        return view('engineer.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
            'pendingApplications',
            'totalPending',
            'totalReceived',
            'totalProcessed',
            'totalSentBack',
            'totalRejected',
            'currentMonthReceivedCount',
            'dashboardNotices',
            'dashboardNotifications',
            'dashboardForwardedApps'
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

        $dashboardNotices = $this->getNoticesForUser($user);
        $dashboardNotifications = $this->getNotificationsForUser($user);

        return view('accountant.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
            'dashboardNotices',
            'dashboardNotifications'
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

        $dashboardNotices = $this->getNoticesForUser($user);
        $dashboardNotifications = $this->getNotificationsForUser($user);

        return view('managing.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
            'dashboardNotices',
            'dashboardNotifications'
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

        $dashboardNotices = $this->getNoticesForUser($user);
        $dashboardNotifications = $this->getNotificationsForUser($user);

        return view('operator.dashboard', compact(
            'users',
            'loginLogs',
            'otpLogs',
            'latestLogin',
            'dashboardNotices',
            'dashboardNotifications'
        ));
    }



    private function getNotificationsForUser($user)
    {
        return \App\Models\Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function getNoticesForUser($user)
    {
        $now = now();
        
        if ($user->role === 'admin') {
            return \App\Models\Notice::with('creator')
                ->where(function($q) use ($now) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', $now);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return \App\Models\Notice::with('creator')
            ->where(function($q) use ($now) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $now);
            })
            ->where(function ($q) use ($user) {
                $q->whereNull('target_user_type')
                  ->orWhere(function ($q2) use ($user) {
                      $q2->where('target_user_type', $user->user_type)
                         ->where(function ($q3) use ($user) {
                             $q3->whereNull('target_division_id')
                                ->orWhere('target_division_id', $user->division_id);
                         })
                         ->where(function ($q4) use ($user) {
                             $q4->whereNull('target_user_id')
                                ->orWhereJsonContains('target_user_id', (string)$user->id)
                                ->orWhereJsonContains('target_user_id', $user->id);
                         });
                  });
            })->orderBy('created_at', 'desc')->get();
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
