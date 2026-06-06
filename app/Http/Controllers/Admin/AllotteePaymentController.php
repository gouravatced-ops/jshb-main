<?php
// app/Http/Controllers/Admin/AllotteePaymentController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllotteeGeneratedDocument;
use App\Models\AllotteePaymentOrder;
use App\Models\AllotteeTransaction;
use App\Models\AllotteeProcessStep;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use NumberFormatter;

class AllotteePaymentController extends Controller
{
    public function payInitialPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required'
        ]);
        DB::beginTransaction();
        try {
            // PAYMENT ORDER
            $payment = AllotteePaymentOrder::with(
                'allottee.scheme'
            )->findOrFail(
                $request->payment_id
            );

            // REFRESH PENALTY
            $payment->refreshPenalty();

            // TRANSACTION NUMBER
            $transactionNo =
                'TXN' .
                now()->format('YmdHis') .
                rand(1000, 9999);

            // UPDATE PAYMENT ORDER
            $payment->update([
                'paid_amount'      => $payment->total_payable,
                'remaining_amount' => 0,
                'paid_at'          => now(),
                'order_status'     => 'paid',
                'remarks'          =>
                'Payment received successfully',
            ]);

            // SAVE TRANSACTION
            $transaction =
                AllotteeTransaction::create([
                    'allottee_id'      => $payment->allottee_id,
                    'order_id'         => $payment->id,
                    'transaction_type' => 'allotment_payment',
                    'payment_stage'    => 'allotment',
                    'amount'           => $payment->total_payable,
                    'principal_amount' => $payment->base_amount,
                    'penalty_amount'   => $payment->penalty_amount,
                    'admin_charge'     => $payment->admin_charge,
                    'total_amount'     => $payment->total_payable,
                    'payment_mode'     => 'gateway',
                    'payment_status'   => 'success',
                    'transaction_no'   => $transactionNo,
                    'paid_at'          => now(),
                    'remarks'          =>
                    'Allotment payment successful',
                    'created_by'       => Auth::id(),
                ]);

            $payment->transaction_no  = $transaction->transaction_no;
            $payment->paid_date  = $transaction->paid_at;
            $payment->payment_gateway  = $transaction->payment_mode;

            // Amount in Word English and Hindi
            $amountInEnglish = amountToWords($payment->paid_amount, 'en');
            $amountInHindi = amountToWords($payment->paid_amount, 'hi');

            // GENERATE RECEIPT PDF
            $pdf = Pdf::loadView(
                'admin.allottee.sections.initial-payment-receipt',
                compact(
                    'payment',
                    'transaction',
                    'amountInEnglish',
                    'amountInHindi'
                )
            )
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'dpi' => 96,
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'chroot' => public_path(),
                ]);

            // RECEIPT FOLDER
            $folder = implode('/', [
                'documents',
                'allottee',
                'payment',
                'allotment',
                now()->format('Y'),
                now()->format('m'),
                now()->format('d'),
            ]);
            $directory = public_path($folder);
            File::ensureDirectoryExists(
                $directory,
                0755,
                true
            );

            // RECEIPT FILE
            $fileName =
                'allotment-payment-receipt-' .
                $payment->id . '-' .
                now()->format('YmdHis') . '-' .
                rand(1000, 9999) .
                '.pdf';
            file_put_contents(
                $directory . '/' . $fileName,
                $pdf->output()
            );

            // UPDATE TRANSACTION RECEIPT
            $transaction->update([
                'receipt_file' => $fileName,
                'receipt_path' => $folder . '/' . $fileName,
            ]);

            // SAVE GENERATED DOCUMENT
            AllotteeGeneratedDocument::create([
                'allottee_id'    =>
                $payment->allottee_id,
                'document_name'  =>
                'Allotment Payment Receipt',
                'document_type'  =>
                'allotment-payment-receipt',
                'file_name'      =>
                $fileName,
                'file_path'      =>
                $folder . '/' . $fileName,
                'generated_by'   =>
                Auth::id(),
                'generated_at'   =>
                now(),
            ]);

            // COMPLETE STEP
            AllotteeProcessStep::where([
                'allottee_id' =>
                $payment->allottee_id,
                'step_no'     => 6,
            ])->update([
                'status'       => 'completed',
                'completed_at' => now(),
                'completed_by' => Auth::id(),
            ]);

            // UNLOCK NEXT STEP
            AllotteeProcessStep::where([
                'allottee_id' =>
                $payment->allottee_id,
                'step_no'     => 7,
            ])->update([
                'status' => 'pending',
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' =>
                'Payment successful',
                'receipt_url' => asset(
                    $folder . '/' . $fileName
                ),
                'redirect' => route(
                    'admin.allottees.payment.success',
                    $payment->id
                )
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error(
                'Allotment payment failed',
                [
                    'payment_id' => $request->payment_id,
                    'error'      => $e->getMessage(),
                    'line'       => $e->getLine(),
                    'file'       => $e->getFile(),
                ]
            );
            return response()->json([
                'success' => false,
                'message' =>
                'Payment failed',
                'error' =>
                $e->getMessage()
            ], 500);
        }
    }

    public function payOnetimePayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required'
        ]);
        $paymentId = base64_decode($request->payment_id, true);
        DB::beginTransaction();
        try {
            // PAYMENT ORDER
            $payment = AllotteePaymentOrder::with(
                'allottee.scheme'
            )->findOrFail(
                $paymentId
            );

            // REFRESH PENALTY
            $payment->refreshPenalty();

            // TRANSACTION NUMBER
            $transactionNo =
                'TXN' .
                now()->format('YmdHis') .
                rand(1000, 9999);

            // UPDATE PAYMENT ORDER
            $payment->update([
                'paid_amount'      => $payment->total_payable,
                'remaining_amount' => 0,
                'paid_at'          => now(),
                'order_status'     => 'paid',
                'remarks'          =>
                'Payment received successfully',
            ]);

            // SAVE TRANSACTION
            $transaction =
                AllotteeTransaction::create([
                    'allottee_id'      => $payment->allottee_id,
                    'order_id'         => $payment->id,
                    'transaction_type' => 'one_time_payment',
                    'payment_stage'    => 'closure',
                    'amount'           => $payment->total_payable,
                    'principal_amount' => $payment->base_amount,
                    'penalty_amount'   => $payment->penalty_amount,
                    'admin_charge'     => $payment->admin_charge,
                    'total_amount'     => $payment->total_payable,
                    'payment_mode'     => 'gateway',
                    'payment_status'   => 'success',
                    'transaction_no'   => $transactionNo,
                    'paid_at'          => now(),
                    'remarks'          =>
                    'Allotment payment successful',
                    'created_by'       => Auth::id(),
                ]);

            $payment->transaction_no  = $transaction->transaction_no;
            $payment->paid_date  = $transaction->paid_at;
            $payment->payment_gateway  = $transaction->payment_mode;

            // Amount in Word English and Hindi
            $amountInEnglish = amountToWords($payment->paid_amount, 'en');
            $amountInHindi = amountToWords($payment->paid_amount, 'hi');

            // GENERATE RECEIPT PDF
            $pdf = Pdf::loadView(
                'admin.allottee.sections.initial-payment-receipt',
                compact(
                    'payment',
                    'transaction',
                    'amountInEnglish',
                    'amountInHindi'
                )
            )
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'dpi' => 96,
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'chroot' => public_path(),
                ]);

            // RECEIPT FOLDER
            $folder = implode('/', [
                'documents',
                'allottee',
                'payment',
                'one-time-payment',
                now()->format('Y'),
                now()->format('m'),
                now()->format('d'),
            ]);
            $directory = public_path($folder);
            File::ensureDirectoryExists(
                $directory,
                0755,
                true
            );

            // RECEIPT FILE
            $fileName =
                'one-time-payment-receipt-' .
                $payment->id . '-' .
                now()->format('YmdHis') . '-' .
                rand(1000, 9999) .
                '.pdf';
            file_put_contents(
                $directory . '/' . $fileName,
                $pdf->output()
            );

            // UPDATE TRANSACTION RECEIPT
            $transaction->update([
                'receipt_file' => $fileName,
                'receipt_path' => $folder . '/' . $fileName,
            ]);

            // SAVE GENERATED DOCUMENT
            AllotteeGeneratedDocument::create([
                'allottee_id'    =>
                $payment->allottee_id,
                'document_name'  =>
                'One Time Payment Receipt',
                'document_type'  =>
                'one-time-payment-receipt',
                'file_name'      =>
                $fileName,
                'file_path'      =>
                $folder . '/' . $fileName,
                'generated_by'   =>
                Auth::id(),
                'generated_at'   =>
                now(),
            ]);

            // COMPLETE STEP
            $now = now();
            $userId = Auth::id();

            AllotteeProcessStep::where('allottee_id', $payment->allottee_id)
                ->whereIn('step_no', [10, 11])
                ->update([
                    'status'       => 'completed',
                    'completed_at' => $now,
                    'completed_by' => $userId,
                ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' =>
                'Payment successful',
                'receipt_url' => asset(
                    $folder . '/' . $fileName
                ),
                'redirect' => route(
                    'admin.allottees.payment.success',
                    $payment->id
                )
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error(
                'Allotment payment failed',
                [
                    'payment_id' => $paymentId,
                    'error'      => $e->getMessage(),
                    'line'       => $e->getLine(),
                    'file'       => $e->getFile(),
                ]
            );
            return response()->json([
                'success' => false,
                'message' =>
                'Payment failed',
                'error' =>
                $e->getMessage()
            ], 500);
        }
    }
}