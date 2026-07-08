<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\AllotteeEmiAccount;
use App\Models\AllotteeEmiSchedule;
use Illuminate\Support\Facades\DB;

class EmiCalculatorService
{
    public function generateSchedule(
        AllotteeEmiAccount $account
    ): void {

        $principal = (float) $account->principal_amount;

        $annualRate = (float) $account->annual_interest_rate;

        $months = (int) $account->tenure_months;

        $monthlyRate = ($annualRate / 12) / 100;

        // EMI Formula

        $emi = (
            $principal *
            $monthlyRate *
            pow(1 + $monthlyRate, $months)
        ) /
            (
                pow(1 + $monthlyRate, $months) - 1
            );

        $emi = round($emi, 2);

        $balance = $principal;

        $totalInterest = 0;

        $startDate = Carbon::parse(
            $account->emi_start_date
        );

        AllotteeEmiSchedule::where(
            'emi_account_id',
            $account->id
        )->delete();

        for ($i = 1; $i <= $months; $i++) {

            $interest = round(
                $balance * $monthlyRate,
                2
            );

            $principalPart = round(
                $emi - $interest,
                2
            );

            $closingBalance = round(
                $balance - $principalPart,
                2
            );

            $dueDate = $startDate
                ->copy()
                ->addMonths($i - 1)
                ->day(7);

            AllotteeEmiSchedule::create([
                'emi_account_id'      => $account->id,
                'allottee_id'         => $account->allottee_id,
                'order_id'            => $account->order_id,
                'emi_no'              => $i,
                'due_date'            => $dueDate,
                'opening_principal'   => $balance,
                'emi_amount'          => $emi,
                'principal_component' => $principalPart,
                'interest_component'  => $interest,
                'penalty_amount'      => 0,
                'admin_charge'        => $account->admin_charge,
                'total_payable'       => $emi,
                'paid_amount'         => 0,
                'balance_amount'      => $closingBalance,
                'payment_status'      => 'pending',
            ]);

            $balance = $closingBalance;

            $totalInterest += $interest;
        }

        $account->update([
            'emi_amount'       => $emi,
            'total_interest'   => round($totalInterest, 2),
            'total_payable'    => round(
                $principal + $totalInterest,
                2
            ),
            'remaining_amount' => round(
                $principal + $totalInterest,
                2
            ),
            'emi_end_date'     => $dueDate,
        ]);
    }

    public function refreshPenalty(AllotteeEmiSchedule $schedule): void
    {
        if ($schedule->payment_status === 'paid') {
            return;
        }

        if (now()->lte($schedule->due_date)) {

            $schedule->update([
                'penalty_amount' => 0,
                'total_payable' => $schedule->emi_amount,
            ]);

            return;
        }

        $daysLate = now()->diffInDays($schedule->due_date);

        $penaltyAmount = round(
            $schedule->emi_amount * 0.01,
            2
        );

        $adminCharge = 10;

        $schedule->update([
            'penalty_amount' => $penaltyAmount,
            'admin_charge' => $adminCharge,
            'total_payable' =>
            $schedule->emi_amount +
                $penaltyAmount +
                $adminCharge,
            'payment_status' => 'overdue',
        ]);
    }

    public function applyPrePayment(
        AllotteeEmiAccount $account,
        float $amount
    ): void {

        if ($amount <= 0) {
            return;
        }

        $newPrincipal =
            max(
                0,
                $account->principal_amount - $amount
            );

        $account->update([
            'principal_amount' => $newPrincipal,
            'remaining_amount' =>
            max(
                0,
                $account->remaining_amount - $amount
            ),
        ]);

        $this->regenerateRemainingEmis(
            $account
        );
    }

    public function closeLoan(
        AllotteeEmiAccount $account
    ): void {

        $account->update([
            'remaining_amount' => 0,
            'account_status' => 'closed',
            'closed_at' => now(),
        ]);

        $account->schedules()
            ->whereIn(
                'payment_status',
                ['pending', 'overdue']
            )
            ->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'paid_amount' => DB::raw('total_payable'),
            ]);

        $account->order->update([
            'order_status' => 'paid',
            'paid_at' => now(),
            'remaining_amount' => 0,
        ]);
    }

    public function regenerateRemainingEmis(
        AllotteeEmiAccount $account
    ): void {

        $pendingSchedules =
            $account->schedules()
            ->whereIn(
                'payment_status',
                ['pending', 'overdue']
            )
            ->orderBy('emi_no')
            ->get();

        if ($pendingSchedules->isEmpty()) {
            return;
        }

        $remainingMonths =
            $pendingSchedules->count();

        $principal =
            $account->remaining_amount;

        $annualRate =
            $account->annual_interest_rate;

        $monthlyRate =
            ($annualRate / 12) / 100;

        $emi =
            (
                $principal *
                $monthlyRate *
                pow(
                    1 + $monthlyRate,
                    $remainingMonths
                )
            )
            /
            (
                pow(
                    1 + $monthlyRate,
                    $remainingMonths
                ) - 1
            );

        $emi = round($emi, 2);

        $balance = $principal;

        foreach ($pendingSchedules as $schedule) {

            $interest =
                round(
                    $balance * $monthlyRate,
                    2
                );

            $principalPart =
                round(
                    $emi - $interest,
                    2
                );

            $closingBalance =
                round(
                    $balance - $principalPart,
                    2
                );

            $schedule->update([
                'opening_principal' =>
                $balance,

                'emi_amount' =>
                $emi,

                'principal_component' =>
                $principalPart,

                'interest_component' =>
                $interest,

                'total_payable' =>
                $emi,

                'balance_amount' =>
                $closingBalance,
            ]);

            $balance = $closingBalance;
        }

        $account->update([
            'emi_amount' => $emi,
            'remaining_amount' => $principal,
        ]);
    }

    public function getOutstandingAmount(
        AllotteeEmiAccount $account
    ): float {

        return round(
            $account->schedules()
                ->whereIn(
                    'payment_status',
                    ['pending', 'overdue']
                )
                ->sum('total_payable'),
            2
        );
    }
}