document.addEventListener('DOMContentLoaded', function() {
    if (!window.WorkflowData) return;

    const roles = window.WorkflowData.roles || [];
    let stepIndex = 0;
    const stepsContainer = document.getElementById('stepsContainer');
    const existingSteps = window.WorkflowData.existingSteps || [];

    function addStep(stepData = null) {
        const i = stepIndex++;
        const id = stepData?.id || '';
        const stepOrder = stepData?.step_order || (i + 1);
        const stepName = stepData?.step_name || '';
        const stepCode = stepData?.step_code || '';
        const roleId = stepData?.role_id || '';
        const actionType = stepData?.action_type || 'verify';
        const allowedDays = stepData?.allowed_days ?? 15;

        const canForward = stepData ? (stepData.can_forward == 1 ? 'checked' : '') : 'checked';
        const canSendBack = stepData ? (stepData.can_send_back == 1 ? 'checked' : '') : 'checked';
        const canReject = stepData ? (stepData.can_reject == 1 ? 'checked' : '') : '';
        const canApprove = stepData ? (stepData.can_approve == 1 ? 'checked' : '') : (actionType === 'approve' ? 'checked' : '');
        const canVerifySite = stepData ? (stepData.can_verify_site == 1 ? 'checked' : '') : (actionType === 'site_verification' ? 'checked' : '');
        const canCorrespondence = stepData ? (stepData.can_correspondence == 1 ? 'checked' : '') : '';
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

                <div class="wf-permissions-box">
                    <div class="wf-permissions-title">
                        <i class="fa-solid fa-shield-halved text-primary"></i> Step Action Permissions & Role Capabilities
                    </div>

                    <div class="wf-permissions-grid">
                        <label class="wf-permission-chip ${canForward ? 'checked' : ''}">
                            <input type="checkbox" id="can_forward_${i}" name="steps[${i}][can_forward]" value="1" ${canForward} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-arrow-right text-success"></i> Can Forward</span>
                        </label>

                        <label class="wf-permission-chip ${canSendBack ? 'checked' : ''}">
                            <input type="checkbox" id="can_send_back_${i}" name="steps[${i}][can_send_back]" value="1" ${canSendBack} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-reply text-warning"></i> Can Send Back</span>
                        </label>

                        <label class="wf-permission-chip ${canReject ? 'checked' : ''}">
                            <input type="checkbox" id="can_reject_${i}" name="steps[${i}][can_reject]" value="1" ${canReject} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-ban text-danger"></i> Can Reject</span>
                        </label>

                        <label class="wf-permission-chip ${canApprove ? 'checked' : ''}">
                            <input type="checkbox" id="can_approve_${i}" name="steps[${i}][can_approve]" value="1" ${canApprove} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-check-double text-teal" style="color:#0d9488;"></i> Can Approve</span>
                        </label>

                        <label class="wf-permission-chip ${canVerifySite ? 'checked' : ''}">
                            <input type="checkbox" id="can_verify_site_${i}" name="steps[${i}][can_verify_site]" value="1" ${canVerifySite} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-map-location-dot" style="color:#ea580c;"></i> Site Verification</span>
                        </label>

                        <label class="wf-permission-chip ${canCorrespondence ? 'checked' : ''}">
                            <input type="checkbox" id="can_correspondence_${i}" name="steps[${i}][can_correspondence]" value="1" ${canCorrespondence} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-envelope-open-text" style="color:#7c3aed;"></i> Office Correspondences</span>
                        </label>

                        <label class="wf-permission-chip ${canAddNote ? 'checked' : ''}">
                            <input type="checkbox" id="can_add_note_${i}" name="steps[${i}][can_add_note]" value="1" ${canAddNote} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-pen-to-square text-primary"></i> Add Note</span>
                        </label>

                        <label class="wf-permission-chip ${canUploadDocument ? 'checked' : ''}">
                            <input type="checkbox" id="can_upload_${i}" name="steps[${i}][can_upload_document]" value="1" ${canUploadDocument} onchange="toggleChipClass(this)">
                            <span><i class="fa-solid fa-file-arrow-up text-secondary"></i> Upload Docs</span>
                        </label>

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

    const addStepBtn = document.getElementById('addStepBtn');
    if (addStepBtn) {
        addStepBtn.addEventListener('click', () => addStep());
    }

    if (existingSteps && existingSteps.length > 0) {
        existingSteps.forEach(step => addStep(step));
    } else if (stepsContainer) {
        addStep();
    }

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
