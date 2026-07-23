<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\AllotteeNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isAllottee = ($user && ($user->user_type === 'allottee' || $user->getConnectionName() === 'adms_allottees'));
        $model = $isAllottee ? AllotteeNotification::class : Notification::class;

        $notifications = $model::where('user_id', $user->id ?? auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('notifications.index', compact('notifications'));
    }
}
