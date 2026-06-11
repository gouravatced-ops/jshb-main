{{-- resources/views/admin/allottee/sections/emi-schedule.blade.php --}}

@php

    $emiAccount = \App\Models\AllotteeEmiAccount::where('allottee_id', $allottee->id)->first();

    // Initialize EmiCalculatorService for refreshing penalties
    $emiCalculatorService = app(\App\Services\EmiCalculatorService::class);

    $demands = $emiAccount
        ? \App\Models\AllotteeMonthlyDemand::where('emi_account_id', $emiAccount->id)->orderBy('emi_no')->paginate(12)
        : collect();

@endphp

<div>

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">
                EMI Schedule
            </h1>

            <p class="page-subtitle">
                EMI Schedule ·
                Application :
                {{ $allottee->application_no ?? '-' }}
            </p>
        </div>

        <button class="btn-ghost" onclick="window.close();">

            <i class="fa-solid fa-arrow-left"></i>
            Back to List

        </button>
    </div>

    @if ($emiAccount)

        {{-- ACCOUNT SUMMARY --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Account No
                    </p>

                    <p class="info-card-value">
                        {{ $emiAccount->account_no }}
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Principal Amount
                    </p>

                    <p class="info-card-value">
                        ₹ {{ number_format($emiAccount->principal_amount, 2) }}
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        EMI Amount
                    </p>

                    <p class="info-card-value text-primary">
                        ₹ {{ number_format($emiAccount->emi_amount, 2) }}
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Tenure
                    </p>

                    <p class="info-card-value">
                        {{ $emiAccount->tenure_months }} Months
                    </p>
                </div>
            </div>

        </div>

        {{-- EMI DEMANDS TABLE --}}
        <div class="card shadow-sm border-0">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h6 class="mb-0">
                    EMI Repayment Schedule - Complete Breakdown
                </h6>

                <span class="badge bg-primary">
                    Total Demands: {{ $demands->count() ?? $emiAccount->demands()->count() }}
                </span>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">

                    <table class="table table-hover table-bordered align-middle mb-0" style="min-width: 1500px;">

                        <thead class="table-light position-sticky" style="top: 0; background-color: #f8f9fa;">
                            <tr>
                                <th style="width: 60px;">EMI#</th>
                                <th style="width: 100px;">Due Date</th>
                                <th style="width: 120px;">Opening<br>Balance</th>
                                <th style="width: 100px;">EMI Amt<br>(Fixed)</th>
                                <th style="width: 100px;">Interest<br>Rate %</th>
                                <th style="width: 100px;">Interest<br>Amount</th>
                                <th style="width: 110px;">Annualized<br>(Princ+Int)</th>
                                <th style="width: 100px;">Principal<br>Paid</th>
                                <th style="width: 100px;">Balance<br>Amount</th>
                                <th style="width: 100px;">Late Fine<br>Penalty</th>
                                <th style="width: 100px;">Penalty<br>Interest</th>
                                <th style="width: 100px;">Admin<br>Charge</th>
                                <th style="width: 120px;">Total Payable<br>Amount</th>
                                <th style="width: 120px;">Total Paid<br>Amount</th>
                                <th style="width: 100px;">Outstanding</th>
                                <th style="width: 100px;">Paid Date</th>
                                <th style="width: 90px;">Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($demands as $demand)
                                @php
                                    $emiCalculatorService->refreshPenalty($demand);
                                @endphp
                                <tr
                                    @if ($demand->demand_status === 'Paid') class="table-success" @elseif($demand->demand_status === 'Overdue') class="table-danger" @endif>

                                    <td class="fw-bold text-center">
                                        {{ str_pad($demand->emi_no, 2, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($demand->due_date)->format('d-m-Y') }}
                                    </td>

                                    <td class="text-end">
                                        ₹ {{ number_format($demand->opening_balance, 2) }}
                                    </td>

                                    <td class="text-end text-primary fw-bold">
                                        ₹ {{ number_format($demand->emi_amount, 2) }}
                                    </td>

                                    <td class="text-end">
                                        {{ $demand->interest_rate }}%
                                    </td>

                                    <td class="text-end">
                                        ₹ {{ number_format($demand->interest_amount, 2) }}
                                    </td>

                                    <td class="text-end fw-bold" style="background-color: #e8f4f8;">
                                        ₹ {{ number_format($demand->opening_balance + $demand->interest_amount, 2) }}
                                    </td>

                                    <td class="text-end fw-bold">
                                        ₹ {{ number_format($demand->principle_amount, 2) }}
                                    </td>

                                    <td class="text-end fw-bold" style="background-color: #fff3cd;">
                                        ₹ {{ number_format($demand->balance_amount, 2) }}
                                    </td>

                                    <td class="text-end text-danger">
                                        ₹ {{ number_format($demand->late_fine_penalty, 2) }}
                                    </td>

                                    <td class="text-end text-danger">
                                        ₹ {{ number_format($demand->penalty_interest_amount, 2) }}
                                    </td>

                                    <td class="text-end text-danger">
                                        ₹ {{ number_format($demand->penalty_admin_charges, 2) }}
                                    </td>

                                    <td class="text-end fw-bold" style="background-color: #f0f8ff;">
                                        ₹ {{ number_format($demand->total_demand_amount, 2) }}
                                    </td>

                                    <td class="text-end fw-bold" style="background-color: #e8f5e9;">
                                        ₹ {{ number_format($demand->total_paid_amount, 2) }}
                                    </td>

                                    <td class="text-end fw-bold" style="background-color: #ffebee;">
                                        ₹ {{ number_format($demand->outstanding_amount, 2) }}
                                    </td>

                                    <td class="text-center">
                                        @if ($demand->paid_at)
                                            {{ \Carbon\Carbon::parse($demand->paid_at)->format('d-m-Y') }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td class="text-center">

                                        @switch($demand->demand_status)
                                            @case('Paid')
                                                <span class="badge bg-success">Paid</span>
                                            @break

                                            @case('Partially Paid')
                                                <span class="badge bg-info">Partial</span>
                                            @break

                                            @case('Overdue')
                                                <span class="badge bg-danger">Overdue</span>
                                            @break

                                            @default
                                                <span class="badge bg-warning text-dark">Pending</span>
                                        @endswitch

                                    </td>

                                </tr>

                                @empty

                                    <tr>
                                        <td colspan="17" class="text-center py-4">
                                            No EMI Demands Available
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            @if ($emiAccount && method_exists($demands, 'links'))
                <div class="card-footer">
                    {{ $demands->links() }}
                </div>
            @endif

    </div>

    {{-- EMI TIMELINE --}}
    <div class="card shadow-sm border-0 mt-4">

        <div class="card-header">
            EMI Timeline
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">
                    <div class="info-card">
                        <p class="info-card-label">
                            EMI Start Date
                        </p>

                        <p class="info-card-value">
                            {{ optional($emiAccount->emi_start_date)->format('d-m-Y') }}
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-card">
                        <p class="info-card-label">
                            EMI End Date
                        </p>

                        <p class="info-card-value">
                            {{ optional($emiAccount->emi_end_date)->format('d-m-Y') }}
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-card">
                        <p class="info-card-label">
                            Account Status
                        </p>

                        <p class="info-card-value">

                            @if ($emiAccount->account_status === 'closed')
                                <span class="badge bg-success">
                                    Closed
                                </span>
                            @else
                                <span class="badge bg-primary">
                                    Active
                                </span>
                            @endif

                        </p>
                    </div>
                </div>

            </div>

        </div>

    </div>
@else
    <div class="alert alert-warning">
        EMI Account has not been generated yet.
    </div>

    @endif

    </div>
