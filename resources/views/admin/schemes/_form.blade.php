@csrf

<div class="form-container">
    <div class="form-section">
        <h5 class="section-title">Location & Property Details</h5>
        <div class="form-grid">
            <div class="form-group">
                <label>Division <span class="required">*</span></label>
                <select name="division_id" id="division_id" class="form-select" required>
                    <option value="">Select Division</option>
                    @foreach ($divisions as $division)
                        <option value="{{ $division->id }}"
                            {{ old('division_id', $scheme->division_id) == $division->id ? 'selected' : '' }}>
                            {{ $division->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Sub Division</label>
                <select name="sub_division_id" id="sub_division_id" class="form-select">
                    <option value="">Select Sub Division</option>
                    @foreach ($subDivisions as $subDivision)
                        <option value="{{ $subDivision->id }}"
                            {{ old('sub_division_id', $scheme->sub_division_id) == $subDivision->id ? 'selected' : '' }}>
                            {{ $subDivision->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Property Category <span class="required">*</span></label>
                <select name="pcategory_id" id="property_category" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach ($propertyCategories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('pcategory_id', $scheme->pcategory_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Property Type <span class="required">*</span></label>
                <select name="p_type_id" id="property_type" class="form-select" required>
                    <option value="">Select Type</option>
                    @foreach ($propertyTypes as $type)
                        <option value="{{ $type->id }}"
                            {{ old('p_type_id', $scheme->p_type_id) == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Property Sub Type</label>
                <select name="p_sub_type_id" id="property_sub_type" class="form-select">
                    <option value="">Select Sub Type</option>
                    @foreach ($propertySubTypes as $subType)
                        <option value="{{ $subType->id }}"
                            {{ old('p_sub_type_id', $scheme->p_sub_type_id) == $subType->id ? 'selected' : '' }}>
                            {{ $subType->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Quarter Type</label>
                <select name="quarter_type_id" id="quarter_type" class="form-select">
                    <option value="">Select Quarter Type</option>
                    @foreach ($quarterTypes as $quarterType)
                        <option value="{{ $quarterType->quarter_id }}"
                            {{ old('quarter_type_id', $scheme->quarter_type_id) == $quarterType->quarter_id ? 'selected' : '' }}>
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
                <input type="text" name="scheme_name" class="form-control"
                    value="{{ old('scheme_name', $scheme->scheme_name) }}" placeholder="Enter scheme name" required>
            </div>

            <div class="form-group full-width">
                <label>Scheme Name (Hindi)</label>
                <input type="text" name="scheme_name_hindi" class="form-control"
                    value="{{ old('scheme_name_hindi', $scheme->scheme_name_hindi) }}"
                    placeholder="योजना का नाम हिंदी में">
            </div>

            <div class="form-group">
                <label>Scheme Code <span class="required">*</span></label>
                <input type="text" name="scheme_code" class="form-control"
                    value="{{ old('scheme_code', $scheme->scheme_code) }}" placeholder="e.g., SCH-001" required>
            </div>

            <div class="form-group">
                <label>Total Units <span class="required">*</span></label>
                <input type="number" name="total_units" class="form-control"
                    value="{{ old('total_units', $scheme->total_units) }}" placeholder="Enter total units"
                    min="1" required>
            </div>
        </div>
    </div>

    <!-- Financial Details Section -->
    <div class="form-section">
        <h5 class="section-title">Properties Financial Details</h5>
        <div class="row g-3">
            <!-- Step 1: Initial Deposit -->
            <div class="col-12 mt-4">
                <div class="d-flex align-items-center p-3 rounded shadow-sm"
                    style="background: #f6def7; border-left: 5px solid #e100ff;">
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
                            <input type="hidden" name="quarter_fees[{{ $qt->quarter_id }}][quarter_type_id]"
                                value="{{ $qt->quarter_id }}">

                            <input type="number" class="form-control"
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
                            <input type="hidden" name="quarter_fees[{{ $qt->quarter_id }}][quarter_type_id]"
                                value="{{ $qt->quarter_id }}">

                            <input type="number" class="form-control"
                                name="quarter_fees[{{ $qt->quarter_id }}][emd_amount]"
                                placeholder="{{ $qt->quarter_code }} - {{ strtoupper($qt->quarter_name) }}"
                                value="{{ old('quarter_fees.' . $qt->quarter_id . '.emd_amount', $existing->emd_amount ?? '') }}">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Step 2: At the Time of Allotment -->
            <div class="col-12 mt-4">
                <div class="d-flex align-items-center p-3 rounded shadow-sm"
                    style="background: #e8f0f7; border-left: 5px solid #0dcaf0;">
                    <h6 class="mb-0 fw-semibold text-info">
                        <i class="bx bx-building-house me-2"></i>
                        Step 2 : At the Time of Allotment
                    </h6>
                </div>
            </div>

            <!-- Property Total Cost -->
            <div class="col-md-4">
                <label class="form-label">Property Total Cost (₹) <span class="required">*</span></label>
                <input type="number" id="total_cost" name="property_total_cost"
                    placeholder="Enter Property Total Cost" class="form-control"
                    value="{{ old('property_total_cost', $scheme->financial->property_total_cost ?? '') }}" required>
            </div>

            <!-- Lottery Payment % -->
            <div class="col-md-4">
                <label class="form-label">Lottery Payment (%) <span class="required">*</span></label>
                <input type="number" id="lottery_percent" name="lottery_percentage"
                    placeholder="Enter Lottery Payment Percentage" class="form-control"
                    value="{{ old('lottery_percentage', $scheme->financial->lottery_percentage ?? '') }}" required>
            </div>

            <!-- Lottery Payment Amount -->
            <div class="col-md-4">
                <label class="form-label">Lottery Payment Amount (₹) <span class="required">*</span></label>
                <input type="number" id="lottery_amount" name="lottery_amount"
                    placeholder="Enter Lottery Payment Amount" class="form-control"
                    value="{{ old('lottery_amount', $scheme->financial->lottery_amount ?? '') }}" required>
            </div>


            <!-- Allotment Payment % -->
            <div class="col-md-4">
                <label class="form-label">Allotment Payment (%) <span class="required">*</span></label>
                <input type="number" id="down_percent" name="allotment_percentage"
                    placeholder="Enter Down Payment Percentage" class="form-control"
                    value="{{ old('allotment_percentage', $scheme->financial->allotment_percentage ?? '') }}"
                    required>
            </div>

            <!-- Allotment Payment Amount -->
            <div class="col-md-4">
                <label class="form-label">Allotment Payment Amount (₹) <span class="required">*</span></label>
                <input type="number" id="down_amount" name="allotement_amount"
                    placeholder="Enter Down Payment Amount" class="form-control"
                    value="{{ old('allotement_amount', $scheme->financial->allotement_amount ?? '') }}" required>
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
                    class="form-control"
                    value="{{ old('balance_amount', $scheme->financial->balance_amount ?? '') }}" required>
            </div>

            <!-- EMI Count -->
            <div class="col-md-4">
                <label class="form-label">No. of EMIs <span class="required">*</span></label>
                <input type="number" id="emi_count" name="emi_count" placeholder="Enter EMI Counts"
                    class="form-control" value="{{ old('emi_count', $scheme->financial->emi_count ?? '') }}"
                    required>
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
                                    <label class="form-label">Interest Rate (%) <span
                                            class="required">*</span></label>
                                    <input type="number" id="normal_interest" name="normal_interest_rate"
                                        value="{{ old('normal_interest_rate', $scheme->financial->normal_interest_rate ?? 13.5) }}"
                                        class="form-control" required>
                                </div>
                                <div>
                                    <label class="form-label">Monthly EMI (₹) <small class="text-danger">/
                                            Month</small></label>
                                    <input type="number" id="emi_normal" name="emi_without_penalty"
                                        class="form-control"
                                        value="{{ old('emi_without_penalty', $scheme->financial->emi_without_penalty ?? '') }}"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <!-- WITH PENALTY -->
                        <div class="col-md-6">
                            <div class="p-3" style="background:#fff1f1; border-left:4px solid #dc3545;">
                                <h6 class="fw-bold text-danger mb-3">With Penalty</h6>
                                <div class="mb-3">
                                    <label class="form-label">Penalty Rate (%) <span class="required">*</span></label>
                                    <input type="number" id="penalty_rate" name="penalty_interest_rate"
                                        value="{{ old('penalty_interest_rate', $scheme->financial->penalty_interest_rate ?? 2.5) }}"
                                        class="form-control" required>
                                </div>
                                <div>
                                    <label class="form-label">Monthly EMI (₹) <small class="text-danger">/
                                            Month</small> &nbsp; (Interest Rate + Penalty Rate) of balance
                                        Amount</label>
                                    <input type="number" id="emi_penalty" name="emi_with_penalty"
                                        class="form-control"
                                        value="{{ old('emi_with_penalty', $scheme->financial->emi_with_penalty ?? '') }}"
                                        readonly>
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
                    <option value="90" {{ old('lease_period', $scheme->lease_period) == 90 ? 'selected' : '' }}>90
                        Years</option>
                    <option value="99" {{ old('lease_period', $scheme->lease_period) == 99 ? 'selected' : '' }}>99
                        Years</option>
                </select>
            </div>

            @php
                $currentYear = date('Y');
            @endphp

            <div class="col-md-4">
                <label for="initiation_year" class="form-label">Year of Initiation <span
                        class="required">*</span></label>
                <select name="initiation_year" id="initiation_year" class="form-select" required>
                    <option value="">-- Select Initiation Year --</option>
                    @for ($year = 1960; $year <= $currentYear; $year++)
                        <option value="{{ $year }}"
                            {{ old('initiation_year', $scheme->initiation_year) == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-4">
                <label>Scheme Start Date <span class="required">*</span></label>
                <input type="date" name="scheme_start_date" class="form-control"
                    value="{{ old('scheme_start_date', optional($scheme->scheme_start_date)->format('Y-m-d')) }}"
                    required>
            </div>

            <div class="col-md-4">
                <label>Scheme End Date</label>
                <input type="date" name="scheme_end_date" class="form-control"
                    value="{{ old('scheme_end_date', optional($scheme->scheme_end_date)->format('Y-m-d')) }}">
            </div>

            <div class="col-md-4">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="1" {{ old('status', $scheme->status ?? 1) == 1 ? 'selected' : '' }}>Active
                    </option>
                    <option value="0" {{ old('status', $scheme->status ?? 1) == 0 ? 'selected' : '' }}>Inactive
                    </option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.schemes.index') }}" class="btn-reset"
        style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Back</a>
    <button type="submit" class="btn-submit">{{ $submitLabel }}</button>
</div>

<script>
    // ELEMENTS
    const el = id => document.getElementById(id);

    const totalCost = el('total_cost');

    const lotteryPercent = el('lottery_percent');
    const lotteryAmount = el('lottery_amount');

    const downPercent = el('down_percent');
    const downAmount = el('down_amount');

    const balanceAmount = el('balance_amount');

    const emiCount = el('emi_count');

    const normalInterest = el('normal_interest');
    const penaltyRate = el('penalty_rate');

    const emiNormal = el('emi_normal');
    const emiPenalty = el('emi_penalty');

    // HELPERS
    const num = value => parseFloat(value) || 0;

    const round = value => Math.ceil(value || 0);

    // EMI FORMULA
    function calculateEMI(principal, annualRate, months) {

        if (months <= 0) {
            return 0;
        }

        const monthlyRate = annualRate / 12 / 100;

        // WITHOUT INTEREST
        if (monthlyRate <= 0) {
            return round(principal / months);
        }

        // EMI FORMULA
        const emi =
            (
                principal *
                monthlyRate *
                Math.pow(1 + monthlyRate, months)
            ) /
            (
                Math.pow(1 + monthlyRate, months) - 1
            );

        return round(emi);
    }

    /*
    |--------------------------------------------------------------------------
    | MAIN CALCULATION
    |--------------------------------------------------------------------------
    |
    | Lottery = 10%
    | Allotment = 15%
    |
    | Balance =
    | Property Cost
    | - Lottery Amount
    | - Allotment Amount
    |
    */

    function calculateFinancials() {

        const total = num(totalCost?.value);

        // LOTTERY
        const lotteryPer = num(lotteryPercent?.value);

        const lotteryAmt = round(
            (total * lotteryPer) / 100
        );

        if (lotteryAmount) {
            lotteryAmount.value = lotteryAmt;
        }

        // ALLOTMENT
        const allotmentPer = num(downPercent?.value);

        const allotmentAmt = round(
            (total * allotmentPer) / 100
        );

        if (downAmount) {
            downAmount.value = allotmentAmt;
        }

        // BALANCE
        const balance =
            total -
            lotteryAmt -
            allotmentAmt;

        if (balanceAmount) {
            balanceAmount.value = round(balance);
        }

        // EMI
        const principal = round(balance);

        const months = num(emiCount?.value);

        const interest = num(normalInterest?.value);

        const penalty = num(penaltyRate?.value);

        // NORMAL EMI
        if (emiNormal) {

            emiNormal.value = calculateEMI(
                principal,
                interest,
                months
            );
        }

        // PENALTY EMI
        if (emiPenalty) {

            emiPenalty.value = calculateEMI(
                principal,
                interest + penalty,
                months
            );
        }
    }

    // EVENTS
    [
        totalCost,
        lotteryPercent,
        downPercent,
        emiCount,
        normalInterest,
        penaltyRate
    ].forEach(input => {

        input?.addEventListener(
            'input',
            calculateFinancials
        );
    });

    // INITIAL LOAD
    document.addEventListener(
        'DOMContentLoaded',
        calculateFinancials
    );
</script>
