<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SystemFailedLoginAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailAttempted;
    public $ipAddress;
    public $location;
    public $timestamp;
    public $attempts;

    public function __construct($emailAttempted, $ipAddress, $location, $timestamp, $attempts)
    {
        $this->emailAttempted = $emailAttempted;
        $this->ipAddress = $ipAddress;
        $this->location = $location;
        $this->timestamp = $timestamp;
        $this->attempts = $attempts;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CRITICAL Security Alert: Multiple Failed Logins',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.system-failed-login-alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
