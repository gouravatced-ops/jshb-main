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
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.extensions.index', compact('requests'));
    }

    public function history()
    {
        $requests = ApplicationExtensionRequest::with(['application', 'requestedBy'])
            ->where('status', '!=', 'pending')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('admin.extensions.history', compact('requests'));
    }

    public function show($id)
    {
        try {
            $extensionId = \Illuminate\Support\Facades\Crypt::decryptString($id);
            $extension = ApplicationExtensionRequest::with(['application', 'requestedBy'])->findOrFail($extensionId);
        } catch (\Exception $e) {
            abort(404, 'Invalid Extension Request ID');
        }

        return view('admin.extensions.show', compact('extension'));
    }

    public function process(Request $request, $id)
    {
        try {
            $extensionId = \Illuminate\Support\Facades\Crypt::decryptString($id);
            $extension = ApplicationExtensionRequest::with(['application', 'requestedBy'])->findOrFail($extensionId);
        } catch (\Exception $e) {
            abort(404, 'Invalid Extension Request ID');
        }

        if ($extension->status !== 'pending') {
            return redirect()->route('admin.extensions.history')->with('error', 'Request has already been processed.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string',
            'extension_days' => 'required_if:action,approve|integer|min:1|max:6',
            'otp_verified' => 'required|in:1'
        ], [
            'otp_verified.in' => 'Please verify OTP first before submitting.'
        ]);

        $extension->status = $request->action === 'approve' ? 'approved' : 'rejected';
        $extension->remarks = $request->remarks;
        if ($request->action === 'approve') {
            $extension->extension_days = $request->extension_days;
            $extension->approved_at = now();
        }
        $extension->save();

        if ($request->action === 'approve') {
            if ($extension->movement_id) {
                // Extend the specific application movement due_date by X days
                $movement = ApplicationMovement::find($extension->movement_id);
                if ($movement) {
                    $currentDate = $movement->due_date ? \Carbon\Carbon::parse($movement->due_date) : now();
                    $movement->due_date = $currentDate->addDays((int) $request->extension_days);
                    $movement->save();
                }
            } else {
                // Fallback for older extension requests that don't have movement_id stored
                $movement = ApplicationMovement::where('application_id', $extension->application_id)
                    ->where('to_user_id', $extension->requested_by)
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->latest()
                    ->first();

                if ($movement) {
                    $currentDate = $movement->due_date ? \Carbon\Carbon::parse($movement->due_date) : now();
                    $movement->due_date = $currentDate->addDays((int) $request->extension_days);
                    $movement->save();
                }
            }
        }

        // Notify the Engineer
        $notificationService = new \App\Services\NotificationService();
        $systemEmail = config('jshb.mail_system_username', 'system@adms.jshb.computered.co.in');

        $actionText = $request->action === 'approve' ? 'Approved' : 'Rejected';
        $notificationType = $request->action === 'approve' ? 'success' : 'warning';

        $message = 'Your extension request has been ' . strtolower($actionText) . '. ';
        if ($request->action === 'approve') {
            $message .= 'You have been granted ' . $request->extension_days . ' additional days.';
        } else {
            $message .= 'Reason: ' . strip_tags($extension->remarks);
        }

        $notificationService->send([
            'user_id' => $extension->requested_by,
            'is_allottee' => false,
            'notification_type' => $notificationType,
            'subject' => 'Extension Request ' . $actionText . ' - Application #' . ($extension->application->application_no ?? 'N/A'),
            'message' => $message,
            'link' => route('engineer.extensions.index'),
            'send_email' => true,
            'cc' => $systemEmail,
            'application_id' => $extension->application_id,
        ]);

        return redirect()->route('admin.extensions.index')
            ->with('success', 'Extension request has been successfully ' . strtolower($actionText) . '.');
    }
}
