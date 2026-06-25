<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\AllotteeEmiAccount;
use App\Models\AllotteeMonthlyDemand;
use App\Models\AllotteeProcessStep;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmiCalculatorService
{
    /**
     * Calculate EMI using standard formula
     * This is your working formula
     */
    private function calculateEmiData(AllotteeEmiAccount $account, ?float $principal = null, ?int $months = null): array
    {
        $principal = $principal ?? (float) $account->principal_amount;
        $annualRate = (float) $account->annual_interest_rate;
        $months = $months ?? (int) $account->tenure_months;

        $monthlyRate = ($annualRate / 12) / 100;

        if ($monthlyRate == 0) {
            $emi = round($principal / $months, 2);
        } else {
            $emi = (
                $principal *
                $monthlyRate *
                pow(1 + $monthlyRate, $months)
            ) / (
                pow(1 + $monthlyRate, $months) - 1
            );
            $emi = round($emi, 2);
        }

        return [
            'principal' => $principal,
            'annual_rate' => $annualRate,
            'months' => $months,
            'monthly_rate' => $monthlyRate,
            'emi' => $emi,
        ];
    }

    /**
     * Generate first demand for EMI account
     */
    public function generateFirstDemand(AllotteeEmiAccount $account): AllotteeMonthlyDemand
    {
        // Check if there's already an active demand
        $existingDemand = $account->demands()
            ->whereIn('demand_status', ['Pending', 'Partially Paid'])
            ->first();

        if ($existingDemand) {
            return $existingDemand;
        }

        // Calculate EMI using your formula
        $emiData = $this->calculateEmiData($account);

        // Calculate total payable
        $totalPayable = round($emiData['emi'] * $emiData['months'], 2);
        $totalInterest = $totalPayable - $account->principal_amount;

        // Update account with calculated EMI
        $account->update([
            'emi_amount' => $emiData['emi'],
            'total_interest' => $totalInterest,
            'total_payable' => $totalPayable,
            'remaining_amount' => $account->principal_amount,
        ]);

        return $this->createDemand($account, $account->principal_amount);
    }

    /**
     * Generate next demand after payment
     */
    public function generateNextDemand(AllotteeEmiAccount $account): ?AllotteeMonthlyDemand
    {
        // If account is fully paid, close it
        if ($account->remaining_amount <= 0.01) {
            $account->update([
                'account_status' => 'closed',
                'closed_at' => now()
            ]);
            return null;
        }

        // Ensure only one active demand exists at a time. If an active demand exists, return it.
        $existingActive = $account->demands()
            ->whereIn('demand_status', ['Pending', 'Partially Paid', 'Overdue'])
            ->orderBy('emi_no')
            ->first();

        if ($existingActive) {
            return $existingActive;
        }

        Log::info('EMI generateNextDemand (demand-based)', [
            'emi_account_id' => $account->id,
            'remaining_amount' => $account->remaining_amount,
        ]);

        // Create a single next demand using current remaining_amount as opening principal
        return $this->createDemand($account, (float) $account->remaining_amount);
    }

    /**
     * Create a new monthly demand
     */
    private function createDemand(AllotteeEmiAccount $account, float $openingPrincipal): AllotteeMonthlyDemand
    {
        $lastDemand = $account->demands()->latest('emi_no')->first();
        $emiNo = ($lastDemand?->emi_no ?? 0) + 1;

        // Use fixed EMI amount stored on account. EMI count is only used to calculate EMI amount
        $emiAmount = $account->emi_amount ?: $this->calculateEmiData($account, $account->principal_amount, $account->tenure_months)['emi'];

        // Calculate monthly interest
        $monthlyInterestRate = ($account->annual_interest_rate / 12) / 100;
        $interestAmount = round($openingPrincipal * $monthlyInterestRate, 2);

        // Calculate due date (7th of next month if first demand, else next month)
        $dueDate = $this->calculateDueDate($lastDemand);

        // Annualized amount = Principal + Interest
        $annualizedAmount = round($openingPrincipal + $interestAmount, 2);

        // Total demand amount is the EMI amount (penalties applied later by refreshPenalty)
        // If annualized amount is less than EMI, use annualized amount (final payment scenario)
        // Otherwise, use the fixed EMI amount
        $totalDemandAmount = ($annualizedAmount < $emiAmount)
            ? $annualizedAmount
            : $emiAmount;

        return AllotteeMonthlyDemand::create([
            'allottee_id' => $account->allottee_id,
            'emi_account_id' => $account->id,
            'order_id' => $account->order_id,
            'emi_no' => $emiNo,
            'due_date' => $dueDate,
            'opening_balance' => $openingPrincipal,
            'emi_amount' => $emiAmount,
            'interest_rate' => $account->annual_interest_rate,
            'interest_amount' => $interestAmount,
            'penalty_interest_rate' => $account->penalty_interest_rate,
            'penalty_interest_amount' => 0,
            'principle_amount' => 0,
            'late_fine_penalty' => 0,
            'penalty_admin_charges' => 0,
            'annualized_amount' => $annualizedAmount,
            'balance_amount' => $openingPrincipal,
            'total_demand_amount' => $totalDemandAmount,
            'total_paid_amount' => 0,
            'demand_status' => 'Pending',
            'outstanding_amount' => $totalDemandAmount,
            'generated_at' => now(),
        ]);
    }

    /**
     * Calculate due date for demand
     */
    private function calculateDueDate($lastDemand): Carbon
    {
        if (!$lastDemand) {
            // First demand: due on 7th of next month
            return now()->addMonth()->startOfMonth()->day(7);
        }

        // Subsequent demands: due on 7th of next month
        return Carbon::parse($lastDemand->due_date)->addMonth();
    }

    /**
     * Apply payment to a demand
     * Returns allocation summary for transaction recording.
     */
    public function applyPayment(AllotteeMonthlyDemand $demand, float $amount, string $paymentMode = 'gateway'): array
    {
        // Refresh penalty before applying payment
        $this->refreshPenalty($demand);

        Log::info('EMI applyPayment start', [
            'demand_id' => $demand->id,
            'allottee_id' => $demand->allottee_id,
            'amount_requested' => $amount,
            'payment_mode' => $paymentMode,
            'total_demand_amount' => $demand->total_demand_amount,
            'total_paid_amount' => $demand->total_paid_amount,
            'outstanding_amount' => $demand->outstanding_amount,
            'interest_amount' => $demand->interest_amount,
            'opening_balance' => $demand->opening_balance,
        ]);

        $paidAmount = (float) $amount; // user-entered amount (allow overpayment)

        $totalDemandAmount = $demand->total_demand_amount;
        $currentPaid = $demand->total_paid_amount;
        $newPaidAmount = $currentPaid + $paidAmount;

        // Penalty and interest on this demand
        $penaltyTotal = $demand->late_fine_penalty + $demand->penalty_interest_amount + $demand->penalty_admin_charges;
        $interestAmount = $demand->interest_amount;

        // Allocation: penalty -> interest -> principal. Allow overpayment beyond demand amount (reduces opening principal)
        $penaltyPaid = min($paidAmount, $penaltyTotal);
        $remainingAfterPenalty = max(0, $paidAmount - $penaltyPaid);

        $interestPaid = min($remainingAfterPenalty, $interestAmount);
        $principalPaid = max(0, $remainingAfterPenalty - $interestPaid);

        // New balance for demand (opening_balance reduced by principal portion paid)
        $newBalance = max(0, $demand->opening_balance - $principalPaid);

        // Update demand totals (we treat each payment as resolving the current demand and roll the remaining
        // liability into the next demand. This ensures only one active demand exists at a time.
        $demand->update([
            'principle_amount' => $demand->principle_amount + $principalPaid,
            'total_paid_amount' => $currentPaid + $paidAmount,
            'balance_amount' => $newBalance,
            // Mark outstanding as zero for this demand — remaining obligation will be reflected in next demand
            'outstanding_amount' => 0,
            'demand_status' => 'Paid',
            'payment_date' => now(),
            'paid_at' => now(),
        ]);

        // Update EMI Account according to business rule:
        // New Opening Principal for next demand = previous annualized_amount - actual amount paid (use full paidAmount)
        $account = $demand->emiAccount;
        $annualizedAmount = $demand->annualized_amount;
        $newOpeningPrincipal = round($annualizedAmount - $paidAmount, 2);
        $newRemainingAmount = max(0, $newOpeningPrincipal);

        $account->update([
            'paid_amount' => $account->paid_amount + $paidAmount,
            'remaining_amount' => $newRemainingAmount,
        ]);

        Log::info('EMI applyPayment success', [
            'demand_id' => $demand->id,
            'allottee_id' => $demand->allottee_id,
            'payment_amount' => $paidAmount,
            'penalty_paid' => $penaltyPaid,
            'interest_paid' => $interestPaid,
            'principal_paid' => $principalPaid,
            'new_total_paid_amount' => $newPaidAmount,
            'new_outstanding_amount' => max(0, $totalDemandAmount - $newPaidAmount),
            'new_demand_status' => $demand->demand_status,
            'account_paid_amount' => $account->paid_amount,
            'account_remaining_amount' => $newRemainingAmount,
            'account_status' => $account->account_status,
        ]);

        // If there is still significant remaining principal, ensure next demand exists.
        // We use a threshold of 0.01 to avoid generating demands for rounding dust.
        if ($newRemainingAmount > 0.01) {
            Log::info('EMI generating next demand after payment', [
                'demand_id' => $demand->id,
                'emi_account_id' => $account->id,
                'account_remaining' => $newRemainingAmount,
            ]);
            $this->generateNextDemand($account);
        } else {
            // Close account if fully paid or balance is negligible (<= 0.01)
            Log::info('EMI account closing after final payment', [
                'emi_account_id' => $account->id,
                'remaining_amount' => $newRemainingAmount,
            ]);


            $account->update([
                'account_status' => 'closed',
                'closed_at' => now(),
                'remaining_amount' => 0, // Settle any remaining tiny fraction
            ]);

            // STEP MANAGEMENT
            $completedSteps = [12, 13, 14, 15];
            AllotteeProcessStep::where('allottee_id', $demand->allottee_id)
                ->whereIn('step_no', $completedSteps)
                ->where('status', '!=', 'completed')
                ->update([
                    'status'       => 'completed',
                    'completed_at' => now(),
                    'completed_by' => Auth::id(),
                ]);

            $nextSteps = [16];

            AllotteeProcessStep::where(
                'allottee_id',
                $demand->allottee_id
            )->whereIn('step_no', $nextSteps)
                ->update([
                    'status' => 'pending',
            ]);
        }

        // Return payment allocation summary for caller to record transaction
        return [
            'paid_amount' => $paidAmount,
            'penalty_paid' => $penaltyPaid,
            'interest_paid' => $interestPaid,
            'principal_paid' => $principalPaid,
            'new_account_remaining' => $newRemainingAmount,
            'demand' => $demand,
            'account' => $account,
        ];
    }

    /**
     * Refresh penalty for overdue demands
     * Penalty applies after 7th of month
     */
    public function refreshPenalty(AllotteeMonthlyDemand $demand): void
    {
        // Skip if demand is already paid
        if ($demand->demand_status === 'Paid') {
            return;
        }

        $emiAccount = $demand->emiAccount;
        $emiCount = $emiAccount->tenure_months;

        $dueDate = Carbon::parse($demand->due_date);
        $today = now();

        // Check if payment is late (after due date)
        if ($today->gt($dueDate)) {
            // Penalty Interest Rate (Dynamic: 16%)
            $penaltyInterestRate = $demand->penalty_interest_rate;

            // Formula: late_fine_penalty = 0.01 * emi_amount
            $lateFinePenalty = round($demand->emi_amount * 0.01, 2);

            // Formula: penalty_admin_charges = 10.00
            $adminCharges = 10.00;

            // Formula: penalty_interest_amount = (opening_balance * penalty_interest_rate) / 100 / 12
            $penaltyInterestAmount = round(($demand->opening_balance * $penaltyInterestRate) / 100 / 12, 2);

            // New Formula: total_demand_amount = penalty_interest_amount + late_fine_penalty + penalty_admin_charges
            $newTotalDemandAmount = $penaltyInterestAmount + $lateFinePenalty + $adminCharges + $demand->emi_amount;

            Log::info('EMI refreshPenalty overdue update', [
                'demand_id' => $demand->id,
                'allottee_id' => $demand->allottee_id,
                'old_total_demand_amount' => $demand->total_demand_amount,
                'penalty_interest_rate' => $penaltyInterestRate,
                'new_total_demand_amount' => $newTotalDemandAmount,
                'late_fine_penalty' => $lateFinePenalty,
                'penalty_admin_charges' => $adminCharges,
                'penalty_interest_amount' => $penaltyInterestAmount,
                'principle_amount' => $demand->principle_amount,
                'outstanding_amount_before' => $demand->outstanding_amount,
                'outstanding_amount_after' => $newTotalDemandAmount - $demand->total_paid_amount,
            ]);

            $demand->update([
                'late_fine_penalty' => $lateFinePenalty,
                'penalty_admin_charges' => $adminCharges,
                'penalty_interest_amount' => $penaltyInterestAmount,
                'total_demand_amount' => $newTotalDemandAmount,
                'outstanding_amount' => $newTotalDemandAmount - $demand->total_paid_amount,
                'demand_status' => 'Overdue',
                'is_late_payment' => 1,
            ]);
        } else {
            // Reset penalties if paid on time
            if ($demand->late_fine_penalty > 0 || $demand->penalty_admin_charges > 0) {
                $originalDemandAmount = $demand->emi_amount;

                Log::info('EMI refreshPenalty reset penalties', [
                    'demand_id' => $demand->id,
                    'allottee_id' => $demand->allottee_id,
                    'old_total_demand_amount' => $demand->total_demand_amount,
                    'new_total_demand_amount' => $originalDemandAmount,
                    'outstanding_amount_before' => $demand->outstanding_amount,
                    'outstanding_amount_after' => $originalDemandAmount - $demand->total_paid_amount,
                ]);

                $demand->update([
                    'late_fine_penalty' => 0,
                    'penalty_admin_charges' => 0,
                    'penalty_interest_amount' => 0,
                    'total_demand_amount' => $originalDemandAmount,
                    'outstanding_amount' => $originalDemandAmount - $demand->total_paid_amount,
                    'demand_status' => 'Pending',
                    'is_late_payment' => 0,
                ]);
            }
        }
    }

    /**
     * Get remaining months based on original tenure and current EMI number
     */
    private function getRemainingMonths(AllotteeEmiAccount $account): int
    {
        $totalDemands = $account->demands()->count();
        $remainingMonths = max(1, $account->tenure_months - $totalDemands);

        return $remainingMonths;
    }

    /**
     * Get all overdue demands and refresh penalties
     */
    public function refreshAllOverdueDemands(): void
    {
        $overdueDemands = AllotteeMonthlyDemand::whereIn('demand_status', ['Pending', 'Partially Paid', 'Overdue'])
            ->where('due_date', '<', now())
            ->get();

        foreach ($overdueDemands as $demand) {
            $this->refreshPenalty($demand);
        }
    }

    /**
     * Get current month's demand for an account
     */
    public function getCurrentDemand(AllotteeEmiAccount $account): ?AllotteeMonthlyDemand
    {
        $currentMonth = now()->startOfMonth();

        return $account->demands()
            ->whereYear('due_date', $currentMonth->year)
            ->whereMonth('due_date', $currentMonth->month)
            ->first();
    }

    /**
     * Get outstanding summary for an account
     */
    public function getOutstandingSummary(AllotteeEmiAccount $account): array
    {
        $pendingDemands = $account->demands()
            ->whereIn('demand_status', ['Pending', 'Partially Paid', 'Overdue'])
            ->get();

        $totalOutstanding = $pendingDemands->sum('outstanding_amount');
        $currentDemand = $this->getCurrentDemand($account);

        return [
            'total_outstanding' => round($totalOutstanding, 2),
            'remaining_principal' => round($account->remaining_amount, 2),
            'current_demand_amount' => $currentDemand ? round($currentDemand->total_demand_amount, 2) : 0,
            'current_demand_due_date' => $currentDemand ? $currentDemand->due_date->format('Y-m-d') : null,
            'pending_demands_count' => $pendingDemands->count(),
            'is_overdue' => $currentDemand && Carbon::parse($currentDemand->due_date)->lt(now()),
        ];
    }
}
