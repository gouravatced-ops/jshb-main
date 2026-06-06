{{-- resources/views/admin/allottee/sections/monthly-emi.blade.php --}}

@php

    $emiAccount = \App\Models\AllotteeEmiAccount::where('allottee_id', $allottee->id)->first();

    $currentEmi = null;

    if ($emiAccount) {
        $currentEmi = \App\Models\AllotteeEmiSchedule::where('emi_account_id', $emiAccount->id)
            ->whereIn('payment_status', ['pending', 'partial', 'overdue'])
            ->orderBy('emi_no')
            ->first();
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

        <button class="btn-ghost" onclick="window.close();">

            <i class="fa-solid fa-arrow-left"></i>
            Back to List

        </button>
    </div>

    @if ($currentEmi)

        {{-- EMI DETAILS --}}
        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        EMI Number
                    </p>

                    <p class="info-card-value">
                        EMI-{{ str_pad($currentEmi->emi_no, 2, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Due Date
                    </p>

                    <p class="info-card-value">
                        {{ \Carbon\Carbon::parse($currentEmi->due_date)->format('d-m-Y') }}
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Status
                    </p>

                    <p class="info-card-value">

                        @if ($currentEmi->payment_status == 'overdue')
                            <span class="badge bg-danger">
                                Overdue
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
                        ₹ {{ number_format($currentEmi->principal_component, 2) }}
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Interest
                    </p>

                    <p class="info-card-value">
                        ₹ {{ number_format($currentEmi->interest_component, 2) }}
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Penalty
                    </p>

                    <p class="info-card-value text-danger">
                        ₹ {{ number_format($currentEmi->penalty_amount, 2) }}
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Admin Charge
                    </p>

                    <p class="info-card-value">
                        ₹ {{ number_format($currentEmi->admin_charge, 2) }}
                    </p>
                </div>
            </div>

        </div>

        {{-- TOTAL PAYABLE --}}
        <div class="card mt-4 border-0 shadow-sm">

            <div class="card-body text-center">

                <div style="
                    font-size:14px;
                    color:#6b7280;
                ">
                    Total Payable Amount
                </div>

                <div
                    style="
                    font-size:34px;
                    font-weight:700;
                    color:var(--brand);
                ">
                    ₹ {{ number_format($currentEmi->total_payable, 2) }}
                </div>

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

            <button class="btn-brand" onclick="payCurrentEmi('{{ encrypt($currentEmi->id) }}')">

                <i class="fa-solid fa-credit-card"></i>
                Pay Now

            </button>

            <button class="btn-ghost" onclick="showDummyGateway('{{ encrypt($currentEmi->id) }}')">

                <i class="fa-solid fa-building-columns"></i>
                Dummy Gateway

            </button>

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

<script>
    function payCurrentEmi(id) {
        if (!confirm('Proceed with EMI payment?')) {
            return;
        }

        window.location.href =
            "";
    }

    function showDummyGateway(id) {
        alert(
            'Dummy Gateway Integration Here'
        );
    }
</script>
