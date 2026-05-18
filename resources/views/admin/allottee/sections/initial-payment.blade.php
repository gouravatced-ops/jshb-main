{{-- resources/views/admin/allottee/sections/initial-payment.blade.php --}}
@php
$payment = \App\Models\AllotteeInitialPayment::where(
'allottee_id',
$allottee->id
)->latest()->first();
if ($payment) {
$payment->refreshPenalty();
}
@endphp
<div>
    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                Initial Payment
            </h1>
            <p class="page-subtitle">
                25% Initial Payment Collection
            </p>
        </div>
        <button
            class="btn-ghost"
            onclick="window.close();">
            <i class="fa-solid fa-arrow-left"></i>
            Back to List
        </button>
    </div>
    @if($payment)
    <div class="row g-3">
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    Property Amount
                </p>
                <p class="info-card-value">
                    ₹ {{ number_format($payment->property_amount, 2) }}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    Initial Payment
                </p>
                <p class="info-card-value">
                    {{ $payment->initial_percentage }}%
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    Initial Amount
                </p>
                <p class="info-card-value">
                    ₹ {{ number_format($payment->initial_amount, 2) }}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    Penalty %
                </p>
                <p class="info-card-value text-danger">
                    {{ $payment->penalty_percentage }}%
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    Penalty Amount
                </p>
                <p class="info-card-value text-danger">
                    ₹ {{ number_format($payment->penalty_amount, 2) }}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    Total Payable
                </p>
                <p class="info-card-value text-success">
                    ₹ {{ number_format($payment->total_payable_amount, 2) }}
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-card">
                <p class="info-card-label">
                    Due Date
                </p>
                <p class="info-card-value">
                    {{ $payment->due_date->format('d-m-Y') }}
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-card">
                <p class="info-card-label">
                    Payment Status
                </p>
                <p class="info-card-value">
                    @if($payment->payment_status === 'paid')
                    <span class="badge bg-success">
                        Paid
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
    <div
        style="
            margin-top:25px;
            display:flex;
            gap:12px;
            flex-wrap:wrap;
        ">
        @if($payment->payment_status !== 'paid')
        <button
            type="button"
            class="btn-brand"
            onclick="payInitialPayment('{{ $payment->id }}')">
            <i class="fa-solid fa-credit-card"></i>
            Pay ₹ {{ number_format($payment->total_payable_amount, 2) }}
        </button>
        @else
        <button
            class="btn-brand"
            disabled>
            <i class="fa-solid fa-circle-check"></i>
            Payment Completed
        </button>
        <a
            href="{{ asset($payment->receipt_path) }}"
            download
            class="btn-brand"
            style="
                    background:#fff;
                    color:var(--brand);
                    border:1px solid #dbeafe;
                ">
            <i class="fa-solid fa-file-pdf"></i>
            Download Payment Slip
        </a>
        @endif
    </div>
    @else
    <div class="alert alert-warning">
        Initial payment not generated yet.
    </div>
    @endif
</div>