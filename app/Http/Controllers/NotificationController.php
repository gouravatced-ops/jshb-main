<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\AllotteeNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAllottee = ($user && ($user->user_type === 'allottee' || $user->getConnectionName() === 'adms_allottees'));
        $model = $isAllottee ? AllotteeNotification::class : Notification::class;

        $userId = $user->id ?? auth()->id();
        $query = $model::where('user_id', $userId)->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', 0);
            } elseif ($request->status === 'read') {
                $query->where('is_read', 1);
            }
        }

        $totalCount = $model::where('user_id', $userId)->count();
        $unreadCount = $model::where('user_id', $userId)->where('is_read', 0)->count();
        $readCount = $model::where('user_id', $userId)->where('is_read', 1)->count();

        $notifications = $query->paginate(15)->withQueryString();
            
        return view('notifications.index', compact('notifications', 'totalCount', 'unreadCount', 'readCount'));
    }
}
