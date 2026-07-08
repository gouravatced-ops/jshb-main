@csrf

<div class="form-container">
    <div class="form-section">
        <h5 class="section-title">Property Type Details</h5>
        <div class="form-grid">
            <div class="form-group">
                <label>Category <span class="required">*</span></label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach ($propertyCategories as $category)
                        <option value="{{ $category->id }}"
                            {{ (string) old('category_id', $propertyType->category_id) === (string) $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="1"
                        {{ (string) old('status', (int) $propertyType->status) === '1' ? 'selected' : '' }}>Active
                    </option>
                    <option value="0"
                        {{ (string) old('status', (int) $propertyType->status) === '0' ? 'selected' : '' }}>Inactive
                    </option>
                </select>
            </div>

            <div class="form-group full-width">
                <label>Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $propertyType->name) }}"
                    placeholder="Enter property type name" required>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.property-types.index') }}" class="btn-reset"
        style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
    <button type="submit" class="btn-submit">{{ $submitLabel }}</button>
</div>
