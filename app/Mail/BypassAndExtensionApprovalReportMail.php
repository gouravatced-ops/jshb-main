<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BypassAndExtensionApprovalReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $reportData;
    public string $reportDate;

    /**
     * Create a new message instance.
     */
    public function __construct(array $reportData, string $reportDate)
    {
        $this->reportData = $reportData;
        $this->reportDate = $reportDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $bypassCount = count($this->reportData['bypass_approved'] ?? []);
        $extCount = count($this->reportData['extension_approved'] ?? []);

        return new Envelope(
            subject: "Automated Approvals Report: {$bypassCount} Bypass, {$extCount} Extension - {$this->reportDate}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.bypass-and-extension-approval-report',
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
