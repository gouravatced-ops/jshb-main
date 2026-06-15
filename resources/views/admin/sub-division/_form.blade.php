@csrf

<div class="form-container">
    <div class="form-section">
        <h5 class="section-title">Sub Division Details</h5>
        <div class="form-grid">
            <div class="form-group">
                <label>Division <span class="required">*</span></label>
                <select name="division_id" class="form-select" required>
                    <option value="">Select Division</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}" {{ (string) old('division_id', $subDivision->division_id) === (string) $division->id ? 'selected' : '' }}>
                            {{ $division->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="1" {{ (string) old('status', (int) $subDivision->status) === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ (string) old('status', (int) $subDivision->status) === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="form-group full-width">
                <label>Sub Division Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $subDivision->name) }}" placeholder="Enter sub division name" required>
            </div>

            <div class="form-group">
                <label>Sub Division Code</label>
                <input type="text" name="subdivision_code" class="form-control" value="{{ old('subdivision_code', $subDivision->subdivision_code) }}" placeholder="Enter sub division code">
            </div>

            <div class="form-group">
                <label>Colony Name</label>
                <input type="text" name="colony_name" class="form-control" value="{{ old('colony_name', $subDivision->colony_name) }}" placeholder="Enter colony name">
            </div>

            <div class="form-group full-width">
                <label>Locality Address</label>
                <input type="text" name="locality_address" class="form-control" value="{{ old('locality_address', $subDivision->locality_address) }}" placeholder="Enter locality address">
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.sub-divisions.index') }}" class="btn-reset" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
    <button type="submit" class="btn-submit">{{ $submitLabel }}</button>
</div>
