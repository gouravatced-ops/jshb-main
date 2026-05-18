<?php

// app/Http/Controllers/Admin/AllotteePaymentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllotteeGeneratedDocument;
use App\Models\AllotteeInitialPayment;
use App\Models\AllotteePaymentTransaction;
use App\Models\AllotteeProcessStep;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AllotteePaymentController extends Controller
{
    public function payInitialPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $payment = AllotteeInitialPayment::with('allottee.scheme')->findOrFail($request->payment_id);

            // REFRESH PENALTY
            $payment->refreshPenalty();

            $transactionNo = 'TXN' . now()->format('YmdHis') . rand(1000, 9999);

            // UPDATE INITIAL PAYMENT
            $payment->update([
                'payment_status'       => 'paid',
                'payment_mode'         => 'online',
                'transaction_no'       => $transactionNo,
                'paid_date'            => now(),
                'remarks'              => 'Initial payment received successfully',
            ]);

            // SAVE PAYMENT TRANSACTION
            AllotteePaymentTransaction::create([
                'allottee_id'      => $payment->allottee_id,
                'payment_id'       => $payment->id,
                'amount'           => $payment->total_payable_amount,
                'transaction_no'   => $transactionNo,
                'payment_gateway'  => 'JSHB GATEWAY',
                'payment_mode'     => 'online',
                'payment_status'   => 'success',
                'paid_at'          => now(),
                'gateway_response' => json_encode([
                    'status'  => 'success',
                    'message' => 'Payment completed',
                ]),
            ]);

            // GENERATE RECEIPT PDF
            $pdf = Pdf::loadView('admin.allottee.sections.initial-payment-receipt',compact('payment'))->setPaper('a4', 'portrait');

            $folder = implode('/', ['documents','allottee','payment','initial',now()->format('Y'),now()->format('m'),now()->format('d')]);

            $directory = public_path($folder);

            File::ensureDirectoryExists($directory,0755,true);

            $fileName ='initial-payment-receipt-' .$payment->id . '-' .now()->format('YmdHis') . '-' .rand(1000, 9999) .'.pdf';
            file_put_contents($directory . '/' . $fileName,$pdf->output());

            // SAVE GENERATED DOCUMENT
            AllotteeGeneratedDocument::create([
                'allottee_id'    => $payment->allottee_id,
                'domument_name'  => 'Initial Payment Receipt',
                'document_type'  => 'initial-payment-receipt',
                'file_name'      => $fileName,
                'file_path'      => $folder . '/' . $fileName,
                'generated_by'   => Auth::id(),
                'generated_at'   => now(),
            ]);

            // COMPLETE STEP
            AllotteeProcessStep::where([
                'allottee_id' => $payment->allottee_id,
                'step_no'     => 4,
            ])->update([
                'status'       => 'completed',
                'completed_at' => now(),
                'completed_by' => Auth::id(),
            ]);

            // UNLOCK NEXT STEP
            AllotteeProcessStep::where([
                'allottee_id' => $payment->allottee_id,
                'step_no'     => 5,
            ])->update([
                'status' => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
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
            return response()->json([
                'success' => false,
                'message' => 'Payment failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
