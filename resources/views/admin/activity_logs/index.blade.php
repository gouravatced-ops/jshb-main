@extends('layouts.main')

@section('title', 'Activity Logs | Admin | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">System Activity Logs</div>
            <div class="card-subtitle">Comprehensive audit trail of internal administrative actions, events, and model modifications</div>
        </div>
        <div class="card-actions">
            @php
                $hasFilters = request('search') || request('event');
            @endphp
            <button type="button" id="toggleFilterBtn" class="btn-sky" style="cursor: pointer;">
                <i class="fa-solid fa-filter"></i>
                <span id="filterBtnText">{{ $hasFilters ? 'Hide Filter' : 'Filter' }}</span>
                @if($hasFilters)
                    <span style="background: #f59e0b; color: #fff; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 999px;">Active</span>
                @endif
            </button>
            <a class="btn-pink" href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Metrics Cards Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin: 20px 24px 16px;">
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Total Logs</div>
                <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ number_format($totalCount ?? $activities->total()) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #166534; font-weight: 700; letter-spacing: 0.5px;">Created Records</div>
                <div style="font-size: 20px; font-weight: 800; color: #15803d; margin-top: 2px;">{{ number_format($createdCount ?? 0) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-plus-circle"></i>
            </div>
        </div>

        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #92400e; font-weight: 700; letter-spacing: 0.5px;">Updated Records</div>
                <div style="font-size: 20px; font-weight: 800; color: #b45309; margin-top: 2px;">{{ number_format($updatedCount ?? 0) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
        </div>

        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #991b1b; font-weight: 700; letter-spacing: 0.5px;">Deleted Records</div>
                <div style="font-size: 20px; font-weight: 800; color: #b91c1c; margin-top: 2px;">{{ number_format($deletedCount ?? 0) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-trash-can"></i>
            </div>
        </div>
    </div>

    <!-- Collapsible Filter Bar -->
    <div id="filterBox" style="display: {{ $hasFilters ? 'block' : 'none' }}; margin: 0 24px 18px; padding: 14px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
        <form action="{{ route('admin.activity-logs') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <!-- Search Field -->
            <div style="flex: 1; min-width: 240px; position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description, log name, model..." autocomplete="off"
                    style="width: 100%; padding: 8px 12px 8px 34px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
            </div>

            <!-- Event Dropdown -->
            <div style="min-width: 170px;">
                <select name="event" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none; cursor: pointer;">
                    <option value="">All Events</option>
                    <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>

            <!-- Filter & Reset Buttons -->
            <div style="display: flex; gap: 8px; align-items: center;">
                <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 13px; border-radius: 6px; border: none; background: #0ea5e9; color: #fff; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if($hasFilters)
                    <a href="{{ route('admin.activity-logs') }}" class="btn-secondary" style="padding: 8px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #64748b; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
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
                        <a href="{{ route('admin.activity-logs', array_merge(request()->except('search'))) }}" style="color: #0284c7; text-decoration: none;">&times;</a>
                    </span>
                @endif
                @if(request('event'))
                    <span style="font-size: 12px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        Event: {{ ucfirst(request('event')) }}
                        <a href="{{ route('admin.activity-logs', array_merge(request()->except('event'))) }}" style="color: #0284c7; text-decoration: none;">&times;</a>
                    </span>
                @endif
                <a href="{{ route('admin.activity-logs') }}" style="font-size: 12px; color: #dc2626; text-decoration: underline; margin-left: 6px;">Clear all</a>
            </div>
        @endif
    </div>

    <!-- Activity Table -->
    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Date & Time</th>
                    <th>User / Official</th>
                    <th>Event</th>
                    <th>Subject Model</th>
                    <th style="width: 42%;">Changes / Properties (Audit)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $log)
                    <tr>
                        <td>{{ $activities->firstItem() + $loop->index }}</td>
                        <td>
                            <div style="font-size: 13px; font-weight: 600; color: #334155;">
                                {{ $log->created_at->format('d M Y') }}
                            </div>
                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                <i class="fa-regular fa-clock" style="font-size: 10px;"></i>
                                {{ $log->created_at->format('h:i A') }}
                            </div>
                        </td>
                        <td>
                            @if($log->causer)
                                <div class="table-name" style="font-weight: 700; color: #0f172a; font-size: 13.5px;">
                                    {{ $log->causer->name }}
                                </div>
                                <div class="table-email" style="font-size: 11.5px; color: #64748b; margin-top: 1px;">
                                    <i class="fa-regular fa-envelope" style="font-size: 10px; margin-right: 3px;"></i>
                                    {{ $log->causer->email }}
                                </div>
                            @else
                                <span class="badge" style="background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; font-size: 11px; padding: 2px 7px; border-radius: 4px; font-weight: 600;">
                                    <i class="fa-solid fa-server" style="font-size: 10px; margin-right: 3px;"></i> System / Guest
                                </span>
                            @endif
                        </td>
                        <td>
                            @php
                                $eventClass = match($log->event) {
                                    'created' => 'active',
                                    'updated' => 'pending',
                                    'deleted' => 'inactive',
                                    default => 'active'
                                };
                            @endphp
                            <span class="badge-status {{ $eventClass }}">
                                <i class="fa-solid fa-circle"></i>
                                {{ ucfirst($log->event) }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #1e293b; font-size: 13px;">
                                {{ class_basename($log->subject_type) ?? 'N/A' }}
                            </div>
                            @if($log->subject_id)
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px; font-family: monospace;">
                                    ID: #{{ $log->subject_id }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($log->properties && count($log->properties) > 0)
                                <details style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 10px;">
                                    <summary style="font-size: 12px; font-weight: 600; color: #0284c7; cursor: pointer; outline: none;">
                                        <i class="fa-solid fa-code" style="font-size: 11px; margin-right: 4px;"></i> View {{ count($log->properties) }} property payload(s)
                                    </summary>
                                    <pre style="margin-top: 8px; margin-bottom: 0; font-family: monospace; font-size: 11.5px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 8px; max-height: 140px; overflow-y: auto; color: #0f172a; white-space: pre-wrap; word-break: break-all;">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                </details>
                            @else
                                <span style="font-size: 12px; color: #94a3b8; font-style: italic;">No specific properties recorded</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px 24px; color: #64748b;">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 12px;">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <div style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">No Activity Logs Found</div>
                            <div style="font-size: 13px; max-width: 420px; margin: 0 auto 16px;">
                                @if($hasFilters)
                                    No activity records match your active search filters. Try clearing or relaxing the filters.
                                @else
                                    No administrative activity events have been recorded yet.
                                @endif
                            </div>
                            @if($hasFilters)
                                <a href="{{ route('admin.activity-logs') }}" class="btn-secondary" style="padding: 6px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #0284c7; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
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
    @if($activities->hasPages())
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-top: 1px solid #f1f5f9; background: #fff; flex-wrap: wrap; gap: 12px;">
            <div style="font-size: 13px; color: #64748b;">
                Showing <strong>{{ $activities->firstItem() }}</strong> to <strong>{{ $activities->lastItem() }}</strong> of <strong>{{ $activities->total() }}</strong> log entries
            </div>
            <div>
                {{ $activities->links('pagination::bootstrap-5') }}
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
