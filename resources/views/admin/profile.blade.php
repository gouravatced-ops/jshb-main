{{-- resources/views/admin/profile.blade.php --}}
@extends('layouts.main')

@section('title', ucfirst($user->role ?? 'User') . ' Profile | JSHB')

@section('content')
@php
    $activeTab = old('active_tab', request()->get('tab', 'basic'));
@endphp

<div class="profile-container">
    <div class="profile-wrapper">
        <!-- Sidebar -->
        <div class="profile-sidebar">
            <div class="sidebar-content">
                <div class="avatar-section">
                    @if($user->photo)
                        <img src="{{ asset('storage/photos/' . $user->photo) }}" class="avatar-img" alt="Profile Photo">
                    @else
                        <div class="avatar-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    @endif
                    <button type="button" class="avatar-edit-btn" data-tab="photo" title="Change Photo">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    </button>
                </div>

                <h3 class="user-name">{{ $user->name }} <span class="user-role">{{ ucfirst($user->role ?? 'Administrator') }}</span></h3>

                <div class="about-section">
                    <h5>Contact Info</h5>
                    <div class="info-list">
                        <div class="info-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <span>{{ $user->email }}</span>
                        </div>
                        <div class="info-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span>{{ $userDetail->phone ?? 'Not provided' }}</span>
                        </div>
                        <div class="info-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>{{ $userDetail->city ?? '—' }}, {{ $userDetail->state ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="profile-main">
            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="profile-header">
                <div>
                    <h4>Profile Settings</h4>
                    <p>Manage your account information and personal details</p>
                </div>
            </div>

            <form action="{{ auth()->user()?->role === 'admin' ? route('admin.profile.update') : route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                @csrf
                <input type="hidden" name="active_tab" id="active_tab" value="{{ $activeTab }}">

                <!-- Tabs Navigation - Mobile Dropdown -->
                <div class="tabs-container">
                    <!-- Mobile Select Dropdown -->
                    <div class="mobile-tab-select">
                        <select id="mobileTabSelect" class="form-control">
                            <option value="basic" {{ $activeTab === 'basic' ? 'selected' : '' }}>Basic Info</option>
                            <option value="address" {{ $activeTab === 'address' ? 'selected' : '' }}>Address</option>
                            <option value="personal" {{ $activeTab === 'personal' ? 'selected' : '' }}>Personal</option>
                            <option value="photo" {{ $activeTab === 'photo' ? 'selected' : '' }}>Photo</option>
                        </select>
                    </div>

                    <!-- Desktop Tabs -->
                    <div class="tabs-nav" role="tablist">
                        <button type="button" class="tab-btn {{ $activeTab === 'basic' ? 'active' : '' }}" data-tab="basic" role="tab">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Basic
                        </button>
                        <button type="button" class="tab-btn {{ $activeTab === 'address' ? 'active' : '' }}" data-tab="address" role="tab">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            Address
                        </button>
                        <button type="button" class="tab-btn {{ $activeTab === 'personal' ? 'active' : '' }}" data-tab="personal" role="tab">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            Personal
                        </button>
                        <button type="button" class="tab-btn {{ $activeTab === 'photo' ? 'active' : '' }}" data-tab="photo" role="tab">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="2.18"/><path d="M7 2v20M17 2v20M2 12h20M2 7h5M2 17h5M17 17h5M17 7h5"/></svg>
                            Photo
                        </button>
                    </div>

                    <!-- Tab Contents -->
                    <div class="tabs-content">
                        <!-- Basic Tab -->
                        <div class="tab-pane {{ $activeTab === 'basic' ? 'active' : '' }}" data-tab="basic" role="tabpanel">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $userDetail->phone) }}" placeholder="+1 234 567 8900">
                                </div>
                                <div class="form-group">
                                    <label>Organization</label>
                                    <input type="text" name="organization" class="form-control" value="{{ old('organization', $userDetail->organization) }}" placeholder="Company name">
                                </div>
                                <div class="form-group">
                                    <label>Designation / Role</label>
                                    <input type="text" name="designation" class="form-control" value="{{ old('designation', $userDetail->designation) }}" placeholder="e.g., Senior Developer">
                                </div>
                                @if(($user->role ?? null) === 'admin')
                                    <div class="form-group">
                                        <label>Security PIN</label>
                                        <input type="text" maxlength="5" minlength="5" name="secure_pin" class="form-control" value="" placeholder="Enter new 5 digit PIN">
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Security PIN</label>
                                        <input type="text" maxlength="5" minlength="5" name="secure_pin_confirmation" class="form-control" value="" placeholder="Confirm new 5 digit PIN">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Address Tab -->
                        <div class="tab-pane {{ $activeTab === 'address' ? 'active' : '' }}" data-tab="address" role="tabpanel">
                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <label>Address Line 1</label>
                                    <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1', $userDetail->address_line1) }}" placeholder="Street address, P.O. Box">
                                </div>
                                <div class="form-group full-width">
                                    <label>Address Line 2 (Optional)</label>
                                    <input type="text" name="address_line2" class="form-control" value="{{ old('address_line2', $userDetail->address_line2) }}" placeholder="Apartment, suite, unit, etc.">
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city', $userDetail->city) }}" placeholder="City">
                                </div>
                                <div class="form-group">
                                    <label>State / Province</label>
                                    <input type="text" name="state" class="form-control" value="{{ old('state', $userDetail->state) }}" placeholder="State">
                                </div>
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $userDetail->postal_code) }}" placeholder="ZIP / Postal code">
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <select name="country" class="form-control">
                                        <option value="India" {{ old('country', $userDetail->country ?? 'India') == 'India' ? 'selected' : '' }}>India</option>
                                        <option value="United States" {{ old('country', $userDetail->country) == 'United States' ? 'selected' : '' }}>United States</option>
                                        <option value="United Kingdom" {{ old('country', $userDetail->country) == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="Canada" {{ old('country', $userDetail->country) == 'Canada' ? 'selected' : '' }}>Canada</option>
                                        <option value="Australia" {{ old('country', $userDetail->country) == 'Australia' ? 'selected' : '' }}>Australia</option>
                                        <option value="Germany" {{ old('country', $userDetail->country) == 'Germany' ? 'selected' : '' }}>Germany</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Tab -->
                        <div class="tab-pane {{ $activeTab === 'personal' ? 'active' : '' }}" data-tab="personal" role="tabpanel">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $userDetail->date_of_birth) }}">
                                </div>
                                <div class="form-group">
                                    <label>Anniversary Date</label>
                                    <input type="date" name="anniversary_date" class="form-control" value="{{ old('anniversary_date', $userDetail->anniversary_date) }}">
                                </div>
                                <div class="form-group">
                                    <label>Spouse Name</label>
                                    <input type="text" name="spouse_name" class="form-control" value="{{ old('spouse_name', $userDetail->spouse_name) }}" placeholder="Spouse's full name">
                                </div>
                                <div class="form-group">
                                    <label>Number of Children</label>
                                    <input type="number" name="no_of_children" class="form-control" value="{{ old('no_of_children', $userDetail->no_of_children) }}" min="0">
                                </div>
                                <div class="form-group">
                                    <label>Boys</label>
                                    <input type="number" name="boys" class="form-control" value="{{ old('boys', $userDetail->boys) }}" min="0">
                                </div>
                                <div class="form-group">
                                    <label>Girls</label>
                                    <input type="number" name="girls" class="form-control" value="{{ old('girls', $userDetail->girls) }}" min="0">
                                </div>
                                <div class="form-group full-width">
                                    <label>Additional Information</label>
                                    <textarea name="additional_info" class="form-control" rows="4" placeholder="Any other relevant information...">{{ old('additional_info', $userDetail->additional_info) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Photo Tab -->
                        <div class="tab-pane {{ $activeTab === 'photo' ? 'active' : '' }}" data-tab="photo" role="tabpanel">
                            <div class="photo-section">
                                <div class="photo-preview">
                                    @if($user->photo)
                                        <img src="{{ asset('storage/photos/' . $user->photo) }}" alt="Profile Photo" class="preview-img" id="photoPreview">
                                    @else
                                        <div class="preview-placeholder" id="photoPreviewPlaceholder">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                            <span>No photo</span>
                                        </div>
                                    @endif
                                    <img style="display: none;" id="photoPreviewImg" class="preview-img" alt="Preview">
                                </div>
                                <div class="photo-upload">
                                    <label>Profile Photo</label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" name="photo" id="photoInput" class="file-input" accept="image/jpeg,image/png,image/jpg,image/gif">
                                        <label for="photoInput" class="file-label">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                            Choose File
                                        </label>
                                        <span class="file-name" id="fileName">No file chosen</span>
                                    </div>
                                    <small>Allowed: JPEG, PNG, JPG, GIF. Max size 2MB.</small>
                                    <div class="photo-tips">
                                        <p class="mb-0">💡 For best results, use a square image (minimum 300x300 pixels).</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="profile-footer">
                    <button type="reset" class="btn-reset" id="resetBtn">Reset</button>
                    <button type="submit" class="btn-update">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tab switching
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');
        const activeTabInput = document.getElementById('active_tab');
        const mobileTabSelect = document.getElementById('mobileTabSelect');

        function switchTab(tabName) {
            // Update desktop buttons
            tabBtns.forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-tab') === tabName);
            });
            // Update panes
            tabPanes.forEach(pane => {
                pane.classList.toggle('active', pane.getAttribute('data-tab') === tabName);
            });
            // Update hidden input
            if (activeTabInput) activeTabInput.value = tabName;
            // Update mobile select
            if (mobileTabSelect) mobileTabSelect.value = tabName;
            // Update URL hash without reload
            history.replaceState(null, null, `?tab=${tabName}`);
        }

        // Desktop tab clicks
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                switchTab(this.getAttribute('data-tab'));
            });
        });

        // Mobile dropdown change
        if (mobileTabSelect) {
            mobileTabSelect.addEventListener('change', function () {
                switchTab(this.value);
            });
        }

        // Avatar edit button - switch to photo tab
        const avatarEditBtn = document.querySelector('.avatar-edit-btn');
        if (avatarEditBtn) {
            avatarEditBtn.addEventListener('click', function () {
                switchTab('photo');
                // Scroll to photo tab on mobile
                const photoSection = document.querySelector('.photo-section');
                if (photoSection && window.innerWidth <= 768) {
                    photoSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        }

        // Photo preview
        const photoInput = document.getElementById('photoInput');
        const photoPreviewImg = document.getElementById('photoPreviewImg');
        const photoPreviewPlaceholder = document.getElementById('photoPreviewPlaceholder');
        const fileNameSpan = document.getElementById('fileName');

        if (photoInput) {
            photoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    fileNameSpan.textContent = file.name;
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        if (photoPreviewImg) {
                            photoPreviewImg.src = event.target.result;
                            photoPreviewImg.style.display = 'block';
                            if (photoPreviewPlaceholder) photoPreviewPlaceholder.style.display = 'none';
                            // Update main avatar preview
                            const mainAvatar = document.querySelector('.avatar-img');
                            if (mainAvatar) mainAvatar.src = event.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    fileNameSpan.textContent = 'No file chosen';
                    if (photoPreviewImg) {
                        photoPreviewImg.style.display = 'none';
                        if (photoPreviewPlaceholder) photoPreviewPlaceholder.style.display = 'flex';
                    }
                }
            });
        }

        // Reset button functionality
        const resetBtn = document.getElementById('resetBtn');
        const profileForm = document.getElementById('profileForm');
        if (resetBtn && profileForm) {
            resetBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (confirm('Reset all changes? Unsaved data will be lost.')) {
                    profileForm.reset();
                    if (photoInput) {
                        photoInput.value = '';
                        fileNameSpan.textContent = 'No file chosen';
                        if (photoPreviewImg) {
                            photoPreviewImg.style.display = 'none';
                            if (photoPreviewPlaceholder) photoPreviewPlaceholder.style.display = 'flex';
                        }
                    }
                    location.reload();
                }
            });
        }

        // Form submit indicator
        const submitBtn = document.querySelector('.btn-update');
        if (submitBtn && profileForm) {
            profileForm.addEventListener('submit', function () {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Saving...';
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Save Changes';
                }, 3000);
            });
        }
    });
</script>
@endsection
