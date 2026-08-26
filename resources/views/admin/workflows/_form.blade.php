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

    <!-- Required Documents Section -->
    <div class="form-section mt-4" style="margin-top: 30px;">
        <h5 class="section-title">Required Documents</h5>
        <div class="form-group full-width">
            <p class="text-muted" style="font-size: 13px;">Select the documents that engineers can request from allottees for this workflow.</p>
            <div class="checkbox-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 10px;">
                @php
                    $selectedDocs = old('required_documents', isset($workflow) && $workflow->exists ? $workflow->requiredDocuments->pluck('id')->toArray() : []);
                @endphp
                @foreach($documents as $doc)
                    <label class="custom-checkbox" style="display: flex; align-items: center; background: #f8fafc; padding: 10px; border-radius: 4px; border: 1px solid #e2e8f0; cursor: pointer;">
                        <input type="checkbox" name="required_documents[]" value="{{ $doc->id }}" 
                            {{ in_array($doc->id, $selectedDocs) ? 'checked' : '' }}
                            style="margin-right: 10px; width: 16px; height: 16px;">
                        <span>{{ $doc->document_name }}</span>
                    </label>
                @endforeach
            </div>
            @error('required_documents')
                <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
            @enderror
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



<script>
    window.WorkflowData = {
        roles: @json($roles),
        existingSteps: @json(old('steps', $workflow->steps ?? []))
    };
</script>
