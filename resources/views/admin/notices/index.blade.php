@extends('layouts.main')

@section('title', 'Notices & Announcements | Admin | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">Notices & Announcements</div>
            <div class="card-subtitle">Broadcast and manage official departmental notices, alerts, circulars, and updates</div>
        </div>
        <div class="card-actions">
            @php
                $hasFilters = request('search') || request('notice_type');
            @endphp
            <button type="button" id="toggleFilterBtn" class="btn-sky" style="cursor: pointer;">
                <i class="fa-solid fa-filter"></i>
                <span id="filterBtnText">{{ $hasFilters ? 'Hide Filter' : 'Filter' }}</span>
                @if($hasFilters)
                    <span style="background: #f59e0b; color: #fff; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 999px;">Active</span>
                @endif
            </button>
            <a class="btn-pink" href="{{ route('admin.notices.create') }}">
                <i class="fa-solid fa-plus"></i> Create Notice
            </a>
        </div>
    </div>

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

    <style>
        /* Modern Pill Action Buttons */
        .btn-action-pill {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 5px !important;
            padding: 5px 11px !important;
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

        .btn-action-pill.btn-action-edit {
            background: #f0f9ff !important;
            color: #0284c7 !important;
            border-color: #bae6fd !important;
        }
        .btn-action-pill.btn-action-edit:hover {
            background: #e0f2fe !important;
            color: #0369a1 !important;
            border-color: #7dd3fc !important;
            box-shadow: 0 2px 4px rgba(2, 132, 199, 0.15) !important;
            transform: translateY(-1px);
        }

        .btn-action-pill.btn-action-delete {
            background: #fef2f2 !important;
            color: #dc2626 !important;
            border-color: #fecaca !important;
        }
        .btn-action-pill.btn-action-delete:hover {
            background: #fee2e2 !important;
            color: #b91c1c !important;
            border-color: #fca5a5 !important;
            box-shadow: 0 2px 4px rgba(220, 38, 38, 0.15) !important;
            transform: translateY(-1px);
        }
    </style>

    <!-- Metrics Cards Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin: 20px 24px 16px;">
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Total Notices</div>
                <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ number_format($totalCount ?? $notices->total()) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
        </div>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #1e40af; font-weight: 700; letter-spacing: 0.5px;">Announcements</div>
                <div style="font-size: 20px; font-weight: 800; color: #1d4ed8; margin-top: 2px;">{{ number_format($announcementCount ?? 0) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-newspaper"></i>
            </div>
        </div>

        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #92400e; font-weight: 700; letter-spacing: 0.5px;">Warnings / Alerts</div>
                <div style="font-size: 20px; font-weight: 800; color: #b45309; margin-top: 2px;">{{ number_format($warningCount ?? 0) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #166534; font-weight: 700; letter-spacing: 0.5px;">Info & Updates</div>
                <div style="font-size: 20px; font-weight: 800; color: #15803d; margin-top: 2px;">{{ number_format($infoCount ?? 0) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-circle-info"></i>
            </div>
        </div>
    </div>

    <!-- Collapsible Filter Bar -->
    <div id="filterBox" style="display: {{ $hasFilters ? 'block' : 'none' }}; margin: 0 24px 18px; padding: 14px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
        <form action="{{ route('admin.notices.index') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <!-- Search Field -->
            <div style="flex: 1; min-width: 240px; position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search notice title, keywords, content..." autocomplete="off"
                    style="width: 100%; padding: 8px 12px 8px 34px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
            </div>

            <!-- Notice Type Dropdown -->
            <div style="min-width: 180px;">
                <select name="notice_type" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none; cursor: pointer;">
                    <option value="">All Notice Types</option>
                    <option value="announcement" {{ request('notice_type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                    <option value="warning" {{ request('notice_type') == 'warning' ? 'selected' : '' }}>Warning</option>
                    <option value="info" {{ request('notice_type') == 'info' ? 'selected' : '' }}>Information</option>
                    <option value="new" {{ request('notice_type') == 'new' ? 'selected' : '' }}>New Update</option>
                </select>
            </div>

            <!-- Filter & Reset Buttons -->
            <div style="display: flex; gap: 8px; align-items: center;">
                <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 13px; border-radius: 6px; border: none; background: #0ea5e9; color: #fff; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if($hasFilters)
                    <a href="{{ route('admin.notices.index') }}" class="btn-secondary" style="padding: 8px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #64748b; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
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
                        <a href="{{ route('admin.notices.index', array_merge(request()->except('search'))) }}" style="color: #0284c7; text-decoration: none;">&times;</a>
                    </span>
                @endif
                @if(request('notice_type'))
                    <span style="font-size: 12px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        Type: {{ ucfirst(request('notice_type')) }}
                        <a href="{{ route('admin.notices.index', array_merge(request()->except('notice_type'))) }}" style="color: #0284c7; text-decoration: none;">&times;</a>
                    </span>
                @endif
                <a href="{{ route('admin.notices.index') }}" style="font-size: 12px; color: #dc2626; text-decoration: underline; margin-left: 6px;">Clear all</a>
            </div>
        @endif
    </div>

    <!-- Notices Table -->
    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Notice Title & Description</th>
                    <th>Type</th>
                    <th>Target Audience</th>
                    <th>Validity Period</th>
                    <th>Created By</th>
                    <th style="text-align: right; width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notices as $notice)
                    <tr>
                        <td>{{ $notices->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="table-name" style="font-weight: 700; color: #0f172a; font-size: 13.5px;">
                                {{ $notice->title }}
                            </div>
                            <div class="table-email" style="font-size: 12px; color: #64748b; margin-top: 3px; max-width: 380px;">
                                {{ Str::limit(strip_tags(html_entity_decode($notice->message)), 90) }}
                            </div>
                        </td>
                        <td>
                            @php
                                $typeBadge = match($notice->notice_type) {
                                    'warning' => ['bg' => '#fef3c7', 'color' => '#b45309', 'icon' => 'fa-triangle-exclamation'],
                                    'announcement' => ['bg' => '#eff6ff', 'color' => '#1d4ed8', 'icon' => 'fa-bullhorn'],
                                    'new' => ['bg' => '#f0fdf4', 'color' => '#15803d', 'icon' => 'fa-star'],
                                    default => ['bg' => '#f1f5f9', 'color' => '#475569', 'icon' => 'fa-circle-info']
                                };
                            @endphp
                            <span class="badge" style="background: {{ $typeBadge['bg'] }}; color: {{ $typeBadge['color'] }}; font-size: 11px; padding: 4px 8px; border-radius: 4px; font-weight: 600; text-transform: uppercase;">
                                <i class="fa-solid {{ $typeBadge['icon'] }}" style="margin-right: 3px; font-size: 10px;"></i>
                                {{ ucfirst($notice->notice_type) }}
                            </span>
                        </td>
                        <td>
                            @if(!$notice->target_user_type && !$notice->target_division_id && !$notice->target_user_id)
                                <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 11px; padding: 3px 7px; border-radius: 4px; font-weight: 600;">
                                    <i class="fa-solid fa-users" style="font-size: 10px; margin-right: 3px;"></i> All Users
                                </span>
                            @else
                                <div style="display: flex; flex-direction: column; gap: 3px;">
                                    @if($notice->target_user_type)
                                        <span class="badge" style="background: #f1f5f9; color: #334155; font-size: 10.5px; padding: 2px 6px; border-radius: 4px; text-transform: capitalize; align-self: flex-start;">
                                            <i class="fa-solid fa-user-tag" style="font-size: 9px; margin-right: 3px;"></i> {{ ucfirst($notice->target_user_type) }}
                                        </span>
                                    @endif
                                    @if($notice->division)
                                        <span class="badge" style="background: #fffbeb; color: #92400e; font-size: 10.5px; padding: 2px 6px; border-radius: 4px; align-self: flex-start;">
                                            <i class="fa-solid fa-map-pin" style="font-size: 9px; margin-right: 3px;"></i> {{ $notice->division->name }}
                                        </span>
                                    @endif
                                    @if($notice->target_user_id)
                                        @php
                                            $ids = json_decode($notice->target_user_id, true);
                                            $count = is_array($ids) ? count($ids) : 1;
                                        @endphp
                                        <span class="badge" style="background: #f8fafc; color: #475569; border: 1px solid #cbd5e1; font-size: 10.5px; padding: 2px 6px; border-radius: 4px; align-self: flex-start;">
                                            {{ $count }} Member(s)
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 12px; color: #334155;">
                                <span style="color: #64748b; font-weight: 500;">Start:</span>
                                <strong>{{ $notice->start_date ? $notice->start_date->format('d M Y') : 'Immediate' }}</strong>
                            </div>
                            <div style="font-size: 12px; color: #334155; margin-top: 2px;">
                                <span style="color: #64748b; font-weight: 500;">End:</span>
                                <strong>{{ $notice->end_date ? $notice->end_date->format('d M Y') : 'Indefinite' }}</strong>
                            </div>
                        </td>
                        <td>
                            <div class="table-name" style="font-size: 12.5px; font-weight: 600; color: #1e293b;">
                                {{ $notice->creator->name ?? 'System Admin' }}
                            </div>
                            <div class="table-email" style="font-size: 11px; color: #64748b; margin-top: 1px;">
                                {{ $notice->created_at ? $notice->created_at->format('d M Y, h:i A') : 'N/A' }}
                            </div>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 6px;">
                                <a href="{{ route('admin.notices.edit', $notice) }}" class="btn-action-pill btn-action-edit" title="Edit Notice">
                                    <i class="fa-solid fa-pen"></i>
                                    <span>Edit</span>
                                </a>
                                <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete notice: {{ $notice->title }}?');" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-pill btn-action-delete" title="Delete Notice">
                                        <i class="fa-solid fa-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 48px 24px; color: #64748b;">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 12px;">
                                <i class="fa-solid fa-bullhorn"></i>
                            </div>
                            <div style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">No Notices Found</div>
                            <div style="font-size: 13px; max-width: 420px; margin: 0 auto 16px;">
                                @if($hasFilters)
                                    No notices match your active search filters. Try clearing the filter options.
                                @else
                                    No departmental notices or announcements have been published yet.
                                @endif
                            </div>
                            @if($hasFilters)
                                <a href="{{ route('admin.notices.index') }}" class="btn-secondary" style="padding: 6px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #0284c7; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-rotate-left"></i> Clear All Filters
                                </a>
                            @else
                                <a href="{{ route('admin.notices.create') }}" class="btn-pink" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-plus"></i> Create First Notice
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination & Summary Footer -->
    @if($notices->hasPages())
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-top: 1px solid #f1f5f9; background: #fff; flex-wrap: wrap; gap: 12px;">
            <div style="font-size: 13px; color: #64748b;">
                Showing <strong>{{ $notices->firstItem() }}</strong> to <strong>{{ $notices->lastItem() }}</strong> of <strong>{{ $notices->total() }}</strong> notices
            </div>
            <div>
                {{ $notices->links('pagination::bootstrap-5') }}
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
