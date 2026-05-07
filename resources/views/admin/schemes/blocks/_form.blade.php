@csrf

<div class="form-container">
    <div class="form-section">
        <h5 class="section-title">Block Basic Information</h5>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Block Name <span class="required">*</span></label>
                <input type="text" name="block_name" class="form-control"
                    value="{{ old('block_name', $block->block_name) }}"
                    placeholder="Enter block name (e.g., Block 1, Tower A, etc.)" required>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>Scheme Property Type</label>
                <input type="text" name="scheme_property_type" class="form-control"
                    value="{{ old('scheme_property_type', $scheme->propertyType->name) }}"
                    placeholder="e.g., Flat, House, Plot">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="1" {{ old('status', $block->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $block->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div class="form-grid4">
            <div class="form-group">
                <label>Area (Sq. Ft.) <span class="required">*</span></label>
                <div class="input-group">
                    <input type="number" name="area_sqft" class="form-control"
                        value="{{ old('area_sqft', $block->area_sqft) }}"
                        placeholder="Enter area" step="0.01" min="1" required>
                    <span class="input-group-text">sq. ft.</span>
                </div>
            </div>
            <div class="form-group">
                <label>Undivided Land Share</label>
                <div class="input-group">
                    <input type="number" name="undivided_land_share" class="form-control"
                        value="{{ old('undivided_land_share', $block->undivided_land_share) }}"
                        placeholder="e.g., 100 sq ft" step="0.01" min="0">
                    <span class="input-group-text">sq. ft.</span>
                </div>
            </div>

            <div class="form-group">
                <label>Total Buildup Area</label>
                <div class="input-group">
                    <input type="number" name="total_buildup" class="form-control"
                        value="{{ old('total_buildup', $block->total_buildup) }}"
                        placeholder="e.g., 1500 sq ft" step="0.01" min="0">
                    <span class="input-group-text">sq. ft.</span>
                </div>
            </div>

            <div class="form-group">
                <label>Total Area of Construction</label>
                <div class="input-group">
                    <input type="number" name="total_area_of_construction" class="form-control"
                        value="{{ old('total_area_of_construction', $block->total_area_of_construction) }}"
                        placeholder="e.g., 1800 sq ft" step="0.01" min="0">
                    <span class="input-group-text">sq. ft.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h5 class="section-title">Dimensions</h5>
        <div class="form-grid4">
            <div class="form-group">
                <label>East Side</label>
                <div class="input-group">
                    <input type="text" name="dimension_east" class="form-control"
                        value="{{ old('dimension_east', $block->dimension_east) }}"
                        placeholder="e.g., 30">
                    <span class="input-group-text">ft</span>
                </div>
            </div>

            <div class="form-group">
                <label>West Side</label>
                <div class="input-group">
                    <input type="text" name="dimension_west" class="form-control"
                        value="{{ old('dimension_west', $block->dimension_west) }}"
                        placeholder="e.g., 30">
                    <span class="input-group-text">ft</span>
                </div>
            </div>

            <div class="form-group">
                <label>North Side</label>
                <div class="input-group">
                    <input type="text" name="dimension_north" class="form-control"
                        value="{{ old('dimension_north', $block->dimension_north) }}"
                        placeholder="e.g., 40">
                    <span class="input-group-text">ft</span>
                </div>
            </div>

            <div class="form-group">
                <label>South Side</label>
                <div class="input-group">
                    <input type="text" name="dimension_south" class="form-control"
                        value="{{ old('dimension_south', $block->dimension_south) }}"
                        placeholder="e.g., 40">
                    <span class="input-group-text">ft</span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h5 class="section-title">Boundary Measurements</h5>
        <div class="form-grid4">
            <div class="form-group">
                <label>East-West (North Side)</label>
                <div class="input-group">
                    <input type="text" name="arm_east_west_north" class="form-control"
                        value="{{ old('arm_east_west_north', $block->arm_east_west_north ?? '') }}"
                        placeholder="e.g., 4">
                    <span class="input-group-text">ft</span>
                </div>
            </div>

            <div class="form-group">
                <label>East-West (South Side)</label>
                <div class="input-group">
                    <input type="text" name="arm_east_west_south" class="form-control"
                        value="{{ old('arm_east_west_south', $block->arm_east_west_south ?? '') }}"
                        placeholder="e.g., 4">
                    <span class="input-group-text">ft</span>
                </div>
            </div>

            <div class="form-group">
                <label>North-South (East Side)</label>
                <div class="input-group">
                    <input type="text" name="arm_north_south_east" class="form-control"
                        value="{{ old('arm_north_south_east', $block->arm_north_south_east ?? '') }}"
                        placeholder="e.g., 4">
                    <span class="input-group-text">ft</span>
                </div>
            </div>

            <div class="form-group">
                <label>North-South (West Side)</label>
                <div class="input-group">
                    <input type="text" name="arm_north_south_west" class="form-control"
                        value="{{ old('arm_north_south_west', $block->arm_north_south_west ?? '') }}"
                        placeholder="e.g., 4">
                    <span class="input-group-text">ft</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Block Information Card (for Edit Mode) -->
    @if(isset($block) && $block->exists)
    <div class="form-section">
        <div class="info-card">
            <h5 class="section-title">Block Information</h5>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Created By:</span>
                    <span class="info-value">{{ $block->creator->name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Created At:</span>
                    <span class="info-value">{{ optional($block->created_at)->format('d M Y, h:i A') ?? 'N/A' }}</span>
                </div>
                @if($block->updated_by)
                <div class="info-item">
                    <span class="info-label">Last Updated By:</span>
                    <span class="info-value">{{ $block->updater->name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Updated:</span>
                    <span class="info-value">{{ optional($block->updated_at)->format('d M Y, h:i A') ?? 'N/A' }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<div class="form-footer">
    <a href="{{ route('admin.schemes.blocks.index', $scheme) }}" class="btn-reset" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">
        <i class="bx bx-x me-1"></i> Cancel
    </a>
    <button type="submit" class="btn-submit">
        <i class="bx bx-save me-1"></i> {{ $submitLabel }}
    </button>
</div>

<style>
    .info-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px 20px;
        margin-top: 8px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 12px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        background: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .info-label {
        font-size: 13px;
        color: #6b7280;
        font-weight: 500;
    }

    .info-value {
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
    }

    .mt-1 {
        margin-top: 4px;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const form = document.getElementById('schemeBlockForm');

        if (form) {
            form.addEventListener('submit', function(e) {
                const blockName = document.querySelector('input[name="block_name"]')?.value.trim();
                const areaSqft = document.querySelector('input[name="area_sqft"]')?.value;

                if (!blockName) {
                    e.preventDefault();
                    alert('Please enter block name');
                    document.querySelector('input[name="block_name"]')?.focus();
                    return false;
                }

                if (!areaSqft || parseFloat(areaSqft) <= 0) {
                    e.preventDefault();
                    alert('Please enter valid area in square feet');
                    document.querySelector('input[name="area_sqft"]')?.focus();
                    return false;
                }

                // Confirm before update (only for edit mode)
                @if(isset($block) && $block->exists)
                if (!confirm('Are you sure you want to update this block?')) {
                    e.preventDefault();
                    return false;
                }
                @endif
            });
        }

        // Auto-format number inputs to prevent negative values
        const numberInputs = document.querySelectorAll('input[type="number"]');
        numberInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.value && parseFloat(this.value) < 0) {
                    this.value = 0;
                }
            });
        });

        // Add visual feedback for required fields
        const requiredFields = document.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.style.borderColor = '#ef4444';
                } else {
                    this.style.borderColor = '#10b981';
                }
            });

            field.addEventListener('focus', function() {
                this.style.borderColor = '#6366f1';
            });
        });
    });
</script>