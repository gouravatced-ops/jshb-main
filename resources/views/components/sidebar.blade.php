<!-- SIDEBAR OVERLAY -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
@php
$sidebarUser = auth()->user();
$sidebarInitials = 'U';
if ($sidebarUser && ! empty($sidebarUser->name)) {
$sidebarNameParts = preg_split('/\s+/', trim($sidebarUser->name));
$sidebarInitials = strtoupper(($sidebarNameParts[0][0] ?? 'U') . ($sidebarNameParts[1][0] ?? ''));
}

$isAdmin = $sidebarUser?->role === 'admin';
$divisionIndexRoute = route('admin.divisions.index');
$divisionCreateRoute = route('admin.divisions.create');
$subDivisionIndexRoute = route('admin.sub-divisions.index');
$subDivisionCreateRoute = route('admin.sub-divisions.create');
$organizationIndexRoute = route('admin.organizations.index');
$organizationCreateRoute = route('admin.organizations.create');
$parentOrganizationIndexRoute = route('admin.parent-organizations.index');
$parentOrganizationCreateRoute = route('admin.parent-organizations.create');
$isDivisionActive = request()->routeIs('admin.divisions.*');
$isSubDivisionActive = request()->routeIs('admin.sub-divisions.*');
$isCategoriesActive = request()->routeIs('admin.categories.*');
$isParentOrganizationActive = request()->routeIs('admin.parent-organizations.*');
$isOrganizationActive = request()->routeIs('admin.organizations.*');
@endphp

<aside id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><img src="{{ asset(config('panel.logo')) }}" alt="JESA Logo"></div>
        <div class="logo-text">
            <div class="logo-title">JSHB</div>
        </div>
    </div>

    <div class="sidebar-section-label">Main Menu</div>

    @if($sidebarUser?->role === 'admin')
    @include('components.partials.admin-sidebar')
    @elseif($sidebarUser?->role === 'user')
    @include('components.partials.user-sidebar')
    @elseif($sidebarUser?->role === 'staff')
    @include('components.partials.staff-sidebar')
    @elseif($sidebarUser?->role === 'division')
    @include('components.partials.division-sidebar')
    @elseif($sidebarUser?->role === 'subdivision')
    @include('components.partials.subdivision-sidebar')
    @endif

    <!-- Common Settings and Footer -->
    @include('components.partials.common-sidebar-elements')

</aside>