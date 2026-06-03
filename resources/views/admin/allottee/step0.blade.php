<style>
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

    .payment-section {
        display: grid;
        grid-template-columns: 1fr 500px;
        gap: 30px;
        align-items: start;
        margin-right: 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        width: 100%;
    }

    .field {
        width: 100%;
        max-width: 100%;
    }

    .field-label {
        margin-bottom: 6px;
        font-weight: 600;
    }

    .custom-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d0d0d0;
        border-radius: 8px;
        font-size: 14px;
    }

    .req-star {
        color: red;
    }

    .full-width {
        grid-column: span 2;
    }

    .preview-box {
        border: 2px dashed #d5d5d5;
        border-radius: 12px;
        padding: 15px;
        background: #fafafa;
        text-align: center;
        min-height: 320px;
        margin-top: 12px;
    }

    .preview-title {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .preview-image {
        width: 100%;
        max-height: 260px;
        object-fit: contain;
        border-radius: 10px;
        display: none;
    }

    .preview-placeholder {
        color: #888;
        margin-top: 90px;
        font-size: 14px;
    }

    .text-muted {
        color: #777;
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .payment-section {
            grid-template-columns: 1fr;
            margin-right: 0px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .full-width {
            grid-column: span 1;
        }
    }

    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@php
    #debug($applicant);
    $divisions = getDivisions();
    $propertyCategory = getPropertyCategory();
    $quaterType = getQuarterType();
    $subdivisions = $subdivisions ?? [];
    $propertyTypes = $propertyTypes ?? [];
    $propertySubTypes = $propertySubTypes ?? [];
    $getSchemeList = $getSchemeList ?? [];
@endphp
<form id="step0Form" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="applicant_id" value="{{ $applicant->id ?? '' }}" id="step0_applicant_id">
    {{-- ── Allottee Details ── --}}
    <div class="form-section" style="margin-top:10px;">
        <div class="form-grid3">
            <div class="field">
                <label class="field-label">
                    Division <span class="req-star">*</span>
                </label>
                <select name="division_id" id="divisionId" class="custom-input division-select">
                    <option value="">— Select Division —</option>
                    @foreach ($divisions as $division)
                        <option value="{{ $division->dv_en_id }}"
                            {{ isset($applicant) && $applicant->division_id == $division->id ? 'selected' : '' }}>
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
                    @foreach ($subdivisions as $subDiv)
                        <option value="{{ $subDiv->sub_dv_en_id }}"
                            {{ isset($applicant) && $applicant->subdivision_id == $subDiv->id ? 'selected' : '' }}>
                            {{ $subDiv->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label class="field-label">
                    Property Category <span class="req-star">*</span>
                </label>
                <select name="pcategory_id" id="pCategory" class="custom-input property-category-select">
                    <option value="">— Select Property Category —</option>
                    @foreach ($propertyCategory as $Category)
                        <option value="{{ $Category->pct_en_id }}"
                            {{ isset($applicant) && $applicant->pcategory_id == $Category->id ? 'selected' : '' }}>
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
                    @foreach ($propertyTypes as $proType)
                        <option value="{{ $proType->pt_en_id }}"
                            {{ isset($applicant) && $applicant->property_type_id == $proType->id ? 'selected' : '' }}>
                            {{ $proType->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label class="field-label">
                    Property Sub Type
                </label>
                <select name="p_sub_type_id" id="property_sub_type" class="custom-input property-sub-type-select">
                    <option value="">— Select Property Sub Type —</option>
                    @foreach ($propertySubTypes as $proSubType)
                        <option value="{{ $proSubType->pctm_en_id }}"
                            {{ isset($applicant) && $applicant->p_sub_type_id == $proSubType->id ? 'selected' : '' }}>
                            {{ $proSubType->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label class="field-label">
                    Quarter Type <span class="req-star">*</span>
                </label>
                <select name="quarter_id" id="quaterTypeOption" class="custom-input quater-type-option">
                    <option value="">— Select Quarter Type —</option>
                    @foreach ($quaterType as $quat)
                        <option value="{{ $quat->qt_en_id }}"
                            {{ isset($applicant) && $applicant->quarter_id == $quat->quarter_id ? 'selected' : '' }}>
                            {{ $quat->quarter_code }} - {{ $quat->quarter_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- Hidden input for selected scheme ID -->
        <input type="hidden" name="scheme_id" id="selected_scheme_id" value="{{ $applicant->scheme_id ?? '' }}">
        <div class="form-grid">
            <div class="field">
                <label class="field-label">
                    Schemes <span class="req-star">*</span>
                </label>
                <div class="custom-select-wrapper">
                    <input type="text" class="custom-input mb-2" id="schemeSearch"
                        placeholder="Type to search scheme by name"
                        value="{{ optional($getSchemeList)->scheme_code ? optional($getSchemeList)->scheme_code . ' ' . optional($getSchemeList)->scheme_name : '' }}"
                        autocomplete="off" autofocus="" required>
                    <div class="custom-options" id="customOptions">
                    </div>
                    <small class="text-muted mt-1" id="searchResultCount">0 schemes
                        available</small>
                </div>
            </div>
        </div>
        <div class="section-header gradient-header" style="background:linear-gradient(90deg,#0c9a78,#066a53)">
            <div class="section-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <rect x="2" y="5" width="20" height="14" rx="2" />
                    <path d="M2 10h20" />
                </svg>
            </div>
            <div>
                <h3 class="form-section-title">Payment details</h3>
                <p class="form-section-sub" style="opacity:.9;font-size:12px;margin:4px 0 0;">Enter payment information
                    and upload proof before continuing to allottee details.</p>
            </div>
        </div>
        <div class="payment-section">
            <!-- LEFT SIDE FORM -->
            <div class="form-grid22">
                <!-- <div class="field">
                    <label class="field-label">
                        Amount paid <span class="req-star">*</span>
                    </label>
                    <input type="number"
                        name="payment_amount"
                        class="custom-input"
                        step="0.01"
                        min="0.01"
                        placeholder="0.00"
                        value="{{ old('payment_amount', $applicant->payment_amount ?? '') }}"
                        required>
                </div> -->
                <div class="field">
                    <label class="field-label">
                        Amount paid <span class="req-star">*</span>
                    </label>

                    <!-- Visible readonly input -->
                    <input type="number" id="payment_amount_display" class="custom-input" step="0.01"
                        placeholder="0.00" value="{{ old('payment_amount', $applicant->payment_amount ?? '') }}"
                        readonly>

                    <!-- Hidden actual form value -->
                    <input type="hidden" name="payment_amount" id="payment_amount"
                        value="{{ old('payment_amount', $applicant->payment_amount ?? '') }}">
                </div>
                <div class="field">
                    <label class="field-label">
                        Payment date <span class="req-star">*</span>
                    </label>
                    <div class="date-group">
                        <!-- Day -->
                        <select name="payment_day" class="custom-input">
                            <option value="">दिन / Day</option>
                            <?php
                            $selectedDay =  old('payment_day', $applicant->payment_day ?? '');
                            for ($d = 1; $d <= 31; $d++):
                                $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                            ?>
                            <option value="<?= $day ?>" <?= $selectedDay == $day ? 'selected' : '' ?>>
                                <?= $day ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                        <!-- Month -->
                        <select name="payment_month" class="custom-input">
                            <option value="">माह / Month</option>
                            <?php
                            $selectedMonth = old('payment_month', $applicant->payment_month ?? '');
                            for ($m = 1; $m <= 12; $m++):
                                $month = str_pad($m, 2, '0', STR_PAD_LEFT);
                            ?>
                            <option value="<?= $month ?>" <?= $selectedMonth == $month ? 'selected' : '' ?>>
                                <?= $month ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                        <!-- Year -->
                        <select name="payment_year" class="custom-input">
                            <option value="">वर्ष / Year</option>
                            <?php
                            $selectedYear = old('payment_year', $applicant->payment_year ?? '');
                            $currentYear = date('Y');
                            for ($y = $currentYear; $y >= 1970; $y--):
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
                        UTR No.
                    </label>
                    <input type="text" name="payment_utr_no" class="custom-input only-alphanumeric"
                        minlength="12" maxlength="22" placeholder="UTR, cheque no., etc."
                        value="{{ old('payment_utr_no', $applicant->payment_utr_no ?? '') }}">
                </div>
                <div class="field full-width">
                    <label class="field-label">
                        Payment receipt / screenshot
                        <span class="req-star">*</span>
                    </label>
                    <input type="file" name="payment_receipt" id="payment_receipt" class="custom-input"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        {{ !empty($applicant->payment_receipt_path) ? '' : 'required' }}>
                    @if (!empty($applicant->payment_receipt_path))
                        <small class="text-muted" style="margin-top:6px;">
                            Current file is saved. Upload a new image only if you want to replace it.
                        </small>
                    @endif
                </div>
            </div>
            <!-- RIGHT SIDE PREVIEW -->
            <div class="preview-box">
                <div class="preview-title">
                    Receipt Preview
                </div>
                <img id="receiptPreview" class="preview-image imagePopupModal"
                    src="{{ !empty($applicant->payment_receipt_path) ? asset($applicant->payment_receipt_path) : '' }}"
                    alt="Receipt Preview"
                    style="{{ !empty($applicant->payment_receipt_path) ? 'display:block' : 'display:none' }}">
                <div id="receiptPlaceholder" class="preview-placeholder"
                    style="{{ !empty($applicant->payment_receipt_path) ? 'display:none' : '' }}">
                    No image selected
                </div>
            </div>
        </div>
    </div>
</form>
