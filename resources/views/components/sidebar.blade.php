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
    $dashboardRoute = $isAdmin ? route('admin.dashboard') : route('dashboard');
    $profileRoute = $isAdmin ? route('admin.profile') : route('profile');
    $divisionIndexRoute = route('admin.divisions.index');
    $divisionCreateRoute = route('admin.divisions.create');
    $subDivisionIndexRoute = route('admin.sub-divisions.index');
    $subDivisionCreateRoute = route('admin.sub-divisions.create');
    $organizationIndexRoute = route('admin.organizations.index');
    $organizationCreateRoute = route('admin.organizations.create');
    $parentOrganizationIndexRoute = route('admin.parent-organizations.index');
    $parentOrganizationCreateRoute = route('admin.parent-organizations.create');
    $postTypeIndexRoute = route('admin.post-types.index');
    $postTypeCreateRoute = route('admin.post-types.create');
    $departmentIndexRoute = route('admin.departments.index');
    $departmentCreateRoute = route('admin.departments.create');
    $blockIndexRoute = route('admin.blocks.index');
    $blockCreateRoute = route('admin.blocks.create');
    $engineerIndexRoute = route('admin.engineers.index');
    $engineerCreateRoute = route('admin.engineers.create');
    $userRequisitionIndexRoute = route('requisitions.index');
    $userRequisitionCreateRoute = route('requisitions.create');
    $adminRequisitionIndexRoute = route('admin.requisitions.index');
    $isDashboardActive = request()->routeIs('dashboard') || request()->routeIs('admin.dashboard');
    $isProfileActive = request()->routeIs('profile') || request()->routeIs('admin.profile');
    $isDivisionActive = request()->routeIs('admin.divisions.*');
    $isSubDivisionActive = request()->routeIs('admin.sub-divisions.*');
    $isParentOrganizationActive = request()->routeIs('admin.parent-organizations.*');
    $isOrganizationActive = request()->routeIs('admin.organizations.*');
    $isPostTypeActive = request()->routeIs('admin.post-types.*');
    $isDepartmentActive = request()->routeIs('admin.departments.*');
    $isBlockActive = request()->routeIs('admin.blocks.*');
    $isEngineerActive = request()->routeIs('admin.engineers.*');
    $isRequisitionActive = request()->routeIs('requisitions.*') || request()->routeIs('admin.requisitions.*');
    @endphp
    <aside id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon"><img src="{{ asset(config('panel.logo')) }}" alt="JESA Logo"></div>
            <div class="logo-text">
                <div class="logo-title">{{ config('panel.organization') }}</div>
            </div>
        </div>

        <div class="sidebar-section-label">Main Menu</div>

        <div class="nav-item-wrap">
            <a class="nav-link-custom {{ $isDashboardActive ? 'active' : '' }}" href="{{ $dashboardRoute }}">
                <div class="nav-icon"><i class="fa-solid fa-house-chimney"></i></div>
                <span class="nav-text">Dashboard</span>
            </a>
        </div>

        @if($isAdmin)
        <!-- <div class="nav-item-wrap">
                <div class="nav-link-custom" onclick="toggleSubmenu('engsub',this)">
                    <div class="nav-icon"><i class="fa-solid fa-hard-hat"></i></div>
                    <span class="nav-text">Engineers</span>
                    <i class="fa-solid fa-chevron-right nav-chevron" id="engsub-chev"></i>
                </div>
                <div class="submenu" id="engsub">
                    <a class="submenu-item" href="#">All Engineers</a>
                    <a class="submenu-item" href="#">Add Engineer</a>
                    <a class="submenu-item" href="#">Designations</a>
                </div>
            </div>

            <div class="nav-item-wrap">
                <div class="nav-link-custom" onclick="toggleSubmenu('postsub',this)">
                    <div class="nav-icon"><i class="fa-solid fa-file-lines"></i></div>
                    <span class="nav-text">Posts</span>
                    <span class="nav-badge">12</span>
                    <i class="fa-solid fa-chevron-right nav-chevron" id="postsub-chev"></i>
                </div>
                <div class="submenu" id="postsub">
                    <a class="submenu-item" href="#">All Posts</a>
                    <a class="submenu-item" href="#">Create Post</a>
                    <a class="submenu-item" href="#">Post Categories</a>
                    <a class="submenu-item" href="#">Archived Posts</a>
                </div>
            </div>

            <div class="nav-item-wrap">
                <div class="nav-link-custom" onclick="toggleSubmenu('projsub',this)">
                    <div class="nav-icon"><i class="fa-solid fa-diagram-project"></i></div>
                    <span class="nav-text">Projects</span>
                    <i class="fa-solid fa-chevron-right nav-chevron" id="projsub-chev"></i>
                </div>
                <div class="submenu" id="projsub">
                    <a class="submenu-item" href="#">Active Projects</a>
                    <a class="submenu-item" href="#">Completed</a>
                    <a class="submenu-item" href="#">Project Reports</a>
                </div>
            </div>

            <div class="nav-item-wrap">
                <a class="nav-link-custom" href="#">
                    <div class="nav-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <span class="nav-text">Activity Log</span>
                </a>
            </div> -->

        <div class="sidebar-section-label">Management</div>

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


        <div class="nav-item-wrap">
            <div class="nav-link-custom {{ $isEngineerActive ? 'active' : '' }}" onclick="toggleSubmenu('engineersub',this)">
                <div class="nav-icon"><i class="fa-solid fa-user-gear"></i></div>
                <span class="nav-text">Engineers</span>
                <i class="fa-solid fa-chevron-right nav-chevron" id="engineersub-chev"></i>
            </div>
            <div class="submenu" id="engineersub">
                <a class="submenu-item {{ request()->routeIs('admin.engineers.index') ? 'active' : '' }}" href="{{ $engineerIndexRoute }}">Engineer List</a>
                <a class="submenu-item {{ request()->routeIs('admin.engineers.create') ? 'active' : '' }}" href="{{ $engineerCreateRoute }}">Add Engineer</a>
            </div>
        </div>

        <div class="sidebar-section-label">Components Management</div>


        <div class="nav-item-wrap">
            <div class="nav-link-custom {{ $isPostTypeActive ? 'active' : '' }}" onclick="toggleSubmenu('posttypesub',this)">
                <div class="nav-icon"><i class="fa-solid fa-hard-hat"></i></div>
                <span class="nav-text">Post Types</span>
                <i class="fa-solid fa-chevron-right nav-chevron" id="posttypesub-chev"></i>
            </div>
            <div class="submenu" id="posttypesub">
                <a class="submenu-item {{ request()->routeIs('admin.post-types.index') ? 'active' : '' }}" href="{{ $postTypeIndexRoute }}">Post Type List</a>
                <a class="submenu-item {{ request()->routeIs('admin.post-types.create') ? 'active' : '' }}" href="{{ $postTypeCreateRoute }}">Add Post Type</a>
            </div>
        </div>

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

        <!-- <div class="nav-item-wrap">
            <div class="nav-link-custom {{ $isDepartmentActive ? 'active' : '' }}" onclick="toggleSubmenu('departmentsub',this)">
                <div class="nav-icon"><i class="fa-solid fa-building-user"></i></div>
                <span class="nav-text">Department</span>
                <i class="fa-solid fa-chevron-right nav-chevron" id="departmentsub-chev"></i>
            </div>
            <div class="submenu" id="departmentsub">
                <a class="submenu-item {{ request()->routeIs('admin.departments.index') ? 'active' : '' }}" href="{{ $departmentIndexRoute }}">Department List</a>
                <a class="submenu-item {{ request()->routeIs('admin.departments.create') ? 'active' : '' }}" href="{{ $departmentCreateRoute }}">Add Department</a>
            </div>
        </div> -->

        <!-- <div class="nav-item-wrap">
            <a class="nav-link-custom {{ $isRequisitionActive ? 'active' : '' }}" href="{{ $adminRequisitionIndexRoute }}">
                <div class="nav-icon"><i class="fa-solid fa-hotel"></i></div>
                <span class="nav-text">Requisitions</span>
            </a>
        </div> -->

        <div class="nav-item-wrap">
            <div class="nav-link-custom {{ $isBlockActive ? 'active' : '' }}" onclick="toggleSubmenu('blocksub',this)">
                <div class="nav-icon"><i class="fa-solid fa-cubes-stacked"></i></div>
                <span class="nav-text">Blocks</span>
                <i class="fa-solid fa-chevron-right nav-chevron" id="blocksub-chev"></i>
            </div>
            <div class="submenu" id="blocksub">
                <a class="submenu-item {{ request()->routeIs('admin.blocks.index') ? 'active' : '' }}" href="{{ $blockIndexRoute }}">Block List</a>
                <a class="submenu-item {{ request()->routeIs('admin.blocks.create') ? 'active' : '' }}" href="{{ $blockCreateRoute }}">Add Block</a>
            </div>
        </div>

        @else
        <div class="nav-item-wrap">
            <a class="nav-link-custom {{ $isProfileActive ? 'active' : '' }}" href="{{ $profileRoute }}">
                <div class="nav-icon"><i class="fa-solid fa-id-card"></i></div>
                <span class="nav-text">My Profile</span>
            </a>
        </div>

        <div class="nav-item-wrap">
            <div class="nav-link-custom {{ $isRequisitionActive ? 'active' : '' }}" onclick="toggleSubmenu('usersubreq',this)">
                <div class="nav-icon"><i class="fa-solid fa-hotel"></i></div>
                <span class="nav-text">Requisitions</span>
                <i class="fa-solid fa-chevron-right nav-chevron" id="usersubreq-chev"></i>
            </div>
            <div class="submenu" id="usersubreq">
                <a class="submenu-item {{ request()->routeIs('requisitions.index') ? 'active' : '' }}" href="{{ $userRequisitionIndexRoute }}">My Requisitions</a>
                <a class="submenu-item {{ request()->routeIs('requisitions.create') ? 'active' : '' }}" href="{{ $userRequisitionCreateRoute }}">New Requisition</a>
            </div>
        </div>

        <div class="nav-item-wrap">
            <a class="nav-link-custom" href="#">
                <div class="nav-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <span class="nav-text">My Activity</span>
            </a>
        </div>

        <div class="sidebar-section-label">Account</div>
        @endif

        <div class="nav-item-wrap">
            <div class="nav-link-custom" onclick="toggleSubmenu('settingsub',this)">
                <div class="nav-icon"><i class="fa-solid fa-sliders"></i></div>
                <span class="nav-text">Settings</span>
                <i class="fa-solid fa-chevron-right nav-chevron" id="settingsub-chev"></i>
            </div>
            <div class="submenu" id="settingsub">
                <a class="submenu-item {{ $isProfileActive ? 'active' : '' }}" href="{{ $profileRoute }}">Profile</a>
                <a class="submenu-item" href="javascript:void(0)" onclick="openPasswordResetModal(event); return false;">Change Password</a>
                <a class="submenu-item" href="{{ route('logout') }}">Sign Out</a>
            </div>
        </div>

        <div class="nav-item-wrap">
            <a class="nav-link-custom" href="#" onclick="activateLockScreen(); return false;">
                <div class="nav-icon"><i class="fa-solid fa-lock"></i></div>
                <span class="nav-text">Lock Screen</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    @if($sidebarUser && $sidebarUser->photo)
                    <img src="{{ asset('storage/photos/' . $sidebarUser->photo) }}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                    @else
                    {{ $sidebarInitials }}
                    @endif
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ $sidebarUser->name ?? 'Guest User' }}</div>
                    <small>{{ $sidebarUser->email ?? 'guest@domain.com' }}</small>
                </div>
            </div>
        </div>
        </div>
    </aside>