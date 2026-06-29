<!-- SIDEBAR OVERLAY -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
@php
    $sidebarUser = auth()->user();
    $sidebarInitials = 'U';
    if ($sidebarUser && !empty($sidebarUser->name)) {
        $sidebarNameParts = preg_split('/\s+/', trim($sidebarUser->name));
        $sidebarInitials = strtoupper(($sidebarNameParts[0][0] ?? 'U') . ($sidebarNameParts[1][0] ?? ''));
    }

    $isAdmin = $sidebarUser?->role === 'admin';
    $divisionIndexRoute = route('admin.divisions.index');
    $divisionCreateRoute = route('admin.divisions.create');
    $subDivisionIndexRoute = route('admin.sub-divisions.index');
    $subDivisionCreateRoute = route('admin.sub-divisions.create');
    $isDivisionActive = request()->routeIs('admin.divisions.*');
    $isSubDivisionActive = request()->routeIs('admin.sub-divisions.*');
    $isCategoriesActive = request()->routeIs('admin.categories.*');
@endphp

<aside id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><img src="{{ asset(config('panel.logo')) }}" alt="JESA Logo"></div>
        <div class="logo-text">
            <div class="logo-title"><span style="color:#f5c518;">J</span>SHB</div>
        </div>
    </div>

    <div class="sidebar-section-label">Main Menu</div>

    @if ($sidebarUser?->role === 'admin')
        @include('components.partials.admin-sidebar')
    @elseif($sidebarUser?->role === 'user')
        @include('components.partials.user-sidebar')
    @elseif($sidebarUser?->role === 'staff')
        @include('components.partials.staff-sidebar')
    @elseif($sidebarUser?->role === 'division')
        @include('components.partials.division-sidebar')
    @elseif($sidebarUser?->role === 'subdivision')
        @include('components.partials.subdivision-sidebar')
    @elseif($sidebarUser?->role === 'engineer')
        @include('components.partials.engineer-sidebar')
    @elseif($sidebarUser?->role === 'managing')
        @include('components.partials.managing-sidebar')
    @elseif($sidebarUser?->role === 'operator')
        @include('components.partials.operator-sidebar')
    @endif

    <!-- Common Settings and Footer -->
    @include('components.partials.common-sidebar-elements')

</aside>
