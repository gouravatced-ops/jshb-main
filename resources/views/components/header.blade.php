<!-- HEADER -->
@php
$authUser = auth()->user();
$profileRoute = $authUser?->user_type === 'administration' ? route('admin.profile') : route('profile');
$profileInitials = 'U';
if ($authUser && ! empty($authUser->name)) {
$nameParts = preg_split('/\s+/', trim($authUser->name));
$profileInitials = strtoupper(($nameParts[0][0] ?? 'U') . ($nameParts[1][0] ?? ''));
}
@endphp
<header id="header">
    <button class="header-toggle" id="sidebarToggle" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </button>
    <div class="header-breadcrumb">
        <div class="breadcrumb-title" id="pageTitle">{{ config('panel.app_name') }}</div>
        <!-- subtitle  -->
        <span class="breadcrumb-sub">({{ config('panel.organization') }})</span>
    </div>

    @if(session()->has('session_expires_at_ts'))
    <div class="session-timer" id="sessionTimer"
        data-expiry-ts="{{ session('session_expires_at_ts') }}"
        data-logout-url="{{ route('logout', ['auto' => 1]) }}">
        <i class="fa-solid fa-clock"></i>
        <span>Session</span>
        <strong id="sessionCountdown">00:00</strong>
    </div>
    @endif

    <div class="header-actions">
        <!-- Search -->
        <!-- <button class="header-icon-btn" title="Search">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button> -->

        @if($authUser?->user_type === 'engineer')
            <span style="font-size: 13px; color: var(--text-dark); margin-right: 15px; font-weight: 600; display: inline-flex; align-items: center; background: rgba(255, 255, 255, 0.05); padding: 6px 12px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.1);">
                <i class="fa-solid fa-user-gear" style="margin-right: 8px; color: var(--pink-color);"></i>
                {{ $authUser->roleRelation?->name ?: 'Engineer' }} &nbsp; <strong>({{ $authUser->division?->name ?: 'No Division' }})</strong>
            </span>
        @endif

        <!-- Lock Screen -->
        <button class="header-icon-btn" title="Lock Screen" onclick="activateLockScreen()">
            <i class="fa-solid fa-lock"></i>
        </button>
        <!-- Notifications -->
        <div style="position:relative">
            <!-- <button class="header-icon-btn" id="notifBtn" onclick="toggleNotif()" title="Notifications">
                <i class="fa-solid fa-bell"></i>
                <span class="notif-dot"></span>
            </button> -->
            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-head">
                    <span class="notif-head-title">Notifications <span
                            style="font-size:11px;background:var(--pink-light);color:var(--primary-color);border-radius:20px;padding:2px 7px;margin-left:5px;">4
                            New</span></span>
                    <span class="notif-mark">Mark all read</span>
                </div>
                <div class="notif-item">
                    <div class="notif-avatar pink"><i class="fa-solid fa-hard-hat"></i></div>
                    <div class="notif-body">
                        <div class="notif-msg">New engineer post submitted for approval — Rahul Verma</div>
                        <div class="notif-time"><i class="fa-regular fa-clock"></i> 5 mins ago</div>
                    </div>
                    <div class="unread-dot"></div>
                </div>
                <div class="notif-item">
                    <div class="notif-avatar sky"><i class="fa-solid fa-file-lines"></i></div>
                    <div class="notif-body">
                        <div class="notif-msg">Post #EP-0042 has been approved by Department Head</div>
                        <div class="notif-time"><i class="fa-regular fa-clock"></i> 22 mins ago</div>
                    </div>
                    <div class="unread-dot"></div>
                </div>
                <div class="notif-item">
                    <div class="notif-avatar green"><i class="fa-solid fa-circle-check"></i></div>
                    <div class="notif-body">
                        <div class="notif-msg">Project Alpha milestone completed successfully</div>
                        <div class="notif-time"><i class="fa-regular fa-clock"></i> 1 hour ago</div>
                    </div>
                    <div class="unread-dot"></div>
                </div>
                <div class="notif-item">
                    <div class="notif-avatar pink"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div class="notif-body">
                        <div class="notif-msg">System maintenance scheduled for tonight 11 PM</div>
                        <div class="notif-time"><i class="fa-regular fa-clock"></i> 3 hours ago</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <!-- <button class="header-icon-btn" title="Messages">
            <i class="fa-solid fa-envelope"></i>
            <span class="notif-dot sky"></span>
        </button> -->

        <!-- Profile -->
        <div style="position:relative">
            <button class="profile-btn" id="profileBtn" onclick="toggleProfile()">
                <div class="profile-avatar">
                    @if($authUser && $authUser->photo)
                    <img src="{{ asset('storage/photos/' . $authUser->photo) }}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                    @else
                    {{ $profileInitials }}
                    @endif
                </div>
                <div style="text-align:left">
                    <div class="profile-name">{{ $authUser->name ?? 'Guest User' }}</div>
                    <div class="profile-role">{{ ucfirst($authUser->roleRelation->name ?? 'User') }}</div>
                </div>
                <i class="fa-solid fa-chevron-down profile-chevron"></i>
            </button>
            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-drop-head">
                    <!-- <div class="profile-drop-avatar">AS</div> -->
                    <div>
                        <div class="profile-drop-name">{{ $authUser->name ?? 'Guest User' }}</div>
                        <div class="profile-drop-role">{{ $authUser->email ?? 'no-email@domain.com' }}</div>
                    </div>
                </div>
                <a class="profile-drop-item" href="{{ $profileRoute }}"><i class="fa-solid fa-user"></i> My Profile</a>
                <!-- <a class="profile-drop-item" href="{{ $profileRoute }}"><i class="fa-solid fa-id-card"></i> Account Details</a> -->
                <a class="profile-drop-item" href="javascript:void(0)" onclick="openPasswordResetModal(event); return false;"><i class="fa-solid fa-lock"></i> Change Password</a>
                <!-- <a class="profile-drop-item" href="#"><i class="fa-solid fa-gear"></i> Preferences</a> -->
                <!-- <a class="profile-drop-item" href="#"><i class="fa-solid fa-circle-question"></i> Help & Support</a> -->
                <a class="profile-drop-item danger" href="{{ route('logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Sign
                    Out</a>
            </div>
        </div>
    </div>
</header>