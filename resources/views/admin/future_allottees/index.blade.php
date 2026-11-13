@extends('layouts.main')

@section('title', 'Citizen Portal Sync | Future Allottees | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">Citizen Portal Sync (Future Allottees)</div>
            <div class="card-subtitle">Review incoming citizen applications from the online portal and migrate them into live allottees</div>
        </div>
        <div class="card-actions">
            @php
                $hasFilters = request('search') || request('scheme_id') || request('status');
            @endphp
            <button type="button" id="toggleFilterBtn" class="btn-sky" style="cursor: pointer;">
                <i class="fa-solid fa-filter"></i>
                <span id="filterBtnText">{{ $hasFilters ? 'Hide Filter' : 'Filter' }}</span>
                @if($hasFilters)
                    <span style="background: #f59e0b; color: #fff; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 999px;">Active</span>
                @endif
            </button>

            <!-- Generate Sample Data Button -->
            <form action="{{ route('admin.future-allottees.seed') }}" method="POST" style="display:inline;" onsubmit="return confirm('Generate 10 realistic sample citizen applications in staging?');">
                @csrf
                <button type="submit" class="btn-secondary" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: #fff; cursor: pointer;">
                    <i class="fa-solid fa-wand-magic-sparkles" style="color: #facc15;"></i> Generate 10 Test Applicants
                </button>
            </form>

            <!-- Bulk Sync Button -->
            <button type="button" class="btn-pink" id="btnSyncAllPending" onclick="syncAllPending()">
                <i class="fa-solid fa-bolt"></i> Sync All Pending (One-Click)
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin: 20px 20px 0;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
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

        .btn-action-pill.btn-action-view {
            background: #f0f9ff !important;
            color: #0284c7 !important;
            border-color: #bae6fd !important;
        }
        .btn-action-pill.btn-action-view:hover {
            background: #e0f2fe !important;
            color: #0369a1 !important;
            border-color: #7dd3fc !important;
            box-shadow: 0 2px 4px rgba(2, 132, 199, 0.15) !important;
            transform: translateY(-1px);
        }

        .btn-action-pill.btn-action-sync {
            background: linear-gradient(135deg, #0ea5e9, #0284c7) !important;
            color: #ffffff !important;
            border-color: #0284c7 !important;
        }
        .btn-action-pill.btn-action-sync:hover {
            background: linear-gradient(135deg, #0284c7, #0369a1) !important;
            color: #ffffff !important;
            box-shadow: 0 2px 6px rgba(14, 165, 233, 0.25) !important;
            transform: translateY(-1px);
        }

        .btn-action-pill.btn-action-live {
            background: #f0fdf4 !important;
            color: #15803d !important;
            border-color: #bbf7d0 !important;
        }
        .btn-action-pill.btn-action-live:hover {
            background: #dcfce7 !important;
            color: #166534 !important;
            border-color: #86efac !important;
            box-shadow: 0 2px 4px rgba(22, 163, 74, 0.15) !important;
            transform: translateY(-1px);
        }
    </style>

    <!-- Metrics Cards Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin: 20px 24px 16px;">
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Total Submissions</div>
                <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ number_format($totalCount) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #92400e; font-weight: 700; letter-spacing: 0.5px;">Pending to Sync</div>
                <div style="font-size: 20px; font-weight: 800; color: #b45309; margin-top: 2px;">{{ number_format($pendingCount) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #166534; font-weight: 700; letter-spacing: 0.5px;">Successfully Synced</div>
                <div style="font-size: 20px; font-weight: 800; color: #15803d; margin-top: 2px;">{{ number_format($syncedCount) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #991b1b; font-weight: 700; letter-spacing: 0.5px;">Sync Failed</div>
                <div style="font-size: 20px; font-weight: 800; color: #b91c1c; margin-top: 2px;">{{ number_format($failedCount) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
    </div>

    <!-- Filter Bar: Search, Scheme & Status -->
    <div id="filterBox" style="display: {{ $hasFilters ? 'block' : 'none' }}; margin: 0 24px 18px; padding: 14px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
        <form action="{{ route('admin.future-allottees.index') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <!-- Search Field -->
            <div style="flex: 1; min-width: 220px; position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, ref no, mobile, aadhaar..." autocomplete="off"
                    style="width: 100%; padding: 8px 12px 8px 34px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
            </div>

            <!-- Scheme Dropdown -->
            <div style="min-width: 200px;">
                <select name="scheme_id" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none; cursor: pointer;">
                    <option value="">All Schemes</option>
                    @foreach($schemes as $s)
                        <option value="{{ $s->id }}" {{ request('scheme_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->scheme_code }} ({{ Str::limit($s->scheme_name, 22) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Dropdown -->
            <div style="min-width: 150px;">
                <select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none; cursor: pointer;">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="synced" {{ request('status') == 'synced' ? 'selected' : '' }}>Synced</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <!-- Filter & Reset Buttons -->
            <div style="display: flex; gap: 8px; align-items: center;">
                <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 13px; border-radius: 6px; border: none; background: #0ea5e9; color: #fff; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if($hasFilters)
                    <a href="{{ route('admin.future-allottees.index') }}" class="btn-secondary" style="padding: 8px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #64748b; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
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
                        <a href="{{ route('admin.future-allottees.index', array_merge(request()->except('search'))) }}" style="color: #0284c7; text-decoration: none;">&times;</a>
                    </span>
                @endif
                @if(request('scheme_id'))
                    @php $activeScheme = $schemes->firstWhere('id', request('scheme_id')); @endphp
                    @if($activeScheme)
                        <span style="font-size: 12px; background: #e0e7ff; color: #4338ca; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                            Scheme: {{ $activeScheme->scheme_code }}
                            <a href="{{ route('admin.future-allottees.index', array_merge(request()->except('scheme_id'))) }}" style="color: #4338ca; text-decoration: none;">&times;</a>
                        </span>
                    @endif
                @endif
                @if(request('status'))
                    <span style="font-size: 12px; background: #fef3c7; color: #b45309; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        Status: {{ ucfirst(request('status')) }}
                        <a href="{{ route('admin.future-allottees.index', array_merge(request()->except('status'))) }}" style="color: #b45309; text-decoration: none;">&times;</a>
                    </span>
                @endif
            </div>
        @endif
    </div>

    <!-- Table Section -->
    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">
                        <input type="checkbox" id="checkSelectAll" title="Select All Pending" style="cursor: pointer;">
                    </th>
                    <th>#</th>
                    <th>Ref No & Date</th>
                    <th>Applicant Details</th>
                    <th>Scheme / Division</th>
                    <th>Payment Info</th>
                    <th>Sync Status</th>
                    <th style="text-align: center; width: 160px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allottees as $item)
                <tr>
                    <td style="text-align: center;">
                        @if($item->sync_status !== 'synced')
                            <input type="checkbox" class="row-select" value="{{ $item->id }}" style="cursor: pointer;">
                        @else
                            <i class="fa-solid fa-check" style="color: #16a34a;" title="Already Synced"></i>
                        @endif
                    </td>
                    <td>{{ $allottees->firstItem() + $loop->index }}</td>
                    <td>
                        <span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; font-size: 11px; padding: 2px 7px; border-radius: 4px; font-weight: 700; font-family: monospace;">{{ $item->reference_portal_id }}</span>
                        @if($item->application_no)
                            <div class="table-email" style="color: #0284c7; font-weight: 600; font-family: monospace; font-size: 12px; margin-top: 3px;">
                                <i class="fa-solid fa-hashtag" style="font-size: 10px;"></i> {{ $item->application_no }}
                            </div>
                        @endif
                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">{{ $item->created_at ? $item->created_at->format('d M Y, h:i A') : 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="table-name" style="font-weight: 700; color: #0f172a;">
                            {{ trim(($item->prefix ?? '') . ' ' . ($item->allottee_name ?? '') . ' ' . ($item->allottee_middle_name ?? '') . ' ' . ($item->allottee_surname ?? '')) ?: '-' }}
                        </div>
                        @if($item->allottee_name_hindi)
                            <div style="font-family: 'KrutiDev', sans-serif, Arial; font-size: 18px; color: #475569; margin-top: 1px;">
                                {{ trim(($item->allottee_prefix_hindi ?? '') . ' ' . ($item->allottee_name_hindi ?? '') . ' ' . ($item->allottee_middle_hindi ?? '') . ' ' . ($item->allottee_surname_hindi ?? '')) ?: '-' }}
                            </div>
                        @endif
                        <div class="table-email" style="font-size: 12px; color: #64748b; margin-top: 2px;">
                            <i class="fa-solid fa-phone" style="font-size: 10px; color: #0284c7;"></i> {{ $item->mobile }}
                        </div>
                    </td>
                    <td>
                        <div class="table-name" style="color: #0f172a; font-weight: 600;">{{ $item->scheme?->scheme_code ?? 'Scheme #' . $item->scheme_id }}</div>
                        <div class="table-email" style="color: #64748b; font-size: 12px;">
                            {{ $item->division?->name ?? 'Div ' . $item->division_id }}
                            @if($item->subDivision)
                                • {{ $item->subDivision->name }}
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($item->transaction)
                            <div style="font-weight: 800; color: #16a34a; font-size: 14px;">₹{{ number_format($item->transaction->amount, 2) }}</div>
                            <div style="font-size: 11px; color: #64748b; margin-top: 2px; font-family: monospace;">
                                <span class="badge" style="background: #e2e8f0; color: #334155; font-size: 10px; padding: 1px 5px; border-radius: 3px;">{{ strtoupper($item->transaction->payment_mode) }}</span>
                                {{ $item->transaction->utr_no }}
                            </div>
                        @else
                            <span style="font-size: 12px; color: #94a3b8;">No payment info</span>
                        @endif
                    </td>
                    <td>
                        @if($item->sync_status === 'synced')
                            <span class="badge-status active">
                                <i class="fa-solid fa-circle-check"></i> Synced
                            </span>
                            <div style="font-size: 10px; color: #64748b; margin-top: 3px;">
                                {{ $item->synced_at ? $item->synced_at->format('d/m/y h:i A') : '' }}
                            </div>
                        @elseif($item->sync_status === 'failed')
                            <span class="badge-status inactive" title="{{ $item->error_log }}">
                                <i class="fa-solid fa-circle-xmark"></i> Failed
                            </span>
                        @else
                            <span class="badge-status" style="background: #fef3c7; color: #92400e;">
                                <i class="fa-solid fa-clock"></i> Pending
                            </span>
                        @endif
                    </td>
                    <td style="text-align: center; white-space: nowrap;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                            <button type="button" class="btn-action-pill btn-action-view" onclick="previewRecord({{ $item->id }})" title="Preview Applicant Application">
                                <i class="fa-solid fa-eye"></i>
                                <span>View</span>
                            </button>

                            @if($item->sync_status !== 'synced')
                                <form action="{{ route('admin.future-allottees.sync', $item->id) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirm('Sync applicant {{ $item->full_name }} into live allottee & application?');">
                                    @csrf
                                    <button type="submit" class="btn-action-pill btn-action-sync" title="Migrate & Sync to Live Allottee">
                                        <i class="fa-solid fa-bolt"></i>
                                        <span>Sync</span>
                                    </button>
                                </form>
                            @else
                                @if($item->synced_allottee_id)
                                    <a href="{{ route('admin.allottees.show', encryptId($item->synced_allottee_id)) }}" target="_blank" class="btn-action-pill btn-action-live" title="View in Live Allottees">
                                        <i class="fa-solid fa-user-check"></i>
                                        <span>Live</span>
                                    </a>
                                @else
                                    <span class="btn-action-pill btn-action-live" style="opacity: 0.85; cursor: default;" title="Already Synced">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Synced</span>
                                    </span>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px 20px; color: #64748b;">
                        <i class="fa-solid fa-inbox fa-3x" style="color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                        <h4 style="font-size: 16px; color: #1e293b; font-weight: 700; margin-bottom: 6px;">No Staging Applications Found</h4>
                        <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">Click below to generate sample citizen applications for testing.</p>
                        <form action="{{ route('admin.future-allottees.seed') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-pink" style="cursor: pointer;">
                                <i class="fa-solid fa-wand-magic-sparkles"></i> Generate 10 Test Applicants
                            </button>
                        </form>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($allottees->hasPages())
    <div style="padding: 20px;">
        {{ $allottees->links() }}
    </div>
    @endif
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light py-3">
                <h5 class="modal-title fw-bold" id="previewModalLabel">
                    <i class="fa-solid fa-id-card text-primary me-2"></i>Citizen Application Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modalContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2" id="modalFooter">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Sync Form (Hidden) -->
<form id="bulkSyncForm" action="{{ route('admin.future-allottees.bulk-sync') }}" method="POST" style="display: none;">
    @csrf
</form>

@push('scripts')
<script>
    // Toggle Filter Box
    const toggleFilterBtn = document.getElementById('toggleFilterBtn');
    const filterBox = document.getElementById('filterBox');
    const filterBtnText = document.getElementById('filterBtnText');

    if (toggleFilterBtn && filterBox) {
        toggleFilterBtn.addEventListener('click', function() {
            const isHidden = filterBox.style.display === 'none' || getComputedStyle(filterBox).display === 'none';
            filterBox.style.display = isHidden ? 'block' : 'none';
            if (filterBtnText) {
                filterBtnText.textContent = isHidden ? 'Hide Filter' : 'Filter';
            }
        });
    }

    // Select all pending checkboxes
    document.getElementById('checkSelectAll')?.addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.row-select').forEach(cb => cb.checked = checked);
    });

    // Preview record via AJAX
    function previewRecord(id) {
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();

        const content = document.getElementById('modalContent');
        const footer = document.getElementById('modalFooter');
        content.innerHTML = `<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 small text-muted">Fetching application details...</p></div>`;

        fetch(`{{ url('admin/future-allottees') }}/${id}/preview`)
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    const d = res.data;
                    const c = d.contact_detail || {};
                    const t = d.transaction || {};

                    let html = `
                        <div class="row g-3">
                            <!-- Basic Header Info -->
                            <div class="col-12 p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-primary font-monospace">${d.reference_portal_id}</span>
                                    ${d.application_no ? `<span class="badge bg-secondary font-monospace ms-1"><i class="fa-solid fa-hashtag me-1"></i>${d.application_no}</span>` : ''}
                                    <h5 class="fw-bold mb-0 mt-1">${d.prefix || ''} ${d.allottee_name} ${d.allottee_middle_name || ''} ${d.allottee_surname || ''}</h5>
                                    <small class="text-secondary">${d.allottee_prefix_hindi || ''} ${d.allottee_name_hindi || ''} ${d.allottee_surname_hindi || ''}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge ${d.sync_status === 'synced' ? 'bg-success' : (d.sync_status === 'failed' ? 'bg-danger' : 'bg-warning')} text-uppercase px-3 py-2">
                                        ${d.sync_status}
                                    </span>
                                </div>
                            </div>

                            <!-- Target Property & Scheme -->
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 h-100">
                                    <h6 class="fw-bold text-primary mb-2"><i class="fa-solid fa-building me-1"></i> Scheme & Property</h6>
                                    <table class="table table-sm table-borderless mb-0 small">
                                        <tr><td class="text-muted" style="width: 120px;">Scheme:</td><td class="fw-semibold">${d.scheme ? d.scheme.scheme_name : 'Scheme #' + d.scheme_id}</td></tr>
                                        <tr><td class="text-muted">Division:</td><td>${d.division ? d.division.name : 'Division #' + d.division_id}</td></tr>
                                        <tr><td class="text-muted">Sub-Division:</td><td>${d.sub_division ? d.sub_division.name : 'N/A'}</td></tr>
                                        <tr><td class="text-muted">Category:</td><td>${d.property_category ? d.property_category.name : 'N/A'}</td></tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Personal Details -->
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 h-100">
                                    <h6 class="fw-bold text-primary mb-2"><i class="fa-solid fa-user me-1"></i> Personal Demographics</h6>
                                    <table class="table table-sm table-borderless mb-0 small">
                                        <tr><td class="text-muted" style="width: 120px;">Relation:</td><td>${d.allottee_relation_type || 'Father'}: ${d.relation_name || 'N/A'}</td></tr>
                                        <tr><td class="text-muted">DOB:</td><td>${d.date_of_birth_day}/${d.date_of_birth_month}/${d.date_of_birth_year} (${d.allottee_gender}, ${d.marital_status})</td></tr>
                                        <tr><td class="text-muted">Aadhaar:</td><td class="font-monospace">${d.aadhar_card_number || 'N/A'}</td></tr>
                                        <tr><td class="text-muted">PAN:</td><td class="font-monospace">${d.pan_card_number || 'N/A'}</td></tr>
                                        <tr><td class="text-muted">Category:</td><td>${d.allottee_category || 'GEN'} (${d.allottee_religion || 'Hindu'})</td></tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Address Details -->
                            <div class="col-12">
                                <div class="p-3 border rounded-3">
                                    <h6 class="fw-bold text-primary mb-2"><i class="fa-solid fa-location-dot me-1"></i> Contact & Address</h6>
                                    <div class="row small g-2">
                                        <div class="col-md-6">
                                            <strong>Present Address:</strong>
                                            <p class="mb-1 text-muted">${c.present_address || 'N/A'}</p>
                                            <span class="text-secondary">${c.present_district || ''}, ${c.present_state || ''} - ${c.present_pincode || ''}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Contact Info:</strong>
                                            <div class="text-muted"><i class="fa-solid fa-phone me-1"></i> ${d.mobile || 'N/A'}</div>
                                            <div class="text-muted"><i class="fa-solid fa-envelope me-1"></i> ${d.email || 'N/A'}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lottery Payment Details -->
                            <div class="col-12">
                                <div class="p-3 border rounded-3 bg-light bg-opacity-50">
                                    <h6 class="fw-bold text-success mb-2"><i class="fa-solid fa-receipt me-1"></i> Lottery Payment Information</h6>
                                    <div class="row align-items-center g-2 small">
                                        <div class="col-md-4">
                                            <span class="text-muted d-block">Amount Paid</span>
                                            <span class="fs-5 fw-bold text-success">₹${Number(t.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted d-block">Mode & UTR</span>
                                            <span class="fw-semibold text-dark">${(t.payment_mode || 'online').toUpperCase()}</span> • <span class="font-monospace">${t.utr_no || 'N/A'}</span>
                                        </div>
                                        <div class="col-md-4 text-md-end">
                                            ${t.receipt_path ? `
                                                <a href="/${t.receipt_path.replace(/^\//, '')}" target="_blank" class="btn btn-outline-success btn-sm">
                                                    <i class="fa-solid fa-file-pdf me-1"></i> Open Payment Slip
                                                </a>
                                            ` : '<span class="text-muted">No slip uploaded</span>'}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    content.innerHTML = html;

                    // Footer buttons
                    if (d.sync_status !== 'synced') {
                        footer.innerHTML = `
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <form action="{{ url('admin/future-allottees') }}/${d.id}/sync" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-bolt me-1"></i> Sync & Create Live Records
                                </button>
                            </form>
                        `;
                    } else {
                        footer.innerHTML = `
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <span class="text-success small fw-semibold me-2"><i class="fa-solid fa-check me-1"></i> Already Synced (Allottee #${d.synced_allottee_id})</span>
                        `;
                    }
                } else {
                    content.innerHTML = `<div class="alert alert-danger mb-0">Failed to load applicant details.</div>`;
                }
            })
            .catch(err => {
                content.innerHTML = `<div class="alert alert-danger mb-0">Error: ${err.message}</div>`;
            });
    }

    // Sync all pending or selected records
    function syncAllPending() {
        const selected = Array.from(document.querySelectorAll('.row-select:checked')).map(cb => cb.value);
        let msg = "Sync ALL pending citizen applications now?";
        if (selected.length > 0) {
            msg = `Sync the ${selected.length} selected applications now?`;
        }

        if (!confirm(msg)) return;

        const form = document.getElementById('bulkSyncForm');
        form.innerHTML = `@csrf`;

        if (selected.length > 0) {
            selected.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });
        }

        form.submit();
    }
</script>
@endpush
@endsection
