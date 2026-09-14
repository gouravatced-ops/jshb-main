<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApplicationExtensionRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApplicationExtensionRequestController extends Controller
{
    public function index()
    {
        $requests = ApplicationExtensionRequest::where('requested_by', Auth::id())
            ->with('application')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('engineer.extensions.index', compact('requests'));
    }

    public function create(Application $application)
    {
        return view('engineer.extensions.create', compact('application'));
    }

    public function store(Request $request, Application $application)
    {
        $request->validate([
            'request_reason' => 'required|string',
            'otp_verified' => 'required|in:1'
        ], [
            'otp_verified.in' => 'Please verify OTP first before submitting.'
        ]);

        $extension = ApplicationExtensionRequest::create([
            'application_id' => $application->id,
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
                'message' => 'Engineer ' . Auth::user()->name . ' has requested a timeline extension for application ' . $application->application_no . '. Reason: ' . strip_tags($request->request_reason),
                'link' => route('admin.extensions.index'),
                'send_email' => true,
                'cc' => $systemEmail,
                'application_id' => $application->id,
            ]);
        }

        return redirect()->route('engineer.applications.index')
            ->with('success', 'Extension request submitted successfully and is pending admin approval.');
    }
}
