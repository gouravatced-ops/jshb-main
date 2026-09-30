@extends('layouts.main')

@section('title', 'Transactions | Admin | JSHB')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">All Transactions</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Transactions</li>
        </ol>
    </nav>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="transaction_no" class="form-control" placeholder="Search Transaction No..." value="{{ request('transaction_no') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success / Completed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-2"></i>Filter</button>
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-light"><i class="fas fa-undo me-2"></i>Reset</a>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Transaction No</th>
                        <th>Allottee Name</th>
                        <th>Amount (₹)</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $txn)
                    <tr>
                        <td class="ps-4">{{ $transactions->firstItem() + $index }}</td>
                        <td>
                            <span class="fw-bold">{{ $txn->transaction_no }}</span><br>
                            <small class="text-muted">UTR: {{ $txn->utr_no ?? 'N/A' }}</small>
                        </td>
                        <td>
                            @if($txn->allottee)
                                {{ $txn->allottee->allottee_name }} {{ $txn->allottee->allottee_surname }}<br>
                                <small class="text-muted">Prop No: {{ $txn->allottee->property_number ?? 'N/A' }}</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td><span class="fw-bold text-success">₹{{ number_format($txn->total_amount, 2) }}</span></td>
                        <td>{{ ucwords(str_replace('_', ' ', $txn->transaction_type)) }}</td>
                        <td>{{ \Carbon\Carbon::parse($txn->created_at)->format('d M Y, h:i A') }}</td>
                        <td>
                            @php
                                $statusLower = strtolower($txn->payment_status);
                                $badgeColor = match($statusLower) {
                                    'success', 'paid', 'completed' => 'success',
                                    'pending' => 'warning',
                                    'failed' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeColor }}">{{ ucfirst($txn->payment_status) }}</span>
                        </td>
                        <td class="text-end pe-4">
                            @if($txn->receipt_path || $txn->payment_file_path || $txn->receipt_file)
                                @php
                                    // if it's a full URL, open in new tab directly
                                    $path = $txn->receipt_path ?? $txn->payment_file_path ?? $txn->receipt_file;
                                    $isUrl = filter_var($path, FILTER_VALIDATE_URL);
                                @endphp
                                @if($isUrl)
                                    <a href="{{ $path }}" target="_blank" class="btn btn-sm btn-outline-primary" title="View Receipt">
                                        <i class="fas fa-file-invoice"></i> Receipt
                                    </a>
                                @else
                                    <a href="{{ route('media.document', ['path' => $path]) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="View Receipt">
                                        <i class="fas fa-file-invoice"></i> Receipt
                                    </a>
                                @endif
                            @else
                                <span class="text-muted small">No Receipt</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                            <p class="mb-0">No transactions found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($transactions->hasPages())
    <div class="card-footer bg-white border-0 pt-4 pb-3">
        {{ $transactions->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
