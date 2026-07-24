@extends('layouts.main')

@section('title', 'Manage Members | JSHB')

@section('content')
    {{-- @php
        debug($members);
    @endphp --}}
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
            <div class="alert alert-error" style="margin: 20px 20px 0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="card-head">
            <div>
                <div class="card-title">Member List</div>
                <div class="card-subtitle">Manage administrative board members, staff, and roles</div>
            </div>
            <div class="card-actions">
                <form method="GET" action="{{ route('admin.members.index') }}" class="search-box">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Search members..." autocomplete="off">
                </form>
                <a class="btn-pink" href="{{ route('admin.members.create') }}">
                    <i class="fa-solid fa-user-plus"></i> Add Member
                </a>
            </div>
        </div>

        <div class="members-tabs" style="display: flex; gap: 20px; margin: 0 24px 20px; border-bottom: 1px solid #e2e8f0;">
            <a href="{{ route('admin.members.index', ['type' => 'all', 'search' => $search]) }}" 
               style="text-decoration: none; font-weight: 500; font-size: 15px; padding: 10px 4px; border-bottom: 2px solid {{ $type === 'all' ? '#0ea5e9' : 'transparent' }}; color: {{ $type === 'all' ? '#0ea5e9' : '#64748b' }}; transition: all 0.2s;">
                All Members
            </a>
            <a href="{{ route('admin.members.index', ['type' => 'divisional', 'search' => $search]) }}" 
               style="text-decoration: none; font-weight: 500; font-size: 15px; padding: 10px 4px; border-bottom: 2px solid {{ $type === 'divisional' ? '#0ea5e9' : 'transparent' }}; color: {{ $type === 'divisional' ? '#0ea5e9' : '#64748b' }}; transition: all 0.2s;">
                Divisional Based
            </a>
            <a href="{{ route('admin.members.index', ['type' => 'non_divisional', 'search' => $search]) }}" 
               style="text-decoration: none; font-weight: 500; font-size: 15px; padding: 10px 4px; border-bottom: 2px solid {{ $type === 'non_divisional' ? '#0ea5e9' : 'transparent' }}; color: {{ $type === 'non_divisional' ? '#0ea5e9' : '#64748b' }}; transition: all 0.2s;">
                Non-Divisional Based
            </a>
        </div>

        <div class="table-responsive">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Division</th>
                        <th>Designation</th>
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
                                <span
                                    class="badge-status {{ $member->division ? 'active' : '' }}"
                                    title="{{ $member->division?->name ?? 'N/A' }}"
                                    style="
                                        background: {{ $member->division ? '#114466' : 'transparent' }};
                                        color: {{ $member->division ? '#e8f5fc' : '#000' }};
                                        font-size:14px;
                                    "
                                >
                                    {{ $member->division?->division_code ?: 'N/A' }}
                                </span>
                            </td>
                            <td>{{ $member->detail?->designation ?: '-' }}</td>
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
                                    @php
                                        $roleSlug = $member->roleRelation?->slug;
                                        $isProtected = in_array($roleSlug, ['admin', 'super-admin', 'allottee']);
                                    @endphp

                                    @if(!$isProtected)
                                        <a class="action-btn edit" href="{{ route('admin.members.edit', $member->id) }}"
                                            title="Edit">
                                            <i class="fa-solid fa-pen text-primary"></i>
                                        </a>
                                    @endif

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
                    if(data.success) {
                        alert(data.success);
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
