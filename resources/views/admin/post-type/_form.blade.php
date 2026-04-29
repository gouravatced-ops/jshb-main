@csrf

<div class="form-container">
    <div class="form-section">
        <h5 class="section-title">Post Type Details</h5>
        <div class="form-grid">
            <div class="form-group">
                <label>Engineer Post Level <span class="required">*</span></label>
                <select name="level" class="form-control" required>
                    <option value="">Select Level</option>
                    @php
                        $levels = [
                            'Top-Level (Executive / Head)',
                            'Senior Management',
                            'Middle Management',
                            'Junior Management',
                            'Entry-Level / Field Roles',
                            'Specialized Roles (Non-IT but domain-specific)',
                        ];
                    @endphp
                    @foreach($levels as $level)
                        <option value="{{ $level }}" {{ old('level', $postType->level) === $level ? 'selected' : '' }}>{{ $level }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <div class="status-switch-card">
                    <div>
                        <div class="status-switch-title">Active / Inactive</div>
                        <div class="status-switch-subtitle">Enable this post type for engineer post selection.</div>
                    </div>
                    <label class="status-switch">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" {{ old('status', (int) $postType->status) ? 'checked' : '' }}>
                        <span class="status-slider"></span>
                    </label>
                </div>
            </div>

            <div class="form-group full-width">
                <label>Post Type Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $postType->name) }}" placeholder="Enter post type name" required>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.post-types.index') }}" class="btn-reset" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
    <button type="submit" class="btn-submit">{{ $submitLabel }}</button>
</div>
