@if($sidebarUser?->role === 'coassistant')
<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('coassistant.dashboard') ? 'active' : '' }}" href="{{ route('coassistant.dashboard') }}">
        <div class="nav-icon"><i class="fa-solid fa-house-chimney"></i></div>
        <span class="nav-text">Dashboard</span>
    </a>
</div>

<div class="sidebar-section-label">Management</div>
<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('coassistant.applications.index') ? 'active' : '' }}" href="{{ route('coassistant.applications.index') }}">
        <div class="nav-icon"><i class="fa-solid fa-file-contract"></i></div>
        <span class="nav-text">Pending Applications</span>
    </a>
</div>

<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('coassistant.applications.history') ? 'active' : '' }}" href="{{ route('coassistant.applications.history') }}">
        <div class="nav-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <span class="nav-text">Application History</span>
    </a>
</div>

<div class="sidebar-section-label">Account</div>
<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('my-activity') ? 'active' : '' }}" href="{{ route('my-activity') }}">
        <div class="nav-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <span class="nav-text">My Activity</span>
    </a>
</div>

<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('coassistant.assets.*') ? 'active' : '' }}" href="{{ route('coassistant.assets.index') }}">
        <div class="nav-icon"><i class="fa-solid fa-stamp"></i></div>
        <span class="nav-text">My Assets</span>
    </a>
</div>

<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('coassistant.profile') ? 'active' : '' }}" href="{{ route('coassistant.profile') }}">
        <div class="nav-icon"><i class="fa-solid fa-id-card"></i></div>
        <span class="nav-text">My Profile</span>
    </a>
</div>
@endif
