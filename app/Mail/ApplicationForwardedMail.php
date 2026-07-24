<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationForwardedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $receiverName;
    public $applicationNo;
    public $senderName;
    public $actionType;
    public $dashboardUrl;
    public $remarks;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($receiverName, $applicationNo, $senderName, $actionType, $dashboardUrl, $remarks)
    {
        $this->receiverName = $receiverName;
        $this->applicationNo = $applicationNo;
        $this->senderName = $senderName;
        $this->actionType = $actionType;
        $this->dashboardUrl = $dashboardUrl;
        $this->remarks = $remarks;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $actionWord = $this->actionType == 'forward' ? 'forwarded' : 'sent back';
        return $this->subject("Application {$actionWord} to you: {$this->applicationNo}")
                    ->view('emails.application-forwarded');
    }
}
