<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification as BaseNotification;

class RealTimeNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public array $data;

    /**
     * Create a new notification instance.
     *
     * @param array $data [title, message, type, action_url, sender_id, created_at]
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param mixed $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        $title = $this->data['title'] ?? ($this->data['subject'] ?? 'New Notification');
        $message = $this->data['message'] ?? '';
        $type = $this->data['type'] ?? ($this->data['notification_type'] ?? 'info');
        $actionUrl = $this->data['action_url'] ?? ($this->data['link'] ?? null);

        return new BroadcastMessage([
            'id' => $this->data['id'] ?? null,
            'title' => $title,
            'subject' => $title,
            'message' => $message,
            'type' => $type,
            'notification_type' => $type,
            'action_url' => $actionUrl,
            'link' => $actionUrl,
            'sender_id' => $this->data['sender_id'] ?? null,
            'created_at' => $this->data['created_at'] ?? now()->toDateTimeString(),
            'time_ago' => 'Just now',
            'notification' => [
                'id' => $this->data['id'] ?? null,
                'subject' => $title,
                'message' => $message,
                'notification_type' => $type,
                'link' => $actionUrl,
                'created_at' => $this->data['created_at'] ?? now()->toDateTimeString(),
            ],
        ]);
    }

    /**
     * The type of the broadcast event.
     *
     * @return string
     */
    public function broadcastType(): string
    {
        return 'EngineerNotification';
    }
}
