<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Address;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GenericNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailSubject;
    public $mailBody;
    public $link;
    public $fromAddress;

    /**
     * Create a new message instance.
     */
    public function __construct($mailSubject, $mailBody, $link = null, $fromAddress = null)
    {
        $this->mailSubject = $mailSubject;
        $this->mailBody = $mailBody;
        $this->link = $link;
        $this->fromAddress = $fromAddress;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $from = $this->fromAddress ? new Address($this->fromAddress, config('app.name')) : null;

        return new Envelope(
            from: $from,
            subject: $this->mailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.generic_notification',
            with: [
                'mailBody' => $this->mailBody,
                'link' => $this->link,
            ],
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
