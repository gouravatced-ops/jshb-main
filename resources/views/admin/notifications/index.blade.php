@extends('layouts.main')

@section('title', $pageTitle . ' | Admin | JSHB')

@section('content')
<div class="card">
    <!-- Header -->
    <div class="card-head">
        <div>
            <div class="card-title">{{ $pageTitle }}</div>
            <div class="card-subtitle">Notify selected users and broadcast announcements via multi-channel delivery (Email, SMS & WhatsApp)</div>
        </div>
        <div class="card-actions">
            <a class="btn-sky" href="{{ $btnLink }}" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-arrows-rotate"></i>
                <span>{{ $btnText }}</span>
            </a>
            <a class="btn-pink" href="{{ route('my-notifications.index') }}" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-bell"></i>
                <span>My Notifications</span>
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success" style="margin: 20px 24px 0;">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="margin: 20px 24px 0; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 6px;">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger" style="margin: 20px 24px 0; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 6px;">
            <strong><i class="fa-solid fa-triangle-exclamation me-1"></i> Please check the form errors:</strong>
            <ul class="mb-0 mt-1 ps-3" style="font-size: 13px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Metrics Cards Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin: 20px 24px 16px;">
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Target Audience</div>
                <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ count($users) }} Users</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #1e40af; font-weight: 700; letter-spacing: 0.5px;">Active Channels</div>
                <div style="font-size: 18px; font-weight: 800; color: #1d4ed8; margin-top: 2px;">3 Gateways</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-tower-broadcast"></i>
            </div>
        </div>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #166534; font-weight: 700; letter-spacing: 0.5px;">Audience Mode</div>
                <div style="font-size: 18px; font-weight: 800; color: #15803d; margin-top: 2px;">{{ ucfirst($type) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>

        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #92400e; font-weight: 700; letter-spacing: 0.5px;">Dispatch Engine</div>
                <div style="font-size: 18px; font-weight: 800; color: #b45309; margin-top: 2px;">Real-Time</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-paper-plane"></i>
            </div>
        </div>
    </div>

    <!-- Form Body -->
    <form action="{{ route('admin.notifications.send') }}" method="POST" id="notificationForm" style="padding: 8px 24px 24px;">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 24px; align-items: start;">
            
            <!-- Left Column: Compose Content & Channels -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                
                <!-- Section 1: Message Details -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">1. Notification Details</h4>
                    </div>

                    <!-- Subject Input -->
                    <div style="margin-bottom: 16px;">
                        <label for="subjectInput" style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px;">
                            Subject <span style="color: #dc2626;">*</span>
                        </label>
                        <div style="position: relative;">
                            <i class="fa-solid fa-heading" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                            <input type="text" id="subjectInput" name="subject" value="{{ old('subject') }}" required placeholder="e.g., Notice regarding upcoming Board Meeting..."
                                style="width: 100%; padding: 9px 12px 9px 34px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; color: #1e293b; outline: none; transition: border-color 0.2s;"
                                onfocus="this.style.borderColor='#0284c7'" onblur="this.style.borderColor='#cbd5e1'">
                        </div>
                        @error('subject')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Message Textarea -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <label for="messageInput" style="font-size: 13px; font-weight: 600; color: #1e293b; margin: 0;">
                                Message Content <span style="color: #dc2626;">*</span>
                            </label>
                            <span id="charCount" style="font-size: 12px; color: #64748b; font-weight: 500;">0 characters</span>
                        </div>
                        <textarea id="messageInput" name="message" rows="6" required placeholder="Type your official announcement or message here..."
                            style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; color: #1e293b; line-height: 1.5; outline: none; resize: vertical; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#0284c7'" onblur="this.style.borderColor='#cbd5e1'">{{ old('message') }}</textarea>
                        @error('message')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                        <div style="font-size: 12px; color: #64748b; margin-top: 6px;">
                            <i class="fa-solid fa-circle-info" style="font-size: 11px; margin-right: 4px;"></i>
                            Message will be formatted appropriately for each selected delivery gateway.
                        </div>
                    </div>
                </div>

                <!-- Section 2: Delivery Channels -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                            <i class="fa-solid fa-tower-broadcast"></i>
                        </div>
                        <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">2. Choose Delivery Channels</h4>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
                        <!-- Email Channel -->
                        <label style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px 14px; cursor: pointer; display: flex; align-items: flex-start; gap: 10px; transition: all 0.2s; background: #f8fafc;"
                            class="channel-box" id="box_email">
                            <input type="checkbox" name="send_email" id="send_email" value="1" checked style="margin-top: 3px; cursor: pointer;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-envelope" style="color: #0284c7; font-size: 13px;"></i>
                                    <strong style="font-size: 13px; color: #0f172a;">Email</strong>
                                </div>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">Direct HTML email</div>
                                <span class="badge" style="background: #e0f2fe; color: #0284c7; font-size: 10px; padding: 1px 6px; border-radius: 4px; margin-top: 6px; font-weight: 600;">SMTP Ready</span>
                            </div>
                        </label>

                        <!-- SMS Gateway -->
                        <label style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px 14px; cursor: pointer; display: flex; align-items: flex-start; gap: 10px; transition: all 0.2s; background: #f8fafc;"
                            class="channel-box" id="box_sms">
                            <input type="checkbox" name="send_sms" id="send_sms" value="1" style="margin-top: 3px; cursor: pointer;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-comment-sms" style="color: #16a34a; font-size: 13px;"></i>
                                    <strong style="font-size: 13px; color: #0f172a;">SMS</strong>
                                </div>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">Instant text SMS</div>
                                <span class="badge" style="background: #dcfce7; color: #16a34a; font-size: 10px; padding: 1px 6px; border-radius: 4px; margin-top: 6px; font-weight: 600;">DLT Approved</span>
                            </div>
                        </label>

                        <!-- WhatsApp Gateway -->
                        <label style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px 14px; cursor: pointer; display: flex; align-items: flex-start; gap: 10px; transition: all 0.2s; background: #f8fafc;"
                            class="channel-box" id="box_whatsapp">
                            <input type="checkbox" name="send_whatsapp" id="send_whatsapp" value="1" style="margin-top: 3px; cursor: pointer;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-brands fa-whatsapp" style="color: #25d366; font-size: 14px;"></i>
                                    <strong style="font-size: 13px; color: #0f172a;">WhatsApp</strong>
                                </div>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">Business template</div>
                                <span class="badge" style="background: #ecfdf5; color: #059669; font-size: 10px; padding: 1px 6px; border-radius: 4px; margin-top: 6px; font-weight: 600;">Meta Cloud</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Column: Select Target Recipients -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; flex-direction: column;">
                
                <!-- Section Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9; flex-wrap: wrap; gap: 8px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                            <i class="fa-solid fa-users-viewfinder"></i>
                        </div>
                        <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">3. Select Recipients <span style="color: #dc2626;">*</span></h4>
                    </div>
                    <div>
                        <span id="selectedCountBadge" style="background: #e0f2fe; color: #0369a1; font-weight: 700; font-size: 11.5px; padding: 3px 9px; border-radius: 999px; border: 1px solid #bae6fd;">
                            <span id="selectedNumber">0</span> / {{ count($users) }} Selected
                        </span>
                    </div>
                </div>

                <!-- Recipient Search Box -->
                <div style="position: relative; margin-bottom: 12px;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 12px;"></i>
                    <input type="text" id="recipientSearch" placeholder="Filter recipients by name, designation, email..."
                        style="width: 100%; padding: 7px 12px 7px 32px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12.5px; color: #1e293b; background: #fff; outline: none;">
                </div>

                <!-- Quick Action Buttons Bar -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding: 8px 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <label style="display: flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 600; color: #334155; margin: 0; cursor: pointer;">
                        <input type="checkbox" id="selectAllCheckbox" style="cursor: pointer;">
                        <span>Select All (<span id="visibleTotal">{{ count($users) }}</span>)</span>
                    </label>
                    <div style="display: flex; gap: 6px;">
                        <button type="button" id="btnSelectAll" style="background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 2px 8px; font-size: 11px; font-weight: 600; color: #0284c7; cursor: pointer;">
                            All
                        </button>
                        <button type="button" id="btnDeselectAll" style="background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 2px 8px; font-size: 11px; font-weight: 600; color: #64748b; cursor: pointer;">
                            None
                        </button>
                    </div>
                </div>

                @error('user_ids')
                    <div style="color: #dc2626; font-size: 12px; margin-bottom: 8px;">{{ $message }}</div>
                @enderror

                <!-- Scrollable User Checkbox List -->
                <div id="usersListContainer" style="max-height: 380px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px; background: #fff; display: flex; flex-direction: column; gap: 4px;">
                    @forelse($users as $user)
                        <label class="user-row" data-name="{{ strtolower($user->name) }}" data-role="{{ strtolower($user->designation ?? $user->user_type ?? '') }}" data-email="{{ strtolower($user->email ?? '') }}"
                            style="display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 6px; border: 1px solid #f1f5f9; cursor: pointer; transition: all 0.15s; margin: 0; background: #ffffff;">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox" style="cursor: pointer; width: 16px; height: 16px; flex-shrink: 0;">
                            
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; flex-shrink: 0;">
                                {{ strtoupper(substr(trim($user->name), 0, 1)) }}
                            </div>

                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                    <span style="font-size: 13px; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $user->name }}
                                    </span>
                                    <span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-size: 10px; padding: 1px 6px; border-radius: 4px; font-weight: 600; text-transform: capitalize;">
                                        {{ $user->designation ?? str_replace('_', ' ', $user->user_type ?? 'Member') }}
                                    </span>
                                </div>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <i class="fa-regular fa-envelope" style="font-size: 10px; margin-right: 3px;"></i>
                                    {{ $user->email }}
                                </div>
                            </div>
                        </label>
                    @empty
                        <div style="text-align: center; padding: 30px 10px; color: #64748b;">
                            <i class="fa-solid fa-user-slash fa-2x" style="color: #cbd5e1; margin-bottom: 8px;"></i>
                            <div style="font-size: 13px; font-weight: 600;">No Users Found</div>
                            <div style="font-size: 11.5px;">No eligible users are configured under this role group.</div>
                        </div>
                    @endforelse
                    
                    <!-- Search Not Found Message (Hidden by default) -->
                    <div id="noMatchMessage" style="display: none; text-align: center; padding: 24px 10px; color: #64748b;">
                        <i class="fa-solid fa-magnifying-glass" style="font-size: 20px; color: #cbd5e1; margin-bottom: 6px;"></i>
                        <div style="font-size: 13px; font-weight: 600; color: #1e293b;">No Matching Recipients</div>
                        <div style="font-size: 11.5px; color: #94a3b8;">Try refining your search keyword.</div>
                    </div>
                </div>

                <div style="font-size: 11.5px; color: #64748b; margin-top: 8px;">
                    <i class="fa-solid fa-info-circle" style="font-size: 10px;"></i>
                    Tick the users you wish to notify. Use search to locate specific officials quickly.
                </div>
            </div>
        </div>

        <!-- Sticky Footer Action Bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px; padding-top: 18px; border-top: 1px solid #e2e8f0; flex-wrap: wrap; gap: 12px;">
            <div style="font-size: 13px; color: #64748b;">
                Ready to dispatch notification to <strong id="footerSelectedCount" style="color: #0f172a;">0</strong> recipient(s).
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
                <a href="{{ route('admin.notifications.index', ['type' => $type]) }}" class="btn-secondary" style="padding: 9px 18px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; color: #64748b; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; background: #fff;">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Reset</span>
                </a>
                <button type="submit" class="btn-pink" id="btnSubmitNotification" style="padding: 10px 24px; font-size: 13.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; border-radius: 6px; border: none;">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Send Broadcast Notification</span>
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.channel-box:hover {
    border-color: #7dd3fc !important;
    background: #f0f9ff !important;
}
.user-row:hover {
    background: #f8fafc !important;
    border-color: #cbd5e1 !important;
}
.user-row.selected {
    background: #f0f9ff !important;
    border-color: #bae6fd !important;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageInput = document.getElementById('messageInput');
    const charCount = document.getElementById('charCount');
    const recipientSearch = document.getElementById('recipientSearch');
    const userRows = document.querySelectorAll('.user-row');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const btnSelectAll = document.getElementById('btnSelectAll');
    const btnDeselectAll = document.getElementById('btnDeselectAll');
    const selectedNumber = document.getElementById('selectedNumber');
    const footerSelectedCount = document.getElementById('footerSelectedCount');
    const visibleTotal = document.getElementById('visibleTotal');
    const noMatchMessage = document.getElementById('noMatchMessage');
    const notificationForm = document.getElementById('notificationForm');

    // 1. Character Counter
    function updateCharCount() {
        if (messageInput && charCount) {
            const count = messageInput.value.length;
            charCount.textContent = count + ' character' + (count === 1 ? '' : 's');
        }
    }
    if (messageInput) {
        messageInput.addEventListener('input', updateCharCount);
        updateCharCount();
    }

    // 2. Selected Recipients Counter & Highlight
    function updateSelectedCount() {
        let count = 0;
        userCheckboxes.forEach(cb => {
            const row = cb.closest('.user-row');
            if (cb.checked) {
                count++;
                if (row) row.classList.add('selected');
            } else {
                if (row) row.classList.remove('selected');
            }
        });
        if (selectedNumber) selectedNumber.textContent = count;
        if (footerSelectedCount) footerSelectedCount.textContent = count;

        // Update select all checkbox state
        let visibleCheckboxes = Array.from(userCheckboxes).filter(cb => {
            const row = cb.closest('.user-row');
            return row && row.style.display !== 'none';
        });
        if (selectAllCheckbox && visibleCheckboxes.length > 0) {
            const allChecked = visibleCheckboxes.every(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
        }
    }

    userCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });

    // 3. Select All / Deselect All
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            userCheckboxes.forEach(cb => {
                const row = cb.closest('.user-row');
                if (row && row.style.display !== 'none') {
                    cb.checked = isChecked;
                }
            });
            updateSelectedCount();
        });
    }

    if (btnSelectAll) {
        btnSelectAll.addEventListener('click', function() {
            userCheckboxes.forEach(cb => {
                const row = cb.closest('.user-row');
                if (row && row.style.display !== 'none') {
                    cb.checked = true;
                }
            });
            if (selectAllCheckbox) selectAllCheckbox.checked = true;
            updateSelectedCount();
        });
    }

    if (btnDeselectAll) {
        btnDeselectAll.addEventListener('click', function() {
            userCheckboxes.forEach(cb => cb.checked = false);
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            updateSelectedCount();
        });
    }

    // 4. Live Search Filter for Recipients
    if (recipientSearch) {
        recipientSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visibleCount = 0;

            userRows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const role = row.getAttribute('data-role') || '';
                const email = row.getAttribute('data-email') || '';

                if (name.includes(query) || role.includes(query) || email.includes(query)) {
                    row.style.display = 'flex';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleTotal) visibleTotal.textContent = visibleCount;
            if (noMatchMessage) {
                noMatchMessage.style.display = visibleCount === 0 ? 'block' : 'none';
            }
            updateSelectedCount();
        });
    }

    // 5. Form Submit Validation: Check if at least 1 recipient is selected
    if (notificationForm) {
        notificationForm.addEventListener('submit', function(e) {
            const anyUserSelected = Array.from(userCheckboxes).some(cb => cb.checked);
            if (!anyUserSelected) {
                e.preventDefault();
                alert('Please select at least one recipient from the list before sending.');
                return false;
            }

            const sendEmail = document.getElementById('send_email');
            const sendSms = document.getElementById('send_sms');
            const sendWhatsapp = document.getElementById('send_whatsapp');
            const anyChannel = (sendEmail && sendEmail.checked) || (sendSms && sendSms.checked) || (sendWhatsapp && sendWhatsapp.checked);

            if (!anyChannel) {
                e.preventDefault();
                alert('Please select at least one delivery channel (Email, SMS, or WhatsApp).');
                return false;
            }
        });
    }

    // Initial count update
    updateSelectedCount();
});
</script>
@endpush
