@extends('layouts.main')

@section('title', 'My Notifications | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">My Notifications</div>
            <div class="card-subtitle">Personal alert center, workflow updates, and official correspondence inbox</div>
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
            <button type="button" class="btn-pink" onclick="markAllNotificationsRead(); setTimeout(() => location.reload(), 500);" style="cursor: pointer;">
                <i class="fa-solid fa-envelope-open-text"></i> Mark All as Read
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin: 20px 24px 0;">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Metrics Cards Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin: 20px 24px 16px;">
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Total Alerts</div>
                <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ number_format($totalCount ?? $notifications->total()) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-bell"></i>
            </div>
        </div>

        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #92400e; font-weight: 700; letter-spacing: 0.5px;">Unread Messages</div>
                <div style="font-size: 20px; font-weight: 800; color: #b45309; margin-top: 2px;">{{ number_format($unreadCount ?? 0) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
        </div>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #166534; font-weight: 700; letter-spacing: 0.5px;">Read / Archived</div>
                <div style="font-size: 20px; font-weight: 800; color: #15803d; margin-top: 2px;">{{ number_format($readCount ?? 0) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #1e40af; font-weight: 700; letter-spacing: 0.5px;">Sync Engine</div>
                <div style="font-size: 18px; font-weight: 800; color: #1d4ed8; margin-top: 2px;">Live Updates</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-tower-broadcast"></i>
            </div>
        </div>
    </div>

    <!-- Collapsible Filter Bar -->
    <div id="filterBox" style="display: {{ $hasFilters ? 'block' : 'none' }}; margin: 0 24px 18px; padding: 14px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
        <form action="{{ route('my-notifications.index') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <!-- Search Field -->
            <div style="flex: 1; min-width: 240px; position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search subject, message content..." autocomplete="off"
                    style="width: 100%; padding: 8px 12px 8px 34px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
            </div>

            <!-- Read Status Dropdown -->
            <div style="min-width: 160px;">
                <select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none; cursor: pointer;">
                    <option value="">All Statuses</option>
                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread Only</option>
                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read Only</option>
                </select>
            </div>

            <!-- Filter & Reset Buttons -->
            <div style="display: flex; gap: 8px; align-items: center;">
                <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 13px; border-radius: 6px; border: none; background: #0ea5e9; color: #fff; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if($hasFilters)
                    <a href="{{ route('my-notifications.index') }}" class="btn-secondary" style="padding: 8px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #64748b; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
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
                        <a href="{{ route('my-notifications.index', array_merge(request()->except('search'))) }}" style="color: #0284c7; text-decoration: none;">&times;</a>
                    </span>
                @endif
                @if(request('status'))
                    <span style="font-size: 12px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        Status: {{ ucfirst(request('status')) }}
                        <a href="{{ route('my-notifications.index', array_merge(request()->except('status'))) }}" style="color: #0284c7; text-decoration: none;">&times;</a>
                    </span>
                @endif
                <a href="{{ route('my-notifications.index') }}" style="font-size: 12px; color: #dc2626; text-decoration: underline; margin-left: 6px;">Clear all</a>
            </div>
        @endif
    </div>

    <!-- Notifications Table -->
    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 60px; text-align: center;">Type</th>
                    <th>Subject & Headline</th>
                    <th>Message Details</th>
                    <th style="width: 160px;">Date & Time</th>
                    <th style="text-align: right; width: 120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notif)
                    <tr style="{{ $notif->is_read ? 'opacity: 0.85;' : 'background: #f8fafc;' }}">
                        <td>{{ $notifications->firstItem() + $loop->index }}</td>
                        <td style="text-align: center;">
                            @if($notif->notification_type == 'success')
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: inline-flex; align-items: center; justify-content: center; font-size: 14px;">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                            @elseif($notif->notification_type == 'warning' || $notif->notification_type == 'document_request')
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #fef3c7; color: #d97706; display: inline-flex; align-items: center; justify-content: center; font-size: 14px;">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </div>
                            @else
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: inline-flex; align-items: center; justify-content: center; font-size: 14px;">
                                    <i class="fa-solid fa-bell"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="table-name" style="font-weight: 700; color: #0f172a; font-size: 13.5px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <span>{{ $notif->subject }}</span>
                                @if(!$notif->is_read)
                                    <span class="badge" style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; font-size: 10px; padding: 1px 6px; border-radius: 4px; font-weight: 700; letter-spacing: 0.5px;">NEW</span>
                                @endif
                            </div>
                            <div class="table-email" style="font-size: 11.5px; color: #64748b; margin-top: 2px;">
                                <i class="fa-solid fa-tag" style="font-size: 10px; margin-right: 3px;"></i>
                                {{ ucfirst(str_replace('_', ' ', $notif->notification_type ?? 'General')) }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 13px; color: #334155; line-height: 1.5; max-width: 520px;">
                                {{ $notif->message }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 13px; font-weight: 600; color: #334155;">
                                {{ $notif->created_at ? $notif->created_at->format('d M Y') : 'N/A' }}
                            </div>
                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                <i class="fa-regular fa-clock" style="font-size: 10px;"></i>
                                {{ $notif->created_at ? $notif->created_at->format('h:i A') : '' }}
                            </div>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            @if($notif->link)
                                <a href="{{ $notif->link }}" class="btn-action-pill btn-action-view" style="text-decoration: none;">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    <span>Open</span>
                                </a>
                            @else
                                <span style="font-size: 12px; color: #94a3b8; font-style: italic;">Informational</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px 24px; color: #64748b;">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 12px;">
                                <i class="fa-solid fa-bell-slash"></i>
                            </div>
                            <div style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">No Notifications</div>
                            <div style="font-size: 13px; max-width: 420px; margin: 0 auto 16px;">
                                @if($hasFilters)
                                    No notifications match your active search filters.
                                @else
                                    You have no notifications or alerts in your inbox right now.
                                @endif
                            </div>
                            @if($hasFilters)
                                <a href="{{ route('my-notifications.index') }}" class="btn-secondary" style="padding: 6px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #0284c7; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
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
    @if($notifications->hasPages())
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-top: 1px solid #f1f5f9; background: #fff; flex-wrap: wrap; gap: 12px;">
            <div style="font-size: 13px; color: #64748b;">
                Showing <strong>{{ $notifications->firstItem() }}</strong> to <strong>{{ $notifications->lastItem() }}</strong> of <strong>{{ $notifications->total() }}</strong> notifications
            </div>
            <div>
                {{ $notifications->links('pagination::bootstrap-5') }}
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
