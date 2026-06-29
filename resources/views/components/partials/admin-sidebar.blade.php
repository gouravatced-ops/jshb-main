@if ($sidebarUser?->role === 'admin')
    <div class="nav-item-wrap">
        <a class="nav-link-custom {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            href="{{ route('admin.dashboard') }}">
            <div class="nav-icon"><i class="fa-solid fa-house-chimney"></i></div>
            <span class="nav-text">Dashboard</span>
        </a>
    </div>

    <div class="sidebar-section-label">Management</div>

    <!-- Allottee -->
    <div class="nav-item-wrap">
        <div class="nav-link-custom" onclick="toggleSubmenu('allottee',this)">
            <div class="nav-icon">
                <i class="fa-solid fa-user-check"></i> <!-- Main Allottee Icon -->
            </div>
            <span class="nav-text">Allottee</span>
            <i class="fa-solid fa-chevron-right nav-chevron" id="allottee-chev"></i>
        </div>

        <div class="submenu" id="allottee">
            <!-- Allottee List -->
            <a class="submenu-item {{ request()->routeIs('admin.allottees.index') ? 'active' : '' }}"
                href="{{ route('admin.allottees.index') }}">
                <i class="fa-solid fa-list"></i> Allottee List
            </a>

            <!-- Create Allottee -->
            <a class="submenu-item {{ request()->routeIs('admin.apply.index') ? 'active' : '' }}"
                href="{{ route('admin.apply.index') }}">
                <i class="fa-solid fa-user-plus"></i> Add Allottee
            </a>
        </div>
    </div>

    @if ($sidebarUser->roleRelation?->slug === 'super-admin')
        <!-- Member Management -->
        <div class="nav-item-wrap">
            <div class="nav-link-custom {{ request()->routeIs('admin.members.*') ? 'active' : '' }}" onclick="toggleSubmenu('members',this)">
                <div class="nav-icon">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <span class="nav-text">Members</span>
                <i class="fa-solid fa-chevron-right nav-chevron" id="members-chev"></i>
            </div>

            <div class="submenu" id="members" style="{{ request()->routeIs('admin.members.*') ? 'display:block' : '' }}">
                <a class="submenu-item {{ request()->routeIs('admin.members.index') ? 'active' : '' }}"
                    href="{{ route('admin.members.index') }}">
                    <i class="fa-solid fa-users"></i> Manage Members
                </a>

                <a class="submenu-item {{ request()->routeIs('admin.members.create') ? 'active' : '' }}"
                    href="{{ route('admin.members.create') }}">
                    <i class="fa-solid fa-user-plus"></i> Add Member
                </a>
            </div>
        </div>
    @endif

    <div class="sidebar-section-label">Components Management</div>

    <!-- Division -->
    <div class="nav-item-wrap">
        <div class="nav-link-custom {{ $isDivisionActive ? 'active' : '' }}"
            onclick="toggleSubmenu('divisionsub',this)">
            <div class="nav-icon"><i class="fa-solid fa-diagram-project"></i></div>
            <span class="nav-text">Division</span>
            <i class="fa-solid fa-chevron-right nav-chevron" id="divisionsub-chev"></i>
        </div>
        <div class="submenu" id="divisionsub">
            <a class="submenu-item {{ request()->routeIs('admin.divisions.index') ? 'active' : '' }}"
                href="{{ $divisionIndexRoute }}">
                <i class="fa-solid fa-list"></i> Division List
            </a>
            <a class="submenu-item {{ request()->routeIs('admin.divisions.create') ? 'active' : '' }}"
                href="{{ $divisionCreateRoute }}">
                <i class="fa-solid fa-plus"></i> Add Division
            </a>
        </div>
    </div>

    <!-- Sub Division -->
    <div class="nav-item-wrap">
        <div class="nav-link-custom {{ $isSubDivisionActive ? 'active' : '' }}"
            onclick="toggleSubmenu('subdivisionsub',this)">
            <div class="nav-icon"><i class="fa-solid fa-sitemap"></i></div>
            <span class="nav-text">Sub Division</span>
            <i class="fa-solid fa-chevron-right nav-chevron" id="subdivisionsub-chev"></i>
        </div>
        <div class="submenu" id="subdivisionsub">
            <a class="submenu-item {{ request()->routeIs('admin.sub-divisions.index') ? 'active' : '' }}"
                href="{{ $subDivisionIndexRoute }}">
                <i class="fa-solid fa-list-ul"></i> Sub Division List
            </a>
            <a class="submenu-item {{ request()->routeIs('admin.sub-divisions.create') ? 'active' : '' }}"
                href="{{ $subDivisionCreateRoute }}">
                <i class="fa-solid fa-plus"></i> Add Sub Division
            </a>
        </div>
    </div>

    <!-- Categories -->
    <div class="nav-item-wrap">
        <div class="nav-link-custom {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.property-types.*') || request()->routeIs('admin.property-sub-types.*') ? 'active' : '' }}"
            onclick="toggleSubmenu('categories',this)">
            <div class="nav-icon">
                <i class="fa-solid fa-grip"></i>
            </div>
            <span class="nav-text">Categories</span>
            <i class="fa-solid fa-chevron-right nav-chevron" id="categories-chev"></i>
        </div>
        <div class="submenu" id="categories">
            <a class="submenu-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}"
                href="{{ route('admin.categories.index') }}">
                <i class="fa-solid fa-list"></i> Category List
            </a>
            <a class="submenu-item {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}"
                href="{{ route('admin.categories.create') }}">
                <i class="fa-solid fa-plus"></i> Add Category
            </a>
            <a class="submenu-item {{ request()->routeIs('admin.property-types.index') ? 'active' : '' }}"
                href="{{ route('admin.property-types.index') }}">
                <i class="fa-solid fa-list-check"></i> Property Type List
            </a>
            <a class="submenu-item {{ request()->routeIs('admin.property-types.create') ? 'active' : '' }}"
                href="{{ route('admin.property-types.create') }}">
                <i class="fa-solid fa-plus"></i> Add Property Type
            </a>
            <a class="submenu-item {{ request()->routeIs('admin.property-sub-types.index') ? 'active' : '' }}"
                href="{{ route('admin.property-sub-types.index') }}">
                <i class="fa-solid fa-list-check"></i> Property Sub Type List
            </a>
            <a class="submenu-item {{ request()->routeIs('admin.property-sub-types.create') ? 'active' : '' }}"
                href="{{ route('admin.property-sub-types.create') }}">
                <i class="fa-solid fa-plus"></i> Add Property Sub Type
            </a>
        </div>
    </div>

    <!-- Quarter Type -->
    <div class="nav-item-wrap">
        <div class="nav-link-custom {{ request()->routeIs('admin.quarter-types.*') ? 'active' : '' }}"
            onclick="toggleSubmenu('quartertypes',this)">
            <div class="nav-icon">
                <i class="fa-solid fa-building"></i>
            </div>
            <span class="nav-text">Quarter Type</span>
            <i class="fa-solid fa-chevron-right nav-chevron" id="quartertypes-chev"></i>
        </div>
        <div class="submenu" id="quartertypes">
            <a class="submenu-item {{ request()->routeIs('admin.quarter-types.index') ? 'active' : '' }}"
                href="{{ route('admin.quarter-types.index') }}">
                <i class="fa-solid fa-list"></i> Quarter Type List
            </a>
            <a class="submenu-item {{ request()->routeIs('admin.quarter-types.create') ? 'active' : '' }}"
                href="{{ route('admin.quarter-types.create') }}">
                <i class="fa-solid fa-plus"></i> Add Quarter Type
            </a>
        </div>
    </div>

    <!-- Schemes -->
    <div class="nav-item-wrap">
        <div class="nav-link-custom {{ request()->routeIs('admin.schemes.*') ? 'active' : '' }}"
            onclick="toggleSubmenu('schemes',this)">
            <div class="nav-icon">
                <i class="fa-solid fa-database"></i>
            </div>
            <span class="nav-text">Schemes</span>
            <i class="fa-solid fa-chevron-right nav-chevron" id="schemes-chev"></i>
        </div>
        <div class="submenu" id="schemes">
            <a class="submenu-item {{ request()->routeIs('admin.schemes.index') ? 'active' : '' }}"
                href="{{ route('admin.schemes.index') }}">
                <i class="fa-solid fa-table"></i> Scheme List
            </a>
            <a class="submenu-item {{ request()->routeIs('admin.schemes.create') ? 'active' : '' }}"
                href="{{ route('admin.schemes.create') }}">
                <i class="fa-solid fa-circle-plus"></i> Add Scheme
            </a>
        </div>
    </div>
@endif
