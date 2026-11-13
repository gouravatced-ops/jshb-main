@extends('layouts.main')

@section('title', 'Reverb Real-Time Live Notification Test | JSHB')

@section('content')
<div class="container-fluid py-4" style="max-width: 1200px; margin: 0 auto;">
    
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1 fw-bold text-dark" style="font-size: 1.35rem;">
                <i class="fa-solid fa-satellite-dish text-success me-2"></i> Real-Time WebSocket Notification Test Console
            </h4>
            <p class="text-muted small mb-0">
                Test on-the-fly live notifications between officers. <span class="badge bg-light text-dark border">Zero Database Writes</span> (Pure In-Memory Reverb Broadcast)
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span id="reverbConnectionStatus" class="badge rounded-pill bg-secondary px-3 py-2" style="font-size: 12px;">
                <i class="fa-solid fa-spinner fa-spin me-1"></i> Checking WebSocket...
            </span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Sender Info & Test Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fw-bold text-dark small text-uppercase" style="letter-spacing: 0.5px;">
                            <i class="fa-solid fa-paper-plane text-primary me-2"></i> Dispatch Live Notification
                        </span>
                        <span class="badge bg-info-subtle text-info border border-info-subtle small">
                            Sender: {{ $currentUser->name }} (#{{ $currentUser->id }})
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="liveNotifForm">
                        @csrf

                        <!-- Recipient Officer -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted mb-1">
                                Target Officer (Recipient) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <select class="form-select" id="targetUserId" name="target_user_id" required>
                                    <option value="" disabled selected>-- Select Officer / Recipient --</option>
                                    @foreach($officers as $officer)
                                        <option value="{{ $officer->id }}" {{ $officer->id === $currentUser->id ? 'data-self="true"' : '' }}>
                                            {{ $officer->name }} ({{ ucfirst($officer->roleRelation->name ?? $officer->user_type ?? 'Officer') }}) - #{{ $officer->id }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-success btn-sm px-3" onclick="selectMyself()" title="Select your own account to see the alert on your current screen">
                                    <i class="fa-solid fa-user-check me-1"></i> Test on Myself
                                </button>
                            </div>
                            <div class="form-text small text-muted">
                                Select another logged-in officer (in another window/browser) or select yourself to test immediately.
                            </div>
                        </div>

                        <!-- Notification Type -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted mb-1">Notification Alert Type</label>
                            <div class="d-flex gap-2 flex-wrap" id="typeButtons">
                                <label class="btn btn-sm btn-outline-success active px-3" style="cursor: pointer;">
                                    <input type="radio" name="type" value="success" class="d-none" checked onchange="updateTypeSelection(this)">
                                    <i class="fa-solid fa-circle-check me-1"></i> Success
                                </label>
                                <label class="btn btn-sm btn-outline-primary px-3" style="cursor: pointer;">
                                    <input type="radio" name="type" value="info" class="d-none" onchange="updateTypeSelection(this)">
                                    <i class="fa-solid fa-circle-info me-1"></i> Info
                                </label>
                                <label class="btn btn-sm btn-outline-warning px-3" style="cursor: pointer;">
                                    <input type="radio" name="type" value="warning" class="d-none" onchange="updateTypeSelection(this)">
                                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Warning
                                </label>
                                <label class="btn btn-sm btn-outline-danger px-3" style="cursor: pointer;">
                                    <input type="radio" name="type" value="error" class="d-none" onchange="updateTypeSelection(this)">
                                    <i class="fa-solid fa-circle-xmark me-1"></i> Error
                                </label>
                            </div>
                        </div>

                        <!-- Subject -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted mb-1">
                                Subject / Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="notifSubject" name="subject"
                                   value="Urgent: Application Review Requested" required>
                        </div>

                        <!-- Message -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted mb-1">
                                Notification Message <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="notifMessage" name="message" rows="3" required>Hello! This is a live real-time test notification broadcasted via Laravel Reverb from {{ $currentUser->name }}.</textarea>
                        </div>

                        <!-- Action Link -->
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted mb-1">
                                Call-to-Action Link (Optional)
                            </label>
                            <input type="text" class="form-control" id="notifLink" name="link"
                                   value="{{ route('engineer.test.notification') }}">
                            <div class="form-text small text-muted">Clicking the notification in dropdown or browser popup will navigate to this URL.</div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="sendNotifBtn" class="btn btn-success px-4 py-2 fw-bold w-100 shadow-sm" style="background-color: #1f7b4d; border-color: #1f7b4d;">
                            <i class="fa-solid fa-bolt me-1"></i> Dispatch Real-Time Notification Now
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Live Stream & Instructions -->
        <div class="col-lg-5">
            <!-- Live Incoming Event Stream Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <span class="fw-bold text-dark small text-uppercase" style="letter-spacing: 0.5px;">
                        <i class="fa-solid fa-wave-square text-success me-2"></i> Live Event Monitor (Your Screen)
                    </span>
                    <button type="button" class="btn btn-link btn-sm text-muted text-decoration-none p-0" onclick="clearEventLogs()">
                        <i class="fa-solid fa-trash-can"></i> Clear
                    </button>
                </div>
                <div class="card-body p-3" style="min-height: 280px; max-height: 400px; overflow-y: auto;" id="liveEventsContainer">
                    <div class="text-center py-4 text-muted" id="waitingLogsPlaceholder">
                        <i class="fa-solid fa-radar fa-beat text-secondary mb-2" style="font-size: 24px; opacity: 0.5;"></i>
                        <div class="small">Listening on private channel:</div>
                        <code class="small text-primary">App.Models.User.{{ $currentUser->id }}</code>
                        <div class="small text-muted mt-2">Incoming real-time alerts received by your browser will appear here.</div>
                    </div>
                </div>
            </div>

            <!-- How It Works Card -->
            <div class="card border-0 shadow-sm rounded-3 bg-light">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-dark small mb-2"><i class="fa-solid fa-circle-question text-info me-1"></i> How to Test with 2 Officers:</h6>
                    <ol class="small text-muted mb-0 ps-3" style="line-height: 1.6;">
                        <li>Open this URL in Window 1 (Officer A, e.g., Dealing Assistant).</li>
                        <li>Open another browser or Incognito window in Window 2 (Officer B, e.g., Pratibha Kumari / Office Superintendent).</li>
                        <li>In Window 1, select <strong>Officer B</strong> in the dropdown and click <strong>"Dispatch"</strong>.</li>
                        <li>In Window 2, watch:
                            <ul class="mb-0">
                                <li>Floating toast alert appears on the top-right.</li>
                                <li>Bell red dot & count increases.</li>
                                <li>Audio chime plays.</li>
                                <li>Zero page refresh needed!</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function selectMyself() {
        const select = document.getElementById('targetUserId');
        const selfOption = select.querySelector('option[data-self="true"]');
        if (selfOption) {
            select.value = selfOption.value;
            selfOption.selected = true;
        } else {
            select.value = "{{ $currentUser->id }}";
        }
    }

    function updateTypeSelection(radio) {
        document.querySelectorAll('#typeButtons label').forEach(lbl => {
            lbl.classList.remove('active');
        });
        radio.closest('label').classList.add('active');
    }

    function clearEventLogs() {
        const container = document.getElementById('liveEventsContainer');
        container.innerHTML = `
            <div class="text-center py-4 text-muted" id="waitingLogsPlaceholder">
                <i class="fa-solid fa-radar fa-beat text-secondary mb-2" style="font-size: 24px; opacity: 0.5;"></i>
                <div class="small">Listening on private channel:</div>
                <code class="small text-primary">App.Models.User.{{ $currentUser->id }}</code>
                <div class="small text-muted mt-2">Logs cleared. Waiting for new real-time alerts...</div>
            </div>
        `;
    }

    // Monitor WebSocket Connection State
    document.addEventListener('DOMContentLoaded', function() {
        const statusBadge = document.getElementById('reverbConnectionStatus');

        const updateStatus = (state) => {
            if (!statusBadge) return;
            if (state === 'connected') {
                statusBadge.className = 'badge rounded-pill bg-success px-3 py-2';
                statusBadge.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> Reverb Connected (WSS)';
            } else if (state === 'connecting') {
                statusBadge.className = 'badge rounded-pill bg-warning text-dark px-3 py-2';
                statusBadge.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Reverb Connecting...';
            } else {
                statusBadge.className = 'badge rounded-pill bg-danger px-3 py-2';
                statusBadge.innerHTML = '<i class="fa-solid fa-circle-exclamation me-1"></i> Reverb ' + state;
            }
        };

        if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
            updateStatus(window.Echo.connector.pusher.connection.state);
            window.Echo.connector.pusher.connection.bind('state_change', function(states) {
                updateStatus(states.current);
            });
        } else {
            setTimeout(() => {
                if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                    updateStatus(window.Echo.connector.pusher.connection.state);
                }
            }, 1000);
        }

        // Listen on own channel to display incoming logs on this page
        if (window.Echo) {
            window.Echo.private('App.Models.User.{{ $currentUser->id }}')
                .listen('.EngineerNotification', (e) => {
                    const container = document.getElementById('liveEventsContainer');
                    const placeholder = document.getElementById('waitingLogsPlaceholder');
                    if (placeholder) placeholder.remove();

                    const data = e.notification || e;
                    const subject = data.subject || data.title || 'New Notification';
                    const msg = data.message || '';
                    const sender = data.sender_name || 'System';
                    const type = data.type || data.notification_type || 'info';

                    const item = document.createElement('div');
                    item.className = 'p-3 mb-2 rounded-2 border';
                    item.style.backgroundColor = type === 'success' ? '#f0fdf4' : (type === 'warning' ? '#fffbeb' : (type === 'error' ? '#fef2f2' : '#f8fafc'));
                    item.style.borderColor = type === 'success' ? '#bbf7d0' : (type === 'warning' ? '#fde68a' : (type === 'error' ? '#fecaca' : '#e2e8f0'));

                    item.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-${type === 'error' ? 'danger' : (type === 'warning' ? 'warning text-dark' : (type === 'success' ? 'success' : 'primary'))} text-uppercase" style="font-size:10px;">
                                ${type}
                            </span>
                            <small class="text-muted" style="font-size:11px;">${new Date().toLocaleTimeString()}</small>
                        </div>
                        <div class="fw-bold small text-dark">${subject}</div>
                        <div class="small text-muted mt-1" style="word-break: break-word;">${msg}</div>
                        <div class="small text-secondary mt-1" style="font-size:11px;">From: <strong>${sender}</strong></div>
                    `;

                    container.insertBefore(item, container.firstChild);
                });
        }

        // Form Submit Handler (AJAX)
        const form = document.getElementById('liveNotifForm');
        const submitBtn = document.getElementById('sendNotifBtn');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Broadcasting via Reverb...';

            const formData = new FormData(form);
            const jsonData = {};
            formData.forEach((val, key) => jsonData[key] = val);

            fetch("{{ route('engineer.test.notification.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(jsonData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (typeof window.showToast === 'function') {
                        window.showToast('Broadcast Sent!', data.message, 'success');
                    } else {
                        alert(data.message);
                    }
                } else {
                    alert(data.message || 'Error broadcasting notification');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Failed to send notification request.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            });
        });
    });
</script>
@endsection
