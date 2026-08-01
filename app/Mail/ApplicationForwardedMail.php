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
    public $customMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($receiverName, $applicationNo, $senderName, $actionType, $dashboardUrl, $remarks, $customMessage = null)
    {
        $this->receiverName = $receiverName;
        $this->applicationNo = $applicationNo;
        $this->senderName = $senderName;
        $this->actionType = $actionType;
        $this->dashboardUrl = $dashboardUrl;
        $this->remarks = $remarks;
        $this->customMessage = $customMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $actionWord = $this->actionType;
        if ($this->actionType == 'forward') {
            $actionWord = 'forwarded';
        } elseif ($this->actionType == 'send_back') {
            $actionWord = 'sent back';
        } elseif ($this->actionType == 'approve') {
            $actionWord = 'approved';
        } elseif ($this->actionType == 'reject') {
            $actionWord = 'rejected';
        }

        $subject = "Application {$actionWord}: {$this->applicationNo}";
        if (in_array($this->actionType, ['forward', 'send_back'])) {
            $subject = "Application {$actionWord} to you: {$this->applicationNo}";
        }

        return $this->subject($subject)
            ->view('emails.application-forwarded');
    }
}
