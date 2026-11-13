<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use App\Models\AllotteeNotification;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'members');

        if ($type === 'administration') {
            $users = User::whereNotNull('role_id')
                         ->where('user_type', 'administration')
                         ->get();
            $pageTitle = 'Notify Administration';
            $btnText = 'Switch to Notify Members';
            $btnLink = route('admin.notifications.index', ['type' => 'members']);
        } else {
            $users = User::whereNotNull('role_id')
                         ->where('user_type', '!=', 'administration')
                         ->where('user_type', '!=', 'allottee')
                         ->where('user_type', '!=', 'operator')
                         ->get();
            $pageTitle = 'Notify Members';
            $btnText = 'Switch to Notify Administration';
            $btnLink = route('admin.notifications.index', ['type' => 'administration']);
        }

        return view('admin.notifications.index', compact('users', 'pageTitle', 'btnText', 'btnLink', 'type'));
    }

    public function sendBulk(Request $request, NotificationService $notificationService)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'type' => 'nullable|string'
        ]);

        $subject = $request->subject;
        $message = $request->message;
        $sendEmail = $request->has('send_email');
        $sendSms = $request->has('send_sms');
        $sendWhatsapp = $request->has('send_whatsapp');

        $isAllottee = ($request->type === 'allottees');
        $count = 0;
        foreach ($request->user_ids as $userId) {
            $notificationService->send([
                'user_id' => $userId,
                'is_allottee' => $isAllottee,
                'notification_type' => 'info',
                'subject' => $subject,
                'message' => $message,
                'send_email' => $sendEmail,
                'send_sms' => $sendSms,
                'send_whatsapp' => $sendWhatsapp,
                'link' => null
            ]);
            $count++;
        }

        return back()->with('success', "Notification sent successfully to {$count} user(s).");
    }

    public function markAllRead()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $isAllottee = ($user && ($user->user_type === 'allottee' || $user->getConnectionName() === 'adms_allottees'));
        $model = $isAllottee ? AllotteeNotification::class : Notification::class;

        $model::where('user_id', $user->id)
            ->where('is_read', 0)
            ->update([
                'is_read' => 1,
                'read_at' => now(),
            ]);

        return response()->json([
            'status' => 'success',
            'unread_count' => 0,
        ]);
    }

    public function markSingleRead($id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $isAllottee = ($user && ($user->user_type === 'allottee' || $user->getConnectionName() === 'adms_allottees'));
        $model = $isAllottee ? AllotteeNotification::class : Notification::class;

        $notif = $model::where('user_id', $user->id)->where('id', $id)->first();
        if ($notif) {
            $notif->is_read = 1;
            $notif->read_at = now();
            $notif->save();

            $unreadCount = $model::where('user_id', $user->id)->where('is_read', 0)->count();

            return response()->json([
                'status' => 'success',
                'unread_count' => $unreadCount,
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Notification not found'], 404);
    }

    public function getUnreadCount()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['unread_count' => 0]);
        }

        $isAllottee = ($user && ($user->user_type === 'allottee' || $user->getConnectionName() === 'adms_allottees'));
        $model = $isAllottee ? AllotteeNotification::class : Notification::class;

        $unreadCount = $model::where('user_id', $user->id)->where('is_read', 0)->count();

        return response()->json([
            'status' => 'success',
            'unread_count' => $unreadCount,
        ]);
    }

    public function getLatest()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        $isAllottee = ($user && ($user->user_type === 'allottee' || $user->getConnectionName() === 'adms_allottees'));
        $model = $isAllottee ? AllotteeNotification::class : Notification::class;

        $notifications = $model::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $unreadCount = $model::where('user_id', $user->id)->where('is_read', 0)->count();

        return response()->json([
            'status' => 'success',
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }
}
