<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject(config('panel.portal_name') . ' Password Reset')
            ->greeting('Hello ' . ($notifiable->name ?: 'Member') . ',')
            ->line('We received a password reset request for your JSHB member account.')
            ->action('Reset Password', $url)
            ->line('This reset link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }
}
