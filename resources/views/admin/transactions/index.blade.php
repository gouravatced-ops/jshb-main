@extends('layouts.main')

@section('title', 'Transactions | Admin | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">Allottee Transactions</div>
            <div class="card-subtitle">Search, filter, and audit all online & offline payment transactions</div>
        </div>
        <div class="card-actions">
            @php
                $hasFilters = request('transaction_no') || request('status') || request('payment_mode');
            @endphp
            <button type="button" id="toggleFilterBtn" class="btn-sky" style="cursor: pointer;">
                <i class="fa-solid fa-filter"></i>
                <span id="filterBtnText">{{ $hasFilters ? 'Hide Filter' : 'Filter' }}</span>
                @if($hasFilters)
                    <span style="background: #f59e0b; color: #fff; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 999px;">Active</span>
                @endif
            </button>
            <a class="btn-pink" href="{{ route('admin.allottees.index') }}">
                <i class="fa-solid fa-users"></i> Allottee List
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin: 20px 20px 0;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="margin: 20px 20px 0; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 6px;">
            {{ session('error') }}
        </div>
    @endif

    <style>
        /* Modern Pill Action Buttons */
        .btn-action-pill {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            padding: 5px 12px !important;
            height: 30px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            line-height: 1 !important;
            border-radius: 6px !important;
            text-decoration: none !important;
            cursor: pointer !important;
            white-space: nowrap !important;
            border: 1px solid transparent !important;
            transition: all 0.15s ease-in-out !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
            width: auto !important;
            box-sizing: border-box !important;
        }

        .btn-action-pill i {
            font-size: 11px !important;
        }

        .btn-action-pill.btn-action-receipt {
            background: #eff6ff !important;
            color: #1d4ed8 !important;
            border-color: #bfdbfe !important;
        }
        .btn-action-pill.btn-action-receipt:hover {
            background: #dbeafe !important;
            color: #1e40af !important;
            border-color: #93c5fd !important;
            box-shadow: 0 2px 5px rgba(37, 99, 235, 0.18) !important;
            transform: translateY(-1px);
        }

        .btn-action-pill.btn-action-disabled {
            background: #f8fafc !important;
            color: #94a3b8 !important;
            border-color: #e2e8f0 !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
            opacity: 0.85 !important;
        }
    </style>

    <!-- Metrics Cards Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin: 20px 24px 16px;">
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Total Transactions</div>
                <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ number_format($totalCount ?? $transactions->total()) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #166534; font-weight: 700; letter-spacing: 0.5px;">Successful Payments</div>
                <div style="font-size: 20px; font-weight: 800; color: #15803d; margin-top: 2px;">{{ number_format($successCount ?? 0) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #1e40af; font-weight: 700; letter-spacing: 0.5px;">Total Revenue Collected</div>
                <div style="font-size: 20px; font-weight: 800; color: #1d4ed8; margin-top: 2px;">₹{{ number_format($totalSuccessAmount ?? 0, 2) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
        </div>

        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #92400e; font-weight: 700; letter-spacing: 0.5px;">Pending / Failed</div>
                <div style="font-size: 18px; font-weight: 800; color: #b45309; margin-top: 2px;">
                    {{ number_format($pendingCount ?? 0) }} <span style="font-size: 12px; font-weight: 600; color: #9a3412;">P</span> / 
                    {{ number_format($failedCount ?? 0) }} <span style="font-size: 12px; font-weight: 600; color: #dc2626;">F</span>
                </div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>
    </div>

    <!-- Collapsible Filter Form -->
    <div id="filterBox" style="display: {{ $hasFilters ? 'block' : 'none' }}; margin: 0 24px 18px; padding: 14px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
        <form action="{{ route('admin.transactions.index') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <!-- Search Field -->
            <div style="flex: 1; min-width: 240px; position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                <input type="text" name="transaction_no" value="{{ request('transaction_no') }}" placeholder="Search by Txn No, UTR, Property No, Name..." autocomplete="off"
                    style="width: 100%; padding: 8px 12px 8px 34px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
            </div>

            <!-- Status Dropdown -->
            <div style="min-width: 170px;">
                <select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none; cursor: pointer;">
                    <option value="">All Statuses</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success / Completed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <!-- Payment Mode Dropdown -->
            <div style="min-width: 170px;">
                <select name="payment_mode" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none; cursor: pointer;">
                    <option value="">All Payment Modes</option>
                    <option value="online" {{ request('payment_mode') == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="cheque" {{ request('payment_mode') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                    <option value="dd" {{ request('payment_mode') == 'dd' ? 'selected' : '' }}>Demand Draft (DD)</option>
                    <option value="neft_rtgs" {{ request('payment_mode') == 'neft_rtgs' ? 'selected' : '' }}>NEFT / RTGS</option>
                    <option value="cash" {{ request('payment_mode') == 'cash' ? 'selected' : '' }}>Cash</option>
                </select>
            </div>

            <!-- Filter & Reset Buttons -->
            <div style="display: flex; gap: 8px; align-items: center;">
                <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 13px; border-radius: 6px; border: none; background: #0ea5e9; color: #fff; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if($hasFilters)
                    <a href="{{ route('admin.transactions.index') }}" class="btn-secondary" style="padding: 8px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #64748b; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Active Filter Badges -->
        @if($hasFilters)
            <div style="display: flex; align-items: center; gap: 8px; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #e2e8f0; flex-wrap: wrap;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600;">Active Filters:</span>
                @if(request('transaction_no'))
                    <span style="font-size: 12px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        Search: "{{ request('transaction_no') }}"
                        <a href="{{ route('admin.transactions.index', array_merge(request()->except('transaction_no'))) }}" style="color: #0284c7; text-decoration: none;">&times;</a>
                    </span>
                @endif
                @if(request('status'))
                    <span style="font-size: 12px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        Status: {{ ucfirst(request('status')) }}
                        <a href="{{ route('admin.transactions.index', array_merge(request()->except('status'))) }}" style="color: #0284c7; text-decoration: none;">&times;</a>
                    </span>
                @endif
                @if(request('payment_mode'))
                    <span style="font-size: 12px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        Mode: {{ strtoupper(str_replace('_', '/', request('payment_mode'))) }}
                        <a href="{{ route('admin.transactions.index', array_merge(request()->except('payment_mode'))) }}" style="color: #0284c7; text-decoration: none;">&times;</a>
                    </span>
                @endif
                <a href="{{ route('admin.transactions.index') }}" style="font-size: 12px; color: #dc2626; text-decoration: underline; margin-left: 6px;">Clear all</a>
            </div>
        @endif
    </div>

    <!-- Transactions Table -->
    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Transaction Details</th>
                    <th>Allottee & Property</th>
                    <th>Stage / Type</th>
                    <th>Amount (₹)</th>
                    <th>Payment Mode</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th style="text-align: right; width: 140px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $txn)
                    <tr>
                        <td>{{ $transactions->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="table-name" style="font-family: monospace; font-weight: 700; color: #0f172a; font-size: 13px;">
                                {{ $txn->transaction_no }}
                            </div>
                            <div class="table-email" style="font-size: 12px; color: #64748b; margin-top: 2px;">
                                <span style="font-weight: 600;">UTR:</span> {{ $txn->utr_no ?? 'N/A' }}
                            </div>
                        </td>
                        <td>
                            @if($txn->allottee)
                                <div class="table-name">
                                    {{ trim(($txn->allottee->allottee_name ?? '') . ' ' . ($txn->allottee->allottee_middle_name ?? '') . ' ' . ($txn->allottee->allottee_surname ?? '')) ?: 'N/A' }}
                                </div>
                                <div class="table-email" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-top: 2px;">
                                    @if($txn->allottee->property_number)
                                        <span style="color: #0284c7; font-weight: 600; font-size: 12px;">
                                            <i class="fa-solid fa-building"></i> {{ $txn->allottee->property_number }}
                                        </span>
                                    @endif
                                    @if($txn->allottee->username)
                                        <span style="color: #64748b; font-size: 12px;">
                                            <i class="fa-solid fa-user-circle"></i> {{ $txn->allottee->username }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted" style="font-size: 13px;">N/A (Unlinked)</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #1e293b; font-size: 13px;">
                                {{ ucwords(str_replace('_', ' ', $txn->transaction_type ?? 'N/A')) }}
                            </div>
                            <div style="font-size: 11px; color: #64748b; text-transform: uppercase; margin-top: 2px;">
                                Stage: {{ ucfirst($txn->payment_stage ?? 'Payment') }}
                            </div>
                        </td>
                        <td>
                            <strong style="color: #047857; font-size: 14px;">
                                ₹{{ number_format((float)$txn->total_amount, 2) }}
                            </strong>
                            @if($txn->penalty_amount > 0 || $txn->interest_amount > 0)
                                <div style="font-size: 11px; color: #b45309;">
                                    +₹{{ number_format((float)($txn->penalty_amount + $txn->interest_amount), 2) }} charges
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; font-size: 11px; padding: 3px 8px; border-radius: 4px; font-weight: 600; text-transform: uppercase;">
                                <i class="fa-solid fa-wallet" style="margin-right: 3px; font-size: 10px;"></i>
                                {{ str_replace('_', '/', $txn->payment_mode ?? 'N/A') }}
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 13px; font-weight: 600; color: #334155;">
                                {{ \Carbon\Carbon::parse($txn->created_at)->format('d M Y') }}
                            </div>
                            <div style="font-size: 11px; color: #64748b;">
                                <i class="fa-regular fa-clock" style="font-size: 10px;"></i>
                                {{ \Carbon\Carbon::parse($txn->created_at)->format('h:i A') }}
                            </div>
                        </td>
                        <td>
                            @php
                                $statusLower = strtolower($txn->payment_status ?? '');
                                $statusClass = match($statusLower) {
                                    'success', 'paid', 'completed' => 'active',
                                    'pending' => 'pending',
                                    default => 'inactive'
                                };
                            @endphp
                            <span class="badge-status {{ $statusClass }}">
                                <i class="fa-solid fa-circle"></i>
                                {{ ucfirst($txn->payment_status ?? 'Unknown') }}
                            </span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            @if($txn->receipt_path || $txn->payment_file_path || $txn->receipt_file)
                                @php
                                    $path = $txn->receipt_path ?? $txn->payment_file_path ?? $txn->receipt_file;
                                    $isUrl = filter_var($path, FILTER_VALIDATE_URL);
                                    $receiptUrl = $isUrl ? $path : route('media.document', ['path' => $path]);
                                @endphp
                                <a href="{{ $receiptUrl }}" target="_blank" class="btn-action-pill btn-action-receipt" title="Download / View Official Receipt">
                                    <i class="fa-solid fa-file-invoice"></i>
                                    <span>Receipt</span>
                                </a>
                            @else
                                <span class="btn-action-pill btn-action-disabled" title="No Receipt File Available">
                                    <i class="fa-solid fa-ban"></i>
                                    <span>No Receipt</span>
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 48px 24px; color: #64748b;">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 12px;">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">No Transactions Found</div>
                            <div style="font-size: 13px; max-width: 420px; margin: 0 auto 16px;">
                                @if($hasFilters)
                                    No transaction records match your active search filters. Try clearing or relaxing the filters.
                                @else
                                    No payment transaction records have been recorded in the system yet.
                                @endif
                            </div>
                            @if($hasFilters)
                                <a href="{{ route('admin.transactions.index') }}" class="btn-secondary" style="padding: 6px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #0284c7; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-rotate-left"></i> Clear All Filters
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination & Summary Footer -->
    @if($transactions->hasPages())
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-top: 1px solid #f1f5f9; background: #fff; flex-wrap: wrap; gap: 12px;">
            <div style="font-size: 13px; color: #64748b;">
                Showing <strong>{{ $transactions->firstItem() }}</strong> to <strong>{{ $transactions->lastItem() }}</strong> of <strong>{{ $transactions->total() }}</strong> transactions
            </div>
            <div>
                {{ $transactions->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleFilterBtn');
    const filterBox = document.getElementById('filterBox');
    const filterText = document.getElementById('filterBtnText');

    if (toggleBtn && filterBox) {
        toggleBtn.addEventListener('click', function() {
            if (filterBox.style.display === 'none' || filterBox.style.display === '') {
                filterBox.style.display = 'block';
                if (filterText) filterText.innerText = 'Hide Filter';
            } else {
                filterBox.style.display = 'none';
                if (filterText) filterText.innerText = 'Filter';
            }
        });
    }
});
</script>
@endpush
