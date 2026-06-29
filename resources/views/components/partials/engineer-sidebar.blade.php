@if($sidebarUser?->role === 'engineer')
<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('engineer.dashboard') ? 'active' : '' }}" href="{{ route('engineer.dashboard') }}">
        <div class="nav-icon"><i class="fa-solid fa-house-chimney"></i></div>
        <span class="nav-text">Dashboard</span>
    </a>
</div>

<div class="sidebar-section-label">Account</div>
<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('engineer.profile') ? 'active' : '' }}" href="{{ route('engineer.profile') }}">
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
