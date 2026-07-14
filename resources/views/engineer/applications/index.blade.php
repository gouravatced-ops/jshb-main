@extends('layouts.main')

@section('title', 'Pending Applications | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">Pending Applications</div>
            <div class="card-subtitle">All applications currently awaiting your review</div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>Application No</th>
                    <th>Type</th>
                    <th>Allottee Details</th>
                    <th>Property / Allotment</th>
                    <th>Created Date</th>
                    <th>Priority</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td><strong>{{ $app->application_no }}</strong></td>
                    <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $app->application_type) }}</td>
                    <td>
                        <div class="table-user">
                            <div>
                                <div class="table-name">{{ trim(($app->prefix ?? '') . ' ' . ($app->allottee_name ?? '') . ' ' . ($app->allottee_middle_name ?? '') . ' ' . ($app->allottee_surname ?? '')) ?: '-' }}</div>
                                <div class="table-subtitle">{{ trim(($app->allottee_prefix_hindi ?? '') . ' ' . ($app->allottee_name_hindi ?? '') . ' ' . ($app->allottee_middle_hindi ?? '') . ' ' . ($app->allottee_surname_hindi ?? '')) ?: '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>Property: {{ $app->property_number ?? 'N/A' }}</div>
                        <small class="text-muted">Allotment: {{ $app->allotment_no ?? 'N/A' }}</small>
                    </td>
                    <td>
                        <div>{{ $app->created_date_formatted }}</div>
                        <small class="text-muted">{{ $app->days_pending }} days pending</small>
                    </td>
                    <td>
                        @if($app->priority === 'Urgent' || $app->priority === 'Overdue')
                            <span class="badge-status inactive"><i class="fa-solid fa-circle"></i> {{ $app->priority }}</span>
                        @else
                            <span class="badge-status active"><i class="fa-solid fa-circle"></i> {{ $app->priority }}</span>
                        @endif
                        
                        @if($app->total_movements > 0)
                        <div style="margin-top: 5px; font-size: 11px; color: #666;">
                            {{ $app->total_movements }} movements
                        </div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('engineer.applications.show', $app) }}" class="btn-primary" style="padding: 6px 12px; font-size: 13px; text-decoration: none;">Review Application</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                        No pending applications found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
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
@endsection
