<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\EngineerNotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealtimeNotificationTestController extends Controller
{
    /**
     * Show the live real-time notification testing console.
     */
    public function index()
    {
        $currentUser = Auth::user();

        // Fetch all active internal users (officers, engineers, admins, assistants)
        $officers = User::with('roleRelation')
            ->where('user_type', '!=', 'allottee')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role_id', 'user_type', 'username']);

        return view('engineer.test_notification', compact('currentUser', 'officers'));
    }

    /**
     * Dispatch an on-the-fly real-time notification without saving to database.
     */
    public function send(Request $request)
    {
        $request->validate([
            'target_user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|string|in:info,success,warning,error,document_request',
            'link' => 'nullable|string|max:500',
        ]);

        $sender = Auth::user();
        $targetUser = User::with('roleRelation')->find($request->target_user_id);

        $type = $request->type ?: 'info';
        $subject = $request->subject;
        $message = $request->message;
        $link = $request->link ?: url('/engineer/test/message/notification');

        // Purely in-memory payload - NO DATABASE WRITE
        $payload = [
            'id' => rand(900000, 999999),
            'title' => $subject,
            'subject' => $subject,
            'message' => $message,
            'type' => $type,
            'notification_type' => $type,
            'action_url' => $link,
            'link' => $link,
            'sender_id' => $sender->id,
            'sender_name' => $sender->name,
            'sender_role' => $sender->roleRelation?->name ?? ucfirst($sender->user_type ?? 'Officer'),
            'created_at' => now()->toDateTimeString(),
            'time_ago' => 'Just now',
            'is_test' => true,
        ];

        // Broadcast directly to the recipient officer via Reverb WebSocket
        EngineerNotificationEvent::dispatch($targetUser->id, $payload);

        return response()->json([
            'status' => 'success',
            'message' => "Live notification broadcasted to {$targetUser->name} (#{$targetUser->id}) via Reverb!",
            'target_officer' => [
                'id' => $targetUser->id,
                'name' => $targetUser->name,
                'role' => $targetUser->roleRelation?->name ?? $targetUser->user_type,
            ],
            'payload' => $payload,
        ]);
    }
}
