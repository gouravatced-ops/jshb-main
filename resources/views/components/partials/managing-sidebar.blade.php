@if($sidebarUser?->role === 'managing')
<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('managing.dashboard') ? 'active' : '' }}" href="{{ route('managing.dashboard') }}">
        <div class="nav-icon"><i class="fa-solid fa-house-chimney"></i></div>
        <span class="nav-text">Dashboard</span>
    </a>
</div>

<div class="sidebar-section-label">Account</div>
<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('managing.profile') ? 'active' : '' }}" href="{{ route('managing.profile') }}">
        <div class="nav-icon"><i class="fa-solid fa-id-card"></i></div>
        <span class="nav-text">My Profile</span>
    </a>
</div>

<div class="nav-item-wrap">
    <a class="nav-link-custom" href="#">
        <div class="nav-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <span class="nav-text">My Activity</span>
    </a>
</div>
@endif
