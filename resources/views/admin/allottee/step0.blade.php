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

        <div class="form-grid3">
            <div class="field">
                <label class="field-label">Amount paid <span class="req-star">*</span></label>
                <input type="number" name="payment_amount" class="custom-input" step="0.01" min="0.01"
                    placeholder="0.00" value="{{ old('payment_amount', $applicant->payment_amount ?? '') }}" required>
            </div>
            <div class="field">
                <label class="field-label">Payment date <span class="req-star">*</span></label>
                <input type="date" name="payment_date" class="custom-input"
                    value="{{ old('payment_date', $applicant->payment_date ?? '') }}"
                    required>
            </div>
            <div class="field">
                <label class="field-label">Mode of payment <span class="req-star">*</span></label>
                <select name="payment_mode" class="custom-input" required>
                    <option value="">— Select —</option>
                    @foreach (['UPI' => 'UPI', 'NEFT' => 'NEFT / RTGS', 'IMPS' => 'IMPS', 'Cheque' => 'Cheque', 'Cash' => 'Cash', 'Demand Draft' => 'Demand Draft', 'Other' => 'Other'] as $val => $label)
                        <option value="{{ $val }}" {{ old('payment_mode', $applicant->payment_mode ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-grid3">
            <div class="field">
                <label class="field-label">Transaction / reference no.</label>
                <input type="text" name="payment_reference" class="custom-input" maxlength="255"
                    placeholder="UTR, cheque no., etc."
                    value="{{ old('payment_reference', $applicant->payment_reference ?? '') }}">
            </div>
            <div class="field" style="grid-column: span 2;">
                <label class="field-label">Payment receipt / screenshot <span class="req-star">*</span></label>
                <input type="file" name="payment_receipt" id="payment_receipt" class="custom-input" accept="image/jpeg,image/png,image/jpg,image/webp"
                    {{ !empty($applicant->payment_receipt_path) ? '' : 'required' }}>
                @if (!empty($applicant->payment_receipt_path))
                    <small class="text-muted" style="display:block;margin-top:6px;">Current file is saved. Upload a new image only if you want to replace it.</small>
                @endif
            </div>
        </div>
    </div>
</form>
