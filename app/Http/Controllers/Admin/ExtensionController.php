<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApplicationExtensionRequest;
use App\Models\ApplicationMovement;

class ExtensionController extends Controller
{
    public function index()
    {
        $requests = ApplicationExtensionRequest::with(['application', 'requestedBy'])
            ->orderBy('status', 'asc') // Pending first usually
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.extensions.index', compact('requests'));
    }

    public function approve(Request $request, ApplicationExtensionRequest $extension)
    {
        $request->validate([
            'extension_days' => 'required|integer|min:1|max:6',
            'remarks' => 'nullable|string'
        ]);

        if ($extension->status !== 'pending') {
            return back()->with('error', 'Request has already been processed.');
        }

        $extension->status = 'approved';
        $extension->remarks = $request->input('remarks', 'Approved by admin');
        $extension->extension_days = $request->extension_days;
        $extension->approved_at = now();
        $extension->save();

        // Extend the application movement due_date by X days
        $movement = ApplicationMovement::where('application_id', $extension->application_id)
            ->where('to_user_id', $extension->requested_by)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($movement) {
            $movement->due_date = $movement->due_date ? $movement->due_date->addDays($request->extension_days) : now()->addDays($request->extension_days);
            $movement->save();
        }

        // Notify the Engineer
        $notificationService = new \App\Services\NotificationService();
        $systemEmail = config('jshb.mail_system_username', 'system@adms.jshb.computered.co.in');
        
        $notificationService->send([
            'user_id' => $extension->requested_by,
            'is_allottee' => false,
            'notification_type' => 'success',
            'subject' => 'Extension Request Approved - Application #' . ($extension->application->application_no ?? 'N/A'),
            'message' => 'Your extension request has been approved. You have been granted ' . $request->extension_days . ' additional days. Remarks: ' . $extension->remarks,
            'link' => route('engineer.extensions.index'),
            'send_email' => true,
            'cc' => $systemEmail,
            'application_id' => $extension->application_id,
        ]);

        return redirect()->back()->with('success', 'Extension request approved. ' . $request->extension_days . ' days added to the deadline.');
    }

    public function reject(Request $request, ApplicationExtensionRequest $extension)
    {
        if ($extension->status !== 'pending') {
            return back()->with('error', 'Request has already been processed.');
        }

        $extension->status = 'rejected';
        $extension->remarks = $request->input('remarks', 'Rejected by admin');
        $extension->save();

        // Notify the Engineer
        $notificationService = new \App\Services\NotificationService();
        $systemEmail = config('jshb.mail_system_username', 'system@adms.jshb.computered.co.in');

        $notificationService->send([
            'user_id' => $extension->requested_by,
            'is_allottee' => false,
            'notification_type' => 'warning',
            'subject' => 'Extension Request Rejected - Application #' . ($extension->application->application_no ?? 'N/A'),
            'message' => 'Your extension request has been rejected. Remarks: ' . $extension->remarks,
            'link' => route('engineer.extensions.index'),
            'send_email' => true,
            'cc' => $systemEmail,
            'application_id' => $extension->application_id,
        ]);

        return redirect()->back()->with('success', 'Extension request rejected.');
    }
}
