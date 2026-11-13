<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\BatchProgramDetail;
use Exception;

class ProcessBatchEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $batchDetailId;
    protected $email;
    protected $mailable;

    /**
     * Create a new job instance.
     */
    public function __construct($batchDetailId, $email, $mailable)
    {
        $this->batchDetailId = $batchDetailId;
        $this->email = $email;
        $this->mailable = $mailable;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $detail = BatchProgramDetail::find($this->batchDetailId);

        if (!$detail) {
            Log::warning("ProcessBatchEmailJob failed: BatchDetail ID {$this->batchDetailId} not found.");
            return;
        }

        try {
            // Validate primary recipient before processing
            $validation = \Thenetbotz\EmailValidator\Facades\EmailValidator::validate($this->email);
            if (!$validation->valid) {
                $readableReason = \App\Listeners\ValidateEmailRecipientListener::getReadableReason($validation->reason);
                $detail->update([
                    'status' => 'failed',
                    'error_message' => "Blocked: {$readableReason} ({$validation->reason})",
                ]);

                $logMsg = "[BATCH MAIL BLOCKED] Detail ID {$this->batchDetailId} | Recipient: {$this->email} | Reason: {$readableReason} ({$validation->reason})";
                Log::warning($logMsg);
                if (config()->has('logging.channels.send_mail')) {
                    Log::channel('send_mail')->warning($logMsg);
                }
                return;
            }

            // Filter valid CC emails
            $validCcEmails = [];
            if (!empty($detail->cc_email)) {
                $rawCcEmails = array_map('trim', explode(',', $detail->cc_email));
                foreach ($rawCcEmails as $cc) {
                    if (empty($cc)) continue;
                    $ccRes = \Thenetbotz\EmailValidator\Facades\EmailValidator::validate($cc);
                    if ($ccRes->valid) {
                        $validCcEmails[] = $cc;
                    } else {
                        $ccReason = \App\Listeners\ValidateEmailRecipientListener::getReadableReason($ccRes->reason);
                        $ccMsg = "[BATCH MAIL CC STRIPPED] Detail ID {$this->batchDetailId} | Stripped CC: {$cc} | Reason: {$ccReason} ({$ccRes->reason})";
                        Log::warning($ccMsg);
                        if (config()->has('logging.channels.send_mail')) {
                            Log::channel('send_mail')->warning($ccMsg);
                        }
                    }
                }
            }

            // Prepare Mail
            $mail = Mail::to($this->email);
            if (!empty($validCcEmails)) {
                $mail->cc($validCcEmails);
            }

            // Send the email
            $mail->send($this->mailable);

            // Update the detail record
            $detail->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            Log::info("ProcessBatchEmailJob successful for Detail ID {$this->batchDetailId} to {$this->email}.");

        } catch (Exception $e) {
            // Log the failure
            $detail->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error("ProcessBatchEmailJob failed for Detail ID {$this->batchDetailId}: " . $e->getMessage());

            // Re-throw to let the queue manager know it failed
            throw $e;
        }
    }
}
