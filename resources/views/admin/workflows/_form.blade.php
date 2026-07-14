@csrf

<div class="form-container">
    <div class="form-section">
        <h5 class="section-title">Workflow Details</h5>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Workflow Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $workflow->name) }}" placeholder="Enter workflow name" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Slug <span class="required">*</span></label>
                <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror"
                    value="{{ old('slug', $workflow->slug) }}" placeholder="Enter slug" required>
                @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Application Type <span class="required">*</span></label>
                <select id="application_type" name="application_type" class="form-select @error('application_type') is-invalid @enderror" required>
                    <option value="">Select Application Type</option>
                    <option value="allotment" {{ old('application_type', $workflow->application_type) == 'allotment' ? 'selected' : '' }}>Allotment</option>
                    <option value="agreement" {{ old('application_type', $workflow->application_type) == 'agreement' ? 'selected' : '' }}>Agreement</option>
                    <option value="possession" {{ old('application_type', $workflow->application_type) == 'possession' ? 'selected' : '' }}>Possession</option>
                    <option value="registry" {{ old('application_type', $workflow->application_type) == 'registry' ? 'selected' : '' }}>Registry</option>
                    <option value="mutation" {{ old('application_type', $workflow->application_type) == 'mutation' ? 'selected' : '' }}>Mutation</option>
                    <option value="transfer" {{ old('application_type', $workflow->application_type) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="noc" {{ old('application_type', $workflow->application_type) == 'noc' ? 'selected' : '' }}>NOC</option>
                    <option value="lease_renewal" {{ old('application_type', $workflow->application_type) == 'lease_renewal' ? 'selected' : '' }}>Lease Renewal</option>
                    <option value="duplicate_certificate" {{ old('application_type', $workflow->application_type) == 'duplicate_certificate' ? 'selected' : '' }}>Duplicate Certificate</option>
                    <option value="cancellation" {{ old('application_type', $workflow->application_type) == 'cancellation' ? 'selected' : '' }}>Cancellation</option>
                    <option value="name_correction" {{ old('application_type', $workflow->application_type) == 'name_correction' ? 'selected' : '' }}>Name Correction</option>
                </select>
                @error('application_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group full-width">
                <label>Description</label>
                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="2" placeholder="Enter workflow description">{{ old('description', $workflow->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group full-width">
                <label>Status <span class="required">*</span></label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $workflow->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', $workflow->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('is_active')
                    <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 mt-4" style="display: flex; justify-content: space-between; margin-bottom: 15px; margin-top: 30px;">
        <h5 class="section-title" style="margin:0; border:none; padding:0;">Workflow Steps</h5>
        <button type="button" class="btn-submit" id="addStepBtn" style="padding: 8px 15px;">
            <i class="fa-solid fa-plus"></i> Add Step
        </button>
    </div>

    <div id="stepsContainer">
        <!-- Steps will be dynamically injected here -->
    </div>
</div>

<div class="form-footer mt-4" style="margin-top: 30px;">
    <a href="{{ route('admin.workflows.index') }}" class="btn-reset" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
    <button type="submit" class="btn-submit">{{ $submitLabel }}</button>
</div>

<style>
    /* Styling for the step checkboxes grid */
    .step-checkboxes {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px dashed #e2e8f0;
    }
    .step-checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .step-checkbox-item input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--primary-color, #0f172a);
    }
    .step-checkbox-item label {
        margin: 0 !important;
        font-weight: 500 !important;
        cursor: pointer;
        text-transform: none !important;
        color: #475569 !important;
        font-size: 0.85rem !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roles = @json($roles);
    let stepIndex = 0;
    const stepsContainer = document.getElementById('stepsContainer');
    
    const existingSteps = @json(old('steps', $workflow->steps ?? []));
    
    function addStep(stepData = null) {
        const i = stepIndex++;
        const id = stepData?.id || '';
        const stepOrder = stepData?.step_order || (i + 1);
        const stepName = stepData?.step_name || '';
        const stepCode = stepData?.step_code || '';
        const roleId = stepData?.role_id || '';
        const actionType = stepData?.action_type || 'view';
        
        const canForward = stepData?.can_forward == 1 ? 'checked' : '';
        const canReject = stepData?.can_reject == 1 ? 'checked' : '';
        const canSendBack = stepData?.can_send_back == 1 ? 'checked' : '';
        const canUploadDocument = stepData?.can_upload_document == 1 ? 'checked' : '';
        const canAddNote = stepData?.can_add_note == 1 ? 'checked' : '';
        const requiresSignature = stepData?.requires_signature == 1 ? 'checked' : '';
        const isStartingStep = stepData?.is_starting_step == 1 ? 'checked' : '';
        const isFinalStep = stepData?.is_final_step == 1 ? 'checked' : '';
        
        let roleOptions = '<option value="">Select Role</option>';
        roles.forEach(role => {
            const selected = role.id == roleId ? 'selected' : '';
            roleOptions += `<option value="${role.id}" ${selected}>${role.name}</option>`;
        });

        const actionTypes = ['view', 'verify', 'approve', 'generate_document', 'payment'];
        let actionOptions = '<option value="">Select Action Type</option>';
        actionTypes.forEach(action => {
            const selected = action == actionType ? 'selected' : '';
            actionOptions += `<option value="${action}" ${selected}>${action}</option>`;
        });

        const html = `
            <div class="form-section step-card" id="step-row-${i}" style="margin-bottom: 20px; position: relative;">
                <button type="button" class="btn-remove-step" style="position: absolute; right: 20px; top: 15px; background: none; border: none; color: #ef4444; font-size: 16px; cursor: pointer;" onclick="document.getElementById('step-row-${i}').remove();" title="Remove Step">
                    <i class="fa-solid fa-trash"></i> Remove
                </button>
                <h5 class="section-title" style="margin-bottom: 20px;">Step Detail</h5>
                <input type="hidden" name="steps[${i}][id]" value="${id}">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Order <span class="required">*</span></label>
                        <input type="number" name="steps[${i}][step_order]" class="form-control" value="${stepOrder}" placeholder="e.g. 1" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Step Name <span class="required">*</span></label>
                        <input type="text" name="steps[${i}][step_name]" class="form-control" value="${stepName}" placeholder="Enter step name" required>
                    </div>

                    <div class="form-group">
                        <label>Step Code <span class="required">*</span></label>
                        <input type="text" name="steps[${i}][step_code]" class="form-control" value="${stepCode}" placeholder="Enter step code" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Role <span class="required">*</span></label>
                        <select name="steps[${i}][role_id]" class="form-select" required>
                            ${roleOptions}
                        </select>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Action Type <span class="required">*</span></label>
                        <select name="steps[${i}][action_type]" class="form-select" required>
                            ${actionOptions}
                        </select>
                    </div>
                </div>
                
                <div class="step-checkboxes">
                    <div class="step-checkbox-item">
                        <input type="checkbox" id="is_starting_${i}" name="steps[${i}][is_starting_step]" value="1" ${isStartingStep}>
                        <label for="is_starting_${i}">Starting Step</label>
                    </div>
                    <div class="step-checkbox-item">
                        <input type="checkbox" id="is_final_${i}" name="steps[${i}][is_final_step]" value="1" ${isFinalStep}>
                        <label for="is_final_${i}">Final Step</label>
                    </div>
                    <div class="step-checkbox-item">
                        <input type="checkbox" id="can_forward_${i}" name="steps[${i}][can_forward]" value="1" ${canForward}>
                        <label for="can_forward_${i}">Can Forward</label>
                    </div>
                    <div class="step-checkbox-item">
                        <input type="checkbox" id="can_reject_${i}" name="steps[${i}][can_reject]" value="1" ${canReject}>
                        <label for="can_reject_${i}">Can Reject</label>
                    </div>
                    <div class="step-checkbox-item">
                        <input type="checkbox" id="can_send_back_${i}" name="steps[${i}][can_send_back]" value="1" ${canSendBack}>
                        <label for="can_send_back_${i}">Can Send Back</label>
                    </div>
                    <div class="step-checkbox-item">
                        <input type="checkbox" id="can_upload_${i}" name="steps[${i}][can_upload_document]" value="1" ${canUploadDocument}>
                        <label for="can_upload_${i}">Upload Docs</label>
                    </div>
                    <div class="step-checkbox-item">
                        <input type="checkbox" id="can_add_note_${i}" name="steps[${i}][can_add_note]" value="1" ${canAddNote}>
                        <label for="can_add_note_${i}">Add Note</label>
                    </div>
                    <div class="step-checkbox-item">
                        <input type="checkbox" id="req_sign_${i}" name="steps[${i}][requires_signature]" value="1" ${requiresSignature}>
                        <label for="req_sign_${i}">Requires Signature</label>
                    </div>
                </div>
            </div>
        `;
        
        stepsContainer.insertAdjacentHTML('beforeend', html);
    }
    
    document.getElementById('addStepBtn').addEventListener('click', () => addStep());
    
    if (existingSteps && existingSteps.length > 0) {
        existingSteps.forEach(step => addStep(step));
    } else {
        addStep(); // Add one empty step by default
    }
    
    // Auto-generate slug from workflow name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            // Convert to lowercase, replace spaces and special characters with hyphens
            slugInput.value = this.value
                .toLowerCase() // Convert to lowercase
                .replace(/[^a-z0-9\s-]/g, '') // Remove invalid characters
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-'); // Remove consecutive hyphens
        });
    }
});
</script>
