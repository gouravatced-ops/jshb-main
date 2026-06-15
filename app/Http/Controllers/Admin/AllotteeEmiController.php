<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allottee;
use App\Models\AllotteeEmiAccount;
use App\Models\AllotteeGeneratedDocument;
use App\Models\AllotteeMonthlyDemand;
use App\Models\AllotteeTransaction;
use App\Services\EmiCalculatorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AllotteeEmiController extends Controller
{
    protected $emiService;

    public function __construct(EmiCalculatorService $emiService)
    {
        $this->emiService = $emiService;
    }

    /**
     * Display EMI Dashboard
     */
    public function dashboard(Allottee $allottee)
    {
        $emiAccount = AllotteeEmiAccount::where('allottee_id', $allottee->id)->first();

        if ($emiAccount) {
            $demands = $emiAccount->demands()->orderBy('emi_no')->get();

            $paidDemands = $demands->where('demand_status', 'Paid')->count();
            $pendingDemands = $demands->whereIn('demand_status', ['Pending', 'Partially Paid', 'Overdue'])->count();
            $nextDemand = $demands->whereIn('demand_status', ['Pending', 'Partially Paid', 'Overdue'])->sortBy('emi_no')->first();

            // Refresh penalties before showing dashboard
            foreach ($demands->where('due_date', '<', now()) as $demand) {
                $this->emiService->refreshPenalty($demand);
            }
        }

        return view('admin.allottee.sections.emi-dashboard', compact('allottee', 'emiAccount', 'demands', 'paidDemands', 'pendingDemands', 'nextDemand'));
    }

    /**
     * Display EMI Schedule
     */
    public function schedule(Allottee $allottee)
    {
        $emiAccount = AllotteeEmiAccount::where('allottee_id', $allottee->id)->first();

        if ($emiAccount) {
            $demands = $emiAccount->demands()->orderBy('emi_no')->paginate(12);
        } else {
            $demands = collect();
        }

        return view('admin.allottee.sections.emi-schedule', compact('allottee', 'emiAccount', 'demands'));
    }

    /**
     * Display Pay EMI Page
     */
    public function payEmi(Allottee $allottee)
    {
        $emiAccount = AllotteeEmiAccount::where('allottee_id', $allottee->id)->first();

        $currentDemand = null;

        if ($emiAccount) {
            $currentDemand = AllotteeMonthlyDemand::where('emi_account_id', $emiAccount->id)
                ->whereIn('demand_status', ['Pending', 'Partially Paid', 'Overdue'])
                ->orderBy('emi_no')
                ->first();

            // Refresh penalty before showing
            if ($currentDemand && $currentDemand->due_date < now()) {
                $this->emiService->refreshPenalty($currentDemand);
                $currentDemand->refresh();
            }
        }

        return view('admin.allottee.sections.monthly-emi', compact('allottee', 'currentDemand'));
    }

    /**
     * Display EMI History
     */
    public function history(Allottee $allottee)
    {
        $transactions = AllotteeTransaction::where('allottee_id', $allottee->id)
            ->whereIn('transaction_type', ['emi_payment', 'penalty_payment', 'extra_payment', 'one_time_payment'])
            ->latest('paid_at')
            ->paginate(20);

        $totalPaid = AllotteeTransaction::where('allottee_id', $allottee->id)
            ->where('payment_status', 'success')
            ->sum('total_amount');

        $totalEmiPaid = AllotteeTransaction::where('allottee_id', $allottee->id)
            ->where('transaction_type', 'emi_payment')
            ->where('payment_status', 'success')
            ->count();

        $lastPayment = AllotteeTransaction::where('allottee_id', $allottee->id)
            ->where('payment_status', 'success')
            ->latest('paid_at')
            ->first();

        // Load demand details for transactions
        foreach ($transactions as $txn) {
            if ($txn->demand_id) {
                $txn->demand = AllotteeMonthlyDemand::find($txn->demand_id);
            }
        }

        return view('admin.allottee.sections.emi-history', compact('allottee', 'transactions', 'totalPaid', 'totalEmiPaid', 'lastPayment'));
    }

    /**
     * Process EMI Payment
     */
    public function processPayment(Request $request, Allottee $allottee)
    {
        $validated = $request->validate([
            'demand_id' => 'required|exists:allottee_emi_demands,id',
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required|in:cash,cheque,dd,upi,netbanking,gateway',
            'transaction_no' => 'nullable|string|max:255',
            'utr_no' => 'nullable|string|max:255',
        ]);

        Log::info('EMI Payment request received', [
            'allottee_id' => $allottee->id,
            'demand_id' => $request->input('demand_id'),
            'amount' => $request->input('amount'),
            'payment_mode' => $request->input('payment_mode'),
            'transaction_no' => $request->input('transaction_no'),
            'utr_no' => $request->input('utr_no'),
        ]);

        try {
            Log::info('EMI Payment validation started', ['allottee_id' => $allottee->id]);

            $result = DB::transaction(function () use ($request, $allottee, $validated) {
                $demand = AllotteeMonthlyDemand::where('id', $validated['demand_id'])
                    ->where('allottee_id', $allottee->id)
                    ->firstOrFail();

                Log::info('EMI Payment demand loaded', [
                    'demand_id' => $demand->id,
                    'demand_status' => $demand->demand_status,
                    'outstanding_amount' => $demand->outstanding_amount,
                    'total_paid_amount' => $demand->total_paid_amount,
                    'total_demand_amount' => $demand->total_demand_amount,
                    'interest_amount' => $demand->interest_amount,
                    'opening_balance' => $demand->opening_balance,
                    'late_fine_penalty' => $demand->late_fine_penalty,
                    'penalty_interest_amount' => $demand->penalty_interest_amount,
                    'penalty_admin_charges' => $demand->penalty_admin_charges,
                ]);

                // Refresh penalty before payment
                $this->emiService->refreshPenalty($demand);
                $demand->refresh();

                Log::info('EMI Payment demand refreshed', [
                    'demand_id' => $demand->id,
                    'demand_status' => $demand->demand_status,
                    'outstanding_amount' => $demand->outstanding_amount,
                    'total_paid_amount' => $demand->total_paid_amount,
                    'total_demand_amount' => $demand->total_demand_amount,
                ]);

                // Apply payment - service returns allocation summary
                $allocation = $this->emiService->applyPayment($demand, $validated['amount'], $validated['payment_mode']);

                // Refresh to get latest state
                $demand->refresh();
                $emiAccount = $demand->emiAccount;
                $emiAccount->refresh();

                // Generate transaction number
                $transactionNo = $validated['transaction_no'] ?? 'TXN-' . strtoupper(uniqid());

                // Create transaction record using allocation from service
                $transaction = AllotteeTransaction::create([
                    'allottee_id' => $allottee->id,
                    'demand_id' => $demand->id,
                    'order_id' => $demand->order_id,
                    'transaction_type' => 'emi_payment',
                    'payment_stage' => 'emi',
                    'amount' => $allocation['paid_amount'],
                    'principal_amount' => $allocation['principal_paid'],
                    'interest_amount' => $allocation['interest_paid'],
                    'penalty_amount' => $allocation['penalty_paid'],
                    'admin_charge' => $demand->penalty_admin_charges,
                    'total_amount' => $allocation['paid_amount'],
                    'payment_mode' => $validated['payment_mode'],
                    'payment_status' => 'success',
                    'transaction_no' => $transactionNo,
                    'utr_no' => $validated['utr_no'] ?? null,
                    'payment_day' => now()->day,
                    'payment_month' => now()->month,
                    'payment_year' => now()->year,
                    'paid_at' => now(),
                    'created_by' => Auth::id(),
                ]);

                // --- START RECEIPT GENERATION ---
                // Amount in Word English and Hindi
                $amountInEnglish = amountToWords($transaction->total_amount, 'en');
                $amountInHindi = amountToWords($transaction->total_amount, 'hi');

                // Information for template (e.g., "January 2024")
                $emiMonth = Carbon::parse($demand->due_date)->format('F Y');

                // GENERATE RECEIPT PDF
                $pdf = Pdf::loadView(
                    'admin.allottee.sections.emi-payment-receipt',
                    compact(
                        'demand',
                        'transaction',
                        'amountInEnglish',
                        'amountInHindi',
                        'emiMonth'
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
                    'emi',
                    now()->format('Y'),
                    now()->format('m'),
                    now()->format('d'),
                ]);
                $directory = public_path($folder);
                File::ensureDirectoryExists($directory, 0755, true);

                // RECEIPT FILE
                $fileName = 'emi-payment-receipt-' . $demand->id . '-' . now()->format('YmdHis') . '-' . rand(1000, 9999) . '.pdf';
                file_put_contents($directory . '/' . $fileName, $pdf->output());

                // UPDATE TRANSACTION RECEIPT
                $transaction->update([
                    'receipt_file' => $fileName,
                    'receipt_path' => $folder . '/' . $fileName,
                ]);

                // SAVE GENERATED DOCUMENT
                AllotteeGeneratedDocument::create([
                    'allottee_id'    => $allottee->id,
                    'document_name'  => 'EMI Payment Receipt - ' . $emiMonth,
                    'document_type'  => 'emi-payment-receipt',
                    'file_name'      => $fileName,
                    'file_path'      => $folder . '/' . $fileName,
                    'generated_by'   => Auth::id(),
                    'generated_at'   => now(),
                ]);
                // --- END RECEIPT GENERATION ---

                $nextDemand = AllotteeMonthlyDemand::where('emi_account_id', $emiAccount->id)
                    ->whereIn('demand_status', ['Pending', 'Partially Paid', 'Overdue'])
                    ->orderBy('emi_no')
                    ->first();

                return [
                    'success' => true,
                    'message' => 'Payment of ₹ ' . number_format($allocation['paid_amount'], 2) . ' processed successfully',
                    'transaction_no' => $transactionNo,
                    'demand_paid' => $demand->demand_status === 'Paid',
                    'has_next_demand' => $nextDemand !== null,
                    'account_status' => $emiAccount->account_status,
                    'remaining_amount' => $emiAccount->remaining_amount,
                    'receipt_url' => asset($folder . '/' . $fileName),
                ];
            });

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json($result);
            }

            return redirect()->route('admin.allottee.emi.pay', $allottee)
                ->with('success', $result['message']);
        } catch (\Throwable $e) {
            Log::error('EMI Payment failed', [
                'allottee_id' => $allottee->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Pre Payment (Extra Payment)
     */
    public function prePayment(Request $request, Allottee $allottee)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_mode' => 'required|in:cash,cheque,dd,upi,netbanking,gateway',
        ]);

        try {
            DB::transaction(function () use ($request, $allottee, $validated) {

                $emiAccount = AllotteeEmiAccount::where('allottee_id', $allottee->id)
                    ->where('account_status', 'active')
                    ->first();

                if (!$emiAccount) {
                    throw new \Exception('No active EMI account found.');
                }

                if ($validated['amount'] > $emiAccount->remaining_amount) {
                    throw new \Exception('Pre-payment amount cannot exceed remaining balance of ₹ ' . number_format($emiAccount->remaining_amount, 2));
                }

                // Get current demand
                $currentDemand = AllotteeMonthlyDemand::where('emi_account_id', $emiAccount->id)
                    ->whereIn('demand_status', ['Pending', 'Partially Paid', 'Overdue'])
                    ->orderBy('emi_no')
                    ->first();

                if (!$currentDemand) {
                    throw new \Exception('No active demand found for pre-payment.');
                }

                // Apply payment to current demand
                $this->emiService->applyPayment($currentDemand, $validated['amount'], $validated['payment_mode']);

                // Create transaction record
                $transaction = AllotteeTransaction::create([
                    'allottee_id' => $allottee->id,
                    'demand_id' => $currentDemand->id,
                    'order_id' => $emiAccount->order_id,
                    'transaction_type' => 'extra_payment',
                    'payment_stage' => 'emi',
                    'amount' => $validated['amount'],
                    'principal_amount' => $validated['amount'],
                    'interest_amount' => 0,
                    'penalty_amount' => 0,
                    'admin_charge' => 0,
                    'total_amount' => $validated['amount'],
                    'payment_mode' => $validated['payment_mode'],
                    'payment_status' => 'success',
                    'transaction_no' => 'PREPAY-' . strtoupper(uniqid()),
                    'payment_day' => now()->day,
                    'payment_month' => now()->month,
                    'payment_year' => now()->year,
                    'paid_at' => now(),
                    'created_by' => Auth::id(),
                    'remarks' => 'Extra payment / Pre-payment',
                ]);

                Log::info('Pre-payment processed', [
                    'allottee_id' => $allottee->id,
                    'amount' => $validated['amount']
                ]);
            });

            return redirect()->route('admin.allottee.emi.dashboard', $allottee)
                ->with('success', 'Pre-payment of ₹ ' . number_format($validated['amount'], 2) . ' processed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Pre-payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Close Loan
     */
    public function closeLoan(Request $request, Allottee $allottee)
    {
        try {
            DB::transaction(function () use ($allottee) {

                $emiAccount = AllotteeEmiAccount::where('allottee_id', $allottee->id)
                    ->where('account_status', 'active')
                    ->first();

                if (!$emiAccount) {
                    throw new \Exception('No active EMI account found.');
                }

                // Get all pending demands
                $pendingDemands = AllotteeMonthlyDemand::where('emi_account_id', $emiAccount->id)
                    ->whereIn('demand_status', ['Pending', 'Partially Paid', 'Overdue'])
                    ->orderBy('emi_no')
                    ->get();

                $totalOutstanding = $pendingDemands->sum('outstanding_amount');

                if ($totalOutstanding > 0) {
                    // Apply full payment to close
                    foreach ($pendingDemands as $demand) {
                        if ($demand->outstanding_amount > 0) {
                            $this->emiService->applyPayment($demand, $demand->outstanding_amount, 'adjustment');
                        }
                    }
                }

                // Create closing transaction
                AllotteeTransaction::create([
                    'allottee_id' => $allottee->id,
                    'order_id' => $emiAccount->order_id,
                    'transaction_type' => 'loan_closed',
                    'payment_stage' => 'closure',
                    'amount' => $totalOutstanding,
                    'total_amount' => $totalOutstanding,
                    'payment_status' => 'success',
                    'transaction_no' => 'CLOSE-' . strtoupper(uniqid()),
                    'paid_at' => now(),
                    'created_by' => Auth::id(),
                    'remarks' => 'Loan closed manually',
                ]);

                Log::info('Loan closed', [
                    'allottee_id' => $allottee->id,
                    'total_paid' => $totalOutstanding
                ]);
            });

            return redirect()->route('admin.allottee.emi.dashboard', $allottee)
                ->with('success', 'Loan account closed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to close loan: ' . $e->getMessage());
        }
    }

    /**
     * Download EMI Statement
     */
    public function downloadStatement(Allottee $allottee)
    {
        $emiAccount = AllotteeEmiAccount::where('allottee_id', $allottee->id)->first();

        if (!$emiAccount) {
            return redirect()->back()->with('error', 'EMI Account not found.');
        }

        $demands = $emiAccount->demands()->orderBy('emi_no')->get();
        $transactions = AllotteeTransaction::where('allottee_id', $allottee->id)
            ->where('payment_stage', 'emi')
            ->orderBy('paid_at')
            ->get();

        // Generate PDF (you can implement PDF generation here)
        // For now, just return a view
        return view('admin.allottee.sections.emi-statement-pdf', compact('allottee', 'emiAccount', 'demands', 'transactions'));
    }

    /**
     * Refresh All Overdue Penalties (AJAX)
     */
    public function refreshPenalties(Request $request)
    {
        try {
            $this->emiService->refreshAllOverdueDemands();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Penalties refreshed successfully']);
            }

            return redirect()->back()->with('success', 'Penalties refreshed successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return redirect()->back()->with('error', 'Failed to refresh penalties: ' . $e->getMessage());
        }
    }
}
