<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DocumentGenerationQueue;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\AllotteeGeneratedDocument;
use App\Models\AllotteeProcessStep;
use App\Models\AllotteePaymentOrder;
use App\Models\AllotteeStageTracker;
use App\Models\BatchProgram;
use App\Models\BatchProgramDetail;
use Illuminate\Support\Facades\Log;
use App\Traits\DocumentUploadTrait;

class ProcessDocumentGenerationQueue extends Command
{
    use DocumentUploadTrait;

    protected $signature = 'app:process-document-generation-queue {--portion=all : Process half or all pending documents}';
    protected $description = 'Process queued document generations in batches to prevent server load';

    public function handle()
    {
        $log = Log::build(['driver' => 'single', 'path' => storage_path('logs/document_generation.log')]);
        $portion = $this->option('portion');

        $log->info("--- Starting Document Generation Queue (Portion: {$portion}) ---");

        $batchProgram = BatchProgram::create([
            'command_name' => 'ProcessDocumentGenerationQueue',
            'started_at' => now(),
            'status' => 'running',
        ]);

        $pendingJobs = DocumentGenerationQueue::where('status', 'pending')->get();
        $totalPending = $pendingJobs->count();

        if ($totalPending === 0) {
            $batchProgram->update([
                'total_jobs' => 0,
                'unique_engineers_count' => 0,
                'completed_at' => now(),
                'status' => 'completed',
            ]);
            $this->info("No pending documents to generate.");
            $log->info("No pending documents. Exiting.");
            return;
        }

        $limit = $totalPending;
        if ($portion === 'half') {
            $limit = ceil($totalPending / 2);
            $pendingJobs = $pendingJobs->take($limit);
        }

        $this->info("Processing {$limit} out of {$totalPending} pending documents...");
        $log->info("Processing {$limit} out of {$totalPending} pending documents...");

        foreach ($pendingJobs as $job) {
            $job->update(['status' => 'processing']);
            
            $detail = BatchProgramDetail::create([
                'batch_program_id' => $batchProgram->id,
                'user_id' => $job->action_by_user_id,
                'application_id' => $job->application_id,
                'status' => 'queued',
                'queued_at' => now(),
            ]);
            
            try {
                $this->generateDocument($job, $log);
                $job->update(['status' => 'completed', 'error_message' => null, 'completed_at' => now()]);
                $detail->update([
                    'status' => 'sent', // Using sent to align with UI statuses
                    'sent_at' => now(),
                ]);
                $log->info("Successfully generated document for Application ID: {$job->application_id}");
            } catch (\Exception $e) {
                $job->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
                $detail->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
                $log->error("Failed to generate document for Application ID: {$job->application_id}. Error: " . $e->getMessage());
            }
        }

        $batchProgram->update([
            'total_jobs' => $limit,
            'completed_at' => now(),
            'status' => 'completed',
        ]);

        $this->info("Document generation queue processed successfully. Batch ID: {$batchProgram->id}");
        $log->info("--- Finished Document Generation Queue ---");
    }

