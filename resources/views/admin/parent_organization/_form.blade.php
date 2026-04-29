@csrf

<div class="form-container">
    <div class="form-section">
        <h5 class="section-title">Parent Organization Details</h5>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Parent Organization Name <span class="required">*</span></label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name', $parentOrganization->name) }}"
                    placeholder="Enter parent organization name"
                    required>
            </div>

            <div class="form-group">
                <label>Display Code <span class="required">*</span></label>
                <input type="text" name="display_code" class="form-control" value="{{ old('display_code', $parentOrganization->display_code) }}" placeholder="Enter display code" required>
            </div>

            <div class="form-group">
                <label>District</label>
                <select name="district" class="form-control">
                    <option value="">Select district</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}" {{ old('district', $parentOrganization->district) == $district->id ? 'selected' : '' }}>
                            {{ $district->name_en }} / {{ $district->name_hi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>PIN Code</label>
                <input type="text" name="pin_code" class="form-control" value="{{ old('pin_code', $parentOrganization->pin_code) }}" placeholder="Enter PIN code">
            </div>

            <div class="form-group">
                <label>Locality</label>
                <input type="text" name="locality" class="form-control" value="{{ old('locality', $parentOrganization->locality) }}" placeholder="Enter locality">
            </div>

            <div class="form-group">
                <label>Police Station</label>
                <input type="text" name="police_station" class="form-control" value="{{ old('police_station', $parentOrganization->police_station) }}" placeholder="Enter police station">
            </div>

            <div class="form-group">
                <label>Post Office</label>
                <input type="text" name="post_office" class="form-control" value="{{ old('post_office', $parentOrganization->post_office) }}" placeholder="Enter post office">
            </div>

            <div class="form-group full-width">
                <label>Address</label>
                <textarea
                    name="address"
                    class="form-control"
                    rows="3"
                    placeholder="Enter address">{{ old('address', $parentOrganization->address) }}</textarea>
            </div>

            <div class="form-group full-width">
                <label>Status</label>
                <div class="status-switch-card">
                    <div>
                        <div class="status-switch-title">Active / Inactive</div>
                        <div class="status-switch-subtitle">Enable this parent organization for use in the system.</div>
                    </div>
                    <label class="status-switch">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" {{ old('status', (int) $parentOrganization->status) ? 'checked' : '' }}>
                        <span class="status-slider"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.parent-organizations.index') }}" class="btn-reset" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
    <button type="submit" class="btn-submit">{{ $submitLabel }}</button>
</div>