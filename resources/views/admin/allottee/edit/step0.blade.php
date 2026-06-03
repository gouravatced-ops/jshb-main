<form id="step0Form" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="applicant_id" value="{{ $applicant->id ?? '' }}" id="step0_applicant_id">

    <div class="form-section" style="margin-top:10px;">
        <div class="section-header gradient-header" style="background:linear-gradient(90deg,#0c9a78,#066a53)">
            <div class="section-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="5" width="20" height="14" rx="2" />
                    <path d="M2 10h20" />
                </svg>
            </div>
            <div>
                <h3 class="form-section-title">Payment details</h3>
                <p class="form-section-sub" style="opacity:.9;font-size:12px;margin:4px 0 0;">Enter payment information and upload proof before continuing to allottee details.</p>
            </div>
        </div>

        <style>
            .payment-section {
                display: grid;
                grid-template-columns: 1fr 400px;
                gap: 30px;
                align-items: start;
                margin-right: 20px;
            }

            .form-grid {
                display: flex;
                flex-direction: column;
                align-items: center;
                /* center fields */
                gap: 18px;
            }

            .field {
                width: 100%;
                max-width: 450px;
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

            /* Chrome, Safari, Edge, Opera */
            input[type=number]::-webkit-outer-spin-button,
            input[type=number]::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            /* Firefox */
            input[type=number] {
                -moz-appearance: textfield;
            }
        </style>

        <div class="payment-section">

            <!-- LEFT SIDE FORM -->
            <div class="form-grid">

                <div class="field">
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

                    <input type="text"
                        name="payment_utr_no"
                        class="custom-input only-alphanumeric"
                        minlength="12"
                        maxlength="22"
                        placeholder="UTR, cheque no., etc."
                        value="{{ old('payment_utr_no', $applicant->payment_utr_no ?? '') }}">
                </div>

                <div class="field full-width">

                    <label class="field-label">
                        Payment receipt / screenshot
                        <span class="req-star">*</span>
                    </label>

                    <input type="file"
                        name="payment_receipt"
                        id="payment_receipt"
                        class="custom-input"
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

                <img id="receiptPreview"
                    class="preview-image"
                    src="{{ !empty($applicant->payment_receipt_path) ? asset($applicant->payment_receipt_path) : '' }}"
                    alt="Receipt Preview"
                    style="{{ !empty($applicant->payment_receipt_path) ? 'display:block' : 'display:none' }}">

                <div id="receiptPlaceholder"
                    class="preview-placeholder"
                    style="{{ !empty($applicant->payment_receipt_path) ? 'display:none' : '' }}">
                    No image selected
                </div>
            </div>

        </div>
    </div>
</form>