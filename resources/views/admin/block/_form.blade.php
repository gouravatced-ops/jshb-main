@csrf

<div class="form-container">
    <div class="form-section">
        <h5 class="section-title">Block Details</h5>
        <div class="form-grid">
            <div class="form-group">
                <label>Jharkhand District <span class="required">*</span></label>
                <select name="district_id" class="form-control" required>
                    <option value="">Select District</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}" {{ (string) old('district_id', $block->district_id) === (string) $district->id ? 'selected' : '' }}>
                            {{ $district->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <div class="status-switch-card">
                    <div>
                        <div class="status-switch-title">Active / Inactive</div>
                        <div class="status-switch-subtitle">Enable this block for Jharkhand district mapping.</div>
                    </div>
                    <label class="status-switch">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" {{ old('status', (int) $block->status) ? 'checked' : '' }}>
                        <span class="status-slider"></span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Block Name English <span class="required">*</span></label>
                <input type="text" name="block_name_eng" class="form-control" value="{{ old('block_name_eng', $block->block_name_eng) }}" placeholder="Enter block name in English" required>
            </div>

            <div class="form-group">
                <label>Block Name Hindi</label>
                <input type="text" name="block_name_hn" class="form-control" value="{{ old('block_name_hn', $block->block_name_hn) }}" placeholder="Enter block name in Hindi">
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.blocks.index') }}" class="btn-reset" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
    <button type="submit" class="btn-submit">{{ $submitLabel }}</button>
</div>
