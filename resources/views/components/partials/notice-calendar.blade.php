<link rel="stylesheet" href="{{ asset('css/calendar-admin.css') }}">
<!-- Notices & Calendar Row -->
<div class="row g-3 mt-2">
    <!-- Notice List Column -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; height: 100%;">
            <div class="card-header bg-white border-0 pt-4 pb-2" style="border-radius: 12px 12px 0 0;">
                <h4 class="card-title fw-bold text-dark mb-0" style="font-size: 1.15rem;"><i class="fa-solid fa-bullhorn text-primary me-2"></i> Recent Notices</h4>
            </div>
            <div class="card-body p-0" style="max-height: 440px; overflow-y: auto;">
                @forelse(collect($dashboardNotices)->take(5) as $notice)
                @php
                $badgeClass = 'bg-primary';
                if ($notice->notice_type === 'warning') $badgeClass = 'bg-danger';
                if ($notice->notice_type === 'new') $badgeClass = 'bg-success';
                if ($notice->notice_type === 'info') $badgeClass = 'bg-info text-dark';
                @endphp
                <div class="p-3 border-bottom hover-bg-light" style="transition: background 0.2s;">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">
                            <span class="badge {{ $badgeClass }} me-1" style="font-size: 0.65rem;">{{ strtoupper($notice->notice_type) }}</span>
                            {{ $notice->title }}
                        </h6>
                        <small class="text-muted" style="font-size: 0.75rem; white-space: nowrap; margin-left: 10px;">{{ $notice->created_at->format('d M') }}</small>
                    </div>
                    <div class="text-muted small mt-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; font-size: 0.8rem;">
                        {!! strip_tags(html_entity_decode($notice->message)) !!}
                    </div>
                    <div class="mt-2 text-end">
                        <button class="btn btn-sm btn-link text-decoration-none p-0 fw-bold" onclick="showSingleNoticeModal({{ $notice->id }})">Read More <i class="fa-solid fa-arrow-right fs-xs"></i></button>
                    </div>

                    <!-- Hidden Content for Modal -->
                    <div id="notice-content-{{ $notice->id }}" style="display: none;">
                        <div class="full-message">{!! $notice->message !!}</div>
                        <div class="creator-name">{{ $notice->creator ? $notice->creator->name : 'System' }}</div>
                        <div class="created-date">{{ $notice->created_at->format('d M, Y h:i A') }}</div>
                        <div class="notice-title">{{ $notice->title }}</div>
                        <div class="badge-class">{{ $badgeClass }}</div>
                        <div class="type-label">{{ strtoupper($notice->notice_type) }}</div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-muted">
                    <i class="fa-regular fa-folder-open fs-2 mb-2"></i>
                    <p class="mb-0 small">No recent notices available.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Calendar Column -->
    <div class="col-12 col-lg-6">
        <div class="calendar-admin-container" id="calendarApp" style="height: 100%; position: relative; padding-bottom: {{ isset($currentMonthReceivedCount) ? '3.5rem' : '1rem' }};">
            <div class="calendar-header">
                <h4 style="font-size: 1.15rem; display: flex; align-items: center;"><i class="fa-regular fa-calendar-alt text-primary me-2"></i> <span id="monthYearDisplay">Month Year</span></h4>
                <div class="nav-buttons">
                    <button id="prevMonthBtn"><i class="fas fa-chevron-left"></i></button>
                    <button id="nextMonthBtn"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="weekdays">
                <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span>
                <span>Thu</span><span>Fri</span><span>Sat</span>
            </div>
            <div class="days-grid" id="daysGrid"></div>

            @if(isset($currentMonthReceivedCount))
            <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 12px; border-top: 1px solid #eef2f7; background: #fcfcfc; border-radius: 0 0 12px 12px; text-align: center; font-size: 0.85rem; color: #495057; font-weight: 600;">
                <i class="fa-solid fa-inbox text-primary me-1"></i> Application Received Current Month: <span class="badge bg-primary rounded-pill ms-1" style="font-size: 0.8rem;">{{ $currentMonthReceivedCount }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Notices Modal -->
<div class="modal fade" id="noticesModal" tabindex="-1" aria-labelledby="noticesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noticesModalLabel">Events for <span id="noticesModalDate"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="noticesModalBody">
                <!-- Notices will be injected here -->
            </div>
        </div>
    </div>
</div>

<!-- Single Notice Read More Modal -->
<div class="modal fade" id="readMoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="readMoreTitle">Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="mb-3">
                    <span id="readMoreBadge" class="badge"></span>
                </div>
                <div id="readMoreMessage" style="color: #444; line-height: 1.6;"></div>
                <hr class="my-3 text-muted">
                <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                    <span><i class="fa-solid fa-user me-1"></i> Published by: <span id="readMoreCreator"></span></span>
                    <span><i class="fa-regular fa-clock me-1"></i> <span id="readMoreDate"></span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.adminNotices = @json($dashboardNotices ?? []);
    window.adminNotifications = @json($dashboardNotifications ?? []);
    window.adminForwardedApps = @json($dashboardForwardedApps ?? []);

    window.showNoticesModal = function(dateStr, dayData) {
        document.getElementById('noticesModalDate').textContent = dateStr;
        const body = document.getElementById('noticesModalBody');
        body.innerHTML = '';

        const notices = dayData.notices || [];
        const notifications = dayData.notifications || [];
        const forwardedApps = dayData.forwardedApps || [];

        if (notices.length === 0 && notifications.length === 0 && forwardedApps.length === 0) {
            body.innerHTML = '<p class="text-muted">No items for this date.</p>';
        } else {
            // Render Notices
            notices.forEach(notice => {
                let badgeClass = 'bg-primary';
                if (notice.notice_type === 'warning') badgeClass = 'bg-danger';
                if (notice.notice_type === 'new') badgeClass = 'bg-success';
                if (notice.notice_type === 'info') badgeClass = 'bg-info text-dark';

                let typeLabel = (notice.notice_type || 'notice').toUpperCase();

                let creatorName = notice.creator ? notice.creator.name : 'System';
                let createdDate = new Date(notice.created_at).toLocaleString();

                let html = `
                    <div class="card mb-3 border-0 shadow-sm" style="border-left: 4px solid #3b82f6 !important;">
                        <div class="card-body">
                            <h6 class="fw-bold">
                                <span class="badge ${badgeClass} me-2">${typeLabel}</span>
                                <span style="color:green;">${notice.title}</span>
                            </h6>
                            <div class="card-text small mt-2" style="color: #444;">${notice.message}</div>
                            <hr class="my-2 text-muted">
                            <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                                <span><i class="fa-solid fa-user me-1"></i> Published by: ${creatorName}</span>
                                <span><i class="fa-regular fa-clock me-1"></i> ${createdDate}</span>
                            </div>
                        </div>
                    </div>
                `;
                body.innerHTML += html;
            });

            // Render Notifications
            notifications.forEach(notif => {
                let createdDate = new Date(notif.created_at).toLocaleString();
                let html = `
                    <div class="card mb-3 border-0 shadow-sm" style="border-left: 4px solid #8b5cf6 !important; background-color: #fcfaff;">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark">
                                <span class="badge me-2" style="background-color: #8b5cf6;"><i class="fa-solid fa-bell me-1"></i> NOTIFICATION</span>
                                ${notif.subject || 'System Notification'}
                            </h6>
                            <div class="card-text small mt-2" style="color: #444;">${notif.message || ''}</div>
                            <hr class="my-2 text-muted">
                            <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                                <span><i class="fa-solid fa-paper-plane me-1"></i> System Auto-Generated</span>
                                <span><i class="fa-regular fa-clock me-1"></i> ${createdDate}</span>
                            </div>
                        </div>
                    </div>
                `;
                body.innerHTML += html;
            });

            // Render Forwarded Apps
            forwardedApps.forEach(app => {
                let createdDate = new Date(app.created_date).toLocaleString();
                let html = `
                    <div class="card mb-3 border-0 shadow-sm" style="border-left: 4px solid #fa5c7c !important; background-color: #fff0f2;">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark">
                                <span class="badge me-2 bg-danger"><i class="fa-solid fa-file-signature me-1"></i> FORWARDED APP</span>
                                App No: ${app.application_no}
                            </h6>
                            <div class="card-text small mt-2" style="color: #444;">
                                <strong>Type:</strong> <span class="text-uppercase">${app.application_type}</span><br>
                                <strong>Allottee:</strong> ${app.allottee_name || 'N/A'}
                            </div>
                            <hr class="my-2 text-muted">
                            <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                                <span><i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Pending action</span>
                                <span><i class="fa-regular fa-clock me-1"></i> ${createdDate}</span>
                            </div>
                        </div>
                    </div>
                `;
                body.innerHTML += html;
            });
        }

        var myModal = new bootstrap.Modal(document.getElementById('noticesModal'));
        myModal.show();
    };

    window.showSingleNoticeModal = function(id) {
        const container = document.getElementById(`notice-content-${id}`);
        if (!container) return;

        document.getElementById('readMoreTitle').textContent = container.querySelector('.notice-title').textContent;
        const badge = document.getElementById('readMoreBadge');
        badge.className = `badge ${container.querySelector('.badge-class').textContent}`;
        badge.textContent = container.querySelector('.type-label').textContent;

        document.getElementById('readMoreMessage').innerHTML = container.querySelector('.full-message').innerHTML;
        document.getElementById('readMoreCreator').textContent = container.querySelector('.creator-name').textContent;
        document.getElementById('readMoreDate').textContent = container.querySelector('.created-date').textContent;

        var myModal = new bootstrap.Modal(document.getElementById('readMoreModal'));
        myModal.show();
    };
</script>
<script src="{{ asset('js/calendar-admin.js') }}"></script>
