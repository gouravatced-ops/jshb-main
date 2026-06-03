@csrf
@php
    $isEditMode = $engineer->exists;
    $sensitiveLocked = $isEditMode;
    $phoneMaskedValue = $userDetail?->masked_phone ?? 'XXXXX';
    $govtIdMaskedValue = $engineer->masked_state_government_engineer_id ?? 'XXXXX';
    $aadharMaskedValue = $engineer->masked_aadhar_no ?? 'XXXXX';
    $panMaskedValue = $engineer->masked_pan_card_no ?? 'XXXXX';
@endphp

<div class="form-container">
    <div class="form-section">
        <h5 class="section-title">Login Credential</h5>
        <div class="form-grid" autocomplete="off">
            <div class="form-group">
                <label>Email ID <span class="required">*</span></label>
                <input type="email" name="email" class="form-control email-validation" 
                 value="{{ old('email', $user?->email) }}" placeholder="Enter login email" required>
            </div>

            <div class="form-group">
                <label>
                    {{ $engineer->exists ? 'New Password' : 'Password' }}
                    @unless($engineer->exists)
                        <span class="required">*</span>
                    @endunless
                </label>
                <input type="password" name="password" class="form-control" placeholder="{{ $engineer->exists ? 'Leave blank to keep current password' : 'Enter password' }}" {{ $engineer->exists ? '' : 'required' }}>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h5 class="section-title">Engineer Details</h5>
        <div class="form-grid">
            <div class="form-group">
                <label>Mobile Number</label>
                <div style="display:flex;gap:10px;align-items:center;">
                    <input
                        type="text"
                        name="phone"
                        id="sensitivePhone"
                        class="form-control only-number"
                        value="{{ $isEditMode ? $phoneMaskedValue : old('phone', $userDetail?->phone) }}"
                        placeholder="Enter mobile number"
                        maxlength="10"
                        minlength="10"
                        {{ $sensitiveLocked ? 'disabled' : '' }}
                    >
                    @if($isEditMode)
                        <button type="button" class="btn-reset sensitive-eye-btn" data-action="view" style="white-space:nowrap;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label>Employee Name <span class="required">*</span></label>
                <input type="text" name="employee_name" class="form-control only-alphabet" value="{{ old('employee_name', $engineer->employee_name) }}" placeholder="Enter employee name" required>
            </div>

            <div class="form-group">
                <label>Employee Hindi Name</label>
                <input type="text" name="employee_hindi_name" class="form-control only-hindi" value="{{ old('employee_hindi_name', $engineer->employee_hindi_name) }}" placeholder="Enter employee name in Hindi">
            </div>

            <div class="form-group">
                <label>State Government Engineer ID <span class="required">*</span></label>
                <div style="display:flex;gap:10px;align-items:center;">
                    <input
                        type="text"
                        name="state_government_engineer_id"
                        id="sensitiveGovernmentId"
                        class="form-control only-alphanumeric"
                        value="{{ $isEditMode ? $govtIdMaskedValue : old('state_government_engineer_id', $engineer->state_government_engineer_id) }}"
                        placeholder="Enter government engineer ID"
                        required
                        {{ $sensitiveLocked ? 'disabled' : '' }}
                    >
                    @if($isEditMode)
                        <button type="button" class="btn-reset sensitive-eye-btn" data-action="view" style="white-space:nowrap;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label>Post Type <span class="required">*</span></label>
                <select name="post_type_id" class="form-control" required>
                    <option value="">Select post type</option>
                    @foreach($postTypes as $postType)
                        <option value="{{ $postType->id }}" {{ (string) old('post_type_id', $engineer->post_type_id) === (string) $postType->id ? 'selected' : '' }}>
                            {{ $postType->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Department <span class="required">*</span></label>
                <select name="department_id" class="form-control" required>
                    <option value="">Select department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ (string) old('department_id', $engineer->department_id) === (string) $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Current Organization <span class="required">*</span></label>
                <select name="current_organization_id" class="form-control" required>
                    <option value="">Select current organization</option>
                    @foreach($organizations as $organization)
                        <option value="{{ $organization->id }}" {{ (string) old('current_organization_id', $engineer->current_organization_id) === (string) $organization->id ? 'selected' : '' }}>
                            {{ $organization->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Parent Organization</label>
                <select name="parent_organization_id" class="form-control">
                    <option value="">Select parent organization</option>
                    @foreach($organizations as $organization)
                        <option value="{{ $organization->id }}" {{ (string) old('parent_organization_id', $engineer->parent_organization_id) === (string) $organization->id ? 'selected' : '' }}>
                            {{ $organization->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>District <span class="required">*</span></label>
                <select name="district_id" id="engineerDistrict" class="form-control" required>
                    <option value="">Select district</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}" {{ (string) old('district_id', $engineer->district_id) === (string) $district->id ? 'selected' : '' }}>
                            {{ $district->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Block <span class="required">*</span></label>
                <select name="block_id" id="engineerBlock" class="form-control" required>
                    <option value="">Select block</option>
                    @foreach($blocks as $block)
                        <option value="{{ $block->id }}" data-district-id="{{ $block->district_id }}" {{ (string) old('block_id', $engineer->block_id) === (string) $block->id ? 'selected' : '' }}>
                            {{ $block->block_name_eng }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Division</label>
                <select name="division_id" id="engineerDivision" class="form-control">
                    <option value="">Select division</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}" {{ (string) old('division_id', $engineer->division_id) === (string) $division->id ? 'selected' : '' }}>
                            {{ $division->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Sub Division</label>
                <select name="sub_division_id" id="engineerSubDivision" class="form-control">
                    <option value="">Select sub division</option>
                    @foreach($subDivisions as $subDivision)
                        <option value="{{ $subDivision->id }}" data-division-id="{{ $subDivision->division_id }}" {{ (string) old('sub_division_id', $engineer->sub_division_id) === (string) $subDivision->id ? 'selected' : '' }}>
                            {{ $subDivision->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', optional($engineer->date_of_birth)->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label>Anniversary Date</label>
                <input type="date" name="anniversary_date" class="form-control" value="{{ old('anniversary_date', optional($engineer->anniversary_date)->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label>Spouse Name</label>
                <input type="text" name="spouse_name only-alphabet" class="form-control" value="{{ old('spouse_name', $engineer->spouse_name) }}" placeholder="Enter spouse name">
            </div>

            <div class="form-group">
                <label>No. of Child</label>
                <input type="number" min="0" name="no_of_children only-number" class="form-control" value="{{ old('no_of_children', $engineer->no_of_children) }}" placeholder="Enter number of children">
            </div>

            <div class="form-group">
                <label>Aadhar No</label>
                <div style="display:flex;gap:10px;align-items:center;">
                    <input
                        type="text"
                        name="aadhar_no"
                        id="sensitiveAadhar"
                        class="form-control"
                        value="{{ $isEditMode ? $aadharMaskedValue : old('aadhar_no', $engineer->aadhar_no) }}"
                        placeholder="Enter Aadhar number"
                        minlength="12"
                        maxlength="12"
                        {{ $sensitiveLocked ? 'disabled' : '' }}
                    >
                    @if($isEditMode)
                        <button type="button" class="btn-reset sensitive-eye-btn" data-action="view" style="white-space:nowrap;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label>PAN Card No</label>
                <div style="display:flex;gap:10px;align-items:center;">
                    <input
                        type="text"
                        name="pan_card_no"
                        id="sensitivePan"
                        class="form-control only-alphanumeric"
                        value="{{ $isEditMode ? $panMaskedValue : old('pan_card_no', $engineer->pan_card_no) }}"
                        placeholder="Enter PAN card number"
                        minlength="10"
                        maxlength="10"
                        {{ $sensitiveLocked ? 'disabled' : '' }}
                    >
                    @if($isEditMode)
                        <button type="button" class="btn-reset sensitive-eye-btn" data-action="view" style="white-space:nowrap;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.engineers.index') }}" class="btn-reset" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
    @if($isEditMode)
        <button type="button" class="btn-reset" id="enableSensitiveEditBtn">Enable Sensitive Edit</button>
    @endif
    <button type="submit" class="btn-submit">{{ $submitLabel }}</button>
</div>

@if($isEditMode)
    <div id="sensitivePinModal" style="position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;align-items:center;justify-content:center;z-index:1200;padding:20px;">
        <div style="width:min(100%,430px);background:#fff;border-radius:20px;padding:24px;box-shadow:0 24px 60px rgba(15,23,42,.2);">
            <h5 style="margin:0 0 8px;font-weight:700;">Verify Security PIN</h5>
            <p style="margin:0 0 16px;color:var(--text-light);">Enter your 5 digit admin PIN to view or edit encrypted employee details.</p>
            <div id="sensitivePinError" style="display:none;margin-bottom:14px;color:#dc2626;font-size:14px;"></div>
            <input type="password" id="sensitivePinInput" class="form-control" maxlength="5" placeholder="Enter 5 digit PIN">
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:18px;">
                <button type="button" class="btn-reset" id="closeSensitivePinModal">Cancel</button>
                <button type="button" class="btn-submit" id="verifySensitivePinBtn">Verify</button>
            </div>
        </div>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const districtField = document.getElementById('engineerDistrict');
        const blockField = document.getElementById('engineerBlock');
        const divisionField = document.getElementById('engineerDivision');
        const subDivisionField = document.getElementById('engineerSubDivision');
        const isEditMode = @json($isEditMode);
        const sensitiveFields = {
            phone: document.getElementById('sensitivePhone'),
            state_government_engineer_id: document.getElementById('sensitiveGovernmentId'),
            aadhar_no: document.getElementById('sensitiveAadhar'),
            pan_card_no: document.getElementById('sensitivePan'),
        };
        const modal = document.getElementById('sensitivePinModal');
        const pinInput = document.getElementById('sensitivePinInput');
        const pinError = document.getElementById('sensitivePinError');
        const verifyBtn = document.getElementById('verifySensitivePinBtn');
        const closeBtn = document.getElementById('closeSensitivePinModal');
        const enableSensitiveEditBtn = document.getElementById('enableSensitiveEditBtn');
        const eyeButtons = document.querySelectorAll('.sensitive-eye-btn');
        let pendingSensitiveAction = 'view';

        function filterOptions(selectField, sourceAttribute, parentValue) {
            const options = Array.from(selectField.options);

            options.forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    return;
                }

                const matches = !parentValue || option.dataset[sourceAttribute] === parentValue;
                option.hidden = !matches;
            });

            const selectedOption = selectField.options[selectField.selectedIndex];
            if (selectedOption && selectedOption.hidden) {
                selectField.value = '';
            }
        }

        function syncBlocks() {
            filterOptions(blockField, 'districtId', districtField.value);
        }

        function syncSubDivisions() {
            filterOptions(subDivisionField, 'divisionId', divisionField.value);

            if (!divisionField.value) {
                subDivisionField.value = '';
            }
        }

        if (districtField && blockField) {
            syncBlocks();
            districtField.addEventListener('change', syncBlocks);
        }

        if (divisionField && subDivisionField) {
            syncSubDivisions();
            divisionField.addEventListener('change', syncSubDivisions);
        }

        function openSensitiveModal(action) {
            if (!modal) {
                return;
            }

            pendingSensitiveAction = action;
            pinError.style.display = 'none';
            pinError.textContent = '';
            pinInput.value = '';
            modal.style.display = 'flex';
            setTimeout(() => pinInput.focus(), 30);
        }

        function closeSensitiveModal() {
            if (modal) {
                modal.style.display = 'none';
            }
        }

        function applySensitiveData(data, editable) {
            Object.entries(sensitiveFields).forEach(([key, field]) => {
                if (!field) {
                    return;
                }

                field.value = data[key] || '';

                if (editable) {
                    field.removeAttribute('disabled');
                }
            });
        }

        function verifySensitivePin() {
            if (!pinInput || !verifyBtn) {
                return;
            }

            pinError.style.display = 'none';
            verifyBtn.disabled = true;

            fetch(@json(route('admin.engineers.verify-sensitive', $engineer->encrypted_route_key)), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    pin: pinInput.value.trim(),
                }),
            })
                .then(async (response) => {
                    const payload = await response.json();

                    if (!response.ok) {
                        throw new Error(payload.message || 'Verification failed.');
                    }

                    applySensitiveData(payload.data || {}, pendingSensitiveAction === 'edit');
                    closeSensitiveModal();
                })
                .catch((error) => {
                    pinError.textContent = error.message;
                    pinError.style.display = 'block';
                })
                .finally(() => {
                    verifyBtn.disabled = false;
                });
        }

        if (isEditMode) {
            eyeButtons.forEach((button) => {
                button.addEventListener('click', function () {
                    openSensitiveModal('view');
                });
            });

            if (enableSensitiveEditBtn) {
                enableSensitiveEditBtn.addEventListener('click', function () {
                    openSensitiveModal('edit');
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', closeSensitiveModal);
            }

            if (verifyBtn) {
                verifyBtn.addEventListener('click', verifySensitivePin);
            }

            if (modal) {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeSensitiveModal();
                    }
                });
            }
        }
    });
</script>
