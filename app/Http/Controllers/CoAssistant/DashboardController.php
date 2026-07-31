<?php

namespace App\Http\Controllers\CoAssistant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginLog;
use App\Models\OtpLog;
use App\Models\Application;
use App\Models\Workflow;
use App\Models\Allottee;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $loginLogs = LoginLog::latest()->take(10)->get();
        $otpLogs = OtpLog::latest()->take(10)->get();
        $latestLogin = $loginLogs->first();

        // Target MD the assistant works for
        $targetUserId = $user->assistant_to_id ?? $user->id;
        $targetRole = User::find($targetUserId);
        $targetRoleId = $targetRole ? $targetRole->role_id : $user->role_id;

        $pendingApplications = collect();
        $workflowId = Workflow::where('application_type', 'allotment')->value('id') ?? 1;
        
        // 1. Get all pending application allottee IDs for the MD role
        $pendingAllotteeIds = Application::where('current_role_id', $targetRoleId)
            ->whereIn('status', ['pending', 'in_progress', 'forwarded'])
            ->pluck('allottee_id')
            ->unique()
            ->toArray();

        // 2. Fetch applications directly (NO division_id filter for administration)
        $pendingApplications = Application::with('allottee')
            ->where('current_role_id', $targetRoleId)
            ->whereIn('status', ['pending', 'in_progress', 'forwarded'])
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

        // Stats (using targetRoleId and NO division_id)
        $totalPending = Application::where('current_role_id', $targetRoleId)
            ->whereIn('status', ['pending', 'in_progress', 'forwarded'])
            ->count();

        $totalReceived = Application::whereHas('movements', function ($q) use ($targetRoleId) {
            $q->where('to_role_id', $targetRoleId);
        })
            ->count();

        $totalProcessed = Application::whereHas('movements', function ($q) use ($targetRoleId) {
            $q->where('from_role_id', $targetRoleId);
        })
            ->count();
            
        $totalSentBack = Application::where('current_role_id', $targetRoleId)
            ->where('status', 'send_back')
            ->count();
            
        $totalRejected = Application::where('current_role_id', $targetRoleId)
            ->where('status', 'rejected')
            ->count();

        $currentMonthReceivedCount = Application::whereHas('movements', function ($q) use ($targetRoleId) {
            $q->where('to_role_id', $targetRoleId);
        })
            ->whereYear('created_date', now()->year)
            ->whereMonth('created_date', now()->month)
            ->count();

        // Fetch all pending applications for the calendar and use their latest movement date to the user
        $dashboardForwardedApps = Application::with('allottee')
            ->where('current_role_id', $targetRoleId)
            ->whereIn('status', ['pending', 'in_progress', 'forwarded'])
            ->whereHas('movements', function($q) use ($targetUserId) {
                $q->where('to_user_id', $targetUserId);
            })
            ->with(['movements' => function($q) use ($targetUserId) {
                $q->where('to_user_id', $targetUserId)->orderBy('id', 'desc');
            }])
            ->get();

        $dashboardForwardedApps->transform(function ($app) {
            $latestMovement = $app->movements->first();
            $app->created_date = $latestMovement ? $latestMovement->movement_date : $app->created_date;
            
            if ($app->allottee) {
                $app->allottee_name = trim(
                    ($app->allottee->prefix ? $app->allottee->prefix . ' ' : '') .
                    $app->allottee->allottee_name . ' ' .
                    $app->allottee->allottee_middle_name . ' ' .
                    $app->allottee->allottee_surname
                );
            }
            return $app;
        });

        // Use notices if needed (we can skip them or leave an empty array)
        $dashboardNotices = collect();
        $dashboardNotifications = collect();

        return view('coassistant.dashboard', compact(
            'user',
            'latestLogin',
            'pendingApplications',
            'totalPending',
            'totalProcessed',
            'totalReceived',
            'totalSentBack',
            'totalRejected',
            'currentMonthReceivedCount',
            'dashboardForwardedApps',
            'dashboardNotices',
            'dashboardNotifications'
        ));
    }
}
