{{-- resources/views/admin/allottee/sections/monthly-emi.blade.php --}}

@php

    // For Testing: Mock the current system date if test_date is passed
    if (request()->has('test_date')) {
        \Carbon\Carbon::setTestNow(request('test_date'));
    }

    $emiAccount = \App\Models\AllotteeEmiAccount::where('allottee_id', $allottee->id)->first();

    $currentDemand = null;

    if ($emiAccount) {
        $currentDemand = \App\Models\AllotteeMonthlyDemand::where('emi_account_id', $emiAccount->id)
            ->whereIn('demand_status', ['Pending', 'Partially Paid', 'Overdue'])
            ->orderBy('emi_no')
            ->first();

        // Refresh penalty for the current demand before displaying
        if ($currentDemand) {
            $emiCalculatorService = app(\App\Services\EmiCalculatorService::class);
            $emiCalculatorService->refreshPenalty($currentDemand);
        }
    }

@endphp

<div>

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">
                Pay EMI
            </h1>

            <p class="page-subtitle">
                Pay EMI ·
                Application :
                {{ $allottee->application_no ?? '-' }}
            </p>
        </div>

        {{-- Simulation Controls for Testing Late Penalties --}}
        <div class="d-flex align-items-center gap-2 p-2 rounded mb-4"
            style="background: #fff9db; border: 1px solid #ffe066;">
            <span class="small fw-bold text-dark"><i class="fa-solid fa-flask me-1"></i> Date Simulation:</span>
            <input type="date" id="sim_test_date" class="form-control form-control-sm" style="width: 140px;"
                value="{{ now()->format('Y-m-d') }}">
            <button class="btn btn-sm btn-warning fw-bold"
                onclick="App.loadStep({{ $step->step_no }}, document.querySelector('.sidebar-submenu-link.active, .sidebar-link.active'), '?test_date=' + document.getElementById('sim_test_date').value)">
                Apply simulation
            </button>
            @if (request()->has('test_date'))
                <button class="btn btn-sm btn-outline-secondary"
                    onclick="App.loadStep({{ $step->step_no }}, document.querySelector('.sidebar-submenu-link.active, .sidebar-link.active'))">
                    Reset Date
                </button>
            @endif
            <div class="ms-auto small text-muted">
                * Current: <strong>{{ now()->format('d-M-Y') }}</strong> (Set to July 10 to see late charges)
            </div>
        </div>

        <button class="btn-ghost" onclick="window.close();">

            <i class="fa-solid fa-arrow-left"></i>
            Back to List

        </button>
    </div>

    @if ($currentDemand)

        {{-- EMI DETAILS --}}
        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        EMI Number
                    </p>

                    <p class="info-card-value">
                        EMI-{{ str_pad($currentDemand->emi_no, 2, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Due Date
                    </p>

                    <p class="info-card-value">
                        {{ \Carbon\Carbon::parse($currentDemand->due_date)->format('d-m-Y') }}
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Status
                    </p>

                    <p class="info-card-value">

                        @if ($currentDemand->demand_status == 'Overdue')
                            <span class="badge bg-danger">
                                Overdue
                            </span>
                        @elseif($currentDemand->demand_status == 'Partially Paid')
                            <span class="badge bg-info">
                                Partially Paid
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>
                        @endif

                    </p>
                </div>
            </div>

        </div>

        {{-- PAYMENT BREAKDOWN --}}
        <div class="row g-3">

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Principal
                    </p>

                    <p class="info-card-value">
                        ₹
                        {{ number_format($currentDemand->opening_balance > 0 ? $currentDemand->opening_balance : $currentDemand->emi_amount - $currentDemand->interest_amount, 2) }}
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Interest
                    </p>

                    <p class="info-card-value">
                        ₹ {{ number_format($currentDemand->interest_amount, 2) }}
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Penalty
                    </p>

                    <p class="info-card-value text-danger">
                        ₹
                        {{ number_format($currentDemand->late_fine_penalty + $currentDemand->penalty_interest_amount, 2) }}
                    </p>
                    @if ($currentDemand->late_fine_penalty > 0 || $currentDemand->penalty_interest_amount > 0)
                        <small class="text-danger">Late payment charges applied</small>
                    @endif
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Admin Charge
                    </p>

                    <p class="info-card-value">
                        ₹ {{ number_format($currentDemand->penalty_admin_charges, 2) }}
                    </p>
                </div>
            </div>

        </div>

        {{-- PARTIAL PAYMENT INFO --}}
        @if ($currentDemand->demand_status == 'Partially Paid')
            <div class="alert alert-info mt-3 mb-0">
                <i class="fa-solid fa-info-circle"></i>
                Partially Paid: ₹ {{ number_format($currentDemand->total_paid_amount, 2) }} paid out of ₹
                {{ number_format($currentDemand->total_demand_amount, 2) }}
                <br>
                <strong>Outstanding: ₹ {{ number_format($currentDemand->outstanding_amount, 2) }}</strong>
            </div>
        @endif

        {{-- TOTAL PAYABLE --}}
        <div class="card mt-4 border-0 shadow-sm">

            <div class="card-body text-center">

                <div style="
                    font-size:14px;
                    color:#6b7280;
                ">
                    @if ($currentDemand->demand_status == 'Partially Paid')
                        Outstanding Amount
                    @else
                        Total Payable Amount
                    @endif
                </div>

                <div
                    style="
                    font-size:34px;
                    font-weight:700;
                    color:var(--brand);
                ">
                    ₹ {{ number_format($currentDemand->outstanding_amount, 2) }}
                </div>

                @if ($currentDemand->demand_status != 'Partially Paid')
                    <div style="font-size:12px; color:#6b7280; margin-top:5px;">
                        EMI Amount: ₹ {{ number_format($currentDemand->emi_amount, 2) }}
                        @if ($currentDemand->late_fine_penalty > 0 || $currentDemand->penalty_interest_amount > 0)
                            + Penalty: ₹
                            {{ number_format($currentDemand->late_fine_penalty + $currentDemand->penalty_interest_amount, 2) }}
                        @endif
                        @if ($currentDemand->penalty_admin_charges > 0)
                            + Admin: ₹ {{ number_format($currentDemand->penalty_admin_charges, 2) }}
                        @endif
                    </div>
                @endif

            </div>

        </div>

        {{-- PAYMENT ACTION --}}
        <div
            style="
            margin-top:25px;
            display:flex;
            gap:12px;
            flex-wrap:wrap;
        ">

            <button class="btn-brand"
                onclick="payCurrentEmi('{{ $currentDemand->id }}', {{ $currentDemand->outstanding_amount }})">

                <i class="fa-solid fa-credit-card"></i>
                Pay Now

            </button>

            {{-- <button class="btn-ghost" onclick="showDummyGateway('{{ $currentDemand->id }}')">

                <i class="fa-solid fa-building-columns"></i>
                Dummy Gateway

            </button> --}}

        </div>

        {{-- EMI HISTORY --}}
        <div class="card mt-4 border-0 shadow-sm">

            <div class="card-header">
                Previous EMI Payments
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered mb-0">

                        <thead>
                            <tr>
                                <th>EMI</th>
                                <th>Paid Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Receipt</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse(\App\Models\AllotteeTransaction::where(
                            'allottee_id',
                            $allottee->id
                        )
                        ->where('transaction_type','emi_payment')
                        ->latest()
                        ->get()
                        as $txn)
                                <tr>

                                    <td>
                                        {{ $txn->transaction_no }}
                                    </td>

                                    <td>
                                        {{ optional($txn->paid_at)->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        ₹ {{ number_format($txn->total_amount, 2) }}
                                    </td>

                                    <td>
                                        <span class="badge bg-success">
                                            Success
                                        </span>
                                    </td>

                                    <td>

                                        @if ($txn->receipt_path)
                                            <a href="{{ asset($txn->receipt_path) }}" target="_blank">

                                                Receipt

                                            </a>
                                        @else
                                            —
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="text-center">
                                        No EMI payment found.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    @else
        <div class="alert alert-warning">
            No pending EMI found.
        </div>

    @endif

</div>
