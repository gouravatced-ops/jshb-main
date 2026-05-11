<style>
    .allotment-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .year-input {
        width: 100px;
        height: 42px;
        border-radius: 6px;
        background: var(--surface);
        border: 1.5px solid var(--border);
    }

    .slash {
        font-weight: 600;
        font-size: 18px;
    }

    /* Invalid state */
    .invalid-year {
        border: 2px solid #dc3545 !important;
        background-color: #fff5f5;
    }

    .error-text {
        color: #dc3545;
        font-size: 12px;
    }

    .custom-select-wrapper {
        position: relative;
    }

    #schemeSearch:focus {
        border-color: #0c9a78;
        box-shadow: 0 0 0 0.2rem #066a5334;
    }

    .custom-options {
        border: 1px solid #dee2e6;
        border-top: none;
        max-height: 300px;
        overflow-y: auto;
        background: white;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: none;
        position: absolute;
        width: 100%;
        z-index: 1000;
    }

    .custom-options.show {
        display: block;
    }

    .custom-option {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
    }

    .custom-option:last-child {
        border-bottom: none;
    }

    .custom-option:hover {
        background-color: #0c9a78;
        color: #ffffff !important;
    }

    .custom-option.selected {
        background-color: #0c9a78;
        color: white;
    }

    .custom-option.selected .badge.bg-secondary {
        background-color: #fff !important;
        color: #0c9a78 !important;
        font-size: 14px;
    }

    .custom-option.selected .badge.bg-info {
        background-color: #fff !important;
        color: #0c9a78 !important;
    }

    .block-item:not(:last-child) {
        margin-bottom: 1.5rem;
    }

    .card-header .btn-light {
        background-color: rgba(255, 255, 255, 0.9);
    }

    .card-header .btn-light:hover {
        background-color: #fff;
    }

    #searchResultCount {
        font-size: 0.85rem;
        padding-left: 5px;
    }

    /* Add to your existing styles */
    .badge.bg-info {
        transition: all 0.3s ease;
    }

    .badge.bg-warning {
        background-color: #ffc107 !important;
        color: #000 !important;
        transition: all 0.3s ease;
    }
    .badge.bg-secondary {
        font-size: 14px !important;
    }
