@csrf

<style>
    .wf-form-container {
        padding: 20px;
    }
    .wf-section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 22px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }
    .wf-section-header {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 18px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .wf-section-header .title-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .wf-section-header i {
        color: #3b82f6;
    }
    .wf-form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px 20px;
    }
    .wf-form-grid .full-width {
        grid-column: 1 / -1;
    }
    .wf-form-label {
        display: block;
        font-size: 12.5px;
        color: #1e293b;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .wf-form-label .required {
        color: #ef4444;
        margin-left: 2px;
    }
    .wf-form-control, .wf-form-select {
        width: 100%;
        padding: 9px 13px;
        font-size: 14px;
        color: #0f172a;
        background-color: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .wf-form-control:focus, .wf-form-select:focus {
        border-color: #3b82f6;
        outline: 0;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    .wf-step-card {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-left: 4px solid #3b82f6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
        transition: all 0.2s ease;
    }
    .wf-step-card:hover {
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        border-color: #94a3b8;
        border-left-color: #2563eb;
    }
    .wf-step-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e2e8f0;
    }
    .wf-step-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .wf-step-title-preview {
        font-size: 14.5px;
        font-weight: 600;
        color: #0f172a;
        margin-left: 8px;
    }
    .wf-btn-remove {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .wf-btn-remove:hover {
        background: #fca5a5;
        color: #7f1d1d;
    }
    .wf-step-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px 18px;
    }
    @media (max-width: 991px) {
        .wf-step-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 640px) {
        .wf-form-grid, .wf-step-grid {
            grid-template-columns: 1fr;
        }
    }
    .wf-permissions-box {
        margin-top: 18px;
        padding-top: 16px;
        border-top: 1px dashed #cbd5e1;
    }
    .wf-permissions-title {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .wf-permissions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 10px;
    }
    .wf-permission-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 9px 12px;
        cursor: pointer;
        user-select: none;
        transition: all 0.15s ease;
    }
    .wf-permission-chip:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    .wf-permission-chip input[type="checkbox"] {
        width: 17px;
        height: 17px;
        cursor: pointer;
        accent-color: #0d6efd;
    }
    .wf-permission-chip span {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .wf-permission-chip.checked {
        background: #f0fdf4;
        border-color: #86efac;
    }
    .wf-permission-chip.checked span {
        color: #166534;
    }
    .wf-doc-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 10px;
    }
    .wf-doc-item {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px 14px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .wf-doc-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    .wf-doc-item input[type="checkbox"] {
        width: 17px;
        height: 17px;
        cursor: pointer;
        accent-color: #0d6efd;
    }
    .wf-doc-item span {
        font-size: 13px;
        font-weight: 500;
        color: #1e293b;
    }
    .wf-footer-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    .btn-wf-primary {
        background: #0d6efd;
        color: #ffffff;
        font-weight: 600;
        font-size: 14px;
        padding: 9px 22px;
        border-radius: 6px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.2);
        transition: all 0.15s ease;
        text-decoration: none;
    }
    .btn-wf-primary:hover {
        background: #0b5ed7;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
    }
    .btn-wf-secondary {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 14px;
        padding: 9px 20px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }
    .btn-wf-secondary:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
</style>

