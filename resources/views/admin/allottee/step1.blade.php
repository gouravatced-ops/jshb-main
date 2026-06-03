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
<form id="step1Form" method="POST">
    @csrf
    <input type="hidden" name="allottee_id" value="{{$applicant->id ?? ''}}">

        <div class="form-grid">
            <div class="field">
                <label class="field-label">
                    Application No. <span class="req-star">*</span>
                </label>
                <input type="text" name="application_no" class="custom-input alpha-num-dash"
                    value="{{ old('application_no', $applicant->application_no ?? '') }}" placeholder="e.g. 1234567890">
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
                        $selectedDay =  old('application_day', $applicant->application_day ?? '');
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
                        $selectedMonth = old('application_month', $applicant->application_month ?? '');
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
                        $selectedYear = old('application_year', $applicant->application_year ?? '');
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
                        <option value="{{ $prefix }}" {{ ($applicant->prefix ?? '') === $prefix ? 'selected' : '' }}>
                            {{ $prefix }}
                        </option>
                        @endforeach
                    </select>
                    <input type="text" name="allottee_name" class="custom-input only-alphabet"
                        value="{{ old('allottee_name', $applicant->allottee_name ?? '') }}" placeholder="e.g. Rajesh">
                </div>
            </div>

            <div class="field">
                <label class="field-label">Middle Name</label>
                <input type="text" name="allottee_middle_name" class="custom-input only-alphabet"
                    value="{{ old('allottee_middle_name', $applicant->allottee_middle_name ?? '') }}" placeholder="Optional">
            </div>

            <div class="field">
                <label class="field-label">
                    Last Name
                </label>
                <input type="text" name="allottee_surname" class="custom-input only-alphabet"
                    value="{{ old('allottee_surname', $applicant->allottee_surname ?? '') }}" placeholder="e.g. Kumar">
            </div>

            <div class="field">
                <label class="field-label">
                    First Name (Hindi)
                </label>
                <div class="input-group">
                    @php $prefixes = ['श्री', 'श्रीमती', 'सुश्री', 'डॉ.', 'मो.', 'स्व०', 'मेसर्स' , 'मेजर', 'कैप्टन']; @endphp
                    <select name="allottee_prefix_hindi" class="prefix-select">
                        @foreach ($prefixes as $prefix)
                        <option value="{{ $prefix }}" {{ ($applicant->allottee_prefix_hindi ?? '') === $prefix ? 'selected' : '' }}>
                            {{ $prefix }}
                        </option>
                        @endforeach
                    </select>
                    <input type="text" name="allottee_name_hindi" class="krutidev custom-input"
                        value="{{ old('allottee_name_hindi', $applicant->allottee_name_hindi ?? '') }}" placeholder="e.g. राजेश">
                </div>
            </div>

            <div class="field">
                <label class="field-label">Middle Name (Hindi)</label>
                <input type="text" name="allottee_middle_hindi" class="krutidev custom-input"
                    value="{{ old('allottee_middle_hindi', $applicant->allottee_middle_hindi ?? '') }}" placeholder="e.g. कुमार">
            </div>

            <div class="field">
                <label class="field-label">
                    Last Name (Hindi)
                </label>
                <input type="text" name="allottee_surname_hindi" class="krutidev custom-input"
                    value="{{ old('allottee_surname_hindi', $applicant->allottee_surname_hindi ?? '') }}" placeholder="e.g. कुमार">
            </div>

            <div class="field">
                <label class="field-label">
                    Relation of allottee <span class="req-star">*</span>
                </label>
                <div class="input-group">
                    @php $prefixes = ['Father', 'Mother', 'Husband', 'Wife']; @endphp
                    <select name="relation_prefix" class="prefix-select">
                        @foreach ($prefixes as $prefix)
                        <option value="{{ $prefix }}" {{ ($applicant->allottee_relation_type ?? '') === $prefix ? 'selected' : '' }}>
                            {{ $prefix }}
                        </option>
                        @endforeach
                    </select>
                    <input type="text" name="relation_name" class="custom-input only-alphabet"
                        value="{{ old('relation_name', $applicant->relation_name ?? '') }}"
                        placeholder="e.g. Father, Mother, Husband, Wife">
                </div>
            </div>

            <div class="field">
                <label class="field-label">
                    Marital Status <span class="req-star">*</span>
                </label>
                <select name="marital_status" class="custom-input">
                    <option value="Unmarried" {{ old('marital_status', $applicant->marital_status ?? '') === 'Unmarried' ? 'selected' : '' }}>Unmarried</option>
                    <option value="Married" {{  old('marital_status', $applicant->marital_status ?? '') === 'Married' ? 'selected' : '' }}>Married</option>
                    <option value="Divorced" {{  old('marital_status', $applicant->marital_status ?? '') === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                    <option value="Widow" {{  old('marital_status', $applicant->marital_status ?? '') === 'Widow' ? 'selected' : '' }}>Widow</option>
                    <option value="Widower" {{  old('marital_status', $applicant->marital_status ?? '') === 'Widower' ? 'selected' : '' }}>Widower</option>
                </select>
            </div>

            <div class="field">
                <label class="field-label">
                    Gender <span class="req-star">*</span>
                </label>
                <select name="allottee_gender" class="custom-input">
                    <option value="Male" {{  old('allottee_gender', $applicant->allottee_gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{  old('allottee_gender', $applicant->allottee_gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Transgender" {{  old('allottee_gender', $applicant->allottee_gender ?? '') === 'Transgender' ? 'selected' : '' }}>Transgender</option>
                </select>
            </div>

            <div class="field" id="pan-field">
                <label class="field-label">
                    PAN Card Number
                </label>
                <input type="text" id="pan_card_number" name="pan_card_number" placeholder="ABCDE1234F"
                    class="custom-input pan-input" value="{{ old('pan_card_number', $applicant->pan_card_number ?? '') }}" maxlength="10"
                    style="text-transform:uppercase">
            </div>

            <div class="field" id="aadhar-field">
                <label class="field-label">
                    Aadhar Card Number
                </label>
                <input type="text" id="aadhar_card_number" name="aadhar_card_number" class="custom-input"
                    value="{{ old('aadhar_card_number', $applicant->aadhar_card_number ?? '') }}"
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
            $selectedCategory = old('allottee_category', $applicant->allottee_category ?? '');
            @endphp

            <div class="field">
                <label class="field-label">
                    Category <span class="req-star">*</span>
                </label>
                <select name="allottee_category" class="custom-input" required>
                    <option value="">Select Category</option>
                    @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" {{ $selectedCategory === $value ? 'selected' : '' }}>
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
                    <option value="Hindu"
                        {{ isset($applicant) && $applicant->allottee_religion == 'Hindu' ? 'selected' : '' }}>
                        Hindu
                    </option>

                    <option value="Muslim"
                        {{ isset($applicant) && $applicant->allottee_religion == 'Muslim' ? 'selected' : '' }}>
                        Muslim
                    </option>

                    <option value="Christian"
                        {{ isset($applicant) && $applicant->allottee_religion == 'Christian' ? 'selected' : '' }}>
                        Christian
                    </option>

                    <option value="Sikh"
                        {{ isset($applicant) && $applicant->allottee_religion == 'Sikh' ? 'selected' : '' }}>
                        Sikh
                    </option>

                    <option value="Buddhist"
                        {{ isset($applicant) && $applicant->allottee_religion == 'Buddhist' ? 'selected' : '' }}>
                        Buddhist
                    </option>

                    <option value="Jain"
                        {{ isset($applicant) && $applicant->allottee_religion == 'Jain' ? 'selected' : '' }}>
                        Jain
                    </option>

                    <option value="Parsi"
                        {{ isset($applicant) && $applicant->allottee_religion == 'Parsi' ? 'selected' : '' }}>
                        Parsi
                    </option>

                    <option value="Other"
                        {{ isset($applicant) && $applicant->allottee_religion == 'Other' ? 'selected' : '' }}>
                        Other
                    </option>
                </select>
            </div>

            <div class="field">
                <label class="field-label">Nationality</label>
                <input type="text" name="allottee_nationality" class="custom-input only-alphabet" value="{{ old('allottee_nationality', $applicant->allottee_nationality ?? 'Indian') }}">
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
                        $selectedDay = old('date_of_birth_day', $applicant->date_of_birth_day ?? '');
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
                        $selectedMonth = old('date_of_birth_month', $applicant->date_of_birth_month ?? '');
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
                        $selectedYear = old('date_of_birth_year', $applicant->date_of_birth_year ?? '');;
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