    private function generateDocument($job, $log)
    {
        $application = Application::with('allottee')->find($job->application_id);
        if (!$application || !$application->allottee) {
            throw new \Exception("Application or Allottee not found.");
        }

        $allottee = $application->allottee;
        $user_id = $job->action_by_user_id;

        $isAgreement = ($application->application_type === 'agreement');
        $isPossession = ($application->application_type === 'possession');

        if ($isAgreement) {
            $pdfTemplate = 'admin.allottee.letters.templates.agreement-pdf';
            $documentType = 'agreement-letter';
            $documentName = 'Agreement Letter';
            $dbDocType = 'AGREEMENT_LETTER';
            $docPrefix = 'agreement_letter_';
        } elseif ($isPossession) {
            $pdfTemplate = 'admin.allottee.letters.templates.possession-pdf';
            $documentType = 'possession-letter';
            $documentName = 'Possession Letter';
            $dbDocType = 'POSSESSION_LETTER';
            $docPrefix = 'possession_letter_';
        } else {
            $pdfTemplate = 'admin.allottee.letters.templates.allotment-pdf';
            $documentType = 'allotment-letter';
            $documentName = 'Allotment Letter';
            $dbDocType = 'ALLOTMENT_LETTER';
            $docPrefix = 'allotment_letter_';
        }

        // 1. Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($pdfTemplate, compact('allottee'))
            ->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'chroot' => [public_path(), storage_path(), base_path()]
            ])
            ->setPaper('a4', 'portrait');

        $pdfContent = $pdf->output();
        $allotmentNo = $allottee->allotment_no ?? $application->application_no;
        $safeAllotmentNo = str_replace(['/', '\\'], '-', $allotmentNo);
        $fileName = $docPrefix . $safeAllotmentNo . '_' . time() . '.pdf';

        // 2. Upload to Document API
        $scheme = $allottee->scheme ?? null;
        $yyyy = $allottee->allotment_year ?? date('Y');
        $mm = $allottee->allotment_month ?? date('m');
        $dd = $allottee->allotment_day ?? date('d');

        $extraData = [
            'application_for' => $application->application_type ?? '',
            'division_code' => $allottee->division->division_code ?? '',
            'subdivision_code' => $allottee->subDivision->subdivision_code ?? '',
            'property_category' => $allottee->propertyCategory->category_code ?? '',
            'property_type' => $allottee->propertyType->type_code ?? '',
            'property_income' => $allottee->quarterType->quarter_code ?? '',
            'username' => $allottee->username ?? ''
        ];

        $uploadResult = $this->uploadContentToDocumentApi(
            $pdfContent,
            $fileName,
            'FINAL',
            $scheme->scheme_code ?? 'SCH',
            $allottee->property_number ?? 'PROP',
            $yyyy,
            $mm,
            $dd,
            $extraData
        );

        if (!isset($uploadResult['file_name']) || !isset($uploadResult['file_path'])) {
            throw new \Exception("Document API upload failed. Missing file_name or file_path in response.");
        }

        // 3. Save to application_documents
        ApplicationDocument::create([
            'application_id' => $application->id,
            'movement_id'    => null,
            'document_type'  => $dbDocType,
            'document_name'  => $documentName . ' (Auto Generated)',
            'file_name'      => $uploadResult['file_name'],
            'file_path'      => $uploadResult['file_path'],
            'file_size'      => strlen($pdfContent),
            'file_mime_type' => 'application/pdf',
            'uploaded_by'    => $user_id,
            'uploader_type'  => 'System',
            'uploaded_at'    => now(),
        ]);

        // 4. Save to allottee_generated_documents
        AllotteeGeneratedDocument::create([
            'allottee_id'    => $allottee->id,
            'document_name'  => $documentName,
            'document_type'  => $documentType,
            'file_name'      => $uploadResult['file_name'],
            'file_path'      => $uploadResult['file_path'],
            'generated_by'   => $user_id,
            'generated_at'   => now(),
            'issue_date'     => now()->format('Y-m-d'),
            'document_number' => $allottee->allotment_no ?? $application->application_no
        ]);

        AllotteeStageTracker::create([
            'allottee_id'    => $allottee->id,
            'application_no' => $application->application_no,
            'stage_type'     => $application->application_type,
            'status'         => 'completed',
            'action_by'      => $user_id,
        ]);

        // Unlocking Steps Logic
        if ($isPossession) {
            $currentStep = AllotteeProcessStep::where([
                'allottee_id' => $allottee->id,
                'menu_key' => 'allotment',
                'sub_menu_key' => 'allotment-possession-letter'
            ])->first();

            if ($currentStep) {
                AllotteeProcessStep::completeStep($allottee->id, 'allotment', $currentStep->sub_menu_key, $user_id);
                AllotteeProcessStep::unlockNextStep($allottee->id, $currentStep->step_no);
            }
        } elseif ($isAgreement) {
            AllotteeProcessStep::completeStep($allottee->id, 'allotment', 'agreement-document-letter', $user_id);
            $currentStep = AllotteeProcessStep::where([
                'allottee_id' => $allottee->id,
                'menu_key' => 'allotment',
                'sub_menu_key' => 'agreement-document-letter'
            ])->first();

            if ($currentStep) {
                AllotteeProcessStep::unlockNextStep($allottee->id, $currentStep->step_no);
            }
        } else {
            AllotteeProcessStep::completeStep($allottee->id, 'allotment', 'generate-allotment', $user_id);

            // Generate Payment Order
            $finance = $allottee->scheme->schemeFinance ?? null;
            $propertyAmount = $finance ? (float) ($finance->property_total_cost ?? 0) : 0;
            $allotmentPercentage = $finance ? (float) ($finance->allotment_percentage ?? 15) : 15;
            $baseAmount = $finance ? (float) ($finance->allotment_amount ?? 0) : 0;

            if ($baseAmount == 0 && $propertyAmount > 0) {
                $baseAmount = ($propertyAmount * $allotmentPercentage) / 100;
            }

            AllotteePaymentOrder::updateOrCreate(
                [
                    'allottee_id' => $allottee->id,
                    'order_type'  => 'allotment',
                ],
                [
                    'order_no'         => AllotteePaymentOrder::generateOrderNo('ODR-ALT'),
                    'title'            => "{$allotmentPercentage}% Allotment Payment Order",
                    'property_amount'  => $propertyAmount,
                    'percentage'       => $allotmentPercentage,
                    'base_amount'      => $baseAmount,
                    'penalty_amount'   => 0,
                    'admin_charge'     => 0,
                    'total_payable'    => $baseAmount,
                    'paid_amount'      => 0,
                    'remaining_amount' => $baseAmount,
                    'due_date'         => now()->addDays(30)->format('Y-m-d'),
                    'issued_at'        => now(),
                    'order_status'     => 'issued',
                    'remarks'          => 'Auto generated ' . $allotmentPercentage . '% allotment payment order',
                    'created_by'       => $user_id,
                ]
            );

            $currentStep = AllotteeProcessStep::where([
                'allottee_id' => $allottee->id,
                'menu_key' => 'allotment',
                'sub_menu_key' => 'generate-allotment'
            ])->first();

            if ($currentStep) {
                AllotteeProcessStep::unlockNextStep($allottee->id, $currentStep->step_no);
            }
        }
    }
}