<div class="wf-form-container">

    <!-- Card 1: Workflow Basic Details -->
    <div class="wf-section-card">
        <div class="wf-section-header">
            <div class="title-left">
                <i class="fa-solid fa-code-branch"></i>
                <span>Workflow Details</span>
            </div>
            <span class="badge bg-light text-muted border px-2 py-1 font-monospace" style="font-size: 11px;">ADMS-WF</span>
        </div>

        <div class="wf-form-grid">
            <div class="full-width">
                <label class="wf-form-label">Workflow Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="wf-form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $workflow->name) }}" placeholder="e.g. Standard Allotment Approval Workflow" required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="wf-form-label">Workflow Slug <span class="required">*</span></label>
                <input type="text" id="slug" name="slug" class="wf-form-control @error('slug') is-invalid @enderror"
                    value="{{ old('slug', $workflow->slug) }}" placeholder="e.g. standard-allotment" required>
                @error('slug')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="wf-form-label">Application Type <span class="required">*</span></label>
                <select id="application_type" name="application_type" class="wf-form-select @error('application_type') is-invalid @enderror" required>
                    <option value="">-- Select Application Type --</option>
                    @php
                        $appTypes = [
                            'allotment'             => 'Allotment',
                            'agreement'             => 'Agreement',
                            'possession'            => 'Possession',
                            'registry'              => 'Registry',
                            'mutation'              => 'Mutation',
                            'transfer'              => 'Transfer',
                            'noc'                   => 'NOC (No Objection Certificate)',
                            'lease_renewal'         => 'Lease Renewal',
                            'duplicate_certificate' => 'Duplicate Certificate',
                            'cancellation'          => 'Cancellation',
                            'name_correction'       => 'Name Correction',
                        ];
                    @endphp
                    @foreach($appTypes as $val => $label)
                        <option value="{{ $val }}" {{ old('application_type', $workflow->application_type) == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('application_type')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="full-width">
                <label class="wf-form-label">Description / Scope</label>
                <textarea id="description" name="description" class="wf-form-control @error('description') is-invalid @enderror" rows="2"
                    placeholder="Describe the purpose, departments involved, and scope of this workflow...">{{ old('description', $workflow->description) }}</textarea>
                @error('description')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="full-width">
                <label class="wf-form-label">Workflow Status <span class="required">*</span></label>
                <select name="is_active" class="wf-form-select" required>
                    <option value="1" {{ old('is_active', $workflow->is_active ?? 1) == 1 ? 'selected' : '' }}>Active (Enabled for applications)</option>
                    <option value="0" {{ old('is_active', $workflow->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive (Draft / Suspended)</option>
                </select>
                @error('is_active')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Card 2: Required Documents -->
    <div class="wf-section-card">
        <div class="wf-section-header">
            <div class="title-left">
                <i class="fa-solid fa-folder-tree"></i>
                <span>Required Documents Master</span>
            </div>
            <span class="text-muted small" style="font-size: 12px; text-transform: none; font-weight: 500;">
                Select documents that officers can request or require from allottees
            </span>
        </div>

        <div class="wf-doc-grid">
            @php
                $selectedDocs = old('required_documents', isset($workflow) && $workflow->exists ? $workflow->requiredDocuments->pluck('id')->toArray() : []);
            @endphp
            @foreach($documents as $doc)
                <label class="wf-doc-item">
                    <input type="checkbox" name="required_documents[]" value="{{ $doc->id }}"
                        {{ in_array($doc->id, $selectedDocs) ? 'checked' : '' }}>
                    <span><i class="fa-regular fa-file-lines text-muted me-1"></i> {{ $doc->document_name }}</span>
                </label>
            @endforeach
        </div>
        @error('required_documents')
            <div class="text-danger small mt-2">{{ $message }}</div>
        @enderror
    </div>

    <!-- Card 3: Workflow Steps Hierarchy -->
    <div class="wf-section-card">
        <div class="wf-section-header">
            <div class="title-left">
                <i class="fa-solid fa-diagram-project"></i>
                <span>Workflow Approval Steps & Role Hierarchy</span>
            </div>
            <button type="button" class="btn-wf-primary" id="addStepBtn" style="padding: 6px 16px; font-size: 13px;">
                <i class="fa-solid fa-plus"></i> Add New Step
            </button>
        </div>

        <p class="text-muted small mb-3" style="font-size: 13px; line-height: 1.5;">
            Define the sequential progression of application movement. Each step assigns a role, allowed action days, and action permissions (Forward, Send Back, Reject, Approve, Site Verification, Office Correspondences, and Notes).
        </p>

        <div id="stepsContainer">
            <!-- Dynamic Step Cards Injected via JS -->
        </div>
    </div>

    <!-- Form Footer Actions -->
    <div class="wf-footer-actions">
        <a href="{{ route('admin.workflows.index') }}" class="btn-wf-secondary">
            <i class="fa-solid fa-arrow-left"></i> Cancel / Back to List
        </a>
        <button type="submit" class="btn-wf-primary">
            <i class="fa-solid fa-floppy-disk"></i> {{ $submitLabel }}
        </button>
    </div>

</div>

<script>
window.WorkflowData = {
    roles: {!! json_encode($roles ?? []) !!},
    existingSteps: {!! json_encode(isset($workflow) ? $workflow->steps : []) !!}
};

document.addEventListener('DOMContentLoaded', function() {
    const roles = window.WorkflowData.roles || [];
    const existingSteps = window.WorkflowData.existingSteps || [];
    let stepIndex = 0;
    const stepsContainer = document.getElementById('stepsContainer');

    function addStep(stepData = null) {
        const i = stepIndex++;
        const id = stepData?.id || '';
        const stepOrder = stepData?.step_order || (i + 1);
        const stepName = stepData?.step_name || '';
        const stepCode = stepData?.step_code || '';
        const roleId = stepData?.role_id || '';
        const actionType = stepData?.action_type || 'verify';
        const allowedDays = stepData?.allowed_days ?? 15;

        // Permissions logic:
        // can_forward: checked if new or if existing is 1
        const canForward = stepData ? (stepData.can_forward == 1 ? 'checked' : '') : 'checked';
        const canSendBack = stepData ? (stepData.can_send_back == 1 ? 'checked' : '') : 'checked';
        const canReject = stepData ? (stepData.can_reject == 1 ? 'checked' : '') : '';
        const canApprove = stepData ? (stepData.can_approve == 1 ? 'checked' : '') : (actionType === 'approve' ? 'checked' : '');
        const canVerifySite = stepData ? (stepData.can_verify_site == 1 ? 'checked' : '') : (actionType === 'site_verification' ? 'checked' : '');
        const canCorrespondence = stepData ? (stepData.can_correspondence == 1 ? 'checked' : '') : '';
        
        // USER REQUIREMENT: "Add Note default checked hi rahega jisse forward mein notes likh sakta hoon"
        const canAddNote = stepData ? (stepData.can_add_note == 1 ? 'checked' : '') : 'checked';
        const canUploadDocument = stepData ? (stepData.can_upload_document == 1 ? 'checked' : '') : 'checked';
        const requiresSignature = stepData ? (stepData.requires_signature == 1 ? 'checked' : '') : '';

        let roleOptions = '<option value="">-- Select Assigned Role --</option>';
        roles.forEach(role => {
            const selected = role.id == roleId ? 'selected' : '';
            roleOptions += `<option value="${role.id}" ${selected}>${role.name}</option>`;
        });

        const actionTypes = [
            { val: 'view', label: 'View Only (Review / Scrutiny)' },
            { val: 'verify', label: 'Verification (Document / Scrutiny Check)' },
            { val: 'site_verification', label: 'Site Inspection / Field Verification' },
            { val: 'recommend', label: 'Recommend / Forward with Noting' },
            { val: 'approve', label: 'Approval (Competent Authority / Final Sanction)' },
            { val: 'generate_document', label: 'Document Generation (Letters / Orders)' },
            { val: 'payment', label: 'Payment / Fee Collection Check' }
        ];

        let actionOptions = '<option value="">-- Select Action Type --</option>';
        actionTypes.forEach(action => {
            const selected = action.val == actionType ? 'selected' : '';
            actionOptions += `<option value="${action.val}" ${selected}>${action.label}</option>`;
        });

        const html = `
            <div class="wf-step-card" id="step-row-${i}">
                <div class="wf-step-card-header">
                    <div class="d-flex align-items-center">
                        <span class="wf-step-badge">
                            <i class="fa-solid fa-list-ol"></i> Step <span class="step-num-display">${stepOrder}</span>
                        </span>
                        <span class="wf-step-title-preview" id="step-preview-${i}">${stepName ? stepName : 'New Workflow Step'}</span>
                    </div>
                    <button type="button" class="wf-btn-remove" onclick="removeStepCard(${i})" title="Remove this step">
                        <i class="fa-solid fa-trash-can"></i> Remove Step
                    </button>
                </div>

                <input type="hidden" name="steps[${i}][id]" value="${id}">

                <div class="wf-step-grid">
                    <div>
                        <label class="wf-form-label">Sequence Order <span class="required">*</span></label>
                        <input type="number" name="steps[${i}][step_order]" class="wf-form-control step-order-input"
                            value="${stepOrder}" min="1" max="50" required onchange="updateStepOrderDisplay(${i}, this.value)">
                    </div>

                    <div>
                        <label class="wf-form-label">Step Name <span class="required">*</span></label>
                        <input type="text" name="steps[${i}][step_name]" class="wf-form-control"
                            value="${stepName}" placeholder="e.g. Dealing Assistant Scrutiny" required
                            oninput="document.getElementById('step-preview-${i}').innerText = this.value || 'Step Detail'">
                    </div>

                    <div>
                        <label class="wf-form-label">Step Code <span class="required">*</span></label>
                        <input type="text" name="steps[${i}][step_code]" class="wf-form-control font-monospace"
                            value="${stepCode}" placeholder="e.g. DA_SCRUTINY" required style="text-transform:uppercase;">
                    </div>

                    <div>
                        <label class="wf-form-label">Assigned Role <span class="required">*</span></label>
                        <select name="steps[${i}][role_id]" class="wf-form-select" required>
                            ${roleOptions}
                        </select>
                    </div>

                    <div>
                        <label class="wf-form-label">Primary Action Type <span class="required">*</span></label>
                        <select name="steps[${i}][action_type]" class="wf-form-select" required onchange="handleActionTypeChange(${i}, this.value)">
                            ${actionOptions}
                        </select>
                    </div>

                    <div>
                        <label class="wf-form-label">Allowed SLA Days</label>
                        <input type="number" name="steps[${i}][allowed_days]" class="wf-form-control"
                            value="${allowedDays}" placeholder="e.g. 15" min="0" max="90">
                    </div>
                </div>

                <!-- Step Capabilities & Permission Matrix -->
                <div class="wf-permissions-box">
                    <div class="wf-permissions-title">
                        <i class="fa-solid fa-shield-halved text-primary"></i> Step Action Permissions & Role Capabilities
                    </div>

                    <div class="wf-permissions-grid">
                        <!-- Can Forward -->
                        <label class="wf-permission-chip ${canForward ? 'checked' : ''}">
                            <input type="checkbox" id="can_forward_${i}" name="steps[${i}][can_forward]" value="1" ${canForward} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-arrow-right text-success"></i> Can Forward</span>
                        </label>

                        <!-- Can Send Back -->
                        <label class="wf-permission-chip ${canSendBack ? 'checked' : ''}">
                            <input type="checkbox" id="can_send_back_${i}" name="steps[${i}][can_send_back]" value="1" ${canSendBack} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-reply text-warning"></i> Can Send Back</span>
                        </label>

                        <!-- Can Reject -->
                        <label class="wf-permission-chip ${canReject ? 'checked' : ''}">
                            <input type="checkbox" id="can_reject_${i}" name="steps[${i}][can_reject]" value="1" ${canReject} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-ban text-danger"></i> Can Reject</span>
                        </label>

                        <!-- Can Approve (Woh approve wo) -->
                        <label class="wf-permission-chip ${canApprove ? 'checked' : ''}">
                            <input type="checkbox" id="can_approve_${i}" name="steps[${i}][can_approve]" value="1" ${canApprove} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-check-double text-teal" style="color:#0d9488;"></i> Can Approve</span>
                        </label>

                        <!-- Site Verification Option -->
                        <label class="wf-permission-chip ${canVerifySite ? 'checked' : ''}">
                            <input type="checkbox" id="can_verify_site_${i}" name="steps[${i}][can_verify_site]" value="1" ${canVerifySite} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-map-location-dot" style="color:#ea580c;"></i> Site Verification</span>
                        </label>

                        <!-- Office Correspondences Option -->
                        <label class="wf-permission-chip ${canCorrespondence ? 'checked' : ''}">
                            <input type="checkbox" id="can_correspondence_${i}" name="steps[${i}][can_correspondence]" value="1" ${canCorrespondence} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-envelope-open-text" style="color:#7c3aed;"></i> Office Correspondences</span>
                        </label>

                        <!-- Add Note (DEFAULT CHECKED) -->
                        <label class="wf-permission-chip ${canAddNote ? 'checked' : ''}">
                            <input type="checkbox" id="can_add_note_${i}" name="steps[${i}][can_add_note]" value="1" ${canAddNote} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-pen-to-square text-primary"></i> Add Note</span>
                        </label>

                        <!-- Upload Docs -->
                        <label class="wf-permission-chip ${canUploadDocument ? 'checked' : ''}">
                            <input type="checkbox" id="can_upload_${i}" name="steps[${i}][can_upload_document]" value="1" ${canUploadDocument} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-file-arrow-up text-secondary"></i> Upload Docs</span>
                        </label>

                        <!-- Requires Signature -->
                        <label class="wf-permission-chip ${requiresSignature ? 'checked' : ''}">
                            <input type="checkbox" id="req_sign_${i}" name="steps[${i}][requires_signature]" value="1" ${requiresSignature} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-signature text-dark"></i> Requires Signature</span>
                        </label>
                    </div>
                </div>
            </div>
        `;

        stepsContainer.insertAdjacentHTML('beforeend', html);
    }

    window.removeStepCard = function(index) {
        const el = document.getElementById(`step-row-${index}`);
        if (el) {
            el.remove();
            resequenceStepOrder();
        }
    };

    window.toggleChipClass = function(input) {
        const chip = input.closest('.wf-permission-chip');
        if (chip) {
            if (input.checked) {
                chip.classList.add('checked');
            } else {
                chip.classList.remove('checked');
            }
        }
    };

    window.updateStepOrderDisplay = function(index, val) {
        const el = document.getElementById(`step-row-${index}`);
        if (el) {
            const badge = el.querySelector('.step-num-display');
            if (badge) badge.innerText = val;
        }
    };

    window.handleActionTypeChange = function(index, actionVal) {
        const approveCheckbox = document.getElementById(`can_approve_${index}`);
        const siteCheckbox = document.getElementById(`can_verify_site_${index}`);
        if (actionVal === 'approve' && approveCheckbox) {
            approveCheckbox.checked = true;
            toggleChipClass(approveCheckbox);
        }
        if (actionVal === 'site_verification' && siteCheckbox) {
            siteCheckbox.checked = true;
            toggleChipClass(siteCheckbox);
        }
    };

    function resequenceStepOrder() {
        const stepCards = document.querySelectorAll('.wf-step-card');
        stepCards.forEach((card, idx) => {
            const orderInput = card.querySelector('.step-order-input');
            const orderBadge = card.querySelector('.step-num-display');
            if (orderInput && (!orderInput.value || orderInput.value == idx)) {
                orderInput.value = idx + 1;
            }
            if (orderBadge && orderInput) {
                orderBadge.innerText = orderInput.value;
            }
        });
    }

    document.getElementById('addStepBtn').addEventListener('click', () => {
        addStep();
        resequenceStepOrder();
    });

    if (existingSteps && existingSteps.length > 0) {
        existingSteps.forEach(step => addStep(step));
    } else {
        // Add default first step (Dealing Assistant)
        addStep({
            step_order: 1,
            step_name: 'Dealing Assistant Scrutiny',
            step_code: 'DA_SCRUTINY',
            action_type: 'verify',
            allowed_days: 15,
            can_forward: 1,
            can_send_back: 0,
            can_reject: 0,
            can_approve: 0,
            can_verify_site: 0,
            can_correspondence: 1,
            can_add_note: 1,
            can_upload_document: 1,
            requires_signature: 0
        });
    }

    // Auto-generate slug from workflow name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            slugInput.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        });
    }
});
</script>
