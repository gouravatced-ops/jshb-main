<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApplicationExtensionRequest;
use App\Models\ApplicationMovement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApplicationExtensionRequestController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $baseQuery = ApplicationExtensionRequest::where('requested_by', $userId);

        $totalCount = (clone $baseQuery)->count();
        $approvedCount = (clone $baseQuery)->where('status', 'approved')->count();
        $pendingCount = (clone $baseQuery)->where('status', 'pending')->count();
        $rejectedCount = (clone $baseQuery)->where('status', 'rejected')->count();

        $query = (clone $baseQuery)->with('application');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('application', function ($appQ) use ($search) {
                    $appQ->where('application_no', 'like', "%{$search}%");
                })->orWhere('request_reason', 'like', "%{$search}%");
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->all());

        return view('engineer.extensions.index', compact('requests', 'totalCount', 'approvedCount', 'pendingCount', 'rejectedCount'));
    }

    public function create($id)
    {
        try {
            $applicationId = \Illuminate\Support\Facades\Crypt::decryptString($id);
            $application = Application::findOrFail($applicationId);
        } catch (\Exception $e) {
            abort(404, 'Invalid Application ID');
        }

        return view('engineer.extensions.create', compact('application'));
    }

    public function store(Request $request, $id)
    {
        try {
            $applicationId = \Illuminate\Support\Facades\Crypt::decryptString($id);
            $application = Application::findOrFail($applicationId);
        } catch (\Exception $e) {
            abort(404, 'Invalid Application ID');
        }

        $request->validate([
            'request_reason' => 'required|string',
            'otp_verified' => 'required|in:1'
        ], [
            'otp_verified.in' => 'Please verify OTP first before submitting.'
        ]);

        $movement = ApplicationMovement::where('application_id', $application->id)
            ->where('to_user_id', Auth::id())
            ->whereIn('status', ['pending', 'in_progress'])
            ->latest()
            ->first();

        $extension = ApplicationExtensionRequest::create([
            'application_id' => $application->id,
            'movement_id' => $movement ? $movement->id : null,
            'requested_by' => Auth::id(),
            'request_reason' => $request->request_reason,
            'status' => 'pending'
        ]);

        // Send Notification to Admins
        $admins = User::whereHas('roleRelation', function($q) {
            $q->where('slug', 'admin')->orWhere('name', 'Admin');
        })->get();

        $notificationService = new \App\Services\NotificationService();
        $systemEmail = config('jshb.mail_system_username', 'system@adms.jshb.computered.co.in');

        foreach ($admins as $admin) {
            $notificationService->send([
                'user_id' => $admin->id,
                'is_allottee' => false,
                'notification_type' => 'info',
                'subject' => 'New Extension Request - Application #' . $application->application_no,
                'message' => 'Officer ' . Auth::user()->name . ' has requested a timeline extension for application ' . $application->application_no . '.',
                'link' => route('admin.extensions.index'),
                'send_email' => true,
                'cc' => $systemEmail,
                'application_id' => $application->id,
            ]);
        }

        return redirect()->route('engineer.applications.index')
            ->with('success', 'Extension request submitted successfully and is pending admin approval.');
    }

    public function show($id)
    {
        try {
            $extensionId = \Illuminate\Support\Facades\Crypt::decryptString($id);
            $extension = ApplicationExtensionRequest::with(['application', 'requestedBy'])
                ->where('requested_by', Auth::id())
                ->findOrFail($extensionId);
        } catch (\Exception $e) {
            abort(404, 'Invalid Extension Request ID');
        }

        return view('engineer.extensions.show', compact('extension'));
    }
}
