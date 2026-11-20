@props(['application', 'routePrefix'])

<div class="action-portal-container">
    <style>
        .action-portal-container {
            margin-top: 15px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        /* --- Top File Snapshot Bar --- */
        .file-snapshot-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 5px solid #15803d;
            border-radius: 10px;
            padding: 16px 20px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
            margin-bottom: 20px;
        }
        .snapshot-top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed #e2e8f0;
        }
        .file-id-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            font-weight: 700;
            color: #15803d;
        }
        .snapshot-metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            padding-top: 12px;
        }
        .snapshot-item {
            display: flex;
            flex-direction: column;
        }
        .snapshot-label {
            font-size: 11.5px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 2px;
        }
        .snapshot-val {
            font-size: 13.5px;
            color: #1e293b;
            font-weight: 600;
        }

        /* --- Workflow Action Stepper --- */
        .action-stepper-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 20px;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
            margin-bottom: 20px;
        }
        .action-stepper-title {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .action-stepper-track {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        @media (max-width: 768px) {
            .action-stepper-track {
                grid-template-columns: 1fr;
            }
        }
        .step-track-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .step-track-item.active {
            background: #f0fdf4;
            border-color: #86efac;
            box-shadow: 0 2px 4px rgba(22, 163, 74, 0.1);
        }
        .step-track-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #cbd5e1;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .step-track-item.active .step-track-num {
            background: #15803d;
        }
        .step-track-text h6 {
            margin: 0;
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
        }
        .step-track-text p {
            margin: 0;
            font-size: 11px;
            color: #64748b;
        }

        /* --- Main Action Card --- */
        .action-main-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            margin-bottom: 25px;
        }
        .action-card-header {
            padding: 16px 22px;
            background: linear-gradient(135deg, #15803d 0%, #16a34a 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .action-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .action-header-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #ffffff;
        }
        .action-header-title {
            font-size: 17px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.3px;
        }
        .action-header-subtitle {
            margin: 2px 0 0 0;
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.9);
        }
        .btn-header-back {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.35);
            font-weight: 600;
            font-size: 13px;
            padding: 7px 14px;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-header-back:hover {
            background: #ffffff;
            color: #15803d;
        }
        .action-card-body {
            padding: 24px;
        }

        /* --- Secretariat Sanction Noting Sheet --- */
        .secretariat-noting-box {
            background: #ffffff;
            border: 2px solid #86efac;
            border-radius: 10px;
            margin-top: 15px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(22, 163, 74, 0.05);
        }
        .noting-box-header {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-bottom: 1px solid #86efac;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .noting-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #14532d;
        }
        .quick-noting-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 10px 18px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .noting-chip {
            font-size: 11.5px;
            font-weight: 600;
            color: #14532d;
            background: #dcfce7;
            border: 1px solid #86efac;
            border-radius: 16px;
            padding: 4px 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .noting-chip:hover {
            background: #15803d;
            color: #ffffff;
            border-color: #15803d;
        }

        /* --- Internal Password Gate --- */
        .password-gate-card {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 16px 20px;
            margin: 20px 0;
        }
        .password-gate-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
        }

        /* --- Bottom Dispatch Bar --- */
        .dispatch-action-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
            flex-wrap: wrap;
            gap: 12px;
        }
        .btn-dispatch-approve {
            background: linear-gradient(135deg, #15803d 0%, #16a34a 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 11px 28px;
            font-size: 15px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .btn-dispatch-approve:hover:not(:disabled) {
            background: linear-gradient(135deg, #166534 0%, #15803d 100%);
            transform: translateY(-1px);
        }
        .btn-dispatch-approve:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
    </style>

    <!-- 1. TOP APPLICANT & FILE SNAPSHOT -->
    <div class="file-snapshot-card">
        <div class="snapshot-top-row">
            <div class="file-id-badge">
                <i class="fa-solid fa-stamp"></i>
                <span>Executive Sanction Docket: {{ $application->application_no }}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1">
                    <i class="fa-solid fa-award me-1"></i> Final Sanction Stage
                </span>
                <span class="badge {{ ($application->priority ?? 'normal') === 'urgent' ? 'bg-danger' : 'bg-secondary' }} px-2 py-1 text-uppercase" style="font-size: 10px;">
                    {{ $application->priority ?? 'Normal Priority' }}
                </span>
            </div>
        </div>

        <div class="snapshot-metrics-grid">
            <div class="snapshot-item">
                <span class="snapshot-label"><i class="fa-solid fa-user me-1"></i> Allottee / Applicant</span>
                <span class="snapshot-val">{{ $application->allottee->name ?? 'N/A' }}</span>
            </div>
            <div class="snapshot-item">
                <span class="snapshot-label"><i class="fa-solid fa-building me-1"></i> Property / Scheme</span>
                <span class="snapshot-val">
                    {{ $application->allottee->property_no ?? ($application->property->property_code ?? 'Plot/Flat') }}
                    <small class="text-muted">({{ $application->allottee->scheme->name ?? ($application->scheme->name ?? 'General Scheme') }})</small>
                </span>
            </div>
            <div class="snapshot-item">
                <span class="snapshot-label"><i class="fa-solid fa-location-dot me-1"></i> Division / Sub-Division</span>
                <span class="snapshot-val">{{ $application->allottee->division->name ?? ($application->division->name ?? 'HQ / Allottee Cell') }}</span>
            </div>
            <div class="snapshot-item">
                <span class="snapshot-label"><i class="fa-regular fa-calendar-check me-1"></i> File Initiated</span>
                <span class="snapshot-val">{{ $application->created_at ? $application->created_at->format('d M, Y') : now()->format('d M, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- 2. WORKFLOW STEPPER (PROGRESSION ROADMAP) -->
    <div class="action-stepper-card">
        <div class="action-stepper-title">
            <i class="fa-solid fa-list-check text-success"></i> Sanction Execution Roadmap (स्वीकृति प्रक्रिया निर्देश)
        </div>
        <div class="action-stepper-track">
            <div class="step-track-item active" id="track-step-1">
                <span class="step-track-num">1</span>
                <div class="step-track-text">
                    <h6>Check Prereqs</h6>
                    <p>Verify all clearances</p>
                </div>
            </div>
            <div class="step-track-item" id="track-step-2">
                <span class="step-track-num">2</span>
                <div class="step-track-text">
                    <h6>Sanction Order</h6>
                    <p>Draft official approval</p>
                </div>
            </div>
            <div class="step-track-item" id="track-step-3">
                <span class="step-track-num">3</span>
                <div class="step-track-text">
                    <h6>Dual Authentication</h6>
                    <p>Internal PIN & OTP</p>
                </div>
            </div>
            <div class="step-track-item" id="track-step-4">
                <span class="step-track-num">4</span>
                <div class="step-track-text">
                    <h6>Digital Seal</h6>
                    <p>Grant final sanction</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. MAIN ACTION CONTAINER -->
    <div class="action-main-card">
        <div class="action-card-header">
            <div class="action-header-left">
                <div class="action-header-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <h5 class="action-header-title">Approve & Grant Official Allotment Sanction</h5>
                    <p class="action-header-subtitle">Execute administrative approval and seal official allotment record</p>
                </div>
            </div>
            <div class="action-header-actions">
                <button type="button" onclick="openQrModal()" class="btn btn-sm btn-light text-success fw-bold px-3 py-2 shadow-sm">
                    <i class="fa-solid fa-mobile-screen-button me-1"></i> Scan & Attach Photos
                </button>
                <a href="{{ route($routePrefix . '.applications.show', $application) }}" class="btn-header-back">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Review
                </a>
            </div>
        </div>

        <div class="action-card-body">
            @if(session('error'))
            <div class="alert alert-danger mb-4">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
            </div>
            @endif

            <div class="alert alert-success d-flex align-items-center mb-4 border-success">
                <i class="fa-solid fa-stamp fs-3 me-3 text-success"></i>
                <div>
                    <strong>Administrative Notice:</strong> You are granting final executive sanction for this application. Once approved, the official allotment / sanction deed will be generated with a cryptographic digital seal.
                </div>
            </div>

            <form action="{{ route($routePrefix . '.applications.action', $application) }}" method="POST" id="approveActionForm">
                @csrf
                <input type="hidden" name="action_type" value="approve">

                <!-- SECTION 1: SECRETARIAT SANCTION NOTING SHEET -->
                <div class="secretariat-noting-box">
                    <div class="noting-box-header">
                        <div class="noting-title">
                            <i class="fa-solid fa-file-signature"></i>
                            <span>1. Executive Sanction Order Noting (अंतिम स्वीकृति आदेश एवं टिप्पणी) <span class="text-danger">*</span></span>
                        </div>
                        <!-- Language Selector -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted fw-bold">Typing Script:</span>
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check font-family-selector" name="font_family" id="font_normal_hindi_note" value="normalhindi" checked>
                                <label class="btn btn-outline-success" for="font_normal_hindi_note">Normal Hindi</label>

                                <input type="radio" class="btn-check font-family-selector" name="font_family" id="font_english_note" value="english">
                                <label class="btn btn-outline-success" for="font_english_note">English</label>

                                <input type="radio" class="btn-check font-family-selector" name="font_family" id="font_hindi_note" value="krutidev">
                                <label class="btn btn-outline-success" for="font_hindi_note">Kruti Dev</label>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Approval Noting Chips -->
                    <div class="quick-noting-chips">
                        <span class="small text-muted me-1 fw-bold"><i class="fa-solid fa-bolt text-warning"></i> Sanction Templates:</span>
                        <button type="button" class="noting-chip" onclick="insertNotingTemplate('विहित नियमावली के अंतर्गत समस्त अभिलेखों, शुल्कों एवं स्थल प्रतिवेदन की पुष्टि उपरांत यह आवंटन अंतिम रूप से स्वीकृत किया जाता है।')">
                            + पूर्ण संतुष्टि उपरांत स्वीकृति
                        </button>
                        <button type="button" class="noting-chip" onclick="insertNotingTemplate('सक्षम प्राधिकार द्वारा प्रदत्त अनुमोदन के आलोक में विलेख निष्पादन एवं अग्रिम कार्रवाई हेतु स्वीकृति आदेश निर्गत किया जाता है।')">
                            + सक्षम अनुमोदन से स्वीकृति
                        </button>
                        <button type="button" class="noting-chip" onclick="insertNotingTemplate('आवंटिती द्वारा समस्त देय शुल्कों का भुगतान पूर्ण कर लिया गया है, अतः अनापत्ति एवं पट्टा निर्गमन स्वीकृत।')">
                            + शुल्क पूर्णता पर स्वीकृति
                        </button>
                    </div>

                    <div class="p-3">
                        <textarea id="summernote" name="remarks" required></textarea>
                    </div>
                </div>

                <!-- SECTION 2: DUAL FACTOR AUTHENTICATION -->
                @if(empty(auth()->user()->internal_password))
                    <div class="alert alert-danger d-flex align-items-center justify-content-between p-3 my-4">
                        <div>
                            <i class="fa-solid fa-triangle-exclamation fs-4 me-2"></i>
                            <strong>Internal Authorization Password Required:</strong> You must configure your internal password before executing approvals.
                        </div>
                        @php
                            $profileRoute = auth()->user()->role === 'user' ? route('profile') : route(auth()->user()->role . '.profile');
                        @endphp
                        <a href="{{ $profileRoute }}" class="btn btn-danger btn-sm fw-bold">Set Password in Profile</a>
                    </div>
                @else
                    <!-- Gate 1: Internal Password -->
                    <div class="password-gate-card">
                        <div class="password-gate-title">
                            <i class="fa-solid fa-key text-success"></i>
                            <span>Security Gate 1: Internal Officer Password <span class="text-danger">*</span></span>
                        </div>
                        <p class="small text-muted mb-2">Confirm your internal authorization password to unlock the approval seal.</p>
                        <div class="input-group" style="max-width: 480px;">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-lock text-muted"></i></span>
                            <input type="password" name="internal_password" id="internalPasswordInput" class="form-control"
                                   placeholder="Enter internal officer password" required autocomplete="current-password">
                        </div>
                        @if(isset($errors) && $errors->has('internal_password'))
                        <div class="text-danger small mt-1">{{ $errors->first('internal_password') }}</div>
                        @endif
                    </div>

                    <!-- Gate 2: Global OTP Verification -->
                    <div class="mt-4">
                        <x-global-otp-verify purpose="approve_application" buttonText="Send OTP to Authorize Approval" />
                    </div>

                    <!-- SECTION 3: DISPATCH & SUBMIT -->
                    <div class="dispatch-action-footer">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-dark border p-2">
                                <i class="fa-solid fa-certificate text-success me-1"></i> Sanctioning Authority: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->roleRelation->name ?? auth()->user()->user_type }})
                            </span>
                        </div>

                        <div>
                            <input type="hidden" name="otp_verified" id="otp_verified_input_approve" value="0">
                            <button type="submit" id="approveSubmitBtn" class="btn-dispatch-approve" disabled>
                                <i class="fa-solid fa-stamp"></i>
                                <span>Execute Final Sanction & Approve</span>
                            </button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    @include('components.partials.summernote-editor')
    @include('components.partials.qr-scanner-modal')
</div>

<script>
    function insertNotingTemplate(text) {
        if (typeof $ !== 'undefined' && $('#summernote').length && typeof $('#summernote').summernote === 'function') {
            const current = $('#summernote').summernote('code');
            if (!current || current === '<p><br></p>' || current.trim() === '') {
                $('#summernote').summernote('code', '<p>' + text + '</p>');
            } else {
                $('#summernote').summernote('pasteHTML', '<p>' + text + '</p>');
            }
        } else {
            const ta = document.getElementById('summernote');
            if (ta) ta.value += (ta.value ? "\n" : "") + text;
        }

        const step1 = document.getElementById('track-step-1');
        const step2 = document.getElementById('track-step-2');
        if (step1) { step1.classList.remove('active'); step1.classList.add('completed'); }
        if (step2) { step2.classList.add('active'); }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('otpVerified:approve_application', function() {
            const btn = document.getElementById('approveSubmitBtn');
            if (btn) {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
            }
            const hiddenInput = document.getElementById('otp_verified_input_approve');
            if (hiddenInput) hiddenInput.value = '1';

            // Mark Stepper
            const step2 = document.getElementById('track-step-2');
            const step3 = document.getElementById('track-step-3');
            const step4 = document.getElementById('track-step-4');
            if (step2) { step2.classList.add('completed'); }
            if (step3) { step3.classList.remove('active'); step3.classList.add('completed'); }
            if (step4) { step4.classList.add('active', 'completed'); }
        });
    });
</script>
