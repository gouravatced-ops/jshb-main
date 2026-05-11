@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<h4 style="margin-bottom:12px;">Payment Details</h4>
<div class="table-responsive" style="margin-bottom:16px;">
    <table class="ep-table">
        <tbody>
            <tr><th>Amount Paid</th><td>{{ $allottee->payment_amount ? number_format((float) $allottee->payment_amount, 2) : '-' }}</td></tr>
            <tr><th>Payment Date</th><td>{{ $allottee->payment_date ?: '-' }}</td></tr>
            <tr><th>Payment Mode</th><td>{{ $allottee->payment_mode ?: '-' }}</td></tr>
            <tr><th>Reference No.</th><td>{{ $allottee->payment_reference ?: '-' }}</td></tr>
            <tr><th>Current Plan</th><td>{{ str_contains((string) $allottee->step_remarks, 'Payment Option:') ? $allottee->step_remarks : '-' }}</td></tr>
        </tbody>
    </table>
</div>

<form method="POST" action="{{ route('admin.allottees.payment-option', $allottee) }}" style="display:flex;gap:10px;align-items:center;">
    @csrf
    <button class="btn btn-primary" type="submit" name="payment_option" value="emi">Choose EMI Plan</button>
    <button class="btn btn-success" type="submit" name="payment_option" value="one_time">Choose One Time Payment</button>
</form>
