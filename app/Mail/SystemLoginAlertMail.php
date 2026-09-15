<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SystemLoginAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ipAddress;
    public $location;
    public $timestamp;

    public function __construct($user, $ipAddress, $location, $timestamp)
    {
        $this->user = $user;
        $this->ipAddress = $ipAddress;
        $this->location = $location;
        $this->timestamp = $timestamp;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'System Alert: Successful Login',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.system-login-alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
