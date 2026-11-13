<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EngineerNotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $notification;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, $notification)
    {
        $this->userId = $userId;
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.' . $this->userId);
    }
    
    public function broadcastAs()
    {
        return 'EngineerNotification';
    }

    public function broadcastWith(): array
    {
        $data = is_array($this->notification) ? $this->notification : $this->notification->toArray();
        $title = $data['subject'] ?? ($data['title'] ?? 'New Notification');
        $msg = $data['message'] ?? '';
        $type = $data['notification_type'] ?? ($data['type'] ?? 'info');
        $link = $data['link'] ?? ($data['action_url'] ?? null);

        return [
            'id' => $data['id'] ?? null,
            'title' => $title,
            'subject' => $title,
            'message' => $msg,
            'type' => $type,
            'notification_type' => $type,
            'action_url' => $link,
            'link' => $link,
            'sender_id' => $data['sender_id'] ?? null,
            'created_at' => isset($data['created_at']) ? (string)$data['created_at'] : now()->toDateTimeString(),
            'time_ago' => 'Just now',
            'notification' => $data,
        ];
    }
}
