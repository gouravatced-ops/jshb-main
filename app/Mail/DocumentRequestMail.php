<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $documentName;
    public $dueDate;
    public $dashboardUrl;
    public $messageBody;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $documentName, $dueDate, $dashboardUrl, $messageBody)
    {
        $this->userName = $userName;
        $this->documentName = $documentName;
        $this->dueDate = $dueDate;
        $this->dashboardUrl = $dashboardUrl;
        $this->messageBody = $messageBody;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Document Request - Action Required | JSHB',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.document_request',
            with: [
                'appName' => config('app.name', 'JSHB')
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
