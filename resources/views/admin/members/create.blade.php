@extends('layouts.main')

@section('title', 'Add Member | JSHB')

@section('content')
<div class="form-container">
    <div class="form-wrapper">
        <div class="form-main">
            @if($errors->any())
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-header">
                <div>
                    <h4>Add Member</h4>
                    <p>Create a new administrative board member or staff account.</p>
                </div>
                <a href="{{ route('admin.members.index') }}" class="btn-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Back
                </a>
            </div>

            <form action="{{ route('admin.members.store') }}" method="POST" id="memberForm">
                @csrf
                <div class="form-container">
                    <div class="form-section">
                        <h5 class="section-title">Account Details</h5>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Full Name <span class="required">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter full name" required>
                            </div>

                            <div class="form-group">
                                <label>Email Address <span class="required">*</span></label>
                                <input type="email" name="email" class="form-control email-validation" value="{{ old('email') }}" placeholder="name@example.com" required>
                            </div>

                            <div class="form-group">
                                <label>Password <span class="required">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" required>
                            </div>

                            <div class="form-group">
                                <label>Confirm Password <span class="required">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                            </div>

                            <div class="form-group">
                                <label>Role <span class="required">*</span></label>
                                <select name="role_id" id="roleSelect" class="form-select" required>
                                    <option value="" disabled selected>Select Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" data-slug="{{ $role->slug }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }} ({{ $role->short_name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="divisionGroup" style="display:none;">
                                <label>Division <span class="required">*</span></label>
                                <select name="division_id" id="divisionSelect" class="form-select">
                                    <option value="" disabled selected>Select Division</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                            {{ $division->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>OTP Login <span class="required">*</span></label>
                                <select name="login_with_otp" class="form-select" required>
                                    <option value="0" {{ old('login_with_otp') === '0' ? 'selected' : '' }}>Disabled (Password Only)</option>
                                    <option value="1" {{ old('login_with_otp') === '1' ? 'selected' : '' }}>Enabled (Requires OTP)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section" style="margin-top:20px;">
                        <h5 class="section-title">Profile Details</h5>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" minlength="10" maxlength="10" class="form-control only-number" value="{{ old('phone') }}" placeholder="Enter phone number">
                            </div>

                            <div class="form-group">
                                <label>Designation</label>
                                <input type="text" name="designation" class="form-control" value="{{ old('designation') }}" placeholder="Enter designation (e.g. Executive Engineer)">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <a href="{{ route('admin.members.index') }}" class="btn-reset" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
                    <button type="submit" class="btn-submit">Create Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('roleSelect');
        const divisionGroup = document.getElementById('divisionGroup');
        const divisionSelect = document.getElementById('divisionSelect');

        function toggleDivisionField() {
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const slug = selectedOption ? selectedOption.getAttribute('data-slug') : '';
            
            if (slug === 'operator' || slug === 'managing-director') {
                divisionGroup.style.display = 'none';
                divisionSelect.removeAttribute('required');
                divisionSelect.value = '';
            } else {
                divisionGroup.style.display = 'block';
                divisionSelect.setAttribute('required', 'required');
            }
        }

        roleSelect.addEventListener('change', toggleDivisionField);
        toggleDivisionField(); // Run on initial load in case of validation errors
    });
</script>
@endsection
