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
        <!-- Clear Cache Removed from Header -->

        <!-- Search -->
        <!-- <button class="header-icon-btn" title="Search">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button> -->

        {{-- @if($authUser?->user_type === 'engineer' || $authUser?->user_type === 'accountant' || $authUser?->user_type === 'administration') --}}
            <span class="header-user-role-badge">
                <i class="fa-solid fa-user-gear" style="margin-right: 8px; color: var(--pink-color);"></i>
                {{ $authUser->roleRelation?->name ?: ucfirst($authUser?->user_type) }} &nbsp; <strong>({{ $authUser->division?->name ?: 'Administration' }})</strong>
            </span>
        {{-- @endif --}}

        <!-- Lock Screen -->
        <button class="header-icon-btn header-lock-btn" title="Lock Screen" onclick="activateLockScreen()">
            <i class="fa-solid fa-lock"></i>
        </button>
        <!-- Notifications -->
        <div style="position:relative">
            <button class="header-icon-btn" id="notifBtn" onclick="toggleNotif()" title="Notifications">
                <i class="fa-solid fa-bell"></i>
                <span class="notif-dot" id="notifBellDot" style="{{ (isset($unreadNotifCount) && $unreadNotifCount > 0) ? '' : 'display:none;' }}"></span>
            </button>
            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-head">
                    <span class="notif-head-title">Notifications
                        <span id="notifCountBadge" style="font-size:11px;background:var(--pink-light);color:var(--primary-color);border-radius:20px;padding:2px 7px;margin-left:5px;{{ (isset($unreadNotifCount) && $unreadNotifCount > 0) ? '' : 'display:none;' }}">
                            {{ $unreadNotifCount ?? 0 }} New
                        </span>
                    </span>
                    <span class="notif-mark" id="markAllReadBtn" style="cursor:pointer;{{ (isset($unreadNotifCount) && $unreadNotifCount > 0) ? '' : 'display:none;' }}" onclick="markAllNotificationsRead()">Mark all read</span>
                </div>

                <div id="notifItemsList" style="max-height:360px; overflow-y:auto;">
                @if(isset($headerNotifications) && count($headerNotifications) > 0)
                    @foreach($headerNotifications as $notif)
                    <div class="notif-item {{ !$notif->is_read ? 'unread' : '' }}" id="notif-item-{{ $notif->id }}" onclick="markSingleNotificationRead({{ $notif->id }}, '{{ $notif->link ?? '' }}')" style="cursor:pointer;">
                        <div class="notif-avatar {{ $notif->notification_type == 'success' ? 'green' : ($notif->notification_type == 'warning' || $notif->notification_type == 'document_request' ? 'pink' : 'sky') }}">
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
                                <strong>{{ $notif->subject }}</strong>: {{ \Illuminate\Support\Str::limit($notif->message, 50) }}
                            </div>
                            <div class="notif-time"><i class="fa-regular fa-clock"></i> {{ $notif->created_at ? $notif->created_at->diffForHumans() : 'Just now' }}</div>
                        </div>
                        @if(!$notif->is_read)
                            <div class="unread-dot"></div>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="notif-empty" id="notifEmptyState" style="text-align:center; padding: 25px 15px; color: #888;">
                        <i class="fa-regular fa-bell-slash" style="font-size:22px; display:block; margin-bottom:6px; opacity:0.6;"></i>
                        <span style="font-size:12px;">No notifications yet</span>
                    </div>
                @endif
                </div>

                <div class="notif-footer" style="padding:10px 15px; text-align:center; border-top:1px solid #f0f0f0; background:#fafafa; border-radius:0 0 12px 12px;">
                    <a href="{{ route('my-notifications.index') }}" style="font-size:12px; font-weight:600; color:var(--primary-color, #1f7b4d); text-decoration:none;">
                        View All Notifications <i class="fa-solid fa-arrow-right" style="font-size:10px; margin-left:4px;"></i>
                    </a>
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
                    <img src="{{ route('media.profile', ['filename' => $authUser->photo ?? 'default', 'user_id' => $authUser->id ?? '']) }}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                </div>
                <div class="profile-user-info" style="text-align:left">
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

