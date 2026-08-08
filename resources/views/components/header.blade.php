<!-- HEADER -->
@php
$authUser = auth()->user();
$profileRoute = $authUser?->role === 'user' ? route('profile') : route($authUser?->role . '.profile');
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

        {{-- @if($authUser?->user_type === 'engineer' || $authUser?->user_type === 'accountant' || $authUser?->user_type === 'administration') --}}
            <span style="font-size: 13px; color: var(--text-dark); margin-right: 15px; font-weight: 600; display: inline-flex; align-items: center; background: rgba(255, 255, 255, 0.05); padding: 6px 12px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.1);">
                <i class="fa-solid fa-user-gear" style="margin-right: 8px; color: var(--pink-color);"></i>
                {{ $authUser->roleRelation?->name ?: ucfirst($authUser?->user_type) }} &nbsp; <strong>({{ $authUser->division?->name ?: 'Administration' }})</strong>
            </span>
        {{-- @endif --}}

        <!-- Lock Screen -->
        <button class="header-icon-btn" title="Lock Screen" onclick="activateLockScreen()">
            <i class="fa-solid fa-lock"></i>
        </button>
        <!-- Notifications -->
        <div style="position:relative">
            <button class="header-icon-btn" id="notifBtn" onclick="toggleNotif()" title="Notifications">
                <i class="fa-solid fa-bell"></i>
                @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
                <span class="notif-dot"></span>
                @endif
            </button>
            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-head">
                    <span class="notif-head-title">Notifications 
                        @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
                        <span style="font-size:11px;background:var(--pink-light);color:var(--primary-color);border-radius:20px;padding:2px 7px;margin-left:5px;">
                            {{ $unreadNotifCount }} New
                        </span>
                        @endif
                    </span>
                    @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
                    <span class="notif-mark" style="cursor:pointer;" onclick="markAllNotificationsRead()">Mark all read</span>
                    @endif
                </div>
                
                @if(isset($headerNotifications) && count($headerNotifications) > 0)
                    @foreach($headerNotifications as $notif)
                    <div class="notif-item">
                        <div class="notif-avatar {{ $notif->notification_type == 'success' ? 'green' : ($notif->notification_type == 'warning' ? 'pink' : 'sky') }}">
                            @if($notif->notification_type == 'success')
                                <i class="fa-solid fa-circle-check"></i>
                            @elseif($notif->notification_type == 'warning' || $notif->notification_type == 'document_request')
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            @else
                                <i class="fa-solid fa-bell"></i>
                            @endif
                        </div>
                        <div class="notif-body">
                            <div class="notif-msg">
                                @if($notif->link)
                                    <a href="{{ $notif->link }}" style="text-decoration:none; color:inherit;">
                                        <strong>{{ $notif->subject }}</strong>: {{ \Illuminate\Support\Str::limit($notif->message, 50) }}
                                    </a>
                                @else
                                    <strong>{{ $notif->subject }}</strong>: {{ \Illuminate\Support\Str::limit($notif->message, 50) }}
                                @endif
                            </div>
                            <div class="notif-time"><i class="fa-regular fa-clock"></i> {{ $notif->created_at->diffForHumans() }}</div>
                        </div>
                        @if(!$notif->is_read)
                            <div class="unread-dot"></div>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="notif-item" style="justify-content:center; padding: 20px;">
                        <span class="text-muted">No notifications</span>
                    </div>
                @endif
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
                    <img src="{{ route('media.profile', ['filename' => $authUser->photo ?? 'default', 'user_id' => $authUser->id ?? '']) }}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
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
                <a class="profile-drop-item" href="javascript:void(0)" onclick="openQuickPinModal(event); return false;"><i class="fa-solid fa-th-large"></i> Set Quick PIN</a>
                <a class="profile-drop-item" href="{{ route('my-activity') }}"><i class="fa-solid fa-user-clock"></i> My Acitivity</a>
                <!-- <a class="profile-drop-item" href="#"><i class="fa-solid fa-gear"></i> Preferences</a> -->
                <!-- <a class="profile-drop-item" href="#"><i class="fa-solid fa-circle-question"></i> Help & Support</a> -->
                <a class="profile-drop-item danger" href="{{ route('logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Sign
                    Out</a>
            </div>
        </div>
    </div>
</header>