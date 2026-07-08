{{-- resources/views/admin/allottee/sections/emi-history.blade.php --}}

@php

    $transactions = \App\Models\AllotteeTransaction::where('allottee_id', $allottee->id)
        ->whereIn('transaction_type', ['emi_payment', 'penalty_payment', 'extra_payment', 'one_time_payment'])
        ->latest('paid_at')
        ->paginate(20);

    $totalPaid = \App\Models\AllotteeTransaction::where('allottee_id', $allottee->id)
        ->where('payment_status', 'success')
        ->where('transaction_type', 'emi_payment')
        ->sum('total_amount');

    $totalEmiPaid = \App\Models\AllotteeTransaction::where('allottee_id', $allottee->id)
        ->where('transaction_type', 'emi_payment')
        ->where('payment_status', 'success')
        ->count();

    $lastPayment = \App\Models\AllotteeTransaction::where('allottee_id', $allottee->id)
        ->where('payment_status', 'success')
        ->latest('paid_at')
        ->first();

    // Get demand details for each transaction
    foreach ($transactions as $txn) {
        if ($txn->demand_id) {
            $txn->demand = \App\Models\AllotteeMonthlyDemand::find($txn->demand_id);
        }
    }

@endphp

<div>

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">
                EMI History
            </h1>

            <p class="page-subtitle">
                EMI History ·
                Application :
                {{ $allottee->application_no ?? '-' }}
            </p>
        </div>

        <button class="btn-ghost" onclick="window.close();">

            <i class="fa-solid fa-arrow-left"></i>
            Back to List

        </button>
    </div>

    {{-- SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    Total EMI Paid
                </p>

                <p class="info-card-value text-success">
                    {{ $totalEmiPaid }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    Total Amount Paid
                </p>

                <p class="info-card-value text-primary">
                    ₹ {{ number_format($totalPaid, 2) }}
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    Last Payment Date
                </p>

                <p class="info-card-value">

                    @if ($lastPayment)
                        {{ \Carbon\Carbon::parse($lastPayment->paid_at)->format('d-m-Y') }}
                    @else
                        —
                    @endif

                </p>
            </div>
        </div>

    </div>

    {{-- HISTORY TABLE --}}
    <div class="card shadow-sm border-0">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h6 class="mb-0">
                EMI Payment Transactions
            </h6>

            <span class="badge bg-primary">
                {{ $transactions->total() }} Records
            </span>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Transaction No</th>

                            <th>EMI No</th>

                            <th>Type</th>

                            <th>Principal</th>

                            <th>Interest</th>

                            <th>Penalty</th>

                            <th>Admin</th>

                            <th>Total Paid</th>

                            <th>Payment Mode</th>

                            <th>Status</th>

                            <th>Paid Date</th>

                            <th>Receipt</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($transactions as $key => $txn)
                            <tr>

                                <td>
                                    {{ $transactions->firstItem() + $key }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $txn->transaction_no ?? '-' }}
                                    </strong>
                                </td>

                                <td>
                                    @if ($txn->demand_id && isset($txn->demand))
                                        EMI-{{ str_pad($txn->demand->emi_no, 2, '0', STR_PAD_LEFT) }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>

                                    @switch($txn->transaction_type)
                                        @case('emi_payment')
                                            <span class="badge bg-primary">
                                                EMI
                                            </span>
                                        @break

                                        @case('penalty_payment')
                                            <span class="badge bg-danger">
                                                Penalty
                                            </span>
                                        @break

                                        @case('extra_payment')
                                            <span class="badge bg-info">
                                                Pre-Payment
                                            </span>
                                        @break

                                        @case('one_time_payment')
                                            <span class="badge bg-success">
                                                One Time
                                            </span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">
                                                Other
                                            </span>
                                    @endswitch

                                </td>

                                <td>
                                    ₹ {{ number_format($txn->principal_amount, 2) }}
                                </td>

                                <td>
                                    ₹ {{ number_format($txn->interest_amount, 2) }}
                                </td>

                                <td class="text-danger">
                                    ₹ {{ number_format($txn->penalty_amount, 2) }}
                                </td>

                                <td>
                                    ₹ {{ number_format($txn->admin_charge, 2) }}
                                </td>

                                <td>
                                    <strong>
                                        ₹ {{ number_format($txn->total_amount, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    {{ strtoupper($txn->payment_mode ?? '-') }}
                                </td>

                                <td>

                                    @switch($txn->payment_status)
                                        @case('success')
                                            <span class="badge bg-success">
                                                Success
                                            </span>
                                        @break

                                        @case('failed')
                                            <span class="badge bg-danger">
                                                Failed
                                            </span>
                                        @break

                                        @case('refunded')
                                            <span class="badge bg-warning text-dark">
                                                Refunded
                                            </span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">
                                                Initiated
                                            </span>
                                    @endswitch

                                </td>

                                <td>

                                    @if ($txn->paid_at)
                                        {{ \Carbon\Carbon::parse($txn->paid_at)->format('d-m-Y h:i A') }}
                                    @else
                                        —
                                    @endif

                                </td>

                                <td>

                                    @if ($txn->receipt_path)
                                        <a href="{{ asset($txn->receipt_path) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">

                                            <i class="fa-solid fa-file-pdf"></i>

                                        </a>
                                    @else
                                        —
                                    @endif

                                </td>

                            </tr>

                            @empty

                                <tr>

                                    <td colspan="13" class="text-center py-5">

                                        <i class="fa-solid fa-clock-rotate-left fa-2x mb-2 text-muted"></i>

                                        <br>

                                        No EMI payment history found.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            @if ($transactions->count())
                <div class="card-footer">

                    {{ $transactions->links() }}

                </div>
            @endif

        </div>

    </div>
