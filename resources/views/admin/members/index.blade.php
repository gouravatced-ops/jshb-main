@extends('layouts.main')

@section('title', 'Manage Members | JSHB')

@section('content')
{{-- @php
        debug($members);
    @endphp --}}
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">Member List</div>
            <div class="card-subtitle">Manage administrative board members, staff, and roles</div>
        </div>
        <div class="card-actions">
            @php
                $hasActiveFilters = ($search !== '' || !empty($divisionId) || !empty($subDivisionId));
            @endphp
            <button type="button" id="toggleFilterBtn" class="btn-sky" style="cursor: pointer;">
                <i class="fa-solid fa-filter"></i>
                <span id="filterBtnText">{{ $hasActiveFilters ? 'Hide Filter' : 'Filter' }}</span>
                @if($hasActiveFilters)
                    <span style="background: #f59e0b; color: #fff; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 999px;">Active</span>
                @endif
            </button>
            <a class="btn-pink" href="{{ route('admin.members.create') }}">
                <i class="fa-solid fa-user-plus"></i> Add Member
            </a>
        </div>
    </div>

    <div class="members-tabs" style="display: flex; gap: 20px; margin: 0 24px 16px; border-bottom: 1px solid #e2e8f0;">
        <a href="{{ route('admin.members.index', array_merge(request()->query(), ['type' => 'all'])) }}"
            style="text-decoration: none; font-weight: 500; font-size: 15px; padding: 10px 4px; border-bottom: 2px solid {{ $type === 'all' ? '#0ea5e9' : 'transparent' }}; color: {{ $type === 'all' ? '#0ea5e9' : '#64748b' }}; transition: all 0.2s;">
            All Members
        </a>
        <a href="{{ route('admin.members.index', array_merge(request()->query(), ['type' => 'divisional'])) }}"
            style="text-decoration: none; font-weight: 500; font-size: 15px; padding: 10px 4px; border-bottom: 2px solid {{ $type === 'divisional' ? '#0ea5e9' : 'transparent' }}; color: {{ $type === 'divisional' ? '#0ea5e9' : '#64748b' }}; transition: all 0.2s;">
            Divisional Based
        </a>
        <a href="{{ route('admin.members.index', array_merge(request()->query(), ['type' => 'non_divisional', 'division_id' => null, 'sub_division_id' => null])) }}"
            style="text-decoration: none; font-weight: 500; font-size: 15px; padding: 10px 4px; border-bottom: 2px solid {{ $type === 'non_divisional' ? '#0ea5e9' : 'transparent' }}; color: {{ $type === 'non_divisional' ? '#0ea5e9' : '#64748b' }}; transition: all 0.2s;">
            Non-Divisional Based
        </a>
    </div>

    <!-- Filter Bar: Search, Division & Sub-Division (Hidden by default unless toggled or filters active) -->
    <div id="membersFilterBox" class="members-filter-box" style="display: {{ $hasActiveFilters ? 'block' : 'none' }}; margin: 0 24px 20px; padding: 14px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
        <form method="GET" action="{{ route('admin.members.index') }}" id="memberFilterForm" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <input type="hidden" name="type" id="filterTypeInput" value="{{ $type }}">

            <!-- Search Field -->
            <div style="flex: 1; min-width: 220px; position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                <input type="text" name="search" id="memberSearchInput" value="{{ $search }}"
                    placeholder="Search name, email, username..." autocomplete="off"
                    style="width: 100%; padding: 8px 12px 8px 34px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none;">
            </div>

            <!-- Division Dropdown -->
            <div style="min-width: 190px; flex-shrink: 0;">
                <select name="division_id" id="filterDivisionSelect"
                    style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none; cursor: pointer;">
                    <option value="">All Divisions</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}" {{ (string)$divisionId === (string)$division->id ? 'selected' : '' }}>
                            {{ $division->name }} ({{ $division->division_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sub Division Dropdown (Filtered by division_id) -->
            <div style="min-width: 200px; flex-shrink: 0;">
                <select name="sub_division_id" id="filterSubDivisionSelect"
                    style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; background: #fff; outline: none; cursor: pointer;">
                    <option value="">All Sub Divisions</option>
                    @foreach($subDivisions as $subDivision)
                        <option value="{{ $subDivision->id }}" data-division="{{ $subDivision->division_id }}"
                            {{ (string)$subDivisionId === (string)$subDivision->id ? 'selected' : '' }}>
                            {{ $subDivision->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter & Reset Buttons -->
            <div style="display: flex; gap: 8px; align-items: center; flex-shrink: 0;">
                <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 13px; border-radius: 6px; border: none; background: #0ea5e9; color: #fff; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if($search !== '' || !empty($divisionId) || !empty($subDivisionId))
                    <a href="{{ route('admin.members.index', ['type' => $type]) }}" class="btn-secondary" title="Clear Filters"
                        style="padding: 8px 14px; font-size: 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #64748b; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; height: 35px;">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Active Filter Tags (if any filter is selected) -->
        @if(!empty($divisionId) || !empty($subDivisionId) || $search !== '')
            <div style="display: flex; align-items: center; gap: 8px; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #e2e8f0; flex-wrap: wrap;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600;">Active Filters:</span>
                @if($search !== '')
                    <span style="font-size: 12px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        Search: "{{ $search }}"
                        <a href="{{ route('admin.members.index', array_merge(request()->except('search'))) }}" style="color: #0284c7; text-decoration: none;">&times;</a>
                    </span>
                @endif
                @if(!empty($divisionId))
                    @php $activeDiv = $divisions->firstWhere('id', $divisionId); @endphp
                    @if($activeDiv)
                        <span style="font-size: 12px; background: #e0e7ff; color: #4338ca; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                            Division: {{ $activeDiv->name }}
                            <a href="{{ route('admin.members.index', array_merge(request()->except(['division_id', 'sub_division_id']))) }}" style="color: #4338ca; text-decoration: none;">&times;</a>
                        </span>
                    @endif
                @endif
                @if(!empty($subDivisionId))
                    @php $activeSubDiv = $subDivisions->firstWhere('id', $subDivisionId); @endphp
                    @if($activeSubDiv)
                        <span style="font-size: 12px; background: #fef3c7; color: #b45309; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                            Sub Division: {{ $activeSubDiv->name }}
                            <a href="{{ route('admin.members.index', array_merge(request()->except('sub_division_id'))) }}" style="color: #b45309; text-decoration: none;">&times;</a>
                        </span>
                    @endif
                @endif
            </div>
        @endif
    </div>

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Division</th>
                    <th>Sub Division</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>OTP Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td>{{ $members->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="table-user">
                            <div>
                                <div class="table-name">
                                    {{ $member->name }}
                                    @if($member->is_default)
                                    <span class="badge-status active" style="background:#fef3c7;color:#d97706;font-size: 11px; margin-left: 5px; padding: 2px 6px;">Default</span>
                                    @endif
                                </div>
                                <div class="table-email">
                                    <span class="badge-status active" title="{{ $member->roleRelation?->short_name ?: 'No Role' }}" style="background:#e0f2fe;color:#0369a1;font-size: 14px;">
                                        {{ $member->roleRelation?->name ?: 'No Role' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="table-user">
                            <div>
                                <div class="table-name">{{ $member->email }}</div>
                                <div class="table-email">{{ $member->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                            <span
                                class="badge-status {{ $member->division ? 'active' : '' }}"
                                title="{{ $member->division?->name ?? 'N/A' }}"
                                style="
                                            background: {{ $member->division ? '#114466' : 'transparent' }};
                                            color: {{ $member->division ? '#e8f5fc' : '#000' }};
                                            font-size:14px;
                                        ">
                                {{ $member->division?->division_code ?: 'N/A' }}
                            </span>
                        </div>
                    </td>
                    <td> @if($member->subDivision)
                        <div style="font-size:13px; color: #09316a !important; font-weight: 800 !important;">
                            {{ $member->subDivision->name }}
                        </div>
                        @else
                        <span> N/A </span>
                        @endif
                    </td>
                    <td>{{ $member->detail?->phone ?: '-' }}</td>
                    <td>
                        <span class="badge-status {{ $member->status ? 'active' : 'inactive' }}">
                            <i class="fa-solid fa-circle"></i>
                            {{ $member->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-status {{ $member->login_with_otp ? 'active' : 'inactive' }}">
                            <i class="fa-solid fa-circle"></i>
                            {{ $member->login_with_otp ? 'Enabled' : 'Disabled' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a class="action-btn edit" href="{{ route('admin.members.edit', $member->id) }}"
                                title="Edit">
                                <i class="fa-solid fa-pen text-primary"></i>
                            </a>

                            <!-- Status Toggle (Deactive / Active Switch) -->
                            <form action="{{ route('admin.members.toggle-status', $member->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button class="action-btn toggle-status" type="submit" title="Toggle Status">
                                    @if($member->status)
                                    <i class="fa-solid fa-toggle-on text-success" style="font-size:18px;"></i>
                                    @else
                                    <i class="fa-solid fa-toggle-off text-danger" style="font-size:18px;"></i>
                                    @endif
                                </button>
                            </form>

                            <!-- Notify Button -->
                            <button class="action-btn notify-btn" data-url="{{ route('admin.members.test-notify', $member->id) }}" title="Send Test Realtime Notification">
                                <i class="fa-solid fa-bell text-warning"></i>
                            </button>


                            <!-- Delete Button (Only if not self, and not protected role) -->
                            {{-- @if($member->id !== Auth::id() && !$isProtected)
                                        <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST"
                            style="display:inline;" onsubmit="return confirm('WARNING: If you delete this member, all related data will be permanently wiped out. If you want to keep the data, please deactivate the ID instead. Are you sure you want to proceed with the deletion?');">
                            @csrf
                            @method('DELETE')
                            <button class="action-btn del" type="submit" title="Delete">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </button>
                            </form>
                            @endif --}}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                        No members found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($members->hasPages())
    <div style="padding:20px;">
        {{ $members->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Filter Box Hide / Show
        const toggleFilterBtn = document.getElementById('toggleFilterBtn');
        const filterBox = document.getElementById('membersFilterBox');
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

        // Dynamic Sub-Division filter dependent on Division selection
        const divisionSelect = document.getElementById('filterDivisionSelect');
        const subDivisionSelect = document.getElementById('filterSubDivisionSelect');
        const filterTypeInput = document.getElementById('filterTypeInput');

        function filterSubDivisions() {
            if (!divisionSelect || !subDivisionSelect) return;
            const selectedDivision = divisionSelect.value;
            let selectedStillVisible = false;

            Array.from(subDivisionSelect.options).forEach(opt => {
                if (opt.value === "") {
                    opt.textContent = selectedDivision ? "All Sub Divisions in Division" : "All Sub Divisions";
                    return;
                }

                const optDivision = opt.getAttribute('data-division');
                if (!selectedDivision || optDivision === selectedDivision) {
                    opt.style.display = "";
                    opt.hidden = false;
                    opt.disabled = false;
                    if (opt.value === subDivisionSelect.value) {
                        selectedStillVisible = true;
                    }
                } else {
                    opt.style.display = "none";
                    opt.hidden = true;
                    opt.disabled = true;
                }
            });

            if (subDivisionSelect.value && !selectedStillVisible) {
                subDivisionSelect.value = "";
            }
        }

        if (divisionSelect) {
            divisionSelect.addEventListener('change', function() {
                filterSubDivisions();
                if (this.value && filterTypeInput && filterTypeInput.value === 'non_divisional') {
                    filterTypeInput.value = 'divisional';
                }
            });
            // Initial filter run on page load
            filterSubDivisions();
        }

        // Test Realtime Notification
        document.querySelectorAll('.notify-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let url = this.getAttribute('data-url');

                // Show small visual feedback
                let originalIcon = this.innerHTML;
                this.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-warning"></i>';

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.innerHTML = originalIcon;
                        if (data.success) {
                            console.log(data.success);
                        } else {
                            alert(data.error || 'Something went wrong');
                        }
                    })
                    .catch(error => {
                        this.innerHTML = originalIcon;
                        alert('Error sending notification.');
                    });
            });
        });
    });
</script>
@endsection
