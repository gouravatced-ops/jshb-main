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
            // Prepare Mail
            $mail = Mail::to($this->email);
            if (!empty($detail->cc_email)) {
                $ccEmails = array_map('trim', explode(',', $detail->cc_email));
                $mail->cc($ccEmails);
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
