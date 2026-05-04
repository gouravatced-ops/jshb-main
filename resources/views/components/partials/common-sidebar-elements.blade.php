<!-- Common Settings and Footer for all roles -->
<div class="nav-item-wrap">
    <div class="nav-link-custom" onclick="toggleSubmenu('settingsub',this)">
        <div class="nav-icon"><i class="fa-solid fa-sliders"></i></div>
        <span class="nav-text">Settings</span>
        <i class="fa-solid fa-chevron-right nav-chevron" id="settingsub-chev"></i>
    </div>
    <div class="submenu" id="settingsub">
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
            <span>{{ $sidebarUser->email ?? 'guest@domain.com' }}</span>
        </div>
    </div>
</div>