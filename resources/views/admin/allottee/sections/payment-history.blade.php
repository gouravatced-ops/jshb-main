{{-- resources/views/admin/allottee/sections/payment-details.blade.php --}}
@php
    $oneTimePaymentsHistory = \App\Models\AllotteeTransaction::where([
        'allottee_id' => $allottee->id,
        'transaction_type' => 'one_time_payment',
        'payment_status' => 'success',
    ])
        ->latest()
        ->get();
@endphp
<div>
    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title"> Payment History </h1>
            <p class="page-subtitle">
                One Time Payment Transactions ·
                Application :
                {{ $allottee->application_no ?? '-' }}
            </p>
        </div>
        <button class="btn-ghost" onclick="window.close();"> <i class="fa-solid fa-arrow-left"></i> Back to List </button>
    </div>
    {{-- LOTTERY PAYMENT SUMMARY --}}
    @php
        $totalLotteryPaid = $oneTimePaymentsHistory->sum('amount');
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-indian-rupee-sign me-1"></i>
                    One Time Amount Paid
                </p>
                <p class="info-card-value">
                    ₹ {{ number_format($totalLotteryPaid, 2) }}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-receipt me-1"></i>
                    Total Transactions
                </p>
                <p class="info-card-value">
                    {{ $oneTimePaymentsHistory->count() }}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-circle-check me-1"></i>
                    Payment Status
                </p>
                <p class="info-card-value text-success">
                    Success
                </p>
            </div>
        </div>
    </div>
    {{-- LOTTERY TRANSACTION HISTORY --}}
    <div class="section-title">
        <i class="fa-solid fa-clock-rotate-left me-2"></i>
        Payment History
    </div>

    @if ($oneTimePaymentsHistory->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle"
                style="
                border-radius:16px;
                overflow:hidden;
            ">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Receipt</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($oneTimePaymentsHistory as $payment)
                        <tr>
                            <td class="fw-semibold text-dark">
                                {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') : '-' }}
                            </td>
                            <td class="fw-semibold text-success">
                                ₹
                                {{ number_format($payment->amount, 2) }}
                            </td>
                            <td>
                                @if ($payment->receipt_path)
                                    @php
                                        $extension = pathinfo($payment->receipt_path, PATHINFO_EXTENSION);
                                    @endphp
                                    <a href="{{ asset($payment->receipt_path) }}" target="_blank"
                                        class="btn btn-sm btn-light border">
                                        @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'docx']))
                                            <i class="fa-solid fa-image text-success"></i>
                                        @else
                                            <i class="fa-solid fa-file-pdf text-danger"></i>
                                        @endif
                                        &nbsp;View
                                    </a>
                                @else
                                    <span class="text-muted">
                                        —
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-status badge-success"
                                    style="
                                background:#dcfce7;
                                color:#166534;
                            ">
                                    <i class="fa-solid fa-circle-check me-1"></i>
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            No payment transactions found.
        </div>
    @endif
</div>
