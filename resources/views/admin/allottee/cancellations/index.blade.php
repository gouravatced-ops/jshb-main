@extends('layouts.main')

@section('title', 'Overdue Allotment Cancellations | Admin | JSHB')

@section('content')
<div class="card">
    <!-- Header -->
    <div class="card-head">
        <div>
            <div class="card-title">Overdue Allotment Cancellations</div>
            <div class="card-subtitle">Monitor, audit, and cancel overdue 15% allotment deposits with automatic cancellation order PDF generation</div>
        </div>
        <div class="card-actions">
            @php
                $hasFilters = request('search') || request('division_id');
            @endphp
            <button type="button" id="toggleFilterBtn" class="btn-sky" style="cursor: pointer;">
                <i class="fa-solid fa-filter"></i>
                <span id="filterBtnText">{{ $hasFilters ? 'Hide Filter' : 'Filter' }}</span>
                @if($hasFilters)
                    <span style="background: #f59e0b; color: #fff; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 999px;">Active</span>
                @endif
            </button>
            <button type="button" class="btn-pink" onclick="confirmCancellation()" style="cursor: pointer;">
                <i class="fa-solid fa-ban"></i>
                <span>Cancel Selected Allotments</span>
            </button>
            <a class="btn-secondary" href="{{ route('admin.allottees.index') }}" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: #fff; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-users"></i>
                <span>Allottee List</span>
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success" style="margin: 20px 24px 0;">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="margin: 20px 24px 0; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 6px;">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Metrics Cards Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin: 20px 24px 16px;">
        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #92400e; font-weight: 700; letter-spacing: 0.5px;">Overdue Allotments</div>
                <div style="font-size: 20px; font-weight: 800; color: #b45309; margin-top: 2px;">{{ number_format($overdueCount) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #991b1b; font-weight: 700; letter-spacing: 0.5px;">Cancelled Records</div>
                <div style="font-size: 20px; font-weight: 800; color: #b91c1c; margin-top: 2px;">{{ number_format($cancelledCount) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-ban"></i>
            </div>
        </div>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #166534; font-weight: 700; letter-spacing: 0.5px;">Live Active Allottees</div>
                <div style="font-size: 20px; font-weight: 800; color: #15803d; margin-top: 2px;">{{ number_format($liveAllotteeCount) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #1e40af; font-weight: 700; letter-spacing: 0.5px;">Monitored Orders</div>
                <div style="font-size: 20px; font-weight: 800; color: #1d4ed8; margin-top: 2px;">{{ number_format($totalOrdersCount) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
        </div>
    </div>

    <!-- Collapsible Filter Bar -->
    <div id="filterBox" style="display: {{ $hasFilters ? 'block' : 'none' }}; margin: 0 24px 18px; padding: 14px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
        <form action="{{ route('admin.allottees.cancellations') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <!-- Search Field -->
            <div style="flex: 1; min-width: 240px; position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search allotment no, property no, allottee name, mobile..." autocomplete="off"
                    style="width: 100%; padding: 8px 12px 8px 34px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
            </div>

            <!-- Division Dropdown -->
            <div style="min-width: 200px;">
                <select name="division_id" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none; cursor: pointer;">
                    <option value="">All Divisions</option>
                    @foreach($divisions as $div)
                        <option value="{{ $div->id }}" {{ request('division_id') == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter & Reset Buttons -->
            <div style="display: flex; gap: 8px; align-items: center;">
                <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 13px; border-radius: 6px; border: none; background: #0ea5e9; color: #fff; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if($hasFilters)
                    <a href="{{ route('admin.allottees.cancellations') }}" class="btn-secondary" style="padding: 8px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #64748b; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Active Filter Badges -->
        @if($hasFilters)
            <div style="display: flex; align-items: center; gap: 8px; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #e2e8f0; flex-wrap: wrap;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600;">Active Filters:</span>
                @if(request('search'))
                    <span style="font-size: 12px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        Search: "{{ request('search') }}"
                        <a href="{{ route('admin.allottees.cancellations', array_merge(request()->except('search'))) }}" style="color: #0284c7; text-decoration: none;">&times;</a>
                    </span>
                @endif
                @if(request('division_id'))
                    @php $activeDiv = $divisions->firstWhere('id', request('division_id')); @endphp
                    @if($activeDiv)
                        <span style="font-size: 12px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                            Division: {{ $activeDiv->name }}
                            <a href="{{ route('admin.allottees.cancellations', array_merge(request()->except('division_id'))) }}" style="color: #0284c7; text-decoration: none;">&times;</a>
                        </span>
                    @endif
                @endif
                <a href="{{ route('admin.allottees.cancellations') }}" style="font-size: 12px; color: #dc2626; text-decoration: underline; margin-left: 6px;">Clear all</a>
            </div>
        @endif
    </div>

    <!-- Cancellation Form & Table -->
    <form action="{{ route('admin.allottees.cancellations.bulk') }}" method="POST" id="cancellationForm">
        @csrf

        <div class="table-responsive">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="selectAll" title="Select All Overdue Allottees" style="cursor: pointer; width: 16px; height: 16px;">
                        </th>
                        <th style="width: 50px;">#</th>
                        <th>Allotment & App No</th>
                        <th>Allottee Details</th>
                        <th>Scheme & Division</th>
                        <th>Property No</th>
                        <th>Overdue Status</th>
                        <th style="text-align: right; width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allottees as $allottee)
                        @php
                            $paymentOrder = $allottee->allotteeOrders->where('order_type', 'allotment')->first();
                            $dueDateObj = $paymentOrder && $paymentOrder->due_date ? \Carbon\Carbon::parse($paymentOrder->due_date) : null;
                            $overdueSince = $dueDateObj ? $dueDateObj->diffForHumans() : 'Overdue';
                        @endphp
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" name="allottee_ids[]" value="{{ $allottee->id }}" class="allottee-checkbox" style="cursor: pointer; width: 16px; height: 16px;">
                            </td>
                            <td>{{ $allottees->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="table-name" style="font-family: monospace; font-weight: 700; color: #0f172a; font-size: 13px;">
                                    {{ $allottee->allotment_no ?? $allottee->application_no ?? 'N/A' }}
                                </div>
                                @if($allottee->application_no && $allottee->allotment_no)
                                    <div class="table-email" style="font-size: 11px; color: #64748b; margin-top: 2px; font-family: monospace;">
                                        App: {{ $allottee->application_no }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="table-name" style="font-weight: 700; color: #0f172a; font-size: 13.5px;">
                                    {{ trim(($allottee->prefix ?? '') . ' ' . ($allottee->allottee_name ?? '') . ' ' . ($allottee->allottee_surname ?? '')) ?: '-' }}
                                </div>
                                @if($allottee->allottee_name_hindi)
                                    <div style="font-family: 'KrutiDev', sans-serif, Arial; font-size: 17px; color: #475569; margin-top: 1px;">
                                        {{ trim(($allottee->allottee_prefix_hindi ?? '') . ' ' . ($allottee->allottee_name_hindi ?? '') . ' ' . ($allottee->allottee_surname_hindi ?? '')) }}
                                    </div>
                                @endif
                                <div class="table-email" style="font-size: 12px; color: #0284c7; margin-top: 2px;">
                                    <i class="fa-solid fa-user-circle"></i> {{ $allottee->username ?: 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div class="table-name" style="font-weight: 600; color: #1e293b;">
                                    {{ $allottee->scheme?->scheme_code ?? $allottee->scheme?->scheme_name ?? 'Scheme #' . $allottee->scheme_id }}
                                </div>
                                <div class="table-email" style="font-size: 12px; color: #64748b; margin-top: 1px;">
                                    {{ $allottee->division?->name ?? 'Division #' . $allottee->division_id }}
                                    @if($allottee->subDivision)
                                        • {{ $allottee->subDivision->name }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 12px; padding: 4px 8px; border-radius: 4px; font-weight: 700;">
                                    <i class="fa-solid fa-building" style="font-size: 10px; margin-right: 3px;"></i>
                                    {{ $allottee->property_number ?: 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-status inactive">
                                    <i class="fa-solid fa-clock"></i>
                                    Overdue {{ $overdueSince }}
                                </span>
                                @if($dueDateObj)
                                    <div style="font-size: 11px; color: #dc2626; margin-top: 3px; font-weight: 600;">
                                        Due Date: {{ $dueDateObj->format('d M Y') }}
                                    </div>
                                @endif
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 6px;">
                                    <a href="{{ route('admin.edit.apply.index', $allottee) }}" class="btn-action-pill btn-action-view" title="Review Allottee Details" target="_blank">
                                        <i class="fa-solid fa-eye"></i>
                                        <span>Review</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 50px 24px; color: #64748b;">
                                <div style="width: 56px; height: 56px; border-radius: 50%; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 12px;">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">No Overdue Allotments Found</div>
                                <div style="font-size: 13px; max-width: 440px; margin: 0 auto 16px;">
                                    @if($hasFilters)
                                        No overdue records match your search filters. Try clearing or adjusting the filter criteria.
                                    @else
                                        Great news! All allottees are currently up to date with their 15% allotment deposit deadlines.
                                    @endif
                                </div>
                                @if($hasFilters)
                                    <a href="{{ route('admin.allottees.cancellations') }}" class="btn-secondary" style="padding: 6px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #0284c7; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-rotate-left"></i> Clear Filters
                                    </a>
                                @else
                                    <a href="{{ route('admin.allottees.index') }}" class="btn-sky" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-users"></i> View All Live Allottees
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Sticky Footer Action Bar -->
        @if($allottees->count() > 0)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-top: 1px solid #f1f5f9; background: #fff; flex-wrap: wrap; gap: 12px;">
                <div style="font-size: 13px; color: #64748b;">
                    Showing <strong>{{ $allottees->firstItem() }}</strong> to <strong>{{ $allottees->lastItem() }}</strong> of <strong>{{ $allottees->total() }}</strong> overdue records
                </div>
                <div>
                    {{ $allottees->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Filter toggle
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

    // 2. Select All Checkbox
    const selectAll = document.getElementById('selectAll');
    const allotteeCheckboxes = document.querySelectorAll('.allottee-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            allotteeCheckboxes.forEach(cb => cb.checked = this.checked);
        });
    }
});

// 3. Confirm Cancellation Action
function confirmCancellation() {
    const selected = document.querySelectorAll('.allottee-checkbox:checked').length;
    if (selected === 0) {
        alert('Please select at least one overdue allottee checkbox before proceeding with cancellation.');
        return;
    }

    if (confirm(`Are you sure you want to permanently cancel ${selected} allotment(s)?\n\nThis will mark them as cancelled, update payment orders, and automatically generate official cancellation order PDFs.`)) {
        document.getElementById('cancellationForm').submit();
    }
}
</script>
@endpush
