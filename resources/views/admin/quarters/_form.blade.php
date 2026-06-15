@csrf

<div class="form-container">
    <div class="form-section">
        <h5 class="section-title">Quarter Type Details</h5>
        <div class="form-grid">
            <div class="form-group">
                <label>Quarter Code <span class="required">*</span></label>
                <input type="text" name="quarter_code" class="form-control"
                    value="{{ old('quarter_code', $quarterType->quarter_code) }}"
                    placeholder="e.g. HIG, LIG, MIG, EWS" maxlength="10" required>
            </div>

            <div class="form-group">
                <label>Quarter Name <span class="required">*</span></label>
                <input type="text" name="quarter_name" class="form-control"
                    value="{{ old('quarter_name', $quarterType->quarter_name) }}"
                    placeholder="Enter quarter name" maxlength="100" required>
            </div>

            <div class="form-group full-width">
                <label>Full Name</label>
                <input type="text" name="quarter_full_name" class="form-control"
                    value="{{ old('quarter_full_name', $quarterType->quarter_full_name) }}"
                    placeholder="Enter full quarter name" maxlength="200">
            </div>

            <div class="form-group">
                <label>Min Income (Lakhs)</label>
                <input type="number" name="min_income" class="form-control" step="0.01" min="0"
                    value="{{ old('min_income', $quarterType->min_income) }}"
                    placeholder="Minimum annual income">
            </div>

            <div class="form-group">
                <label>Max Income (Lakhs)</label>
                <input type="number" name="max_income" class="form-control" step="0.01" min="0"
                    value="{{ old('max_income', $quarterType->max_income) }}"
                    placeholder="Maximum annual income">
            </div>

            <div class="form-group">
                <label>Display Order</label>
                <input type="number" name="display_order" class="form-control" min="0"
                    value="{{ old('display_order', $quarterType->display_order ?? 0) }}"
                    placeholder="Display order">
            </div>

            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="1"
                        {{ (string) old('status', (int) $quarterType->status) === '1' ? 'selected' : '' }}>Active
                    </option>
                    <option value="0"
                        {{ (string) old('status', (int) $quarterType->status) === '0' ? 'selected' : '' }}>Inactive
                    </option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.quarter-types.index') }}" class="btn-reset"
        style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
    <button type="submit" class="btn-submit">{{ $submitLabel }}</button>
</div>