<script>
function handleClearCache(event, element) {
    event.preventDefault();
    const originalHtml = element.innerHTML;
    element.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="margin-right: 5px;"></i> <span>Clearing...</span>';
    element.style.pointerEvents = 'none';

    fetch(element.href, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (response.ok) {
            if (typeof toastr !== 'undefined') {
                toastr.success('Cache cleared successfully!');
            } else if (typeof Swal !== 'undefined') {
                Swal.fire('Success', 'Cache cleared successfully!', 'success');
            } else {
                alert('Cache cleared successfully!');
            }
        } else {
            throw new Error('Failed to clear cache');
        }
    })
    .catch(error => {
        console.error(error);
        if (typeof toastr !== 'undefined') {
            toastr.error('Error clearing cache.');
        } else {
            alert('Error clearing cache.');
        }
    })
    .finally(() => {
        element.innerHTML = originalHtml;
        element.style.pointerEvents = 'auto';
    });
}

function markSingleNotificationRead(id, link) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    fetch('/notifications/' + id + '/read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        const item = document.getElementById('notif-item-' + id);
        if (item) {
            item.classList.remove('unread');
            const dot = item.querySelector('.unread-dot');
            if (dot) dot.remove();
        }
        if (typeof data.unread_count !== 'undefined') {
            updateNotificationBadgeCount(data.unread_count);
        }
        if (link && link.trim() !== '') {
            window.location.href = link;
        }
    })
    .catch(err => {
        console.error('Error marking notification as read:', err);
        if (link && link.trim() !== '') {
            window.location.href = link;
        }
    });
}

function markAllNotificationsRead() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    fetch('/notifications/mark-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            document.querySelectorAll('.unread-dot').forEach(el => el.remove());
            document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
            updateNotificationBadgeCount(0);
        }
    })
    .catch(err => console.error('Error marking all notifications as read:', err));
}

function updateNotificationBadgeCount(count) {
    const bellDot = document.getElementById('notifBellDot');
    const badge = document.getElementById('notifCountBadge');
    const markAllBtn = document.getElementById('markAllReadBtn');

    if (count > 0) {
        if (bellDot) bellDot.style.display = 'block';
        if (badge) {
            badge.innerText = count + ' New';
            badge.style.display = 'inline-block';
        }
        if (markAllBtn) markAllBtn.style.display = 'inline-block';
    } else {
        if (bellDot) bellDot.style.display = 'none';
        if (badge) badge.style.display = 'none';
        if (markAllBtn) markAllBtn.style.display = 'none';
    }
}

window.addNewNotificationToDropdown = function(notif) {
    const list = document.getElementById('notifItemsList');
    if (!list) return;

    // Remove empty placeholder
    const emptyState = document.getElementById('notifEmptyState');
    if (emptyState) emptyState.remove();

    // Determine avatar styles
    let avatarClass = 'sky';
    let iconClass = 'fa-bell';
    const type = notif.type || notif.notification_type || 'info';

    if (type === 'success') {
        avatarClass = 'green';
        iconClass = 'fa-circle-check';
    } else if (type === 'warning' || type === 'document_request') {
        avatarClass = 'pink';
        iconClass = 'fa-triangle-exclamation';
    }

    const notifId = notif.id || Date.now();
    const subject = notif.subject || notif.title || 'New Notification';
    const message = notif.message || '';
    const link = notif.link || notif.action_url || '';
    const timeAgo = notif.time_ago || 'Just now';
    const shortMsg = message.length > 50 ? message.substring(0, 50) + '...' : message;

    const div = document.createElement('div');
    div.className = 'notif-item unread';
    div.id = 'notif-item-' + notifId;
    div.style.cursor = 'pointer';
    div.onclick = function() {
        markSingleNotificationRead(notifId, link);
    };

    const escapeHtml = (str) => {
        if (!str) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return str.toString().replace(/[&<>"']/g, m => map[m]);
    };

    div.innerHTML = `
        <div class="notif-avatar ${avatarClass}">
            <i class="fa-solid ${iconClass}"></i>
        </div>
        <div class="notif-body">
            <div class="notif-msg">
                <strong>${escapeHtml(subject)}</strong>: ${escapeHtml(shortMsg)}
            </div>
            <div class="notif-time"><i class="fa-regular fa-clock"></i> ${timeAgo}</div>
        </div>
        <div class="unread-dot"></div>
    `;

    // Prepend to list
    list.insertBefore(div, list.firstChild);

    // Keep max 10
    while (list.children.length > 10) {
        list.removeChild(list.lastChild);
    }
};
</script>
