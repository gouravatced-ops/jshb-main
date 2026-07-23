@extends('layouts.main')

@section('title', 'Create Notice | JSHB')

@section('content')
<div class="form-container">
    <div class="form-wrapper">
        <div class="form-main">
            @if ($errors->any())
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-header">
                <div>
                    <h4>Create Notice & Announcement</h4>
                    <p>Broadcast a new notice to system users globally or target specific groups.</p>
                </div>
                <a href="{{ route('admin.notices.index') }}" class="btn-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Back
                </a>
            </div>

            <form action="{{ route('admin.notices.store') }}" method="POST">
                @csrf
                
                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label>Notice Title <span style="color:red">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Enter notice title" required>
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label>Notice Type <span style="color:red">*</span></label>
                        <select name="notice_type" class="form-select" required>
                            <option value="announcement" {{ old('notice_type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                            <option value="warning" {{ old('notice_type') == 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="new" {{ old('notice_type') == 'new' ? 'selected' : '' }}>New Feature / Update</option>
                            <option value="info" {{ old('notice_type') == 'info' ? 'selected' : '' }}>General Information</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Message <span style="color:red">*</span></label>
                    <textarea name="message" id="summernote" class="form-control" required>{{ old('message') }}</textarea>
                </div>

                <div class="form-section-label" style="margin-top: 30px; font-weight: 600; border-bottom: 1px solid #eaeaea; padding-bottom: 10px; margin-bottom: 20px;">Targeting & Settings</div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Target Member Type</label>
                        <select name="target_user_type" id="target_user_type" class="form-select">
                            <option value="">All Members (System Wide)</option>
                            @foreach($userTypes as $type)
                            <option value="{{ $type }}" {{ old('target_user_type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group" id="division_wrapper">
                        <label>Target Division</label>
                        <select name="target_division_id" id="target_division_id" class="form-select">
                            <option value="">All Divisions</option>
                            @foreach($divisions as $div)
                            <option value="{{ $div->id }}" {{ old('target_division_id') == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="user_wrapper">
                        <label>Specific Member(s)</label>
                        <select name="target_user_id[]" id="target_user_id" class="form-select" multiple="multiple" style="height: 100px;">
                            @foreach($users as $u)
                            <option value="{{ $u->id }}" data-type="{{ $u->user_type }}" data-div="{{ $u->division_id }}" {{ old('target_user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->username ?? $u->email }})</option>
                            @endforeach
                        </select>
                        <small style="color: #666; font-size: 0.8rem; margin-top: 4px; display: block;">Hold <strong>Ctrl</strong> (or <strong>Cmd</strong> on Mac) + click to select multiple.</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', now()->format('Y-m-d\TH:i')) }}" min="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>
                    
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}" min="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>
                </div>

                <div class="form-section-label" style="margin-top: 30px; font-weight: 600; border-bottom: 1px solid #eaeaea; padding-bottom: 10px; margin-bottom: 20px;">Delivery Channels</div>
                
                <div class="form-row" style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="notice_in_software" id="notice_in_software" value="1" checked>
                        <label for="notice_in_software" style="margin: 0;">Notice in Software (Default)</label>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="send_email" id="send_email" value="1">
                        <label for="send_email" style="margin: 0;">Email</label>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="send_sms" id="send_sms" value="1">
                        <label for="send_sms" style="margin: 0;">SMS</label>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="send_whatsapp" id="send_whatsapp" value="1">
                        <label for="send_whatsapp" style="margin: 0;">WhatsApp</label>
                    </div>
                </div>

                <div class="form-footer">
                    <a href="{{ route('admin.notices.index') }}" class="btn-reset" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
                    <button type="submit" class="btn-submit">Publish Notice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('target_user_type');
        const divSelect = document.getElementById('target_division_id');
        const userSelect = document.getElementById('target_user_id');
        
        // Store original options for filtering
        const userOptions = Array.from(userSelect.options);

        function filterUsers() {
            const selectedType = typeSelect.value;
            const selectedDiv = divSelect.value;

            // Clear current options
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

        // Initial filter on page load
        filterUsers();
        
        // If old user id exists, select it back after filter
        const oldUserIds = @json(old('target_user_id', []));
        if (oldUserIds && oldUserIds.length > 0) {
            Array.from(userSelect.options).forEach(opt => {
                if (oldUserIds.includes(opt.value)) {
                    opt.selected = true;
                }
            });
        }
    });
</script>
@include('components.partials.summernote-editor')
<script>
    // Customize summernote for this specific page after the partial initializes it
    $(document).ready(function() {
        setTimeout(function() {
            $('#summernote').summernote('destroy');
            $('#summernote').summernote({
                height: 350,
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
                        
                        // Forcefully fix the KrutiDev name in the dropdown using JS
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
        }, 100); // Wait for the partial's init to finish
    });
</script>
@endsection
