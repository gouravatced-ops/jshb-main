<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AllotteeCredentialMail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $password;
    public $portalLink;
    public $allotteeName;
    public $applicationNo;
    public $allotmentNo;
    public $customSubject;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $username,
        $password,
        $portalLink,
        $allotteeName = null,
        $applicationNo = null,
        $allotmentNo = null,
        $customSubject = null
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->portalLink = $portalLink;
        $this->allotteeName = $allotteeName;
        $this->applicationNo = $applicationNo;
        $this->allotmentNo = $allotmentNo;
        $this->customSubject = $customSubject;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->customSubject;
        if (!$subject) {
            $subject = $this->allotteeName
                ? 'Your JSHB Allottee Portal Login Credentials - ' . $this->allotteeName
                : 'Your JSHB Allottee Portal Login Credentials';
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.allottee-credentials',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
