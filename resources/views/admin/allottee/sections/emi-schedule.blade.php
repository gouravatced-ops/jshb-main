{{-- resources/views/admin/allottee/sections/emi-schedule.blade.php --}}

@php

    $emiAccount = \App\Models\AllotteeEmiAccount::where('allottee_id', $allottee->id)->first();

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
                    EMI Repayment Schedule
                </h6>

                <span class="badge bg-primary">
                    Total Demands :
                    {{ $demands->total() }}
                </span>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover table-bordered align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th>EMI No</th>
                                <th>Due Date</th>
                                <th>Opening Principal</th>
                                <th>Principal</th>
                                <th>Interest</th>
                                <th>Penalty</th>
                                <th>Admin Charge</th>
                                <th>Total Payable</th>
                                <th>Paid Amount</th>
                                <th>Status</th>
                                <th>Paid On</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($demands as $demand)
                                <tr>

                                    <td>
                                        <strong>
                                            EMI-{{ str_pad($demand->emi_no, 2, '0', STR_PAD_LEFT) }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($demand->due_date)->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        ₹ {{ number_format($demand->opening_balance, 2) }}
                                    </td>

                                    <td>
                                        ₹ {{ number_format($demand->principle_amount, 2) }}
                                    </td>

                                    <td>
                                        ₹ {{ number_format($demand->interest_amount, 2) }}
                                    </td>

                                    <td class="text-danger">
                                        ₹
                                        {{ number_format($demand->late_fine_penalty + $demand->penalty_interest_amount, 2) }}
                                    </td>

                                    <td>
                                        ₹ {{ number_format($demand->penalty_admin_charges, 2) }}
                                    </td>

                                    <td>
                                        <strong>
                                            ₹ {{ number_format($demand->total_demand_amount, 2) }}
                                        </strong>
                                    </td>

                                    <td>
                                        ₹ {{ number_format($demand->total_paid_amount, 2) }}
                                    </td>

                                    <td>

                                        @switch($demand->demand_status)
                                            @case('Paid')
                                                <span class="badge bg-success">
                                                    Paid
                                                </span>
                                            @break

                                            @case('Partially Paid')
                                                <span class="badge bg-info">
                                                    Partial
                                                </span>
                                            @break

                                            @case('Overdue')
                                                <span class="badge bg-danger">
                                                    Overdue
                                                </span>
                                            @break

                                            @default
                                                <span class="badge bg-warning text-dark">
                                                    Pending
                                                </span>
                                        @endswitch

                                    </td>

                                    <td>

                                        @if ($demand->paid_at)
                                            {{ \Carbon\Carbon::parse($demand->paid_at)->format('d-m-Y') }}
                                        @else
                                            —
                                        @endif

                                    </td>

                                </tr>

                                @empty

                                    <tr>
                                        <td colspan="11" class="text-center py-4">
                                            No EMI Demands Available
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

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
