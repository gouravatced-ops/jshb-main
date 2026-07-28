<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allottee;
use App\Models\AllotteePaymentOrder;
use App\Models\AllotteeGeneratedDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\DocumentUploadTrait;

class AllotteeCancellationController extends Controller
{
    use DocumentUploadTrait;

    public function index()
    {
        // Fetch allottees with overdue 15% allotment payment orders that are not yet paid or cancelled
        $allottees = Allottee::with(['division', 'subDivision', 'propertyCategory', 'scheme', 'quarterType'])
            ->where('is_cancelled', false)
            ->whereHas('allotteeOrders', function ($query) {
                $query->where('order_type', 'allotment')
                      ->where('due_date', '<', now()->format('Y-m-d'))
                      ->whereNotIn('order_status', ['paid', 'cancelled']);
            })
            ->get();

        return view('admin.allottee.cancellations.index', compact('allottees'));
    }

    public function bulkCancel(Request $request)
    {
        $validated = $request->validate([
            'allottee_ids' => 'required|array',
            'allottee_ids.*' => 'exists:adms_allottees.allottees,id',
        ]);

        $successCount = 0;
        $errorCount = 0;

        foreach ($validated['allottee_ids'] as $allotteeId) {
            DB::beginTransaction();
            try {
                $allottee = Allottee::findOrFail($allotteeId);
                
                // 1. Mark as cancelled
                $allottee->is_cancelled = true;
                $allottee->cancelled_at = now();
                $allottee->cancellation_reason = 'Non-payment of 15% allotment amount within stipulated time.';
                $allottee->save();

                // 2. Mark payment order as cancelled
                AllotteePaymentOrder::where('allottee_id', $allottee->id)
                    ->where('order_type', 'allotment')
                    ->whereNotIn('order_status', ['paid'])
                    ->update([
                        'order_status' => 'cancelled',
                        'remarks' => 'Cancelled due to overdue payment',
                        'updated_by' => Auth::id() ?? 1
                    ]);

                // 3. Generate Cancellation PDF
                $pdf = Pdf::loadView('admin.allottee.letters.templates.cancellation-pdf', compact('allottee'))
                    ->setOptions([
                        'isRemoteEnabled' => false,
                        'isHtml5ParserEnabled' => true,
                        'chroot' => [public_path(), storage_path(), base_path()]
                    ])
                    ->setPaper('a4', 'portrait');

                $pdfContent = $pdf->output();
                $safeAllotmentNo = str_replace(['/', '\\'], '-', $allottee->allotment_no ?? $allottee->application_no);
                $fileName = 'cancellation_order_' . $safeAllotmentNo . '_' . time() . '.pdf';

                $scheme = $allottee->scheme ?? null;
                $yyyy = date('Y');
                $mm = date('m');
                $dd = date('d');

                $extraData = [
                    'application_for' => $allottee->application_type ?? '',
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

                // 4. Save to allottee_generated_documents
                AllotteeGeneratedDocument::create([
                    'allottee_id'    => $allottee->id,
                    'document_name'  => 'Cancellation Order',
                    'document_type'  => 'cancellation-order',
                    'file_name'      => $uploadResult['file_name'],
                    'file_path'      => $uploadResult['file_path'],
                    'generated_by'   => Auth::id() ?? 1,
                    'generated_at'   => now(),
                    'issue_date'     => now()->format('Y-m-d'),
                    'document_number' => $allottee->allotment_no ?? $allottee->application_no
                ]);

                DB::commit();
                $successCount++;
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to cancel allotment for ID {$allotteeId}: " . $e->getMessage());
                $errorCount++;
            }
        }

        return back()->with('success', "Cancellation completed. Success: {$successCount}, Failed: {$errorCount}");
    }
}
