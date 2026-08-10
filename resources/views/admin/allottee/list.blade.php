@extends('layouts.main')

@section('title', 'Allottee List | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">Allottee List</div>
            <div class="card-subtitle">Search, filter, and manage all allottees</div>
        </div>
        <div class="card-actions">
            <input type="text" id="propertySearch" class="form-control" placeholder="Search Property No..." value="{{ request('property_number') }}" style="display: inline-block; width: 220px; padding: 6px 12px; font-size: 14px; border: 1px solid #ccc; border-radius: 4px; box-shadow: none;">
            <a class="btn-pink" href="{{ route('admin.apply.index') }}">
                <i class="fa-solid fa-plus"></i> Add Allottee
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success" style="margin: 20px 20px 0;">
        {{ session('success') }}
    </div>
    @endif

    <style>
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
            border-left: 3px solid #3b82f6;
            box-shadow: inset 0 3px 6px rgba(0, 0, 0, 0.04);
        }

        .admin-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            height: 100%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .admin-card-header {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-card-header i {
            color: #3b82f6;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px 16px;
        }

        .detail-item {
            font-size: 14px;
            color: #0f172a;
            line-height: 1.5;
            font-weight: 500;
        }

        .detail-label {
            display: block;
            font-size: 12.5px;
            color: #1e293b;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .app-list-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 4px;
        }

        .app-list-table th {
            font-size: 12.5px;
            text-transform: uppercase;
            color: #1e293b;
            font-weight: 700;
            padding: 8px 10px;
            text-align: left;
            border-bottom: 2px solid #cbd5e1;
        }

        .app-list-table td {
            padding: 8px 10px;
            background: #fff;
            font-size: 14px;
            color: #0f172a;
            border: 1px solid #cbd5e1;
        }

        .app-list-table td:first-child {
            border-radius: 4px 0 0 4px;
            border-right: none;
            font-weight: 500;
            color: #334155;
        }

        .app-list-table td:last-child {
            border-radius: 0 4px 4px 0;
            border-left: none;
        }

        .action-btns {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            max-width: 160px;
        }

        .action-btn {
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Timeline CSS */
        .audit-timeline {
            position: relative;
            padding-left: 30px;
            margin: 10px 0;
        }
        .audit-timeline::before {
            content: '';
            position: absolute;
            left: 11px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }
        .audit-timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        .audit-timeline-marker {
            position: absolute;
            left: -30px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #3b82f6;
            border: 4px solid #fff;
            box-shadow: 0 0 0 2px #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            z-index: 1;
        }
        .audit-timeline-content {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 15px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        .audit-timeline-content:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transform: translateY(-1px);
        }
        .audit-timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
        }
        .audit-timeline-action {
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .audit-timeline-date {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 12px;
        }
        .audit-timeline-route {
            font-size: 13px;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .audit-timeline-route i {
            color: #94a3b8;
            font-size: 10px;
        }
        .audit-timeline-notes {
            display: none;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px dashed #cbd5e1;
            font-size: 13.5px;
            color: #1e293b;
            background: #ffffff;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }
        .audit-timeline-item.active .audit-timeline-notes {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        .click-hint {
            position: absolute;
            right: 15px;
            bottom: 12px;
            font-size: 11px;
            color: #94a3b8;
            font-weight: 600;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .audit-timeline-content:hover .click-hint {
            opacity: 1;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;"><i class="fa-solid fa-list"></i></th>
                    <th>#</th>
                    <th>Allottee Details</th>
                    <th>Division / Sub</th>
                    <th>Category</th>
                    <th>Property No</th>
                    <th>Payment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allottees as $allottee)
                <tr>
                    <td style="text-align: center;">
                        <button class="accordion-toggle-btn" type="button" onclick="toggleAccordion(this, 'acc-{{ $allottee->id }}')" title="View Administrative Details">
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                    </td>
                    <td>{{ $allottees->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="table-name">
                            {{ trim(($allottee->allottee_name ?? '') . ' ' . ($allottee->allottee_middle_name ?? '') . ' ' . ($allottee->allottee_surname ?? '')) ?: '-' }}
                        </div>
                        <div class="table-email" style="color: #0284c7; font-weight: 500; font-size: 12px; margin-top: 2px;">
                            <i class="fa-solid fa-user-circle"></i> {{ $allottee->username ?: 'N/A' }}
                        </div>
                    </td>
                    <td>
                        <div class="table-name">{{ $allottee->division->name ?? '-' }}</div>
                        <div class="table-email">{{ $allottee->subDivision->name ?? '-' }}</div>
                    </td>
                    <td>{{ $allottee->propertyCategory->name ?? '-' }}</td>
                    <td><strong style="color: #334155;">{{ $allottee->property_number ?: '-' }}</strong></td>
                    <td>
                        <span class="badge-status {{ $allottee->payment_amount ? 'active' : 'inactive' }}">
                            {{ $allottee->payment_amount ? 'Paid: ' . number_format((float) $allottee->payment_amount, 2) : 'Pending' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">

                            <a class="action-btn view" href="{{ route('admin.allottees.show', $allottee) }}"
                                target="_blank" title="View Full Allottee Record">
                                <i class="fa-solid fa-file-lines"></i>
                            </a>

                            <a class="action-btn edit" href="{{ route('admin.edit.apply.index', $allottee) }}"
                                title="Edit Core Info">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            <a class="action-btn delete"
                                href="{{ route('admin.allottee.delete.components', $allottee) }}"
                                title="Reset Allottee Components">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>

                            @if ($allottee->payment_option == 'emi')
                            <a class="action-btn delete" style="background:#eab308; color:#fff;"
                                href="{{ route('admin.allottee.delete.emi.setup', $allottee) }}"
                                title="Reset EMI Setup">
                                <i class="fas fa-receipt"></i>
                            </a>
                            @endif

                            @if ($allottee->is_step_completed == 1 && $allottee->current_step == 3)
                            <button type="button" class="action-btn" style="background:#2563eb; color:#fff; cursor: pointer; border: none;"
                                onclick="sendCredentialMail({{ $allottee->id }}, this)" title="Send Portal Credentials">
                                <i class="fa-solid fa-envelope"></i>
                            </button>
                            @endif

                        </div>
                    </td>
                </tr>
                <tr class="accordion-row" id="acc-{{ $allottee->id }}">
                    <td colspan="8" style="padding: 0; border: none;">
                        <div class="accordion-wrapper">
                            <div class="row g-4">

                                <!-- Profile & Login Info -->
                                <div class="col-md-4">
                                    <div class="admin-card">
                                        <div class="admin-card-header">
                                            <i class="fa-solid fa-address-card"></i> Profile & IDs
                                        </div>
                                        <div class="detail-grid" style="grid-template-columns: 1fr;">
                                            <div class="detail-item"><span class="detail-label">Username</span> <strong>{{ $allottee->username ?: 'N/A' }}</strong></div>
                                            <div class="detail-item"><span class="detail-label">Application No</span> {{ $allottee->application_no ?: 'N/A' }}</div>
                                            <div class="detail-item"><span class="detail-label">Allotment No</span> {{ $allottee->allotment_no ?: 'N/A' }}</div>
                                            <div class="detail-item"><span class="detail-label">App. Date</span> {{ $allottee->application_day ? $allottee->application_day.'-'.$allottee->application_month.'-'.$allottee->application_year : 'N/A' }}</div>
                                            <div class="detail-item"><span class="detail-label">Allot. Date</span> {{ $allottee->allotment_day ? $allottee->allotment_day.'-'.$allottee->allotment_month.'-'.$allottee->allotment_year : 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Details -->
                                <div class="col-md-4">
                                    <div class="admin-card">
                                        <div class="admin-card-header">
                                            <i class="fa-solid fa-user-shield"></i> Personal Data
                                        </div>
                                        <div class="detail-grid">
                                            <div class="detail-item"><span class="detail-label">Date of Birth</span> {{ $allottee->date_of_birth_day ? $allottee->date_of_birth_day.'-'.$allottee->date_of_birth_month.'-'.$allottee->date_of_birth_year : 'N/A' }}</div>
                                            <div class="detail-item"><span class="detail-label">Gender</span> {{ $allottee->allottee_gender ?? 'N/A' }}</div>
                                            <div class="detail-item"><span class="detail-label">Marital Status</span> {{ $allottee->marital_status ?? 'N/A' }}</div>
                                            <div class="detail-item"><span class="detail-label">Relation</span> {{ $allottee->allottee_relation_type ?? 'N/A' }}</div>
                                            <div class="detail-item" style="grid-column: span 2;"><span class="detail-label">Relative Name</span> {{ $allottee->relation_name ?? 'N/A' }}</div>
                                            <div class="detail-item"><span class="detail-label">PAN No</span> <strong style="color: #0f172a;">{{ $allottee->pan_card_number ?? 'N/A' }}</strong></div>
                                            <div class="detail-item"><span class="detail-label">Aadhar No</span> <strong style="color: #0f172a;">{{ $allottee->aadhar_card_number ?? 'N/A' }}</strong></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Property & Finance -->
                                <div class="col-md-4">
                                    <div class="admin-card">
                                        <div class="admin-card-header">
                                            <i class="fa-solid fa-building-columns"></i> Asset & Finance
                                        </div>
                                        <div class="detail-grid" style="grid-template-columns: 1fr;">
                                            <div class="detail-item"><span class="detail-label">Property No</span> <strong style="color:#0284c7; font-size: 14px;">{{ $allottee->property_number ?: 'N/A' }}</strong></div>
                                            <div class="detail-item"><span class="detail-label">Payment Option</span> <span style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-weight: 500; text-transform:uppercase;">{{ str_replace('_', ' ', $allottee->payment_option ?? 'N/A') }}</span></div>
                                            <div class="detail-item"><span class="detail-label">Total Paid</span> <strong style="color:#16a34a; font-size: 14px;">₹{{ number_format((float) $allottee->payment_amount, 2) }}</strong></div>
                                            <div class="detail-item"><span class="detail-label">Category</span> {{ $allottee->propertyCategory->name ?? 'N/A' }}</div>
                                            <div class="detail-item"><span class="detail-label">Division</span> {{ $allottee->division->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Applications Full Width Table -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="admin-card">
                                        <div class="admin-card-header" style="justify-content: space-between;">
                                            <span><i class="fa-solid fa-file-signature"></i> Applications & Processing Stage</span>
                                            <button type="button" class="btn btn-sm" style="padding: 4px 12px; font-size: 12px; background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; border-radius: 4px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onclick="openApplicationModal({{ $allottee->id }})" onmouseover="this.style.background='#c7d2fe'" onmouseout="this.style.background='#e0e7ff'">
                                                <i class="fa-solid fa-gear"></i> Manage Applications
                                            </button>
                                        </div>
                                        @if($allottee->applications && $allottee->applications->count() > 0)
                                        <div class="table-responsive">
                                            <table class="app-list-table" style="width: 100%;">
                                                <thead style="background: #e2e8f0;">
                                                    <tr>
                                                        <th style="padding: 6px 8px;">App. No.</th>
                                                        <th style="padding: 6px 8px;">Type</th>
                                                        <th style="padding: 6px 8px;">Current Stage</th>
                                                        <th style="padding: 6px 8px;">Pending With</th>
                                                        <th style="padding: 6px 8px;">Status</th>
                                                        <th style="padding: 6px 8px; text-align: center;">Action / Tracks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($allottee->applications as $app)
                                                    <tr>
                                                        <td style="font-weight: 700; color: #000;">{{ $app->application_no }}</td>
                                                        <td style="text-transform: capitalize; font-weight: 600;">{{ str_replace('_', ' ', $app->application_type) }}</td>
                                                        <td style="color: #0f172a; font-weight: 600;">{{ $app->currentStep->step_name ?? 'Not Started' }}</td>
                                                        <td style="color: #0f172a; font-weight: 600;">
                                                            {{ $app->currentUser->name ?? ($app->currentRole->name ?? 'Unassigned') }}
                                                        </td>
                                                        <td>
                                                            <span style="background: #e2e8f0; color: #0f172a; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 700; text-transform: uppercase; border: 1px solid #cbd5e1;">{{ $app->status }}</span>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap; width: 120px; margin: 0 auto;">
                                                                <button type="button" class="btn btn-sm" onclick="openAuditTrailModal({{ $app->id }})" style="padding: 4px 8px; font-size: 11px; background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; border-radius: 6px; font-weight: 700; transition: all 0.2s;" title="Audit Trail" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                                                                    <i class="fa-solid fa-clock-rotate-left"></i> ({{ $app->movements ? $app->movements->count() : 0 }})
                                                                </button>
                                                                <button type="button" class="btn btn-sm" onclick="openCommTrackModal({{ $app->id }})" style="padding: 4px 8px; font-size: 11px; background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; border-radius: 6px; font-weight: 700; transition: all 0.2s;" title="Communication Track" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                                                                    <i class="fa-solid fa-envelope"></i> ({{ $app->communicationTracks ? $app->communicationTracks->count() : 0 }})
                                                                </button>
                                                                <button type="button" class="btn btn-sm" onclick="openCorrespondenceModal({{ $app->id }})" style="padding: 4px 8px; font-size: 11px; background: #fffbeb; color: #d97706; border: 1px solid #fde68a; border-radius: 6px; font-weight: 700; transition: all 0.2s;" title="Office Correspondence" onmouseover="this.style.background='#fef3c7'" onmouseout="this.style.background='#fffbeb'">
                                                                    <i class="fa-solid fa-file-invoice"></i> ({{ $app->correspondences ? $app->correspondences->count() : 0 }})
                                                                </button>
                                                                <button type="button" class="btn btn-sm" onclick="openBypassModal({{ $app->id }})" style="padding: 4px 8px; font-size: 11px; background: #fdf2f8; color: #db2777; border: 1px solid #fbcfe8; border-radius: 6px; font-weight: 700; transition: all 0.2s;" title="Bypass Requests" onmouseover="this.style.background='#fce7f3'" onmouseout="this.style.background='#fdf2f8'">
                                                                    <i class="fa-solid fa-forward-step"></i> ({{ $app->bypassRequests ? $app->bypassRequests->count() : 0 }})
                                                                </button>
                                                            </div>
                                                            <script id="audit-data-{{ $app->id }}" type="application/json">
                                                                {!! $app->movements ? $app->movements->load(['fromUser', 'toUser', 'fromRole', 'toRole', 'fromStep', 'toStep'])->toJson() : '[]' !!}
                                                            </script>
                                                            <script id="comm-data-{{ $app->id }}" type="application/json">
                                                                {!! $app->communicationTracks ? $app->communicationTracks->toJson() : '[]' !!}
                                                            </script>
                                                            <script id="corr-data-{{ $app->id }}" type="application/json">
                                                                {!! $app->correspondences ? $app->correspondences->toJson() : '[]' !!}
                                                            </script>
                                                            <script id="bypass-data-{{ $app->id }}" type="application/json">
                                                                {!! $app->bypassRequests ? $app->bypassRequests->toJson() : '[]' !!}
                                                            </script>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @else
                                        <div style="text-align: center; padding: 40px 0; background: #f8fafc; border-radius: 6px; border: 1px dashed #94a3b8;">
                                            <i class="fa-solid fa-folder-open" style="font-size: 32px; color: #64748b; margin-bottom: 12px;"></i>
                                            <p style="font-size: 15px; color: #1e293b; margin: 0; font-weight: 600;">No applications found for this allottee.</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"
                        style="text-align:center;padding:32px 20px;color:var(--text-dark);font-weight:600;">
                        No allottees found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($allottees->total() > 0)
    <div class="table-pagination">
        <span>
            Showing <strong>{{ $allottees->firstItem() }}</strong> to
            <strong>{{ $allottees->lastItem() }}</strong> of <strong>{{ $allottees->total() }}</strong> allottees
        </span>
        <div class="pagination-btns">
            @if ($allottees->onFirstPage())
            <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                    class="fa-solid fa-chevron-left"></i></span>
            @else
            <a class="pag-btn" href="{{ $allottees->previousPageUrl() }}"><i
                    class="fa-solid fa-chevron-left"></i></a>
            @endif

            @foreach ($allottees->getUrlRange(1, $allottees->lastPage()) as $page => $url)
            <a class="pag-btn {{ $page === $allottees->currentPage() ? 'active' : '' }}"
                href="{{ $url }}">{{ $page }}</a>
            @endforeach

            @if ($allottees->hasMorePages())
            <a class="pag-btn" href="{{ $allottees->nextPageUrl() }}"><i
                    class="fa-solid fa-chevron-right"></i></a>
            @else
            <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                    class="fa-solid fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>
<!-- Application Management Modal -->
<div id="applicationModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1050; align-items: flex-start; padding-top: 50px; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
    <div class="modal-content" style="background: #fff; width: 700px; max-width: 95%; border-radius: 6px; padding: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); transform: translateY(-50px); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eaeaea; padding-bottom: 12px; margin-bottom: 15px;">
            <h3 style="margin: 0; font-size: 17px; color: #333; font-weight: 600;">Manage Applications Flow</h3>
            <div>
                <button type="button" onclick="closeApplicationModal()" style="background: none; border: none; font-size: 22px; color: #999; cursor: pointer; line-height: 1;">&times;</button>
            </div>
        </div>
        <div id="applicationModalAlert" style="display: none; padding: 12px; margin-bottom: 15px; border-radius: 4px; font-size: 13px; font-weight: 500;"></div>
        <div id="applicationModalBody">
            <p>Loading applications...</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let searchTimeout;
        const searchInput = document.getElementById('propertySearch');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const url = new URL(window.location.href);
                    if (this.value.trim() !== '') {
                        url.searchParams.set('property_number', this.value.trim());
                        url.searchParams.delete('page'); // reset page on search
                    } else {
                        url.searchParams.delete('property_number');
                    }
                    window.location.href = url.toString();
                }, 600);
            });

            // Focus at the end of text
            const val = searchInput.value;
            if (val) {
                searchInput.focus();
                searchInput.setSelectionRange(val.length, val.length);
            }
        }
    });

    function toggleAccordion(btn, id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.toggle('open');
            btn.classList.toggle('active');
        }
    }

    let currentModalAllotteeId = null;

    function showModalAlert(message, type = 'error') {
        const alertBox = document.getElementById('applicationModalAlert');
        alertBox.style.display = 'block';
        alertBox.innerHTML = message;
        if (type === 'success') {
            alertBox.style.backgroundColor = '#d1e7dd';
            alertBox.style.color = '#0f5132';
            alertBox.style.border = '1px solid #badbcc';
        } else {
            alertBox.style.backgroundColor = '#f8d7da';
            alertBox.style.color = '#842029';
            alertBox.style.border = '1px solid #f5c2c7';
        }
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 5000);
    }

    function openApplicationModal(allotteeId) {
        currentModalAllotteeId = allotteeId;
        const modal = document.getElementById('applicationModal');
        const modalContent = modal.querySelector('.modal-content');
        const modalBody = document.getElementById('applicationModalBody');

        modal.style.display = 'flex';
        // Trigger reflow
        void modal.offsetWidth;

        modal.style.opacity = '1';
        modalContent.style.transform = 'translateY(0)';

        modalBody.innerHTML = '<p>Loading applications...</p>';

        fetch(`/admin/allottees/${allotteeId}/applications`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const appTypes = [
                        'allotment', 'agreement', 'possession', 'registry', 'mutation',
                        'transfer', 'noc', 'lease_renewal', 'duplicate_certificate',
                        'cancellation', 'name_correction'
                    ];

                    let html = '<table style="width: 100%; border-collapse: collapse; font-size: 13px;">';
                    html += '<thead><tr style="border-bottom: 2px solid #eaeaea; background: #fbfbfc;">';
                    html += '<th style="padding: 10px; text-align: left; text-transform: uppercase; font-size: 11px; color: #6c757d; letter-spacing: 0.5px;">Application Type</th>';
                    html += '<th style="padding: 10px; text-align: left; text-transform: uppercase; font-size: 11px; color: #6c757d; letter-spacing: 0.5px;">Application No</th>';
                    html += '<th style="padding: 10px; text-align: left; text-transform: uppercase; font-size: 11px; color: #6c757d; letter-spacing: 0.5px;">Status</th>';
                    html += '<th style="padding: 10px; text-align: right; text-transform: uppercase; font-size: 11px; color: #6c757d; letter-spacing: 0.5px;">Action</th>';
                    html += '</tr></thead><tbody>';

                    appTypes.forEach(type => {
                        // Find if application exists
                        const existingApp = data.applications.find(a => a.application_type === type);

                        html += `<tr style="border-bottom: 1px solid #f4f4f4; transition: background 0.2s;" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 10px; font-weight: 500; color: #333; text-transform: capitalize;">${type.replace('_', ' ')}</td>`;

                        if (existingApp) {
                            html += `<td style="padding: 10px; color: #555;">${existingApp.application_no}</td>
                                         <td style="padding: 10px;"><span style="background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 500;">${existingApp.status}</span></td>
                                         <td style="padding: 10px; text-align: right;">
                                             <button onclick="deleteApplication(${existingApp.id})" style="background: #fff; color: #dc3545; border: 1px solid #dc3545; padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 12px; transition: all 0.2s;" onmouseover="this.style.background='#dc3545'; this.style.color='#fff'" onmouseout="this.style.background='#fff'; this.style.color='#dc3545'">Delete</button>
                                         </td>`;
                        } else {
                            html += `<td style="padding: 10px; color: #aaa; font-style: italic;">Not Created</td>
                                         <td style="padding: 10px; color: #aaa;">-</td>
                                         <td style="padding: 10px; text-align: right;">
                                             <button onclick="createApplicationOfType('${type}')" style="background: #fff; color: #198754; border: 1px solid #198754; padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 12px; transition: all 0.2s;" onmouseover="this.style.background='#198754'; this.style.color='#fff'" onmouseout="this.style.background='#fff'; this.style.color='#198754'">Create</button>
                                         </td>`;
                        }
                        html += `</tr>`;
                    });

                    html += '</tbody></table>';
                    modalBody.innerHTML = html;
                } else {
                    modalBody.innerHTML = '<p style="color: red; font-size: 16px;">Failed to load applications.</p>';
                }
            })
            .catch(err => {
                console.error(err);
                modalBody.innerHTML = '<p style="color: red;">An error occurred.</p>';
            });
    }

    function closeApplicationModal() {
        const modal = document.getElementById('applicationModal');
        const modalContent = modal.querySelector('.modal-content');

        modal.style.opacity = '0';
        modalContent.style.transform = 'translateY(-50px)';

        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    function deleteApplication(applicationId) {
        if (!confirm('Are you sure you want to delete this application?')) {
            return;
        }

        fetch(`/admin/applications/${applicationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showModalAlert(data.message, 'success');
                    openApplicationModal(currentModalAllotteeId); // Refresh list
                } else {
                    showModalAlert(data.message || 'Error deleting application.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showModalAlert('An error occurred while deleting the application.', 'error');
            });
    }

    function createApplicationOfType(type) {
        if (!currentModalAllotteeId) return;

        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Creating...';
        btn.disabled = true;

        const formData = new FormData();
        formData.append('application_type', type);

        fetch(`/admin/allottees/${currentModalAllotteeId}/applications`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalText;
                btn.disabled = false;

                if (data.success) {
                    showModalAlert(data.message, 'success');
                    openApplicationModal(currentModalAllotteeId); // Refresh list
                } else {
                    showModalAlert(data.message || 'Error creating application.', 'error');
                }
            })
            .catch(err => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                console.error(err);
                showModalAlert('An error occurred while creating the application.', 'error');
            });
    }
</script>

<!-- Audit Trail Modal -->
<div class="modal fade" id="auditTrailModal" tabindex="-1" aria-labelledby="auditTrailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title" id="auditTrailModalLabel" style="font-weight: 700; color: #0f172a;"><i class="fa-solid fa-clock-rotate-left" style="color: #3b82f6; margin-right: 8px;"></i> Application Audit Trail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 24px; max-height: 60vh; overflow-y: auto; background: #ffffff;">
                <div id="auditTrailContent">
                    <!-- Timeline will be injected here -->
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-radius: 0 0 12px 12px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 12px; color: #64748b;"><i class="fa-solid fa-circle-info" style="color:#94a3b8; margin-right:4px;"></i> Click on any timeline item to view its notes & remarks.</span>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-weight: 600; padding: 6px 16px; background: #64748b; border: none;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Communication Track Modal -->
<div class="modal fade" id="commTrackModal" tabindex="-1" aria-labelledby="commTrackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title" id="commTrackModalLabel" style="font-weight: 700; color: #0f172a;"><i class="fa-solid fa-envelope" style="color: #2563eb; margin-right: 8px;"></i> Communication Track</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 24px; max-height: 60vh; overflow-y: auto; background: #ffffff;">
                <div id="commTrackContent">
                    <!-- Comm tracks will be injected here -->
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-radius: 0 0 12px 12px; display: flex; justify-content: flex-end; align-items: center;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-weight: 600; padding: 6px 16px; background: #64748b; border: none;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Office Correspondence Modal -->
<div class="modal fade" id="correspondenceModal" tabindex="-1" aria-labelledby="correspondenceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background: #fffbeb; border-bottom: 1px solid #fde68a; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title" id="correspondenceModalLabel" style="font-weight: 700; color: #92400e;"><i class="fa-solid fa-file-invoice" style="color: #d97706; margin-right: 8px;"></i> Office Correspondence</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 24px; max-height: 60vh; overflow-y: auto; background: #ffffff;">
                <div id="correspondenceContent">
                    <!-- Correspondences will be injected here -->
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-radius: 0 0 12px 12px; display: flex; justify-content: flex-end; align-items: center;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-weight: 600; padding: 6px 16px; background: #64748b; border: none;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bypass Requests Modal -->
<div class="modal fade" id="bypassModal" tabindex="-1" aria-labelledby="bypassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background: #fdf2f8; border-bottom: 1px solid #fbcfe8; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title" id="bypassModalLabel" style="font-weight: 700; color: #9d174d;"><i class="fa-solid fa-forward-step" style="color: #db2777; margin-right: 8px;"></i> Bypass Requests</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 24px; max-height: 60vh; overflow-y: auto; background: #ffffff;">
                <div id="bypassContent">
                    <!-- Bypass Requests will be injected here -->
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-radius: 0 0 12px 12px; display: flex; justify-content: flex-end; align-items: center;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-weight: 600; padding: 6px 16px; background: #64748b; border: none;">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openCommTrackModal(appId) {
        var dataEl = document.getElementById('comm-data-' + appId);
        var contentDiv = document.getElementById('commTrackContent');
        
        if (!dataEl) {
            contentDiv.innerHTML = '<div class="alert alert-danger" style="margin:0;">Communication data not found.</div>';
            var myModal = new bootstrap.Modal(document.getElementById('commTrackModal'));
            myModal.show();
            return;
        }

        try {
            var tracks = JSON.parse(dataEl.innerHTML);
            if (tracks.length === 0) {
                contentDiv.innerHTML = '<div style="text-align: center; padding: 40px 0; color: #64748b;"><i class="fa-solid fa-envelope-open-text fa-3x mb-3" style="opacity: 0.5;"></i><h4>No Communication Yet</h4><p>No messages have been sent for this application.</p></div>';
            } else {
                var html = '<div class="audit-timeline">';
                tracks.forEach(function(t) {
                    var commType = (t.communication_type || 'unknown').toLowerCase();
                    var commColor = '#64748b';
                    var commIcon = 'fa-envelope';
                    
                    if (commType === 'email') {
                        commColor = '#ea4335'; // Google Red
                        commIcon = 'fa-envelope';
                    } else if (commType === 'sms') {
                        commColor = '#3b82f6'; // Blue
                        commIcon = 'fa-comment-sms';
                    } else if (commType === 'whatsapp') {
                        commColor = '#25D366'; // WhatsApp Green
                        commIcon = 'fa-brands fa-whatsapp';
                    }

                    var statusColor = t.status === 'success' ? '#10b981' : '#ef4444';
                    var dateStr = new Date(t.created_at).toLocaleString('en-IN', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit', hour12:true});

                    html += '<div class="audit-timeline-item" onclick="this.classList.toggle(\'active\')">';
                    html += '<div class="audit-timeline-marker" style="background: ' + commColor + '"><i class="fa-solid ' + commIcon + '"></i></div>';
                    html += '<div class="audit-timeline-content">';
                    html += '<div class="audit-timeline-header">';
                    html += '<div class="audit-timeline-action" style="color: ' + commColor + '">' + commType.toUpperCase() + '</div>';
                    html += '<div class="audit-timeline-date">' + dateStr + '</div>';
                    html += '</div>';
                    html += '<div class="audit-timeline-route">';
                    html += '<strong style="color:#1e293b;">To: ' + (t.receiver_type || 'User') + '</strong> <span style="color:' + statusColor + '; font-weight:600; font-size:11px; padding:2px 6px; background:#f1f5f9; border-radius:4px; margin-left:auto;">' + (t.status || 'unknown').toUpperCase() + '</span>';
                    html += '</div>';
                    html += '<div style="margin-top:8px; font-size:13px; color:#334155;"><strong>Subject:</strong> ' + (t.subject || 'No Subject') + '</div>';
                    html += '<div class="audit-timeline-notes" onclick="event.stopPropagation()">';
                    html += '<strong style="display:block; margin-bottom:6px; color:#64748b; font-size:12px; text-transform:uppercase;"><i class="fa-solid fa-align-left" style="margin-right:4px;"></i> Message Content</strong>';
                    html += '<div style="line-height:1.6; max-height:200px; overflow-y:auto; background:#f8fafc; padding:10px; border-radius:6px; border:1px solid #e2e8f0;">' + (t.message || '<em style="color:#94a3b8;">No content.</em>') + '</div>';
                    if (t.error_message) {
                        html += '<strong style="display:block; margin-top:10px; margin-bottom:6px; color:#ef4444; font-size:12px; text-transform:uppercase;"><i class="fa-solid fa-triangle-exclamation" style="margin-right:4px;"></i> Error Info</strong>';
                        html += '<div style="color:#dc2626; font-size:12px;">' + t.error_message + '</div>';
                    }
                    html += '</div>';
                    html += '<div class="click-hint"><i class="fa-solid fa-hand-pointer"></i> Click to view message</div>';
                    html += '</div>';
                    html += '</div>';
                });
                html += '</div>';
                contentDiv.innerHTML = html;
            }
        } catch (e) {
            contentDiv.innerHTML = '<div class="alert alert-danger">Error parsing communication data.</div>';
            console.error(e);
        }

        var myModal = new bootstrap.Modal(document.getElementById('commTrackModal'));
        myModal.show();
    }

    function openAuditTrailModal(appId) {
        var dataEl = document.getElementById('audit-data-' + appId);
        var contentDiv = document.getElementById('auditTrailContent');
        
        if (!dataEl) {
            contentDiv.innerHTML = '<div class="alert alert-danger" style="margin:0;">Audit data not found.</div>';
            var myModal = new bootstrap.Modal(document.getElementById('auditTrailModal'));
            myModal.show();
            return;
        }

        try {
            var movements = JSON.parse(dataEl.innerHTML);
            if (movements.length === 0) {
                contentDiv.innerHTML = '<div style="text-align: center; padding: 40px 0; color: #64748b;"><i class="fa-solid fa-folder-open fa-3x mb-3" style="opacity: 0.5;"></i><h4>No Movements Yet</h4><p>This application hasn\'t been processed or moved yet.</p></div>';
            } else {
                var html = '<div class="audit-timeline">';
                movements.forEach(function(m) {
                    var actionType = m.action_type || 'forwarded';
                    var actionColor = '#3b82f6';
                    var actionIcon = 'fa-arrow-right';
                    
                    if (actionType === 'send_back' || actionType === 'sent_back') {
                        actionColor = '#f59e0b';
                        actionIcon = 'fa-reply';
                    } else if (actionType === 'reject' || actionType === 'rejected') {
                        actionColor = '#ef4444';
                        actionIcon = 'fa-xmark';
                    } else if (actionType === 'approve' || actionType === 'approved') {
                        actionColor = '#10b981';
                        actionIcon = 'fa-check';
                    } else if (actionType === 'completed') {
                        actionColor = '#8b5cf6';
                        actionIcon = 'fa-flag-checkered';
                    }

                    var fromName = m.from_user ? m.from_user.name : (m.from_role ? m.from_role.name : 'System/Initiator');
                    var toName = m.to_user ? m.to_user.name : (m.to_role ? m.to_role.name : 'Unassigned/End');
                    var dateStr = new Date(m.created_at).toLocaleString('en-IN', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit', hour12:true});

                    var notes = (m.remarks && m.remarks.trim() !== '') ? m.remarks : '<em style="color:#94a3b8;">No remarks provided.</em>';

                    html += '<div class="audit-timeline-item" onclick="this.classList.toggle(\'active\')">';
                    html += '<div class="audit-timeline-marker" style="background: ' + actionColor + '"><i class="fa-solid ' + actionIcon + '"></i></div>';
                    html += '<div class="audit-timeline-content">';
                    html += '<div class="audit-timeline-header">';
                    html += '<div class="audit-timeline-action" style="color: ' + actionColor + '">' + actionType.replace(/_/g, ' ') + '</div>';
                    html += '<div class="audit-timeline-date">' + dateStr + '</div>';
                    html += '</div>';
                    html += '<div class="audit-timeline-route">';
                    html += '<strong style="color:#1e293b;">' + fromName + '</strong> <i class="fa-solid fa-arrow-right-long"></i> <strong style="color:#1e293b;">' + toName + '</strong>';
                    html += '</div>';
                    html += '<div class="audit-timeline-notes" onclick="event.stopPropagation()">';
                    html += '<strong style="display:block; margin-bottom:6px; color:#64748b; font-size:12px; text-transform:uppercase;"><i class="fa-solid fa-comment-dots" style="margin-right:4px;"></i> Movement Notes & Remarks</strong>';
                    html += '<div style="line-height:1.6;">' + notes + '</div>';
                    html += '</div>';
                    html += '<div class="click-hint"><i class="fa-solid fa-hand-pointer"></i> Click to view notes</div>';
                    html += '</div>';
                    html += '</div>';
                });
                html += '</div>';
                contentDiv.innerHTML = html;
            }
        } catch (e) {
            contentDiv.innerHTML = '<div class="alert alert-danger">Error parsing audit data.</div>';
            console.error(e);
        }

        var myModal = new bootstrap.Modal(document.getElementById('auditTrailModal'));
        myModal.show();
    }

    function openCorrespondenceModal(appId) {
        var dataEl = document.getElementById('corr-data-' + appId);
        var contentDiv = document.getElementById('correspondenceContent');
        
        if (!dataEl) {
            contentDiv.innerHTML = '<div class="alert alert-danger" style="margin:0;">Correspondence data not found.</div>';
            var myModal = new bootstrap.Modal(document.getElementById('correspondenceModal'));
            myModal.show();
            return;
        }

        try {
            var correspondences = JSON.parse(dataEl.innerHTML);
            if (correspondences.length === 0) {
                contentDiv.innerHTML = '<div style="text-align: center; padding: 40px 0; color: #92400e;"><i class="fa-solid fa-file-invoice fa-3x mb-3" style="opacity: 0.5;"></i><h4>No Correspondences</h4><p>No office correspondence has been generated for this application.</p></div>';
            } else {
                var html = '<div class="audit-timeline">';
                correspondences.forEach(function(c) {
                    var typeLabel = c.type === 'LT' ? 'LETTER' : (c.type === 'OO' ? 'OFFICE ORDER' : 'OFFICE DRAFT');
                    var statusColor = c.status === 'published' ? '#10b981' : '#f59e0b';
                    var dateStr = new Date(c.created_at).toLocaleString('en-IN', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit', hour12:true});
                    var generatedBy = c.generated_by ? c.generated_by.name : 'Unknown';

                    html += '<div class="audit-timeline-item" onclick="this.classList.toggle(\'active\')">';
                    html += '<div class="audit-timeline-marker" style="background: #d97706;"><i class="fa-solid fa-file-invoice"></i></div>';
                    html += '<div class="audit-timeline-content">';
                    html += '<div class="audit-timeline-header">';
                    html += '<div class="audit-timeline-action" style="color: #d97706;">' + typeLabel + '</div>';
                    html += '<div class="audit-timeline-date">' + dateStr + '</div>';
                    html += '</div>';
                    html += '<div class="audit-timeline-route">';
                    html += '<strong style="color:#1e293b;">Ref No: ' + c.reference_number + '</strong> <span style="color:' + statusColor + '; font-weight:600; font-size:11px; padding:2px 6px; background:#fef3c7; border-radius:4px; margin-left:auto;">' + (c.status || '').toUpperCase() + '</span>';
                    html += '</div>';
                    html += '<div style="margin-top:8px; font-size:13px; color:#334155;"><strong>Subject:</strong> ' + (c.subject || 'No Subject') + '</div>';
                    html += '<div style="margin-top:4px; font-size:12px; color:#64748b;"><strong>Generated By:</strong> ' + generatedBy + '</div>';
                    html += '<div class="audit-timeline-notes" onclick="event.stopPropagation()">';
                    html += '<div style="line-height:1.6; max-height:300px; overflow-y:auto; background:#fffbeb; padding:15px; border-radius:6px; border:1px solid #fde68a;">' + (c.content || '') + '</div>';
                    html += '</div>';
                    html += '<div class="click-hint"><i class="fa-solid fa-hand-pointer"></i> Click to view content</div>';
                    html += '</div>';
                    html += '</div>';
                });
                html += '</div>';
                contentDiv.innerHTML = html;
            }
        } catch (e) {
            contentDiv.innerHTML = '<div class="alert alert-danger">Error parsing correspondence data.</div>';
            console.error(e);
        }

        var myModal = new bootstrap.Modal(document.getElementById('correspondenceModal'));
        myModal.show();
    }

    function openBypassModal(appId) {
        var dataEl = document.getElementById('bypass-data-' + appId);
        var contentDiv = document.getElementById('bypassContent');
        
        if (!dataEl) {
            contentDiv.innerHTML = '<div class="alert alert-danger" style="margin:0;">Bypass data not found.</div>';
            var myModal = new bootstrap.Modal(document.getElementById('bypassModal'));
            myModal.show();
            return;
        }

        try {
            var bypassRequests = JSON.parse(dataEl.innerHTML);
            if (bypassRequests.length === 0) {
                contentDiv.innerHTML = '<div style="text-align: center; padding: 40px 0; color: #9d174d;"><i class="fa-solid fa-forward-step fa-3x mb-3" style="opacity: 0.5;"></i><h4>No Bypass Requests</h4><p>No workflow bypass has been requested for this application.</p></div>';
            } else {
                var html = '<div class="audit-timeline">';
                bypassRequests.forEach(function(b) {
                    var statusColor = b.status === 'approved' ? '#10b981' : (b.status === 'rejected' ? '#ef4444' : '#f59e0b');
                    if (b.status === 'used') statusColor = '#8b5cf6'; // Purple for used
                    var dateStr = new Date(b.created_at).toLocaleString('en-IN', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit', hour12:true});
                    
                    var reqBy = b.requested_by ? b.requested_by.name : 'Unknown';
                    var target = b.target_user ? b.target_user.name : (b.target_role ? b.target_role.name : 'Unknown');

                    html += '<div class="audit-timeline-item" onclick="this.classList.toggle(\'active\')">';
                    html += '<div class="audit-timeline-marker" style="background: #db2777;"><i class="fa-solid fa-forward-step"></i></div>';
                    html += '<div class="audit-timeline-content">';
                    html += '<div class="audit-timeline-header">';
                    html += '<div class="audit-timeline-action" style="color: #db2777;">BYPASS REQUEST</div>';
                    html += '<div class="audit-timeline-date">' + dateStr + '</div>';
                    html += '</div>';
                    html += '<div class="audit-timeline-route">';
                    html += '<strong style="color:#1e293b;">Req By: ' + reqBy + '</strong> <i class="fa-solid fa-arrow-right-long mx-2"></i> <strong style="color:#1e293b;">Target: ' + target + '</strong>';
                    html += '<span style="color:' + statusColor + '; font-weight:600; font-size:11px; padding:2px 6px; background:#fce7f3; border-radius:4px; margin-left:auto;">' + (b.status || '').toUpperCase() + '</span>';
                    html += '</div>';
                    html += '<div class="audit-timeline-notes" onclick="event.stopPropagation()">';
                    html += '<strong style="display:block; margin-bottom:6px; color:#64748b; font-size:12px; text-transform:uppercase;"><i class="fa-solid fa-comment-dots" style="margin-right:4px;"></i> Reason for Bypass</strong>';
                    html += '<div style="line-height:1.6;">' + (b.reason || '<em style="color:#94a3b8;">No reason provided.</em>') + '</div>';
                    if (b.admin_remarks) {
                        html += '<strong style="display:block; margin-top:10px; margin-bottom:6px; color:#64748b; font-size:12px; text-transform:uppercase;"><i class="fa-solid fa-reply" style="margin-right:4px;"></i> Admin Remarks</strong>';
                        html += '<div style="line-height:1.6; color:#0f172a; background:#f1f5f9; padding:8px; border-radius:4px;">' + b.admin_remarks + '</div>';
                    }
                    html += '</div>';
                    html += '<div class="click-hint"><i class="fa-solid fa-hand-pointer"></i> Click to view details</div>';
                    html += '</div>';
                    html += '</div>';
                });
                html += '</div>';
                contentDiv.innerHTML = html;
            }
        } catch (e) {
            contentDiv.innerHTML = '<div class="alert alert-danger">Error parsing bypass data.</div>';
            console.error(e);
        }

        var myModal = new bootstrap.Modal(document.getElementById('bypassModal'));
        myModal.show();
    }
    function sendCredentialMail(allotteeId, btnElement) {
        if (!confirm('Are you sure you want to generate a new password and send the portal credentials email to this allottee?')) {
            return;
        }

        var originalHtml = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        btnElement.disabled = true;

        fetch('{{ url("admin/allottees") }}/' + allotteeId + '/send-credentials', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            btnElement.innerHTML = originalHtml;
            btnElement.disabled = false;
            
            if (data.success) {
                alert(data.message || 'Credentials sent successfully!');
            } else {
                alert('Error: ' + (data.message || 'Something went wrong.'));
            }
        })
        .catch(err => {
            console.error(err);
            btnElement.innerHTML = originalHtml;
            btnElement.disabled = false;
            alert('An error occurred while sending the email.');
        });
    }
</script>
@endsection
