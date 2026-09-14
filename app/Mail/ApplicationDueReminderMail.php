<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationDueReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $userName;
    public $applicationNo;
    public $dueDateFormatted;
    public $diffDays;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $applicationNo, $dueDateFormatted, $diffDays)
    {
        $this->userName = $userName;
        $this->applicationNo = $applicationNo;
        $this->dueDateFormatted = $dueDateFormatted;
        $this->diffDays = $diffDays;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Application Lock Warning: Due Date Approaching - {$this->applicationNo}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.application_due_reminder',
            with: [
                'userName' => $this->userName,
                'applicationNo' => $this->applicationNo,
                'dueDateFormatted' => $this->dueDateFormatted,
                'diffDays' => $this->diffDays,
            ],
        );
    }
}
