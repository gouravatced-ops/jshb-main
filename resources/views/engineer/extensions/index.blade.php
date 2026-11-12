@extends('layouts.main')

@section('title', 'Extension Requests | Engineer | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">My Extension Requests</div>
            <div class="card-subtitle">Track, filter, and audit timeline extension requests submitted for application reviews</div>
        </div>
        <div class="card-actions">
            @php
                $hasFilters = request('search') || request('status');
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
            <a class="btn-primary" href="{{ route('engineer.applications.history') }}" style="background: #0f172a; border-color: #0f172a; color: #fff;">
                <i class="fa-solid fa-clock-rotate-left"></i> Application History
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

        .eng-req-pill {
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
                <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Total Requests</div>
                <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ number_format($totalCount ?? $requests->total()) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-calendar-plus"></i>
            </div>
        </div>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #166534; font-weight: 700; letter-spacing: 0.5px;">Approved Extensions</div>
                <div style="font-size: 20px; font-weight: 800; color: #15803d; margin-top: 2px;">{{ number_format($approvedCount ?? 0) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #92400e; font-weight: 700; letter-spacing: 0.5px;">Pending Approval</div>
                <div style="font-size: 20px; font-weight: 800; color: #b45309; margin-top: 2px;">{{ number_format($pendingCount ?? 0) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #991b1b; font-weight: 700; letter-spacing: 0.5px;">Rejected Requests</div>
                <div style="font-size: 20px; font-weight: 800; color: #b91c1c; margin-top: 2px;">{{ number_format($rejectedCount ?? 0) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-ban"></i>
            </div>
        </div>
    </div>

    <!-- Filter Bar: Search & Status -->
    <div id="filterBox" style="display: {{ $hasFilters ? 'block' : 'none' }}; margin: 0 24px 18px; padding: 16px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
        <form action="{{ route('engineer.extensions.index') }}" method="GET">
            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <!-- Search Input -->
                <div style="flex: 1; min-width: 250px; position: relative;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by application number or reason..."
                        style="width: 100%; padding: 8px 12px 8px 34px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
                </div>

                <!-- Status Select -->
                <div style="min-width: 180px;">
                    <select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Filter & Reset Buttons -->
                <div style="display: flex; gap: 8px; align-items: center;">
                    <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 13px; border-radius: 6px; border: none; background: #0ea5e9; color: #fff; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
                        <i class="fa-solid fa-filter"></i> Apply Filter
                    </button>
                    @if($hasFilters)
                        <a href="{{ route('engineer.extensions.index') }}" class="btn-secondary" style="padding: 8px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #64748b; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        @if($hasFilters)
            <div style="display: flex; align-items: center; gap: 8px; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #e2e8f0; flex-wrap: wrap;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600;">Active Filters:</span>
                @if(request('search'))
                    <span style="font-size: 12px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px;">Search: "{{ request('search') }}"</span>
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
                    <th>Request ID</th>
                    <th>Application No</th>
                    <th>Reason for Extension</th>
                    <th>Submitted At</th>
                    <th>Status & Days</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td>{{ $requests->firstItem() + $loop->index }}</td>
                    <td>
                        <span class="eng-req-pill">#REQ-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td>
                        @if($req->application)
                        <a href="{{ route('engineer.applications.show', $req->application) }}" style="text-decoration: none; font-weight: 700; color: #0284c7;">
                            <i class="fa-solid fa-file-lines me-1"></i> {{ $req->application->application_no }}
                        </a>
                        <div style="font-size: 11px; color: #64748b; text-transform: capitalize; margin-top: 2px;">
                            {{ str_replace('_', ' ', $req->application->application_type ?? '') }}
                        </div>
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 13px; line-height: 1.5; color: #334155; max-width: 380px;">
                            {!! Str::limit(strip_tags($req->request_reason), 90) !!}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #1e293b;">{{ $req->created_at->format('d M Y') }}</div>
                        <small class="text-muted">{{ $req->created_at->format('h:i A') }}</small>
                    </td>
                    <td>
                        @if($req->status === 'approved')
                            <span class="badge-status active"><i class="fa-solid fa-check-circle"></i> Approved</span>
                            @if($req->extension_days)
                                <div style="font-size: 11.5px; font-weight: 700; color: #15803d; margin-top: 2px;">
                                    +{{ $req->extension_days }} Day(s) Extended
                                </div>
                            @endif
                        @elseif($req->status === 'rejected')
                            <span class="badge-status inactive"><i class="fa-solid fa-ban"></i> Rejected</span>
                        @else
                            <span class="badge-status" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a;">
                                <i class="fa-solid fa-clock"></i> Pending Approval
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('engineer.extensions.show', \Illuminate\Support\Facades\Crypt::encryptString($req->id)) }}" class="btn-action-pill btn-action-view" title="View Request Details">
                            <i class="fa-solid fa-eye"></i> View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">
                        <div style="font-size: 40px; color: #94a3b8; margin-bottom: 12px;">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h5 style="color: #1e293b; font-weight: 700; margin-bottom: 4px;">No Extension Requests Found</h5>
                        <p class="text-muted" style="font-size: 13px; margin: 0;">You haven't submitted any extension requests yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    @if($requests->hasPages())
    <div class="table-pagination">
        <span>
            Showing <strong>{{ $requests->firstItem() }}</strong> to
            <strong>{{ $requests->lastItem() }}</strong> of <strong>{{ $requests->total() }}</strong>
            requests
        </span>
        <div class="pagination-btns">
            @if ($requests->onFirstPage())
            <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                    class="fa-solid fa-chevron-left"></i></span>
            @else
            <a class="pag-btn" href="{{ $requests->previousPageUrl() }}"><i
                    class="fa-solid fa-chevron-left"></i></a>
            @endif

            @foreach ($requests->getUrlRange(1, $requests->lastPage()) as $page => $url)
            <a class="pag-btn {{ $page === $requests->currentPage() ? 'active' : '' }}"
                href="{{ $url }}">{{ $page }}</a>
            @endforeach

            @if ($requests->hasMorePages())
            <a class="pag-btn" href="{{ $requests->nextPageUrl() }}"><i
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
