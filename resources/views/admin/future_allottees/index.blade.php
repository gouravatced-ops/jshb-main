@extends('layouts.main')

@section('title', 'Citizen Portal Sync | Future Allottees | JSHB')

@section('content')
<div class="container-fluid py-2">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h4 class="mb-1 text-dark fw-bold">
                <i class="fa-solid fa-arrows-rotate text-primary me-2"></i>Citizen Portal Sync (Future Allottees)
            </h4>
            <p class="text-muted small mb-0">Review incoming citizen applications from the online portal and migrate them into live allottees & applications.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Generate Sample Data Button -->
            <form action="{{ route('admin.future-allottees.seed') }}" method="POST" class="d-inline" onsubmit="return confirm('Generate 10 realistic sample citizen applications in staging?');">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm shadow-sm">
                    <i class="fa-solid fa-wand-magic-sparkles me-1 text-warning"></i> Generate 10 Test Applicants
                </button>
            </form>

            <!-- Bulk Sync Button -->
            <button type="button" class="btn btn-primary btn-sm shadow-sm" id="btnSyncAllPending" onclick="syncAllPending()">
                <i class="fa-solid fa-bolt me-1"></i> Sync All Pending (One-Click)
            </button>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Metrics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium d-block">Total Submissions</span>
                        <h3 class="mb-0 fw-bold text-dark">{{ number_format($totalCount) }}</h3>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                        <i class="fa-solid fa-users fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium d-block">Pending to Sync</span>
                        <h3 class="mb-0 fw-bold text-warning">{{ number_format($pendingCount) }}</h3>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                        <i class="fa-solid fa-clock-rotate-left fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium d-block">Successfully Synced</span>
                        <h3 class="mb-0 fw-bold text-success">{{ number_format($syncedCount) }}</h3>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                        <i class="fa-solid fa-circle-check fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium d-block">Sync Failed</span>
                        <h3 class="mb-0 fw-bold text-danger">{{ number_format($failedCount) }}</h3>
                    </div>
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 text-danger">
                        <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Table Card -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <form action="{{ route('admin.future-allottees.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, ref no, mobile, aadhaar..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="scheme_id" class="form-select form-select-sm">
                        <option value="">All Schemes</option>
                        @foreach($schemes as $s)
                            <option value="{{ $s->id }}" {{ request('scheme_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->scheme_code }} ({{ Str::limit($s->scheme_name, 25) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="synced" {{ request('status') == 'synced' ? 'selected' : '' }}>Synced</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-filter me-1"></i>Filter</button>
                    <a href="{{ route('admin.future-allottees.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-rotate-left me-1"></i>Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-3 py-3" style="width: 40px;">
                                <input type="checkbox" class="form-check-input" id="checkSelectAll" title="Select All Pending">
                            </th>
                            <th>Ref No & Date</th>
                            <th>Applicant Name</th>
                            <th>Target Scheme / Location</th>
                            <th>Payment Details</th>
                            <th>Receipt Slip</th>
                            <th>Sync Status</th>
                            <th class="text-end pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($allottees as $item)
                        <tr>
                            <td class="ps-3">
                                @if($item->sync_status !== 'synced')
                                    <input type="checkbox" class="form-check-input row-select" value="{{ $item->id }}">
                                @else
                                    <i class="fa-solid fa-check text-success small" title="Already Synced"></i>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark font-monospace fw-semibold border">{{ $item->reference_portal_id }}</span>
                                @if($item->application_no)
                                    <div class="small text-primary fw-semibold font-monospace mt-1"><i class="fa-solid fa-hashtag me-1" style="font-size: 10px;"></i>{{ $item->application_no }}</div>
                                @endif
                                <div class="text-muted small mt-1">{{ $item->created_at ? $item->created_at->format('d M Y, h:i A') : 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->full_name }}</div>
                                @if($item->allottee_name_hindi)
                                    <div class="small text-secondary">{{ $item->allottee_name_hindi }} {{ $item->allottee_surname_hindi }}</div>
                                @endif
                                <div class="small text-muted">
                                    <i class="fa-solid fa-phone me-1 text-secondary" style="font-size: 10px;"></i>{{ $item->mobile }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $item->scheme?->scheme_code ?? 'Scheme #' . $item->scheme_id }}</div>
                                <div class="small text-muted">
                                    {{ $item->division?->name ?? 'Div ' . $item->division_id }}
                                    @if($item->subDivision)
                                        • {{ $item->subDivision->name }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($item->transaction)
                                    <div class="fw-bold text-success">₹{{ number_format($item->transaction->amount, 2) }}</div>
                                    <div class="small text-muted font-monospace">
                                        <span class="badge bg-light text-secondary border py-0 px-1">{{ strtoupper($item->transaction->payment_mode) }}</span>
                                        {{ $item->transaction->utr_no }}
                                    </div>
                                @else
                                    <span class="text-muted small">No payment info</span>
                                @endif
                            </td>
                            <td>
                                @if($item->transaction && $item->transaction->receipt_path)
                                    <a href="{{ asset($item->transaction->receipt_path) }}" target="_blank" class="btn btn-outline-primary btn-sm py-1 px-2 text-nowrap" style="font-size: 11px;">
                                        <i class="fa-solid fa-file-invoice me-1"></i> View Slip
                                    </a>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($item->sync_status === 'synced')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        <i class="fa-solid fa-circle-check me-1"></i> Synced
                                    </span>
                                    <div class="text-muted small mt-1" style="font-size: 10px;">
                                        {{ $item->synced_at ? $item->synced_at->format('d/m/y h:i A') : '' }}
                                    </div>
                                @elseif($item->sync_status === 'failed')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1" title="{{ $item->error_log }}">
                                        <i class="fa-solid fa-circle-xmark me-1"></i> Failed
                                    </span>
                                    <div class="text-danger small mt-1 text-truncate" style="max-width: 140px; font-size: 10px;" title="{{ $item->error_log }}">
                                        {{ $item->error_log }}
                                    </div>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                        <i class="fa-solid fa-clock me-1"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <!-- Preview Button -->
                                    <button type="button" class="btn btn-light btn-sm border" onclick="previewRecord({{ $item->id }})" title="Preview Details">
                                        <i class="fa-solid fa-eye text-secondary"></i>
                                    </button>

                                    <!-- Sync Button -->
                                    @if($item->sync_status !== 'synced')
                                        <form action="{{ route('admin.future-allottees.sync', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Sync applicant {{ $item->full_name }} into live allottee & application?');">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm ms-1" title="Migrate / Sync to Live">
                                                <i class="fa-solid fa-play me-1"></i> Sync
                                            </button>
                                        </form>
                                    @else
                                        @if($item->synced_allottee_id)
                                            <a href="{{ route('admin.allottees.show', encryptId($item->synced_allottee_id)) }}" class="btn btn-outline-success btn-sm ms-1" title="View Live Allottee">
                                                <i class="fa-solid fa-user-check me-1"></i> Live
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fa-3x mb-3 text-secondary opacity-50 d-block"></i>
                                <h6>No Staging Applications Found</h6>
                                <p class="small mb-3">Click below to generate sample citizen applications for testing.</p>
                                <form action="{{ route('admin.future-allottees.seed') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Generate 10 Test Applicants
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($allottees->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Showing {{ $allottees->firstItem() }} to {{ $allottees->lastItem() }} of {{ $allottees->total() }} records</span>
            <div>
                {{ $allottees->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
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
                                                <a href="/${t.receipt_path.replace(/^\\//, '')}" target="_blank" class="btn btn-outline-success btn-sm">
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
