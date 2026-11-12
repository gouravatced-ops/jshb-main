@extends('layouts.main')

@section('title', 'Application History | Engineer | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">Application History</div>
            <div class="card-subtitle">Complete audit trail of all applications processed or handled by your desk</div>
        </div>
        <div class="card-actions">
            @php
                $hasFilters = request('application_no') || request('status') || request('created_date_from') || request('created_date_to') || request('property_number') || request('sub_division_id');
            @endphp
            <button type="button" id="toggleFilterBtn" class="btn-sky" style="cursor: pointer;">
                <i class="fa-solid fa-filter"></i>
                <span id="filterBtnText">{{ $hasFilters ? 'Hide Filter' : 'Filter' }}</span>
                @if($hasFilters)
                    <span style="background: #f59e0b; color: #fff; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 999px;">Active</span>
                @endif
            </button>
            <a class="btn-pink" href="{{ route('engineer.applications.index') }}">
                <i class="fa-solid fa-inbox"></i> Pending Applications
            </a>
            <a class="btn-primary" href="{{ route('engineer.extensions.index') }}" style="background: #0f172a; border-color: #0f172a; color: #fff;">
                <i class="fa-solid fa-calendar-plus"></i> Extensions
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

        .btn-action-pill.btn-action-view {
            background: #eff6ff !important;
            color: #1d4ed8 !important;
            border-color: #bfdbfe !important;
        }
        .btn-action-pill.btn-action-view:hover {
            background: #dbeafe !important;
            color: #1e40af !important;
            border-color: #93c5fd !important;
            box-shadow: 0 2px 5px rgba(37, 99, 235, 0.18) !important;
            transform: translateY(-1px);
        }

        .eng-app-pill {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 700;
            color: #0f172a;
            font-size: 13px;
        }
    </style>

    <!-- Metrics Cards Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin: 20px 24px 16px;">
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Total Processed History</div>
                <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ number_format($applications->total()) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #92400e; font-weight: 700; letter-spacing: 0.5px;">On Current Page</div>
                <div style="font-size: 20px; font-weight: 800; color: #b45309; margin-top: 2px;">{{ number_format($applications->count()) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-file-waveform"></i>
            </div>
        </div>

        <!-- <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #166534; font-weight: 700; letter-spacing: 0.5px;">Sub Divisions</div>
                <div style="font-size: 20px; font-weight: 800; color: #15803d; margin-top: 2px;">{{ count($subDivisions) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-building"></i>
            </div>
        </div> -->
    </div>

    <!-- Filter Bar: Collapsible -->
    <div id="filterBox" style="display: {{ $hasFilters ? 'block' : 'none' }}; margin: 0 24px 18px; padding: 16px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
        <form action="{{ route('engineer.applications.history') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 14px;">
                <!-- Sub Division -->
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">Sub Division</label>
                    <select name="sub_division_id" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
                        <option value="">All Sub Divisions</option>
                        @foreach($subDivisions as $sd)
                        <option value="{{ $sd->id }}" {{ request('sub_division_id') == $sd->id ? 'selected' : '' }}>{{ $sd->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Application No -->
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">Application No</label>
                    <input type="text" name="application_no" value="{{ request('application_no') }}" placeholder="e.g. APP-..." style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
                </div>

                <!-- Property Number -->
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">Property Number</label>
                    <input type="text" name="property_number" value="{{ request('property_number') }}" placeholder="e.g. B-104..." style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
                </div>

                <!-- Status -->
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">Status</label>
                    <select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="forwarded" {{ request('status') == 'forwarded' ? 'selected' : '' }}>Forwarded</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">Created Date (From)</label>
                    <input type="date" name="created_date_from" value="{{ request('created_date_from') }}" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
                </div>

                <!-- Date To -->
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">Created Date (To)</label>
                    <input type="date" name="created_date_to" value="{{ request('created_date_to') }}" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
                </div>
            </div>

            <div style="display: flex; gap: 8px; align-items: center; justify-content: flex-end;">
                <button type="submit" class="btn-primary" style="padding: 7px 16px; font-size: 13px; border-radius: 6px; border: none; background: #0ea5e9; color: #fff; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-filter"></i> Apply Filter
                </button>
                @if($hasFilters)
                    <a href="{{ route('engineer.applications.history') }}" class="btn-secondary" style="padding: 7px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #64748b; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        @if($hasFilters)
            <div style="display: flex; align-items: center; gap: 8px; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #e2e8f0; flex-wrap: wrap;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600;">Active Filters:</span>
                @if(request('application_no'))
                    <span style="font-size: 12px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px;">App No: "{{ request('application_no') }}"</span>
                @endif
                @if(request('property_number'))
                    <span style="font-size: 12px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px;">Property: "{{ request('property_number') }}"</span>
                @endif
                @if(request('status'))
                    <span style="font-size: 12px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px;">Status: {{ ucfirst(request('status')) }}</span>
                @endif
            </div>
        @endif
    </div>

    <!-- Table Section -->
    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Application No</th>
                    <th>Type</th>
                    <th>Allottee Details</th>
                    <th>Property / Allotment</th>
                    <th>Created Date</th>
                    <th>Current Status</th>
                    <th>Priority</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td>{{ $applications->firstItem() + $loop->index }}</td>
                    <td>
                        <span class="eng-app-pill">{{ $app->application_no }}</span>
                    </td>
                    <td>
                        <span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; font-size: 11px; padding: 3px 8px; border-radius: 4px; font-weight: 600; text-transform: capitalize;">
                            {{ str_replace('_', ' ', $app->application_type) }}
                        </span>
                    </td>
                    <td>
                        @php
                            $allottee = $app->allottee;
                            $allotteeName = $allottee ? trim(($allottee->prefix ?? '') . ' ' . ($allottee->allottee_name ?? '') . ' ' . ($allottee->allottee_middle_name ?? '') . ' ' . ($allottee->allottee_surname ?? '')) : '-';
                            $hindiName = $allottee ? trim(($allottee->allottee_prefix_hindi ?? '') . ' ' . ($allottee->allottee_name_hindi ?? '') . ' ' . ($allottee->allottee_middle_hindi ?? '') . ' ' . ($allottee->allottee_surname_hindi ?? '')) : '';
                        @endphp
                        <div class="table-user">
                            <div>
                                <div class="table-name" style="font-weight: 700; color: #0f172a;">{{ $allotteeName ?: '-' }}</div>
                                @if(!empty($hindiName))
                                <div class="table-subtitle" style="margin-top: 2px;">
                                    <span style="font-family: 'KrutiDev', sans-serif, Arial; font-size: 15px; color: #475569;">{{ $hindiName }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #1e293b;"><i class="fa-solid fa-home text-muted me-1"></i> {{ $app->allottee->property_number ?? 'N/A' }}</div>
                        <small class="text-muted">Allotment: {{ $app->allottee->allotment_no ?? 'N/A' }}</small>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #1e293b;">{{ $app->created_date_formatted }}</div>
                    </td>
                    <td>
                        @php
                            $status = $app->status;
                            $badgeClass = 'inactive';
                            $icon = 'fa-circle';
                            if (in_array($status, ['approved', 'completed'])) {
                                $badgeClass = 'active';
                                $icon = 'fa-check-circle';
                            } elseif ($status === 'in_progress') {
                                $badgeClass = 'active';
                                $icon = 'fa-spinner';
                            } elseif ($status === 'forwarded') {
                                $badgeClass = 'active';
                                $icon = 'fa-arrow-right';
                            } elseif ($status === 'rejected') {
                                $badgeClass = 'inactive';
                                $icon = 'fa-ban';
                            }
                        @endphp
                        <span class="badge-status {{ $badgeClass }}">
                            <i class="fa-solid {{ $icon }}"></i> {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </span>
                    </td>
                    <td>
                        @if($app->priority === 'Urgent' || $app->priority === 'Overdue')
                        <span class="badge-status inactive"><i class="fa-solid fa-circle"></i> {{ $app->priority }}</span>
                        @else
                        <span class="badge-status active"><i class="fa-solid fa-circle"></i> {{ $app->priority }}</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('engineer.applications.show', $app) }}" class="btn-action-pill btn-action-view" title="View Application Details">
                            <i class="fa-solid fa-eye"></i> View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px;">
                        <div style="font-size: 40px; color: #94a3b8; margin-bottom: 12px;">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h5 style="color: #1e293b; font-weight: 700; margin-bottom: 4px;">No History Records Found</h5>
                        <p class="text-muted" style="font-size: 13px; margin: 0;">No applications have been processed by your desk yet matching the criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    @if ($applications->total() > 0)
    <div class="table-pagination">
        <span>
            Showing <strong>{{ $applications->firstItem() }}</strong> to
            <strong>{{ $applications->lastItem() }}</strong> of <strong>{{ $applications->total() }}</strong>
            applications
        </span>
        <div class="pagination-btns">
            @if ($applications->onFirstPage())
            <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                    class="fa-solid fa-chevron-left"></i></span>
            @else
            <a class="pag-btn" href="{{ $applications->previousPageUrl() }}"><i
                    class="fa-solid fa-chevron-left"></i></a>
            @endif

            @foreach ($applications->getUrlRange(1, $applications->lastPage()) as $page => $url)
            <a class="pag-btn {{ $page === $applications->currentPage() ? 'active' : '' }}"
                href="{{ $url }}">{{ $page }}</a>
            @endforeach

            @if ($applications->hasMorePages())
            <a class="pag-btn" href="{{ $applications->nextPageUrl() }}"><i
                    class="fa-solid fa-chevron-right"></i></a>
            @else
            <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                    class="fa-solid fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Toggle Filter JS -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('toggleFilterBtn');
        const filterBox = document.getElementById('filterBox');
        const btnText = document.getElementById('filterBtnText');

        if (toggleBtn && filterBox) {
            toggleBtn.addEventListener('click', function () {
                if (filterBox.style.display === 'none' || filterBox.style.display === '') {
                    filterBox.style.display = 'block';
                    if (btnText) btnText.textContent = 'Hide Filter';
                } else {
                    filterBox.style.display = 'none';
                    if (btnText) btnText.textContent = 'Filter';
                }
            });
        }
    });
</script>
@endsection
