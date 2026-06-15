@csrf

<div class="form-container">
    <div class="form-section">
        <h5 class="section-title">Property Sub Type Details</h5>
        <div class="form-grid">
            <div class="form-group">
                <label>Property Type <span class="required">*</span></label>
                <select name="ptype_id" class="form-select" required>
                    <option value="">Select Property Type</option>
                    @foreach ($propertyTypes as $type)
                        <option value="{{ $type->id }}"
                            {{ (string) old('ptype_id', $propertySubType->ptype_id) === (string) $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="1"
                        {{ (string) old('status', (int) $propertySubType->status) === '1' ? 'selected' : '' }}>Active
                    </option>
                    <option value="0"
                        {{ (string) old('status', (int) $propertySubType->status) === '0' ? 'selected' : '' }}>Inactive
                    </option>
                </select>
            </div>

            <div class="form-group full-width">
                <label>Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control"
                    value="{{ old('name', $propertySubType->name) }}" placeholder="Enter property sub type name"
                    required>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.property-sub-types.index') }}" class="btn-reset"
        style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
    <button type="submit" class="btn-submit">{{ $submitLabel }}</button>
</div>
