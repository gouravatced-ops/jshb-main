@extends('layouts.main')

@section('title', 'Workflow Management | JSHB')

@section('content')
<div class="card">
    @if (session('success'))
        <div class="alert alert-success" style="margin: 20px 20px 0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="margin: 20px 20px 0; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 6px;">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Card Header -->
    <div class="card-head">
        <div>
            <div class="card-title">Workflow Management & Hierarchy</div>
            <div class="card-subtitle">Configure application approval pipelines, sequential roles, and action permissions</div>
        </div>
        <div class="card-actions">
            <form method="GET" action="{{ route('admin.workflows.index') }}" class="search-box" onsubmit="return false;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="workflowSearchInput" name="search" value="{{ $search }}"
                    placeholder="Search workflows, roles, types..." autocomplete="off">
            </form>
            <a class="btn-pink" href="{{ route('admin.workflows.create') }}">
                <i class="fa-solid fa-plus"></i> Add Workflow
            </a>
        </div>
    </div>

    <!-- Top KPI / Metrics Summary Bar (Inspired by allottee list) -->
    <div class="wf-metrics-bar">
        @php
            $totalWf = $workflows->total();
            $activeWf = $workflows->where('is_active', 1)->count();
            $totalStepsCount = $workflows->sum(fn($w) => $w->steps->count());
            $appTypesCount = $workflows->pluck('application_type')->unique()->count();
        @endphp
        <div class="wf-metric-pill">
            <i class="fa-solid fa-diagram-project text-primary"></i>
            <div>
                <span class="wf-metric-num">{{ $totalWf }}</span>
                <span class="wf-metric-lbl">Total Workflows</span>
            </div>
        </div>
        <div class="wf-metric-pill">
            <i class="fa-solid fa-circle-check text-success"></i>
            <div>
                <span class="wf-metric-num">{{ $activeWf }}</span>
                <span class="wf-metric-lbl">Active Pipelines</span>
            </div>
        </div>
        <div class="wf-metric-pill">
            <i class="fa-solid fa-layer-group text-info"></i>
            <div>
                <span class="wf-metric-num">{{ $totalStepsCount }}</span>
                <span class="wf-metric-lbl">Configured Steps</span>
            </div>
        </div>
        <div class="wf-metric-pill">
            <i class="fa-solid fa-tags text-warning"></i>
            <div>
                <span class="wf-metric-num">{{ $appTypesCount }}</span>
                <span class="wf-metric-lbl">Application Types</span>
            </div>
        </div>
    </div>

    <!-- Workflows Table with Accordion Drawers -->
    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;"></th>
                    <th style="width: 50px;">#</th>
                    <th>Workflow Details</th>
                    <th>Application Type</th>
                    <th>Step Pipeline Hierarchy</th>
                    <th>Status</th>
                    <th style="text-align: center; width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody id="workflowTableBody">
                @forelse($workflows as $workflow)
                    @php
                        $typeColors = [
                            'allotment'             => 'background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;',
                            'agreement'             => 'background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;',
                            'possession'            => 'background: #faf5ff; color: #6b21a8; border: 1px solid #e9d5ff;',
                            'registry'              => 'background: #fff7ed; color: #9a3412; border: 1px solid #fed7aa;',
                            'mutation'              => 'background: #fdf2f8; color: #9d174d; border: 1px solid #fbcfe8;',
                            'transfer'              => 'background: #f0fdfa; color: #115e59; border: 1px solid #99f6e4;',
                            'noc'                   => 'background: #fefce8; color: #854d0e; border: 1px solid #fef08a;',
                            'lease_renewal'         => 'background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;',
                            'cancellation'          => 'background: #fef2f2; color: #991b1b; border: 1px solid #fecaca;',
                        ];
                        $badgeStyle = $typeColors[$workflow->application_type] ?? 'background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;';
                    @endphp
                    <tr class="workflow-main-row" id="wf-row-{{ $workflow->id }}">
                        <td style="text-align: center;">
                            <button type="button" class="accordion-toggle-btn"
                                onclick="toggleWorkflowDrawer({{ $workflow->id }}, this)"
                                title="Expand steps & details">
                                <i class="fa-solid fa-chevron-down"></i>
                            </button>
                        </td>
                        <td>{{ $workflows->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="wf-title-block">
                                <div class="wf-title-name">{{ $workflow->name }}</div>
                                <div class="wf-slug-badge">
                                    <code>{{ $workflow->slug }}</code>
                                    <span class="badge bg-light text-muted border ms-2">
                                        <i class="fa-solid fa-stairs me-1"></i> {{ $workflow->steps->count() }} Steps
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="wf-type-pill" style="{{ $badgeStyle }}">
                                <i class="fa-regular fa-folder-open me-1"></i>
                                {{ ucwords(str_replace('_', ' ', $workflow->application_type)) }}
                            </span>
                        </td>
                        <td>
                            <div class="wf-chain-flow">
                                @forelse($workflow->steps->sortBy('step_order') as $step)
                                    <span class="wf-chain-node" title="{{ $step->step_name }} ({{ $step->role->name ?? 'Role' }})">
                                        <span class="step-num">{{ $step->step_order }}</span>
                                        <span class="step-role">{{ $step->role->name ?? $step->step_code }}</span>
                                    </span>
                                    @if(!$loop->last)
                                        <i class="fa-solid fa-arrow-right-long wf-chain-arrow"></i>
                                    @endif
                                @empty
                                    <span class="text-muted small fst-italic">No steps configured yet</span>
                                @endforelse
                            </div>
                        </td>
                        <td>
                            <span class="badge-status {{ $workflow->is_active ? 'active' : 'inactive' }}">
                                <i class="fa-solid fa-circle"></i>
                                {{ $workflow->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btns" style="justify-content: center;">
                                <button class="action-btn text-info" type="button" title="View Visual Roadmap"
                                    onclick="showRoadmap({{ json_encode($workflow->steps) }})">
                                    <i class="fa-solid fa-sitemap text-info"></i>
                                </button>
                                <a class="action-btn edit" href="{{ route('admin.workflows.edit', $workflow) }}" title="Edit Workflow & Steps">
                                    <i class="fa-solid fa-pen text-primary"></i>
                                </a>
                                <form action="{{ route('admin.workflows.toggle-status', $workflow) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="action-btn toggle-status" type="submit" title="Toggle Active / Inactive Status">
                                        @if($workflow->is_active)
                                            <i class="fa-solid fa-toggle-on text-success"></i>
                                        @else
                                            <i class="fa-solid fa-toggle-off text-danger"></i>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('admin.workflows.destroy', $workflow) }}" method="POST" style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this workflow? All associated steps will be removed.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn del" type="submit" title="Delete Workflow">
                                        <i class="fa-solid fa-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Expandable Accordion Drawer (Inspired by Allottee list) -->
                    <tr class="accordion-row" id="wf-drawer-{{ $workflow->id }}">
                        <td colspan="7" style="padding: 0; background: #f8fafc;">
                            <div class="accordion-wrapper" style="border-left: 4px solid #0d6efd;">
                                <div class="row g-3">
                                    <!-- Left Column: Scope & Docs -->
                                    <div class="col-lg-4">
                                        <div class="admin-card">
                                            <div class="admin-card-header">
                                                <i class="fa-solid fa-circle-info text-primary"></i> Workflow Details
                                            </div>
                                            <div class="detail-grid" style="grid-template-columns: 1fr; gap: 10px;">
                                                <div class="detail-item">
                                                    <span class="detail-label">Workflow Name</span>
                                                    {{ $workflow->name }}
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">Slug Code</span>
                                                    <code>{{ $workflow->slug }}</code>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">Application Type</span>
                                                    <span class="badge bg-light text-dark border">
                                                        {{ ucwords(str_replace('_', ' ', $workflow->application_type)) }}
                                                    </span>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">Description / Scope</span>
                                                    <p class="mb-0 text-muted small" style="line-height: 1.5;">
                                                        {{ $workflow->description ?: 'No specific description provided.' }}
                                                    </p>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">Required Documents</span>
                                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                                        @forelse($workflow->requiredDocuments as $doc)
                                                            <span class="badge bg-white text-secondary border small">
                                                                <i class="fa-regular fa-file-lines me-1 text-primary"></i> {{ $doc->document_name }}
                                                            </span>
                                                        @empty
                                                            <span class="text-muted small fst-italic">None selected</span>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column: Full Steps Matrix -->
                                    <div class="col-lg-8">
                                        <div class="admin-card">
                                            <div class="admin-card-header d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fa-solid fa-list-check text-success"></i> Sequential Steps & Role Action Capabilities
                                                </div>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 11px;">
                                                    {{ $workflow->steps->count() }} Steps
                                                </span>
                                            </div>

                                            <div class="table-responsive mt-2">
                                                <table class="app-list-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 45px;">Step</th>
                                                            <th>Step Details</th>
                                                            <th>Assigned Role</th>
                                                            <th>Action Type</th>
                                                            <th>SLA</th>
                                                            <th>Action Capabilities / Permissions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($workflow->steps->sortBy('step_order') as $st)
                                                            <tr>
                                                                <td style="font-weight: 700; color: #1d4ed8; text-align: center;">
                                                                    #{{ $st->step_order }}
                                                                </td>
                                                                <td>
                                                                    <div class="fw-bold text-dark" style="font-size: 13.5px;">{{ $st->step_name }}</div>
                                                                    <code class="text-muted small">{{ $st->step_code }}</code>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-secondary-subtle text-secondary border">
                                                                        <i class="fa-solid fa-user-tie me-1"></i> {{ $st->role->name ?? 'N/A' }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-light text-dark border">
                                                                        {{ ucwords(str_replace('_', ' ', $st->action_type)) }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-light text-muted border" style="font-size: 11px;">
                                                                        <i class="fa-regular fa-clock me-1"></i> {{ $st->allowed_days ?? 15 }}d
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex flex-wrap gap-1">
                                                                        @if($st->can_forward)
                                                                            <span class="wf-cap-badge cap-forward" title="Can Forward application">
                                                                                <i class="fa-solid fa-arrow-right"></i> Forward
                                                                            </span>
                                                                        @endif
                                                                        @if($st->can_send_back)
                                                                            <span class="wf-cap-badge cap-sendback" title="Can Send Back to previous role">
                                                                                <i class="fa-solid fa-reply"></i> Send Back
                                                                            </span>
                                                                        @endif
                                                                        @if($st->can_reject)
                                                                            <span class="wf-cap-badge cap-reject" title="Can Reject application">
                                                                                <i class="fa-solid fa-ban"></i> Reject
                                                                            </span>
                                                                        @endif
                                                                        @if($st->can_approve || $st->action_type === 'approve')
                                                                            <span class="wf-cap-badge cap-approve" title="Can Approve / Final Sanction">
                                                                                <i class="fa-solid fa-check-double"></i> Approve
                                                                            </span>
                                                                        @endif
                                                                        @if($st->can_verify_site || $st->action_type === 'site_verification')
                                                                            <span class="wf-cap-badge cap-site" title="Site Verification / Field Inspection">
                                                                                <i class="fa-solid fa-map-location-dot"></i> Site Verify
                                                                            </span>
                                                                        @endif
                                                                        @if($st->can_correspondence)
                                                                            <span class="wf-cap-badge cap-corr" title="Office Correspondences (LT/OO/OD)">
                                                                                <i class="fa-solid fa-envelope-open-text"></i> Correspondences
                                                                            </span>
                                                                        @endif
                                                                        @if($st->can_add_note)
                                                                            <span class="wf-cap-badge cap-note" title="Add Note / Office Noting">
                                                                                <i class="fa-solid fa-pen-to-square"></i> Note
                                                                            </span>
                                                                        @endif
                                                                        @if($st->can_upload_document)
                                                                            <span class="wf-cap-badge cap-upload" title="Upload verification documents">
                                                                                <i class="fa-solid fa-upload"></i> Upload
                                                                            </span>
                                                                        @endif
                                                                        @if($st->requires_signature)
                                                                            <span class="wf-cap-badge cap-sign" title="Requires Digital / OTP Signature">
                                                                                <i class="fa-solid fa-signature"></i> Sign
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="6" class="text-center text-muted py-3">
                                                                    No steps added to this workflow yet.
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:40px 20px;color:var(--text-light);">
                            <i class="fa-solid fa-diagram-project fa-2x mb-2 text-muted" style="display:block; opacity:0.4;"></i>
                            No workflows found matching your criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($workflows->total() > 0)
        <div class="table-pagination" id="workflowPagination">
            <span>
                Showing <strong>{{ $workflows->firstItem() }}</strong> to
                <strong>{{ $workflows->lastItem() }}</strong> of <strong>{{ $workflows->total() }}</strong>
                workflows
            </span>
            <div class="pagination-btns">
                @if ($workflows->onFirstPage())
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a class="pag-btn" href="{{ $workflows->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @endif

                @foreach ($workflows->getUrlRange(1, $workflows->lastPage()) as $page => $url)
                    <a class="pag-btn {{ $page === $workflows->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                @endforeach

                @if ($workflows->hasMorePages())
                    <a class="pag-btn" href="{{ $workflows->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Roadmap Modal -->
<div id="roadmapModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
    <div style="background-color:#fff; margin: 5% auto; padding: 22px; border-radius: 8px; width: 90%; max-width: 700px; max-height: 85vh; overflow-y: auto; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 12px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-size: 1.25rem; color: #0f172a; font-weight: 700;">
                <i class="fa-solid fa-sitemap text-primary me-2"></i> Workflow Sequential Roadmap
            </h3>
            <span onclick="closeRoadmapModal()" style="cursor:pointer; font-size: 26px; color: #94a3b8; line-height: 1;">&times;</span>
        </div>
        <div id="roadmapContent"></div>
    </div>
</div>

<style>
    /* Metric Pills Top Bar */
    .wf-metrics-bar {
        display: flex;
        gap: 16px;
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }
    .wf-metric-pill {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 16px;
        flex: 1;
        min-width: 170px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .wf-metric-pill i {
        font-size: 20px;
    }
    .wf-metric-num {
        display: block;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    .wf-metric-lbl {
        font-size: 11.5px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    /* Accordion styles (matching allottee list) */
    .accordion-row {
        display: none;
    }
    .accordion-row.open {
        display: table-row;
    }
    .accordion-toggle-btn {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #475569;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .accordion-toggle-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .accordion-toggle-btn.active {
        background: #0d6efd;
        border-color: #0d6efd;
        color: white;
        transform: rotate(180deg);
    }
    .accordion-wrapper {
        padding: 20px;
        background: #f4f7fb;
        border-bottom: 2px solid #e2e8f0;
        box-shadow: inset 0 3px 6px rgba(0, 0, 0, 0.04);
    }

    /* Admin Card styling from allottee list */
    .admin-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        height: 100%;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .admin-card-header {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 14px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px 16px;
    }
    .detail-item {
        font-size: 13.5px;
        color: #0f172a;
        line-height: 1.5;
        font-weight: 500;
    }
    .detail-label {
        display: block;
        font-size: 11.5px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 2px;
    }

    /* Sub-table styling */
    .app-list-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 4px;
    }
    .app-list-table th {
        font-size: 11.5px;
        text-transform: uppercase;
        color: #475569;
        font-weight: 700;
        padding: 8px 10px;
        text-align: left;
        border-bottom: 2px solid #cbd5e1;
    }
    .app-list-table td {
        padding: 9px 10px;
        background: #fff;
        font-size: 13px;
        color: #0f172a;
        border: 1px solid #cbd5e1;
        vertical-align: middle;
    }
    .app-list-table td:first-child {
        border-radius: 4px 0 0 4px;
        border-right: none;
    }
    .app-list-table td:last-child {
        border-radius: 0 4px 4px 0;
        border-left: none;
    }

    /* Table columns */
    .wf-title-block {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .wf-title-name {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }
    .wf-slug-badge {
        display: flex;
        align-items: center;
    }
    .wf-slug-badge code {
        font-size: 12px;
        color: #2563eb;
        background: #eff6ff;
        padding: 1px 6px;
        border-radius: 4px;
    }
    .wf-type-pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Stepper chain in table */
    .wf-chain-flow {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }
    .wf-chain-node {
        display: inline-flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        overflow: hidden;
        font-size: 11.5px;
    }
    .wf-chain-node .step-num {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        padding: 2px 6px;
        border-right: 1px solid #cbd5e1;
    }
    .wf-chain-node .step-role {
        padding: 2px 8px;
        font-weight: 600;
        color: #1e293b;
    }
    .wf-chain-arrow {
        color: #94a3b8;
        font-size: 11px;
    }

    /* Capability badges */
    .wf-cap-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
    .cap-forward   { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .cap-sendback  { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .cap-reject    { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .cap-approve   { background: #f0fdfa; color: #0f766e; border: 1px solid #99f6e4; }
    .cap-site      { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .cap-corr      { background: #faf5ff; color: #7e22ce; border: 1px solid #f3e8ff; }
    .cap-note      { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .cap-upload    { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
    .cap-sign      { background: #fdf4ff; color: #86198f; border: 1px solid #f5d0fe; }

    /* Roadmap Timeline */
    .workflow-timeline { position: relative; padding: 10px 0; }
    .workflow-timeline::before {
        content: ''; position: absolute; top: 0; bottom: 0; left: 24px;
        width: 2px; background: #cbd5e1;
    }
    .timeline-step { position: relative; margin-bottom: 20px; padding-left: 60px; }
    .timeline-step:last-child { margin-bottom: 0; }
    .timeline-icon {
        position: absolute; left: 10px; top: 0; width: 30px; height: 30px;
        background: #fff; border: 2px solid #0d6efd; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; color: #0d6efd; z-index: 1; font-size: 13px;
    }
    .timeline-content {
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px;
    }
    .timeline-title { font-weight: 700; color: #0f172a; margin-bottom: 4px; font-size: 14.5px; }
    .timeline-role { color: #475569; font-size: 13px; margin-bottom: 8px; }
    .timeline-actions { display: flex; gap: 6px; flex-wrap: wrap; }
</style>

<script>
    function toggleWorkflowDrawer(id, btn) {
        const drawer = document.getElementById(`wf-drawer-${id}`);
        if (!drawer) return;
        const isOpen = drawer.classList.contains('open');

        // Close any other open drawers
        document.querySelectorAll('.accordion-row.open').forEach(row => {
            row.classList.remove('open');
        });
        document.querySelectorAll('.accordion-toggle-btn.active').forEach(b => {
            b.classList.remove('active');
        });

        if (!isOpen) {
            drawer.classList.add('open');
            btn.classList.add('active');
        }
    }

    function showRoadmap(steps) {
        let stepsArray = [];
        if (typeof steps === 'string') {
            try {
                stepsArray = JSON.parse(steps);
            } catch(e) {
                console.error("Error parsing steps", e);
            }
        } else {
            stepsArray = steps;
        }

        const contentDiv = document.getElementById('roadmapContent');
        
        if (!stepsArray || stepsArray.length === 0) {
            contentDiv.innerHTML = '<div style="text-align:center; padding: 30px; color:#94a3b8;">No steps configured for this workflow.</div>';
        } else {
            let html = '<div class="workflow-timeline">';
            stepsArray.sort((a,b) => (a.step_order || 0) - (b.step_order || 0)).forEach(step => {
                let actionsHtml = '';
                if (step.can_forward) actionsHtml += '<span class="wf-cap-badge cap-forward"><i class="fa-solid fa-arrow-right"></i> Can Forward</span>';
                if (step.can_send_back) actionsHtml += '<span class="wf-cap-badge cap-sendback"><i class="fa-solid fa-reply"></i> Can Send Back</span>';
                if (step.can_reject) actionsHtml += '<span class="wf-cap-badge cap-reject"><i class="fa-solid fa-ban"></i> Can Reject</span>';
                if (step.can_approve || step.action_type === 'approve') actionsHtml += '<span class="wf-cap-badge cap-approve"><i class="fa-solid fa-check-double"></i> Can Approve</span>';
                if (step.can_verify_site || step.action_type === 'site_verification') actionsHtml += '<span class="wf-cap-badge cap-site"><i class="fa-solid fa-map-location-dot"></i> Site Verification</span>';
                if (step.can_correspondence) actionsHtml += '<span class="wf-cap-badge cap-corr"><i class="fa-solid fa-envelope-open-text"></i> Office Correspondences</span>';
                if (step.can_add_note) actionsHtml += '<span class="wf-cap-badge cap-note"><i class="fa-solid fa-pen-to-square"></i> Add Note</span>';
                if (step.can_upload_document) actionsHtml += '<span class="wf-cap-badge cap-upload"><i class="fa-solid fa-upload"></i> Upload Docs</span>';
                if (step.requires_signature) actionsHtml += '<span class="wf-cap-badge cap-sign"><i class="fa-solid fa-signature"></i> Requires Signature</span>';

                if (actionsHtml === '') actionsHtml = '<span class="text-muted small fst-italic">No active permissions</span>';

                const roleName = step.role_name || (step.role ? step.role.name : 'Unassigned Role');

                html += `
                    <div class="timeline-step">
                        <div class="timeline-icon">${step.step_order}</div>
                        <div class="timeline-content">
                            <div class="timeline-title">${step.step_name} <code class="text-muted small ms-2">${step.step_code || ''}</code></div>
                            <div class="timeline-role">
                                <i class="fa-solid fa-user-tie text-primary me-1"></i> Assigned Role: <strong>${roleName}</strong>
                                <span class="badge bg-light text-muted border ms-2"><i class="fa-regular fa-clock me-1"></i> ${step.allowed_days || 15} Days SLA</span>
                            </div>
                            <div class="timeline-actions">${actionsHtml}</div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            contentDiv.innerHTML = html;
        }

        document.getElementById('roadmapModal').style.display = 'block';
    }

    function closeRoadmapModal() {
        document.getElementById('roadmapModal').style.display = 'none';
    }

    // Client-side quick filter on search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('workflowSearchInput');
        if (!searchInput) return;

        let debounceTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.toLowerCase().trim();

            debounceTimer = setTimeout(() => {
                const rows = document.querySelectorAll('.workflow-main-row');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    const rowId = row.id.replace('wf-row-', '');
                    const drawer = document.getElementById(`wf-drawer-${rowId}`);

                    if (!query || text.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                        if (drawer) {
                            drawer.classList.remove('open');
                        }
                    }
                });
            }, 250);
        });
    });
</script>
@endsection
