@extends('layouts.main')

@section('title', 'Edit Notice | Admin | JSHB')

@section('content')
<div class="card">
    <!-- Header -->
    <div class="card-head">
        <div>
            <div class="card-title">Edit Notice & Announcement</div>
            <div class="card-subtitle">Update notice content, targeting scope, schedule, and active delivery channels</div>
        </div>
        <div class="card-actions">
            <a class="btn-sky" href="{{ route('admin.notices.index') }}" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Notices</span>
            </a>
            <a class="btn-pink" href="{{ route('admin.notices.index') }}" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-bullhorn"></i>
                <span>Notice Registry</span>
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
                <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Notice ID</div>
                <div style="font-size: 18px; font-weight: 800; color: #0f172a; margin-top: 2px;">#{{ $notice->id }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-hashtag"></i>
            </div>
        </div>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #1e40af; font-weight: 700; letter-spacing: 0.5px;">Current Type</div>
                <div style="font-size: 18px; font-weight: 800; color: #1d4ed8; margin-top: 2px;">{{ ucfirst($notice->notice_type) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-tags"></i>
            </div>
        </div>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #166534; font-weight: 700; letter-spacing: 0.5px;">Created On</div>
                <div style="font-size: 16px; font-weight: 800; color: #15803d; margin-top: 2px;">{{ $notice->created_at ? $notice->created_at->format('d M Y') : 'N/A' }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>

        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; text-transform: uppercase; color: #92400e; font-weight: 700; letter-spacing: 0.5px;">Author</div>
                <div style="font-size: 16px; font-weight: 800; color: #b45309; margin-top: 2px;">{{ Str::limit($notice->creator->name ?? 'Admin', 14) }}</div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                <i class="fa-solid fa-user-pen"></i>
            </div>
        </div>
    </div>

    <!-- Main Form Container -->
    <form action="{{ route('admin.notices.update', $notice) }}" method="POST" style="padding: 8px 24px 24px;">
        @csrf
        @method('PUT')

        <div style="display: flex; flex-direction: column; gap: 20px;">

            <!-- Section 1: Notice Details -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 28px; height: 28px; border-radius: 6px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">1. Notice Title & Content</h4>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px;">
                            Notice Title <span style="color: #dc2626;">*</span>
                        </label>
                        <div style="position: relative;">
                            <i class="fa-solid fa-heading" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
                            <input type="text" name="title" value="{{ old('title', $notice->title) }}" required placeholder="Enter concise notice title..."
                                style="width: 100%; padding: 9px 12px 9px 34px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; color: #1e293b; outline: none; transition: border-color 0.2s;"
                                onfocus="this.style.borderColor='#0284c7'" onblur="this.style.borderColor='#cbd5e1'">
                        </div>
                        @error('title')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px;">
                            Notice Type <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="notice_type" required
                            style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; color: #1e293b; outline: none; background: #fff; cursor: pointer;">
                            <option value="announcement" {{ old('notice_type', $notice->notice_type) == 'announcement' ? 'selected' : '' }}>📢 Announcement</option>
                            <option value="warning" {{ old('notice_type', $notice->notice_type) == 'warning' ? 'selected' : '' }}>⚠️ Warning / Critical</option>
                            <option value="new" {{ old('notice_type', $notice->notice_type) == 'new' ? 'selected' : '' }}>✨ New Feature / Update</option>
                            <option value="info" {{ old('notice_type', $notice->notice_type) == 'info' ? 'selected' : '' }}>ℹ️ General Information</option>
                        </select>
                        @error('notice_type')
                            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px;">
                        Notice Message / Body <span style="color: #dc2626;">*</span>
                    </label>
                    <textarea name="message" id="summernote" required>{{ old('message', $notice->message) }}</textarea>
                    @error('message')
                        <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Section 2: Targeting & Scope -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 28px; height: 28px; border-radius: 6px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                        <i class="fa-solid fa-users-viewfinder"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">2. Target Audience & Scope</h4>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    <div>
                        <label for="target_user_type" style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px;">
                            Target Member Type
                        </label>
                        <select name="target_user_type" id="target_user_type"
                            style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; color: #1e293b; outline: none; background: #fff; cursor: pointer;">
                            <option value="">All Members (System Wide)</option>
                            @foreach($userTypes as $type)
                                <option value="{{ $type }}" {{ old('target_user_type', $notice->target_user_type) == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                        <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">Leave blank to broadcast to all member types.</small>
                    </div>

                    <div id="division_wrapper">
                        <label for="target_division_id" style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px;">
                            Target Division
                        </label>
                        <select name="target_division_id" id="target_division_id"
                            style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; color: #1e293b; outline: none; background: #fff; cursor: pointer;">
                            <option value="">All Divisions</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div->id }}" {{ old('target_division_id', $notice->target_division_id) == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                            @endforeach
                        </select>
                        <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">Filter by structural regional division.</small>
                    </div>

                    @php
                        $selectedUserIds = old('target_user_id', json_decode($notice->target_user_id, true) ?? []);
                        if (!is_array($selectedUserIds)) {
                            $selectedUserIds = [$selectedUserIds];
                        }
                    @endphp

                    <div id="user_wrapper">
                        <label for="target_user_id" style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px;">
                            Specific Member(s) (Optional)
                        </label>
                        <select name="target_user_id[]" id="target_user_id" multiple="multiple"
                            style="width: 100%; height: 110px; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12.5px; color: #1e293b; outline: none; background: #fff;">
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" data-type="{{ $u->user_type }}" data-div="{{ $u->division_id }}" {{ in_array($u->id, $selectedUserIds) ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ $u->username ?? $u->email }})
                                </option>
                            @endforeach
                        </select>
                        <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">
                            Hold <strong>Ctrl</strong> (or <strong>Cmd</strong> on Mac) to select multiple specific members.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Section 3: Schedule & Validity -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 28px; height: 28px; border-radius: 6px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">3. Validity Schedule</h4>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px;">
                            Start Date & Time
                        </label>
                        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $notice->start_date ? $notice->start_date->format('Y-m-d\TH:i') : '') }}"
                            style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b;">
                        <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">When notice becomes active in the system.</small>
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px;">
                            End Date & Time (Optional)
                        </label>
                        <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $notice->end_date ? $notice->end_date->format('Y-m-d\TH:i') : '') }}"
                            style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b;">
                        <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">Leave blank for indefinite active notice.</small>
                    </div>
                </div>
            </div>

            <!-- Section 4: Delivery Channels -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 28px; height: 28px; border-radius: 6px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                        <i class="fa-solid fa-tower-broadcast"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">4. Choose Delivery Channels</h4>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                    <!-- Software Channel -->
                    <label style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px 14px; cursor: pointer; display: flex; align-items: flex-start; gap: 10px; transition: all 0.2s; background: #f8fafc;" class="channel-card">
                        <input type="checkbox" name="notice_in_software" id="notice_in_software" value="1" {{ old('notice_in_software', $notice->notice_in_software) ? 'checked' : '' }} style="margin-top: 3px; cursor: pointer;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-desktop" style="color: #0284c7; font-size: 13px;"></i>
                                <strong style="font-size: 13px; color: #0f172a;">Software App</strong>
                            </div>
                            <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">In-app notice board</div>
                            <span class="badge" style="background: #e0f2fe; color: #0284c7; font-size: 10px; padding: 1px 6px; border-radius: 4px; margin-top: 6px; font-weight: 600;">Default</span>
                        </div>
                    </label>

                    <!-- Email Channel -->
                    <label style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px 14px; cursor: pointer; display: flex; align-items: flex-start; gap: 10px; transition: all 0.2s; background: #f8fafc;" class="channel-card">
                        <input type="checkbox" name="send_email" id="send_email" value="1" {{ old('send_email', $notice->send_email) ? 'checked' : '' }} style="margin-top: 3px; cursor: pointer;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-envelope" style="color: #0284c7; font-size: 13px;"></i>
                                <strong style="font-size: 13px; color: #0f172a;">Email Dispatch</strong>
                            </div>
                            <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">Official HTML email</div>
                            <span class="badge" style="background: #e0f2fe; color: #0284c7; font-size: 10px; padding: 1px 6px; border-radius: 4px; margin-top: 6px; font-weight: 600;">SMTP Ready</span>
                        </div>
                    </label>

                    <!-- SMS Channel -->
                    <label style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px 14px; cursor: pointer; display: flex; align-items: flex-start; gap: 10px; transition: all 0.2s; background: #f8fafc;" class="channel-card">
                        <input type="checkbox" name="send_sms" id="send_sms" value="1" {{ old('send_sms', $notice->send_sms) ? 'checked' : '' }} style="margin-top: 3px; cursor: pointer;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-comment-sms" style="color: #16a34a; font-size: 13px;"></i>
                                <strong style="font-size: 13px; color: #0f172a;">SMS Alert</strong>
                            </div>
                            <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">Text message notification</div>
                            <span class="badge" style="background: #dcfce7; color: #16a34a; font-size: 10px; padding: 1px 6px; border-radius: 4px; margin-top: 6px; font-weight: 600;">DLT Approved</span>
                        </div>
                    </label>

                    <!-- WhatsApp Channel -->
                    <label style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px 14px; cursor: pointer; display: flex; align-items: flex-start; gap: 10px; transition: all 0.2s; background: #f8fafc;" class="channel-card">
                        <input type="checkbox" name="send_whatsapp" id="send_whatsapp" value="1" {{ old('send_whatsapp', $notice->send_whatsapp) ? 'checked' : '' }} style="margin-top: 3px; cursor: pointer;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <i class="fa-brands fa-whatsapp" style="color: #25d366; font-size: 14px;"></i>
                                <strong style="font-size: 13px; color: #0f172a;">WhatsApp</strong>
                            </div>
                            <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">Direct messaging</div>
                            <span class="badge" style="background: #ecfdf5; color: #059669; font-size: 10px; padding: 1px 6px; border-radius: 4px; margin-top: 6px; font-weight: 600;">Meta Cloud</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Sticky Action Footer -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 18px; border-top: 1px solid #e2e8f0; flex-wrap: wrap; gap: 12px;">
                <div style="font-size: 13px; color: #64748b;">
                    Make sure to review notice modifications before saving changes.
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <a href="{{ route('admin.notices.index') }}" class="btn-secondary" style="padding: 9px 18px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; color: #64748b; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; background: #fff;">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Cancel</span>
                    </a>
                    <button type="submit" class="btn-pink" style="padding: 10px 24px; font-size: 13.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; border-radius: 6px; border: none;">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Update Notice</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.channel-card:hover {
    border-color: #7dd3fc !important;
    background: #f0f9ff !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('target_user_type');
    const divSelect = document.getElementById('target_division_id');
    const userSelect = document.getElementById('target_user_id');
    
    if (userSelect && typeSelect && divSelect) {
        const userOptions = Array.from(userSelect.options);

        function filterUsers() {
            const selectedType = typeSelect.value;
            const selectedDiv = divSelect.value;

            userSelect.innerHTML = '';

            userOptions.forEach(opt => {
                if (opt.value === "") return;

                let matchType = selectedType === "" || opt.dataset.type === selectedType;
                let matchDiv = selectedDiv === "" || opt.dataset.div === selectedDiv;

                if (matchType && matchDiv) {
                    userSelect.appendChild(opt);
                }
            });
        }

        typeSelect.addEventListener('change', filterUsers);
        divSelect.addEventListener('change', filterUsers);

        filterUsers();
        
        const oldUserIds = @json($selectedUserIds);
        if (oldUserIds && oldUserIds.length > 0) {
            Array.from(userSelect.options).forEach(opt => {
                if (oldUserIds.includes(Number(opt.value)) || oldUserIds.includes(String(opt.value))) {
                    opt.selected = true;
                }
            });
        }
    }
});
</script>

@include('components.partials.summernote-editor')
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('#summernote').summernote('destroy');
            $('#summernote').summernote({
                height: 320,
                placeholder: 'Write your notice message here...',
                fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'KrutiDev011'],
                fontNamesIgnoreCheck: ['KrutiDev011'],
                toolbar: [
                    ['font', ['bold', 'underline', 'italic']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']]
                ],
                callbacks: {
                    onInit: function() {
                        $('.note-editable').css({
                            'background-color': '#fff',
                            'font-family': 'Arial, sans-serif',
                            'font-size': '15px',
                            'line-height': '1.6',
                            'color': '#333'
                        });
                        
                        setTimeout(function() {
                            $('.note-dropdown-menu a').each(function() {
                                if ($(this).attr('data-value') === 'KrutiDev011' || $(this).text().trim() === 'KrutiDev011') {
                                    $(this).css('font-family', 'Arial, sans-serif');
                                }
                            });
                        }, 500);
                    }
                }
            });
        }, 100);
    });
</script>
@endsection
