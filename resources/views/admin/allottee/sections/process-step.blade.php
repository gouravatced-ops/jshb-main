<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h5 class="mb-1">Step {{ $step->step_no }} - {{ $step->title }}</h5>
        <div class="text-muted">{{ $step->description }}</div>
    </div>
    <span class="badge {{ $step->status === 'completed' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($step->status) }}</span>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($step->step_no === 8)
<div class="card border-0 bg-light mb-3">
    <div class="card-body">
        <h6 class="mb-2">Choose Payment Option</h6>
        <p class="text-muted mb-3">If EMI 60 is selected, remaining amount will be distributed into 60 monthly installments. If one-time is selected, final calculation sheet is generated and re-calculation stays locked.</p>
        <form method="POST" action="{{ route('admin.allottees.payment-plan', $allottee) }}" class="d-flex gap-2">
            @csrf
            <button class="btn btn-primary" type="submit" name="payment_option" value="emi_60">Choose EMI 60</button>
            <button class="btn btn-success" type="submit" name="payment_option" value="one_time">Choose One-Time</button>
        </form>
        <div class="small text-muted mt-3">
            Current Option: <strong>{{ $allottee->payment_option ? strtoupper($allottee->payment_option) : '-' }}</strong>
            @if($allottee->payment_option === 'emi_60')
            | EMI: <strong>{{ $allottee->emi_months }}</strong> months | Monthly: <strong>{{ number_format((float) $allottee->emi_monthly_amount, 2) }}</strong>
            @endif
        </div>
    </div>
</div>
@endif

@if($step->status !== 'completed')
<button class="btn btn-dark" data-complete-step="{{ $step->step_no }}">Mark Step Completed</button>
@else
<div class="alert alert-success mb-0">Completed on {{ optional($step->completed_at)->format('d-m-Y H:i') ?: '-' }}</div>
@endif