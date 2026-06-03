@csrf

<div class="form-container">
    <div class="form-section">
        <h5 class="section-title">Department Details</h5>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Department Name <span class="required">*</span></label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name', $department->name) }}"
                    placeholder="Enter department name"
                    required
                >
            </div>

            <div class="form-group">
                <label>Department Code</label>
                <input
                    type="text"
                    name="department_code"
                    class="form-control"
                    value="{{ old('department_code', $department->department_code) }}"
                    placeholder="Enter department code"
                >
            </div>

            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="1" {{ (string) old('status', (int) $department->status) === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ (string) old('status', (int) $department->status) === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.departments.index') }}" class="btn-reset" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
    <button type="submit" class="btn-submit">{{ $submitLabel }}</button>
</div>
