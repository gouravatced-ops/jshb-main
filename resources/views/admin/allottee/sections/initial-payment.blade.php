{{-- resources/views/admin/allottee/sections/initial-payment.blade.php --}}
@php
    use App\Models\AllotteePaymentOrder;
    $payment = AllotteePaymentOrder::where([
        'allottee_id' => $allottee->id,
        'order_type' => 'allotment',
    ])
        ->latest()
        ->first();
    if ($payment) {
        // REFRESH PENALTY FROM MODEL
        $payment->refreshPenalty();
    }
@endphp
<div>
    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                Allotment Payment
            </h1>
            <p class="page-subtitle">
                15% Allotment Payment Collection
            </p>
        </div>
        <button class="btn-ghost" onclick="window.close();">
            <i class="fa-solid fa-arrow-left"></i>
            Back to List
        </button>
    </div>
    @if ($payment)
        <div class="row g-3">
            <!-- PROPERTY AMOUNT -->
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
            <!-- PAYMENT PERCENTAGE -->
            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Payment Percentage
                    </p>
                    <p class="info-card-value">
                        {{ number_format($payment->percentage, 2) }}%
                    </p>
                </div>
            </div>
            <!-- BASE AMOUNT -->
            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Base Amount
                    </p>
                    <p class="info-card-value">
                        ₹ {{ number_format($payment->base_amount, 2) }}
                    </p>
                </div>
            </div>
            <!-- DELAY DAYS -->
            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Delay Days
                    </p>
                    <p class="info-card-value text-danger">
                        {{ $payment->delay_days ?? 0 }} Days
                    </p>
                </div>
            </div>
            <!-- PENALTY PERCENTAGE -->
            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Penalty Percentage
                    </p>
                    <p class="info-card-value text-danger">
                        {{ number_format($payment->penalty_percentage ?? 0, 2) }}%
                    </p>
                </div>
            </div>
            <!-- PENALTY AMOUNT -->
            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Penalty Amount
                    </p>
                    <p class="info-card-value text-danger">
                        ₹ {{ number_format($payment->penalty_amount ?? 0, 2) }}
                    </p>
                </div>
            </div>
            <!-- ADMIN CHARGE -->
            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Administrative Charge
                    </p>
                    <p class="info-card-value">
                        ₹ {{ number_format($payment->admin_charge ?? 0, 2) }}
                    </p>
                </div>
            </div>
            <!-- TOTAL PAYABLE -->
            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Total Payable Amount
                    </p>
                    <p class="info-card-value text-success">
                        ₹ {{ number_format($payment->total_payable, 2) }}
                    </p>
                </div>
            </div>
            <!-- REMAINING -->
            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Remaining Amount
                    </p>
                    <p class="info-card-value text-warning">
                        ₹ {{ number_format($payment->remaining_amount, 2) }}
                    </p>
                </div>
            </div>
            <!-- ISSUE DATE -->
            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Issue Date
                    </p>
                    <p class="info-card-value">
                        {{ optional($payment->issued_at)->format('d-m-Y h:i A') }}
                    </p>
                </div>
            </div>
            <!-- DUE DATE -->
            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        Due Date
                    </p>
                    <p class="info-card-value">
                        {{ optional($payment->due_date)->format('d-m-Y') }}
                    </p>
                </div>
            </div>

            @php
                $transaction = \App\Models\AllotteeTransaction::where([
                    'allottee_id' => $allottee->id,
                    'transaction_type' => 'allotment_payment',
                    'payment_status' => 'success',
                ])
                    ->latest('paid_at')
                    ->first();
            @endphp

            @if ($transaction)
                <!-- PAYMENT DATE -->
                <div class="col-md-4">
                    <div class="info-card">
                        <p class="info-card-label">
                            Payment Date
                        </p>
                        <p class="info-card-value">
                            {{ optional($transaction->paid_at)->format('d-m-Y') }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- STATUS -->
            <div class="col-md-12">
                <div class="info-card">
                    <p class="info-card-label">
                        Order Status
                    </p>
                    <p class="info-card-value">
                        @if ($payment->order_status === 'paid')
                            <span class="badge bg-success">
                                Paid
                            </span>
                        @elseif($payment->order_status === 'partial')
                            <span class="badge bg-info">
                                Partial Paid
                            </span>
                        @elseif($payment->order_status === 'overdue')
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
        <!-- ACTION BUTTONS -->
        <div
            style="
            margin-top:25px;
            display:flex;
            gap:12px;
            flex-wrap:wrap;
        ">
            @if ($payment->order_status !== 'paid')
                <button type="button" class="btn-brand" onclick="payInitialPayment('{{ $payment->id }}')">
                    <i class="fa-solid fa-credit-card"></i>
                    Pay ₹ {{ number_format($payment->remaining_amount, 2) }}
                </button>
            @else
                <button class="btn-brand" disabled>
                    <i class="fa-solid fa-circle-check"></i>
                    Payment Completed
                </button>
            @endif
            @if ($transaction)
                <a href="{{ asset($transaction->receipt_path) }}" download target="_blank" class="btn-brand"
                    style="
                background:#fff;
                color:var(--brand);
                border:1px solid #dbeafe;
            ">
                    <i class="fa-solid fa-file-pdf"></i>
                    Download Payment Receipt
                </a>
            @endif
        </div>
    @else
        <div class="alert alert-warning">
            Payment order not generated yet.
        </div>
    @endif
</div>
