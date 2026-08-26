<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BypassRequest;
use App\Models\Application;
use App\Models\ApplicationMovement;
use App\Models\ApplicationAuditTrail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Notification;
use App\Mail\GenericNotificationMail;


class BypassRequestController extends Controller
{
    public function index()
    {
        $bypassRequests = BypassRequest::with([
                'application.notes.user.roleRelation', 
                'application.notes' => function($q) {
                    $q->orderBy('created_at', 'desc');
                },
                'requestedBy', 
                'targetRole', 
                'targetStep'
            ])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.bypass-requests.index', compact('bypassRequests'));
    }

    public function history()
    {
        $bypassRequests = BypassRequest::with([
                'application', 
                'requestedBy', 
                'approvedBy',
                'targetRole', 
                'targetStep'
            ])
            ->where('status', '!=', 'pending')
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return view('admin.bypass-requests.history', compact('bypassRequests'));
    }

    public function approve(Request $request, $id)
    {
        $bypassRequest = BypassRequest::findOrFail($id);
        
        if ($bypassRequest->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        DB::transaction(function () use ($bypassRequest, $request) {
            // Update bypass request
            $bypassRequest->status = 'approved';
            $bypassRequest->approved_by_user_id = Auth::id();
            $bypassRequest->approved_at = now();
            $bypassRequest->save();

            // Notify the engineer that their bypass request was approved
            $engineer = User::on('adms_jshb')->find($bypassRequest->requested_by_user_id);
            if ($engineer) {
                $mailSubject = "Bypass Request Approved for Application: " . ($bypassRequest->application->application_no ?? '');
                $mailBody = "Your request to bypass workflow for Application No: " . ($bypassRequest->application->application_no ?? '') . " has been approved by Admin. You can now forward the application to the approved officer.";
                $link = route('engineer.applications.show', $bypassRequest->application);

                Notification::create([
                    'application_id' => $bypassRequest->application_id,
                    'user_id' => $engineer->id,
                    'notification_type' => 'bypass_approved',
                    'subject' => $mailSubject,
                    'message' => $mailBody,
                    'link' => $link,
                    'is_read' => false
                ]);

                if ($engineer->email) {
                    try {
                        Mail::to($engineer->email)->send(new GenericNotificationMail($mailSubject, $mailBody, $link));
                    } catch (\Exception $e) {
                        Log::error("Failed to send bypass approval mail to engineer: " . $e->getMessage());
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Bypass request approved. The engineer can now forward the application.');
    }

    public function reject(Request $request, $id)
    {
        $bypassRequest = BypassRequest::findOrFail($id);
        
        if ($bypassRequest->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        $bypassRequest->status = 'rejected';
        $bypassRequest->approved_by_user_id = Auth::id();
        $bypassRequest->approved_at = now();
        $bypassRequest->save();

        return redirect()->back()->with('success', 'Bypass request rejected. The application remains with the original officer.');
    }
}
