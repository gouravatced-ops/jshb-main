<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $messageBody;
    public $attachmentPaths;
    public $purpose;
    public $userName;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $messageBody, $attachmentPaths = [], $purpose = 'login', $userName = null)
    {
        $this->otp = $otp;
        $this->messageBody = $messageBody;
        $this->attachmentPaths = $attachmentPaths;
        $this->purpose = $purpose;
        $this->userName = $userName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $purposeLabel = match ($this->purpose) {
            'login'          => 'Login',
            'password_reset' => 'Password Reset',
            default          => 'Verification',
        };

        return new Envelope(
            subject: "Your OTP for {$purposeLabel} - " . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            with: [
                'otp' => $this->otp,
                'messageBody' => $this->messageBody,
                'appName' => config('app.name'),
                'userName' => $this->userName,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        
        foreach ($this->attachmentPaths as $path) {
            if (file_exists($path)) {
                $attachments[] = Attachment::fromPath($path);
            }
        }
        
        return $attachments;
    }
}