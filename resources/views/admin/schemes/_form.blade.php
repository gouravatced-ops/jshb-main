@csrf

<div class="form-container">
    <div class="form-section">
        <h5 class="section-title">Location & Property Details</h5>
        <div class="form-grid">
            <div class="form-group">
                <label>Division <span class="required">*</span></label>
                <select name="division_id" id="division_id" class="form-select" required>
                    <option value="">Select Division</option>
                    @foreach($divisions as $division)
                    <option value="{{ $division->id }}" {{ old('division_id', $scheme->division_id) == $division->id ? 'selected' : '' }}>
                        {{ $division->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Sub Division</label>
                <select name="sub_division_id" id="sub_division_id" class="form-select">
                    <option value="">Select Sub Division</option>
                    @foreach($subDivisions as $subDivision)
                    <option value="{{ $subDivision->id }}" {{ old('sub_division_id', $scheme->sub_division_id) == $subDivision->id ? 'selected' : '' }}>
                        {{ $subDivision->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Property Category <span class="required">*</span></label>
                <select name="pcategory_id" id="property_category" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($propertyCategories as $category)
                    <option value="{{ $category->id }}" {{ old('pcategory_id', $scheme->pcategory_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Property Type <span class="required">*</span></label>
                <select name="p_type_id" id="property_type" class="form-select" required>
                    <option value="">Select Type</option>
                    @foreach($propertyTypes as $type)
                    <option value="{{ $type->id }}" {{ old('p_type_id', $scheme->p_type_id) == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Property Sub Type</label>
                <select name="p_sub_type_id" id="property_sub_type" class="form-select">
                    <option value="">Select Sub Type</option>
                    @foreach($propertySubTypes as $subType)
                    <option value="{{ $subType->id }}" {{ old('p_sub_type_id', $scheme->p_sub_type_id) == $subType->id ? 'selected' : '' }}>
                        {{ $subType->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Quarter Type</label>
                <select name="quarter_type_id" id="quarter_type" class="form-select">
                    <option value="">Select Quarter Type</option>
                    @foreach($quarterTypes as $quarterType)
                    <option value="{{ $quarterType->quarter_id }}" {{ old('quarter_type_id', $scheme->quarter_type_id) == $quarterType->quarter_id ? 'selected' : '' }}>
                        {{ $quarterType->quarter_code }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h5 class="section-title">Scheme Basic Information</h5>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Scheme Name <span class="required">*</span></label>
                <input type="text" name="scheme_name" class="form-control" value="{{ old('scheme_name', $scheme->scheme_name) }}" placeholder="Enter scheme name" required>
            </div>

            <div class="form-group full-width">
                <label>Scheme Name (Hindi)</label>
                <input type="text" name="scheme_name_hindi" class="form-control" value="{{ old('scheme_name_hindi', $scheme->scheme_name_hindi) }}" placeholder="योजना का नाम हिंदी में">
            </div>

            <div class="form-group">
                <label>Scheme Code <span class="required">*</span></label>
                <input type="text" name="scheme_code" class="form-control" value="{{ old('scheme_code', $scheme->scheme_code) }}" placeholder="e.g., SCH-001" required>
            </div>

            <div class="form-group">
                <label>Total Units <span class="required">*</span></label>
                <input type="number" name="total_units" class="form-control" value="{{ old('total_units', $scheme->total_units) }}" placeholder="Enter total units" min="1" required>
            </div>
        </div>
    </div>

    <!-- Financial Details Section -->
    <div class="form-section">
        <h5 class="section-title">Properties Financial Details</h5>
        <div class="row g-3">
            <!-- Step 1: Initial Deposit -->
            <div class="col-12 mt-4">
                <div class="d-flex align-items-center p-3 rounded shadow-sm" style="background: #f6def7; border-left: 5px solid #e100ff;">
                    <h6 class="mb-0 fw-semibold" style="color:#e100ff;">
                        <i class="bx bx-wallet me-2"></i>
                        Step 1 : Initial Deposit
                    </h6>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Application Form Fee (₹)</label>
                <div class="row g-2">
                    @foreach ($quarterTypes as $qt)
                    @php
                    $existing = $scheme->quarterFees->firstWhere('quarter_type_id', $qt->quarter_id);
                    @endphp

                    <div class="col-md-3">
                        <input type="hidden"
                            name="quarter_fees[{{ $qt->quarter_id }}][quarter_type_id]"
                            value="{{ $qt->quarter_id }}">

                        <input type="number"
                            class="form-control"
                            name="quarter_fees[{{ $qt->quarter_id }}][application_fee]"
                            value="{{ old('quarter_fees.' . $qt->quarter_id . '.application_fee', $existing->application_fee ?? '') }}">
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- EMD -->
            <div class="col-12">
                <label class="form-label fw-semibold">EMD (Earnest Money Deposit) (₹)</label>
                <div class="row g-2">
                    @foreach ($quarterTypes as $qt)
                    @php
                    $existing = $scheme->quarterFees->firstWhere('quarter_type_id', $qt->quarter_id);
                    @endphp
                    <div class="col-md-3">
                        <input type="hidden"
                            name="quarter_fees[{{ $qt->quarter_id }}][quarter_type_id]"
                            value="{{ $qt->quarter_id }}">

                        <input type="number"
                            class="form-control"
                            name="quarter_fees[{{ $qt->quarter_id }}][emd_amount]"
                            placeholder="{{ $qt->quarter_code }} - {{ strtoupper($qt->quarter_name) }}"
                            value="{{ old('quarter_fees.' . $qt->quarter_id . '.emd_amount', $existing->emd_amount ?? '') }}">
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Step 2: At the Time of Allotment -->
            <div class="col-12 mt-4">
                <div class="d-flex align-items-center p-3 rounded shadow-sm" style="background: #e8f0f7; border-left: 5px solid #0dcaf0;">
                    <h6 class="mb-0 fw-semibold text-info">
                        <i class="bx bx-building-house me-2"></i>
                        Step 2 : At the Time of Allotment
                    </h6>
                </div>
            </div>

            <!-- Property Total Cost -->
            <div class="col-md-4">
                <label class="form-label">Property Total Cost (₹) <span class="required">*</span></label>
                <input type="number" id="total_cost" name="property_total_cost" placeholder="Enter Property Total Cost"
                    class="form-control" value="{{ old('property_total_cost', $scheme->financial->property_total_cost ?? '') }}" required>
            </div>

            <!-- Down Payment % -->
            <div class="col-md-4">
                <label class="form-label">Down Payment (%) <span class="required">*</span></label>
                <input type="number" id="down_percent" name="down_payment_percentage" placeholder="Enter Down Payment Percentage"
                    class="form-control" value="{{ old('down_payment_percentage', $scheme->financial->down_payment_percentage ?? '') }}" required>
            </div>

            <!-- Down Payment Amount -->
            <div class="col-md-4">
                <label class="form-label">Down Payment Amount (₹) <span class="required">*</span></label>
                <input type="number" id="down_amount" name="down_payment_amount" placeholder="Enter Down Payment Amount"
                    class="form-control" value="{{ old('down_payment_amount', $scheme->financial->down_payment_amount ?? '') }}" required>
            </div>

            <!-- Step 3: At Agreement -->
            <div class="col-12 mt-4">
                <div class="p-3 rounded shadow-sm" style="background: #e8f7ee; border-left: 5px solid #28a745;">
                    <h6 class="mb-0 fw-semibold text-success">
                        <i class="bx bx-file me-2"></i>
                        Step 3 : At the Time of Agreement
                    </h6>
                </div>
            </div>

            <!-- Balance Amount -->
            <div class="col-md-4">
                <label class="form-label">Balance Amount (₹) <span class="required">*</span></label>
                <input type="number" id="balance_amount" name="balance_amount" placeholder="Balance Amount"
                    class="form-control" value="{{ old('balance_amount', $scheme->financial->balance_amount ?? '') }}" required>
            </div>

            <!-- EMI Count -->
            <div class="col-md-4">
                <label class="form-label">No. of EMIs <span class="required">*</span></label>
                <input type="number" id="emi_count" name="emi_count" placeholder="Enter EMI Counts"
                    class="form-control" value="{{ old('emi_count', $scheme->financial->emi_count ?? '') }}" required>
            </div>

            <!-- Admin Charges -->
            <div class="col-md-4">
                <label>Admin Charges (₹)</label>
                <input type="number" name="admin_charges" placeholder="Admin Charges" class="form-control"
                    value="{{ old('admin_charges', $scheme->financial->admin_charges ?? '') }}">
            </div>

            <!-- EMI Calculation Section -->
            <div class="col-12 mt-4">
                <div class="rounded" style="background:#f8f9fa;">
                    <h6 class="mb-3 fw-semibold">EMI Calculation Details</h6>
                    <div class="row g-4">
                        <!-- WITHOUT PENALTY -->
                        <div class="col-md-6">
                            <div class="p-3" style="background:#eef4ff; border-left:4px solid #0d6efd;">
                                <h6 class="fw-bold text-primary mb-3">Without Penalty</h6>
                                <div class="mb-3">
                                    <label class="form-label">Interest Rate (%) <span class="required">*</span></label>
                                    <input type="number" id="normal_interest" name="normal_interest_rate" value="{{ old('normal_interest_rate', $scheme->financial->normal_interest_rate ?? 13.5) }}" class="form-control" required>
                                </div>
                                <div>
                                    <label class="form-label">Monthly EMI (₹) <small class="text-danger">/ Month</small></label>
                                    <input type="number" id="emi_normal" name="emi_without_penalty" class="form-control"
                                        value="{{ old('emi_without_penalty', $scheme->financial->emi_without_penalty ?? '') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- WITH PENALTY -->
                        <div class="col-md-6">
                            <div class="p-3" style="background:#fff1f1; border-left:4px solid #dc3545;">
                                <h6 class="fw-bold text-danger mb-3">With Penalty</h6>
                                <div class="mb-3">
                                    <label class="form-label">Penalty Rate (%) <span class="required">*</span></label>
                                    <input type="number" id="penalty_rate" name="penalty_interest_rate" value="{{ old('penalty_interest_rate', $scheme->financial->penalty_interest_rate ?? 2.5) }}" class="form-control" required>
                                </div>
                                <div>
                                    <label class="form-label">Monthly EMI (₹) <small class="text-danger">/ Month</small> &nbsp; (Interest Rate + Penalty Rate) of balance Amount</label>
                                    <input type="number" id="emi_penalty" name="emi_with_penalty" class="form-control"
                                        value="{{ old('emi_with_penalty', $scheme->financial->emi_with_penalty ?? '') }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h5 class="section-title">Lease Details</h5>
        <div class="row g-3">

            <div class="col-md-4">
                <label>Lease Period <span class="required">*</span></label>
                <select name="lease_period" class="form-select" required>
                    <option value="90" {{ old('lease_period', $scheme->lease_period) == 90 ? 'selected' : '' }}>90 Years</option>
                    <option value="99" {{ old('lease_period', $scheme->lease_period) == 99 ? 'selected' : '' }}>99 Years</option>
                </select>
            </div>

            @php
            $currentYear = date('Y');
            @endphp

            <div class="col-md-4">
                <label for="initiation_year" class="form-label">Year of Initiation <span class="required">*</span></label>
                <select name="initiation_year" id="initiation_year" class="form-select" required>
                    <option value="">-- Select Initiation Year --</option>
                    @for ($year = 1960; $year <= $currentYear; $year++)
                        <option value="{{ $year }}" {{ old('initiation_year', $scheme->initiation_year) == $year ? 'selected' : '' }}>
                        {{ $year }}
                        </option>
                        @endfor
                </select>
            </div>

            <div class="col-md-4">
                <label>Scheme Start Date <span class="required">*</span></label>
                <input type="date" name="scheme_start_date" class="form-control" value="{{ old('scheme_start_date', optional($scheme->scheme_start_date)->format('Y-m-d')) }}" required>
            </div>

            <div class="col-md-4">
                <label>Scheme End Date</label>
                <input type="date" name="scheme_end_date" class="form-control" value="{{ old('scheme_end_date', optional($scheme->scheme_end_date)->format('Y-m-d')) }}">
            </div>

            <div class="col-md-4">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="1" {{ old('status', $scheme->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $scheme->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.schemes.index') }}" class="btn-reset" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
    <button type="submit" class="btn-submit">{{ $submitLabel }}</button>
</div>

<script>
    // EMI Calculation Functions
    const total_cost = document.getElementById('total_cost');
    const down_percent = document.getElementById('down_percent');
    const down_amount = document.getElementById('down_amount');
    const balance_amount = document.getElementById('balance_amount');
    const emi_count = document.getElementById('emi_count');
    const normal_interest = document.getElementById('normal_interest');
    const penalty_rate = document.getElementById('penalty_rate');
    const emi_normal = document.getElementById('emi_normal');
    const emi_penalty = document.getElementById('emi_penalty');

    function calculateAll(changedField = null) {
        let total = parseFloat(total_cost?.value) || 0;
        let percent = parseFloat(down_percent?.value) || 0;
        let amount = parseFloat(down_amount?.value) || 0;

        // Down Payment Logic
        if (changedField === "percent") {
            down_amount.value = Math.ceil((total * percent) / 100);
        }

        if (changedField === "amount") {
            if (total > 0) {
                down_percent.value = Math.ceil((amount / total) * 100);
            } else {
                down_percent.value = 0;
            }
        }

        let finalDown = parseFloat(down_amount?.value) || 0;

        // Balance Amount
        if (balance_amount) {
            balance_amount.value = Math.ceil(total - finalDown);
        }

        // EMI Logic
        let P = parseFloat(balance_amount?.value) || 0;
        let N = parseFloat(emi_count?.value) || 1;
        let R = parseFloat(normal_interest?.value) || 0;
        let penalty = parseFloat(penalty_rate?.value) || 0;

        // Normal EMI
        let monthlyRate = R / 12 / 100;
        if (monthlyRate > 0 && N > 0) {
            let emi = (P * monthlyRate * Math.pow(1 + monthlyRate, N)) / (Math.pow(1 + monthlyRate, N) - 1);
            if (emi_normal) emi_normal.value = Math.ceil(emi);
        } else {
            if (emi_normal) emi_normal.value = N > 0 ? Math.ceil(P / N) : 0;
        }

        // Penalty EMI
        let penaltyRate = (R + penalty) / 12 / 100;
        if (penaltyRate > 0 && N > 0) {
            let emiPen = (P * penaltyRate * Math.pow(1 + penaltyRate, N)) / (Math.pow(1 + penaltyRate, N) - 1);
            if (emi_penalty) emi_penalty.value = Math.ceil(emiPen);
        } else {
            if (emi_penalty) emi_penalty.value = N > 0 ? Math.ceil(P / N) : 0;
        }
    }

    // Add event listeners if elements exist
    if (total_cost) total_cost.addEventListener('input', () => calculateAll());
    if (down_percent) down_percent.addEventListener('input', () => calculateAll('percent'));
    if (down_amount) down_amount.addEventListener('input', () => calculateAll('amount'));
    if (emi_count) emi_count.addEventListener('input', () => calculateAll());
    if (normal_interest) normal_interest.addEventListener('input', () => calculateAll());
    if (penalty_rate) penalty_rate.addEventListener('input', () => calculateAll());

    // Dynamic Dropdown Functions
    document.addEventListener('DOMContentLoaded', function() {
        // Division → Sub Division
        const divisionSelect = document.getElementById('division_id');
        if (divisionSelect) {
            divisionSelect.addEventListener('change', function() {
                const divisionId = this.value;
                const subDivisionSelect = document.getElementById('sub_division_id');
                if (subDivisionSelect && divisionId) {
                    subDivisionSelect.innerHTML = '<option value="">Loading...</option>';
                    fetch(`/get-sub-divisions/${divisionId}`)
                        .then(res => res.json())
                        .then(data => {
                            subDivisionSelect.innerHTML = '<option value="">Select Sub Division</option>';
                            data.forEach(item => {
                                subDivisionSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                            });
                        })
                        .catch(() => {
                            subDivisionSelect.innerHTML = '<option value="">Error loading data</option>';
                        });
                }
            });
        }

        // Property Category → Property Type
        const categorySelect = document.getElementById('property_category');
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                const category = this.value;
                const typeSelect = document.getElementById('property_type');
                if (typeSelect && category) {
                    typeSelect.innerHTML = '<option value="">Loading...</option>';
                    fetch(`/get-property-types/${category}`)
                        .then(response => response.json())
                        .then(data => {
                            let options = '<option value="">Select Property Type</option>';
                            data.forEach(item => {
                                options += `<option value="${item.id}">${item.name}</option>`;
                            });
                            typeSelect.innerHTML = options;
                        })
                        .catch(() => {
                            typeSelect.innerHTML = '<option value="">Error loading data</option>';
                        });
                }
            });
        }

        // Property Type → Property Sub Type
        const typeSelect = document.getElementById('property_type');
        if (typeSelect) {
            typeSelect.addEventListener('change', function() {
                const typeId = this.value;
                const subTypeSelect = document.getElementById('property_sub_type');
                if (subTypeSelect && typeId) {
                    subTypeSelect.innerHTML = '<option value="">Loading...</option>';
                    fetch(`/get-property-sub-types/${typeId}`)
                        .then(response => response.json())
                        .then(data => {
                            let options = '<option value="">Select Sub Property Type</option>';
                            data.forEach(item => {
                                options += `<option value="${item.id}">${item.name}</option>`;
                            });
                            subTypeSelect.innerHTML = options;
                        })
                        .catch(() => {
                            subTypeSelect.innerHTML = '<option value="">Error loading data</option>';
                        });
                }
            });
        }

        // Quarter Type Filter based on Property Type
        const propertyType = document.getElementById('property_type');
        const quarterSelect = document.getElementById('quarter_type');

        function filterQuarterOptions() {
            if (propertyType && quarterSelect) {
                const text = propertyType.options[propertyType.selectedIndex]?.text.toLowerCase();
                Array.from(quarterSelect.options).forEach(option => {
                    const optionText = option.text.toLowerCase();
                    if (text && text.includes('plot')) {
                        if (optionText.includes('mig') || optionText.includes('hig')) {
                            option.hidden = false;
                        } else {
                            option.hidden = true;
                        }
                    } else {
                        option.hidden = false;
                    }
                });
                if (quarterSelect.selectedOptions[0]?.hidden) {
                    quarterSelect.value = '';
                }
            }
        }

        if (propertyType) {
            propertyType.addEventListener('change', filterQuarterOptions);
            filterQuarterOptions();
        }

        // Date validation
        const startDateInput = document.querySelector('input[name="scheme_start_date"]');
        const endDateInput = document.querySelector('input[name="scheme_end_date"]');

        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', function() {
                if (endDateInput.value && new Date(endDateInput.value) < new Date(this.value)) {
                    alert('End date cannot be before start date');
                    endDateInput.value = '';
                }
                endDateInput.min = this.value;
            });
        }

        // Form submission validation
        const form = document.getElementById('schemeForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to save this scheme?')) {
                    e.preventDefault();
                }
            });
        }

        // Initial calculation on page load
        calculateAll();
    });
</script>