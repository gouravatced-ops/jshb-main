@if($sidebarUser?->role === 'admin')
<div class="nav-item-wrap">
    <a class="nav-link-custom {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <div class="nav-icon"><i class="fa-solid fa-house-chimney"></i></div>
        <span class="nav-text">Dashboard</span>
    </a>
</div>

<div class="sidebar-section-label">Management</div>

<!-- Parent Organization -->
<div class="nav-item-wrap">
    <div class="nav-link-custom {{ $isParentOrganizationActive ? 'active' : '' }}" onclick="toggleSubmenu('templateparent',this)">
        <div class="nav-icon"><i class="fa-solid fa-building-columns"></i></div>
        <span class="nav-text">Parent Organization</span>
        <i class="fa-solid fa-chevron-right nav-chevron" id="templateparent-chev"></i>
    </div>
    <div class="submenu" id="templateparent">
        <a class="submenu-item {{ request()->routeIs('admin.parent-organizations.index') ? 'active' : '' }}" href="{{ $parentOrganizationIndexRoute }}">Parent Organization List</a>
        <a class="submenu-item {{ request()->routeIs('admin.parent-organizations.create') ? 'active' : '' }}" href="{{ $parentOrganizationCreateRoute }}">Add Parent Organization</a>
    </div>
</div>

<!-- Organization -->
<div class="nav-item-wrap">
    <div class="nav-link-custom {{ $isOrganizationActive ? 'active' : '' }}" onclick="toggleSubmenu('templatesub',this)">
        <div class="nav-icon"><i class="fa-solid fa-warehouse"></i></div>
        <span class="nav-text">Organization</span>
        <i class="fa-solid fa-chevron-right nav-chevron" id="templatesub-chev"></i>
    </div>
    <div class="submenu" id="templatesub">
        <a class="submenu-item {{ request()->routeIs('admin.organizations.index') ? 'active' : '' }}" href="{{ $organizationIndexRoute }}">Organization List</a>
        <a class="submenu-item {{ request()->routeIs('admin.organizations.create') ? 'active' : '' }}" href="{{ $organizationCreateRoute }}">Add Organization</a>
    </div>
</div>

<div class="sidebar-section-label">Components Management</div>

<!-- Division -->
<div class="nav-item-wrap">
    <div class="nav-link-custom {{ $isDivisionActive ? 'active' : '' }}" onclick="toggleSubmenu('divisionsub',this)">
        <div class="nav-icon"><i class="fa-solid fa-diagram-project"></i></div>
        <span class="nav-text">Division</span>
        <i class="fa-solid fa-chevron-right nav-chevron" id="divisionsub-chev"></i>
    </div>
    <div class="submenu" id="divisionsub">
        <a class="submenu-item {{ request()->routeIs('admin.divisions.index') ? 'active' : '' }}" href="{{ $divisionIndexRoute }}">Division List</a>
        <a class="submenu-item {{ request()->routeIs('admin.divisions.create') ? 'active' : '' }}" href="{{ $divisionCreateRoute }}">Add Division</a>
    </div>
</div>

<!-- Sub Division -->
<div class="nav-item-wrap">
    <div class="nav-link-custom {{ $isSubDivisionActive ? 'active' : '' }}" onclick="toggleSubmenu('subdivisionsub',this)">
        <div class="nav-icon"><i class="fa-solid fa-sitemap"></i></div>
        <span class="nav-text">Sub Division</span>
        <i class="fa-solid fa-chevron-right nav-chevron" id="subdivisionsub-chev"></i>
    </div>
    <div class="submenu" id="subdivisionsub">
        <a class="submenu-item {{ request()->routeIs('admin.sub-divisions.index') ? 'active' : '' }}" href="{{ $subDivisionIndexRoute }}">Sub Division List</a>
        <a class="submenu-item {{ request()->routeIs('admin.sub-divisions.create') ? 'active' : '' }}" href="{{ $subDivisionCreateRoute }}">Add Sub Division</a>
    </div>
</div>

<!-- Categories -->
<div class="nav-item-wrap">
    <div class="nav-link-custom {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" onclick="toggleSubmenu('categories',this)">
        <div class="nav-icon">
            <i class="fa-solid fa-grip"></i>
        </div>
        <span class="nav-text">Categories</span>
        <i class="fa-solid fa-chevron-right nav-chevron" id="categories-chev"></i>
    </div>
    <div class="submenu" id="categories">
        <a class="submenu-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Category List</a>
        <a class="submenu-item {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}" href="{{ route('admin.categories.create') }}">Add Category</a>
    </div>
</div>

<!-- Schemes -->
<div class="nav-item-wrap">
    <div class="nav-link-custom {{ request()->routeIs('admin.schemes.*') ? 'active' : '' }}" onclick="toggleSubmenu('schemes',this)">
        <div class="nav-icon">
            <i class="fa-solid fa-database"></i>
        </div>
        <span class="nav-text">Schemes</span>
        <i class="fa-solid fa-chevron-right nav-chevron" id="schemes-chev"></i>
    </div>
    <div class="submenu" id="schemes">
        <a class="submenu-item {{ request()->routeIs('admin.schemes.index') ? 'active' : '' }}" href="{{ route('admin.schemes.index') }}">Scheme List</a>
        <a class="submenu-item {{ request()->routeIs('admin.schemes.create') ? 'active' : '' }}" href="{{ route('admin.schemes.create') }}">Add Scheme</a>
    </div>
</div>
@endif