@if($sidebarUser?->role === 'operator')
<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('operator.dashboard') ? 'active' : '' }}" href="{{ route('operator.dashboard') }}">
        <div class="nav-icon"><i class="fa-solid fa-house-chimney"></i></div>
        <span class="nav-text">Dashboard</span>
    </a>
</div>

<div class="sidebar-section-label">Applications</div>
<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('operator.applications.index') ? 'active' : '' }}" href="{{ route('operator.applications.index') }}">
        <div class="nav-icon"><i class="fa-solid fa-file-contract"></i></div>
        <span class="nav-text">Pending Applications</span>
    </a>
</div>
<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('operator.applications.history') ? 'active' : '' }}" href="{{ route('operator.applications.history') }}">
        <div class="nav-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <span class="nav-text">Application History</span>
    </a>
</div>

<div class="sidebar-section-label">Account</div>
<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('operator.profile') ? 'active' : '' }}" href="{{ route('operator.profile') }}">
        <div class="nav-icon"><i class="fa-solid fa-id-card"></i></div>
        <span class="nav-text">My Profile</span>
    </a>
</div>

<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('my-activity') ? 'active' : '' }}" href="{{ route('my-activity') }}">
        <div class="nav-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <span class="nav-text">My Activity</span>
    </a>
</div>
@endif
