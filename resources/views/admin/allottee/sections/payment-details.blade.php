{{-- resources/views/admin/allottee/sections/payment-details.blade.php --}}
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Payment Details</h1>
            <p class="page-subtitle">Payment information and transaction history · Application {{ $allottee->application_no ?? 'JSHBA-24928374' }}</p>
        </div>
        <button class="btn-ghost" onclick="window.close();">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </button>
    </div>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Payment Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="info-card">
                <p class="info-card-label"><i class="fa-solid fa-indian-rupee-sign me-1"></i>Amount Paid</p>
                <p class="info-card-value">₹ {{ $allottee->payment_amount ? number_format((float) $allottee->payment_amount, 2) : '0.00' }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-card">
                <p class="info-card-label"><i class="fa-regular fa-calendar me-1"></i>Payment Date</p>
                <p class="info-card-value">{{ $allottee->payment_date ? \Carbon\Carbon::parse($allottee->payment_date)->format('d M Y') : '-' }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-card">
                <p class="info-card-label"><i class="fa-solid fa-credit-card me-1"></i>Payment Mode</p>
                <p class="info-card-value">{{ $allottee->payment_mode ?: '-' }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-card">
                <p class="info-card-label"><i class="fa-solid fa-qrcode me-1"></i>Reference No.</p>
                <p class="info-card-value" style="font-family:'DM Mono',monospace;">{{ $allottee->payment_utr_no ?: '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Current Plan Section --}}
    @php
        $currentPlan = str_contains((string) $allottee->step_remarks, 'Payment Option:') ? $allottee->step_remarks : null;
    @endphp

    @if($currentPlan)
    <div class="alert alert-info mb-4" style="background:#eef2ff;border:1px solid #bfdbfe;border-radius:16px;">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-chart-line fa-lg" style="color:#2563eb;"></i>
            <div>
                <strong>Current Payment Plan:</strong> 
                <span class="fw-semibold">{{ $currentPlan }}</span>
            </div>
        </div>
    </div>
    @endif

    {{-- Payment Options Section --}}
    <div class="section-title"><i class="fa-solid fa-hand-holding-usd me-2"></i>Choose Payment Plan</div>
    
    <div class="row g-3 mt-2">
        <div class="col-md-6">
            <form method="POST" action="{{ route('admin.allottees.payment-option', $allottee) }}" onsubmit="return confirmPaymentOption('EMI Plan')">
                @csrf
                <input type="hidden" name="payment_option" value="emi">
                <button type="submit" class="btn-brand w-100" style="background:linear-gradient(135deg, #0f766e, #0c5d56);padding:14px;">
                    <i class="fa-solid fa-chart-simple me-2"></i>
                    Choose EMI Plan
                    <small class="d-block mt-1" style="font-size:11px;opacity:0.8;">Pay in easy monthly installments</small>
                </button>
            </form>
        </div>
        <div class="col-md-6">
            <form method="POST" action="{{ route('admin.allottees.payment-option', $allottee) }}" onsubmit="return confirmPaymentOption('One Time Payment')">
                @csrf
                <input type="hidden" name="payment_option" value="one_time">
                <button type="submit" class="btn-brand w-100" style="background:linear-gradient(135deg, #16a34a, #15803d);padding:14px;">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    Choose One Time Payment
                    <small class="d-block mt-1" style="font-size:11px;opacity:0.8;">Pay full amount at once and get benefits</small>
                </button>
            </form>
        </div>
    </div>

    {{-- Payment History Table (if exists) --}}
    @if(isset($allottee->payments) && $allottee->payments->count() > 0)
    <div class="section-title mt-5"><i class="fa-solid fa-clock-rotate-left me-2"></i>Payment History</div>
    <div class="table-responsive">
        <table class="table table-hover" style="border-radius:16px;overflow:hidden;">
            <thead style="background:#f8fafc;">
                <tr>
                    <th><i class="fa-regular fa-calendar me-1"></i> Date</th>
                    <th><i class="fa-solid fa-indian-rupee-sign me-1"></i> Amount</th>
                    <th><i class="fa-solid fa-qrcode me-1"></i> Transaction ID</th>
                    <th><i class="fa-solid fa-credit-card me-1"></i> Mode</th>
                    <th><i class="fa-solid fa-chart-simple me-1"></i> Status</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($allottee->payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : '-' }}</td>
                        <td class="fw-semibold">₹ {{ number_format($payment->amount ?? 0, 2) }}</td>
                        <td><code style="font-family:'DM Mono',monospace;">{{ $payment->transaction_id ?? '-' }}</code></td>
                        <td>{{ $payment->payment_mode ?? '-' }}</td>
                        <td>
                            <span class="badge-status badge-success" style="background:#dcfce7;color:#166534;">
                                <i class="fa-solid fa-circle-check me-1"></i> {{ $payment->status ?? 'Success' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
    function confirmPaymentOption(planName) {
        return confirm(`Are you sure you want to choose ${planName}?\n\nThis action will update your payment plan and cannot be undone easily.`);
    }
</script>