</style>
@php
$divisions = getDivisions();
$propertyCategory = getPropertyCategory();
$quaterType = getQuarterType();
@endphp
<form id="step1Form" method="POST">
    @csrf
    <input type="hidden" name="allottee_id" value="{{$applicant->id ?? ''}}">

    {{-- ── Allottee Details ── --}}
    <div class="form-section" style="margin-top:10px;">
        <div class="form-grid3">
            <div class="field">
                <label class="field-label">
                    Division <span class="req-star">*</span>
                </label>
                <select name="division_id" id="divisionId" class="custom-input division-select">
                    <option value="">— Select Division —</option>
                    @foreach($divisions as $division)
                    <option value="{{ $division->dv_en_id }}" {{ isset($applicant) && $applicant->division_id == $division->id ? 'selected' : '' }}>
                        {{ $division->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label class="field-label">
                    Sub Division <span class="req-star">*</span>
                </label>
                <select name="subdivision_id" id="subdivisionSelect" class="custom-input">
                    <option value="">— Select Sub Division —</option>
                </select>
            </div>
            <div class="field">
                <label class="field-label">
                    Property Category <span class="req-star">*</span>
                </label>
                <select name="pcategory_id" id="pCategory" class="custom-input property-category-select">
                    <option value="">— Select Property Category —</option>
                    @foreach($propertyCategory as $Category)
                    <option value="{{ $Category->pct_en_id }}">
                        {{ $Category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-grid3">
            <div class="field">
                <label class="field-label">
                    Property Type <span class="req-star">*</span>
                </label>
                <select name="property_type_id" id="PropertyCatType" class="custom-input property-cat-type-select">
                    <option value="">— Select Property Type —</option>
                </select>
            </div>

            <div class="field">
                <label class="field-label">
                    Property Sub Type
                </label>
                <select name="p_sub_type_id" id="property_sub_type" class="custom-input property-sub-type-select">
                    <option value="">— Select Property Sub Type —</option>
                </select>
            </div>

            <div class="field">
                <label class="field-label">
                    Quarter Type <span class="req-star">*</span>
                </label>
                <select name="quarter_id" id="quaterTypeOption" class="custom-input quater-type-option">
                    <option value="">— Select Quarter Type —</option>
                    @foreach($quaterType as $quat)
                    <option value="{{ $quat->qt_en_id }}">
                        {{ $quat->quarter_code }} - {{ $quat->quarter_name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <!-- Hidden input for selected scheme ID -->
        <input type="hidden" name="scheme_id" id="selected_scheme_id" value="{{$applicant->scheme_id ?? ''}}">

        <div class="form-grid" style="grid-template-columns: repeat(1, 1fr) !important;">
            <div class="field">
                <label class="field-label">
                    Schemes <span class="req-star">*</span>
                </label>
                <div class="custom-select-wrapper">
                    <input type="text" class="custom-input mb-2" id="schemeSearch" placeholder="Type to search scheme by name" value="{{ optional($getSchemeList)->scheme_code ? optional($getSchemeList)->scheme_code . ' ' . optional($getSchemeList)->scheme_name : '' }}" autocomplete="off" autofocus="" required>
                    <div class="custom-options" id="customOptions">
                    </div>
                    <small class="text-muted mt-1" id="searchResultCount">0 schemes
                        available</small>
                </div>
            </div>
        </div>

        <div class="form-grid4">
            <div class="field">
                <label class="field-label">
                    Application No. <span class="req-star">*</span>
                </label>
                <input type="text" name="application_no" class="custom-input alpha-num-dash"
                    value="" placeholder="e.g. 1234567890">
            </div>
            <div class="field">
                <label class="field-label">
                    Application Date <span class="req-star">*</span>
                </label>
                <div class="date-group">
                    <!-- Day -->
                    <select name="application_day" class="custom-input">
                        <option value="">दिन / Day</option>
                        <?php
                        $selectedDay =  '';
                        for ($d = 1; $d <= 31; $d++):
                            $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                        ?>
                            <option value="<?= $day ?>" <?= $selectedDay == $day ? 'selected' : '' ?>>
                                <?= $day ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <!-- Month -->
                    <select name="application_month" class="custom-input">
                        <option value="">माह / Month</option>
                        <?php
                        $selectedMonth = '';
                        for ($m = 1; $m <= 12; $m++):
                            $month = str_pad($m, 2, '0', STR_PAD_LEFT);
                        ?>
                            <option value="<?= $month ?>" <?= $selectedMonth == $month ? 'selected' : '' ?>>
                                <?= $month ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <!-- Year -->
                    <select name="application_year" class="custom-input" id="application_year">
                        <option value="">वर्ष / Year</option>
                        <?php
                        $selectedYear = '';
                        $currentYear = date('Y');
                        for ($y = $currentYear; $y >= 1960; $y--):
                        ?>
                            <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="field">
                <label class="field-label">
                    Allotment No. <span class="req-star">*</span>
                </label>

                <div class="input-group allotment-group">
                    <input type="text" name="allotment_no" class="custom-input only-number"
                        style="width: 100%;
                            padding: 8px 10px;
                            border: 1px solid #ccc;
                            border-radius: 6px;
                            font-size: 14px;
                            background: #f5f4f0;font-weight:600;"
                        value="" placeholder="e.g. 1234567890" />

                    <span class="slash">/</span>

                    <input type="text" name="year" id="allotmentYear" class="custom-input year-input only-number"
                        value="" placeholder="YYYY" maxlength="4" />
                </div>

                <small id="yearError" class="error-text"></small>
            </div>
            <div class="field">
                <label class="field-label">
                    Allotment Date <span class="req-star">*</span>
                </label>
                <div class="date-group">
                    <!-- Day -->
                    <select name="allotment_day" class="custom-input">
                        <option value="">दिन / Day</option>
                        <?php
                        $selectedDay = '';
                        for ($d = 1; $d <= 31; $d++):
                            $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                        ?>
                            <option value="<?= $day ?>" <?= $selectedDay == $day ? 'selected' : '' ?>>
                                <?= $day ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <!-- Month -->
                    <select name="allotment_month" class="custom-input">
                        <option value="">माह / Month</option>
                        <?php
                        $selectedMonth = '';
                        for ($m = 1; $m <= 12; $m++):
                            $month = str_pad($m, 2, '0', STR_PAD_LEFT);
                        ?>
                            <option value="<?= $month ?>" <?= $selectedMonth == $month ? 'selected' : '' ?>>
                                <?= $month ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <!-- Year -->
                    <select name="allotment_year" id="allotment_year" class="custom-input">
                        <option value="">वर्ष / Year</option>
                        <?php
                        $selectedYear = '';
                        $currentYear = date('Y');
                        for ($y = $currentYear; $y >= 1960; $y--):
                        ?>
                            <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-grid3">
            <div class="field">
                <label class="field-label">
                    First Name <span class="req-star">*</span>
                </label>
                <div class="input-group">
                    @php $prefixes = ['Shri', 'Smt.', 'Miss', 'Dr.', 'Md.', 'Late', 'M/S' , 'Maj.' , 'Capt.']; @endphp
                    <select name="prefix" class="prefix-select">
                        @foreach ($prefixes as $prefix)
                        <option value="{{ $prefix }}">
                            {{ $prefix }}
                        </option>
                        @endforeach
                    </select>
                    <input type="text" name="allottee_name" class="custom-input only-alphabet"
                        value="" placeholder="e.g. Rajesh">
                </div>
            </div>

            <div class="field">
                <label class="field-label">Middle Name</label>
                <input type="text" name="allottee_middle_name" class="custom-input only-alphabet"
                    value="" placeholder="Optional">
            </div>

            <div class="field">
                <label class="field-label">
                    Last Name
                </label>
                <input type="text" name="allottee_surname" class="custom-input only-alphabet"
                    value="" placeholder="e.g. Kumar">
            </div>

            <div class="field">
                <label class="field-label">
                    First Name (Hindi)
                </label>
                <div class="input-group">
                    @php $prefixes = ['श्री', 'श्रीमती', 'सुश्री', 'डॉ.', 'मो.', 'स्व०', 'मेसर्स' , 'मेजर', 'कैप्टन']; @endphp
                    <select name="allottee_prefix_hindi" class="prefix-select">
                        @foreach ($prefixes as $prefix)
                        <option value="{{ $prefix }}">
                            {{ $prefix }}
                        </option>
                        @endforeach
                    </select>
                    <input type="text" name="allottee_name_hindi" class="custom-input only-hindi"
                        value="" placeholder="e.g. राजेश">
                </div>
            </div>

            <div class="field">
                <label class="field-label">Middle Name (Hindi)</label>
                <input type="text" name="allottee_middle_hindi" class="custom-input only-hindi"
                    value="" placeholder="e.g. कुमार">
            </div>

            <div class="field">
                <label class="field-label">
                    Last Name (Hindi)
                </label>
                <input type="text" name="allottee_surname_hindi" class="custom-input only-hindi"
                    value="" placeholder="e.g. कुमार">
            </div>

            <div class="field">
                <label class="field-label">
                    Relation of allottee <span class="req-star">*</span>
                </label>
                <div class="input-group">
                    @php $prefixes = ['Father', 'Mother', 'Husband', 'Wife']; @endphp
                    <select name="relation_prefix" class="prefix-select">
                        @foreach ($prefixes as $prefix)
                        <option value="{{ $prefix }}">
                            {{ $prefix }}
                        </option>
                        @endforeach
                    </select>
                    <input type="text" name="relation_name" class="custom-input only-alphabet"
                        value=""
                        placeholder="e.g. Father, Mother, Husband, Wife">
                </div>
            </div>

            <div class="field">
                <label class="field-label">
                    Marital Status <span class="req-star">*</span>
                </label>
                <select name="marital_status" class="custom-input">
                    <option value="Unmarried">Unmarried</option>
                    <option value="Married">Married</option>
                    <option value="Divorced">Divorced</option>
                    <option value="Widow">Widow</option>
                    <option value="Widower">Widower</option>
                </select>
            </div>

            <div class="field">
                <label class="field-label">
                    Gender <span class="req-star">*</span>
                </label>
                <select name="allottee_gender" class="custom-input">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Transgender">Transgender</option>
                </select>
            </div>

            <div class="field" id="pan-field" style="display:none;">
                <label class="field-label">
                    PAN Card Number
                </label>
                <input type="text" id="pan_card_number" name="pan_card_number" placeholder="ABCDE1234F"
                    class="custom-input pan-input" value="" maxlength="10"
                    style="text-transform:uppercase">
            </div>

            <div class="field" id="aadhar-field" style="display:none;">
                <label class="field-label">
                    Aadhar Card Number
                </label>
                <input type="text" id="aadhar_card_number" name="aadhar_card_number" class="custom-input"
                    value=""
                    placeholder="12-digit Aadhar number, no spaces" pattern="[0-9]{12}" maxlength="12">
            </div>

            @php
            $categories = [
            'General' => 'General',
            'General (PwD)' => 'General (PwD)',
            'Scheduled Caste (SC)' => 'Scheduled Caste (SC)',
            'Scheduled Caste (SC) (PwD)' => 'Scheduled Caste (SC) (PwD)',
            'Scheduled Tribe (ST)' => 'Scheduled Tribe (ST)',
            'Scheduled Tribe (ST) (PwD)' => 'Scheduled Tribe (ST) (PwD)',
            'Other Backward Class (OBC)' => 'Other Backward Class (OBC)',
            'Other Backward Class (OBC) (PwD)' => 'Other Backward Class (OBC) (PwD)',
            'Retired Government Servant' => 'Retired Government Servant',
            'Govt. Servant retiring within one year' => 'Govt. Servant retiring within one year',
            'Armed Forces Personnel' => 'Armed Forces Personnel',
            'Ex-Servicemen' => 'Ex-Servicemen',
            'Abandoned' => 'Abandoned',
            'Destitute Widows' => 'Destitute Widows',
            'Vidhaanmandal' => 'Vidhaanmandal',
            'Vidhansabha' => 'Vidhansabha',
            ];
            @endphp

            <div class="field">
                <label class="field-label">
                    Category <span class="req-star">*</span>
                </label>
                <select name="allottee_category" class="custom-input" required>
                    <option value="">Select Category</option>
                    @foreach ($categories as $value => $label)
                    <option value="{{ $value }}">
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label class="field-label">
                    Religion <span class="req-star">*</span>
                </label>

                <select name="allottee_religion" class="custom-input">
                    <option value="">Select Religion</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Muslim">Muslim</option>
                    <option value="Christian">Christian</option>
                    <option value="Sikh">Sikh</option>
                    <option value="Buddhist">Buddhist</option>
                    <option value="Jain">Jain</option>
                    <option value="Parsi">Parsi</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="field">
                <label class="field-label">Nationality</label>
                <input type="text" name="allottee_nationality" class="custom-input only-alphabet" value="Indian">
            </div>

            <div class="field">
                <label class="field-label">
                    Date of Birth (जन्म तिथि)
                </label>
                <div class="date-group">
                    <!-- Day -->
                    <select name="date_of_birth_day" class="custom-input">
                        <option value="">दिन / Day</option>
                        <?php
                        $selectedDay = '';
                        for ($d = 1; $d <= 31; $d++):
                            $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                        ?>
                            <option value="<?= $day ?>" <?= $selectedDay == $day ? 'selected' : '' ?>>
                                <?= $day ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <!-- Month -->
                    <select name="date_of_birth_month" class="custom-input">
                        <option value="">माह / Month</option>
                        <?php
                        $selectedMonth = '';
                        for ($m = 1; $m <= 12; $m++):
                            $month = str_pad($m, 2, '0', STR_PAD_LEFT);
                        ?>
                            <option value="<?= $month ?>" <?= $selectedMonth == $month ? 'selected' : '' ?>>
                                <?= $month ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <!-- Year -->
                    <select name="date_of_birth_year" class="custom-input">
                        <option value="">वर्ष / Year</option>
                        <?php
                        $selectedYear = '';
                        $currentYear = date('Y');
                        for ($y = $currentYear; $y >= 1925; $y--):
                        ?>
                            <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="field">
                <label class="field-label">Current Age</label>
                <input type="text" name="current_age" class="custom-input" id="current_age"
                    value="" placeholder="e.g. 99 year old">
            </div>
        </div>
    </div>
</form>