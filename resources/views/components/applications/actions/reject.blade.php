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
            border-left: 5px solid #dc2626;
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
            color: #dc2626;
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
            background: #fef2f2;
            border-color: #fca5a5;
            box-shadow: 0 2px 4px rgba(220, 38, 38, 0.1);
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
            background: #dc2626;
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
            background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
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
            color: #b91c1c;
        }
        .action-card-body {
            padding: 24px;
        }

        /* --- Secretariat Rejection Noting Sheet --- */
        .secretariat-noting-box {
            background: #ffffff;
            border: 2px solid #fca5a5;
            border-radius: 10px;
            margin-top: 15px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(220, 38, 38, 0.05);
        }
        .noting-box-header {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-bottom: 1px solid #fca5a5;
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
            color: #991b1b;
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
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fca5a5;
            border-radius: 16px;
            padding: 4px 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .noting-chip:hover {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
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
        .btn-dispatch-reject {
            background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 11px 28px;
            font-size: 15px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .btn-dispatch-reject:hover:not(:disabled) {
            background: linear-gradient(135deg, #991b1b 0%, #b91c1c 100%);
            transform: translateY(-1px);
        }
        .btn-dispatch-reject:disabled {
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
                <i class="fa-solid fa-ban"></i>
                <span>Rejection Docket: {{ $application->application_no }}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Irreversible Action
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
            <i class="fa-solid fa-list-check text-danger"></i> Rejection Execution Roadmap (अस्वीकृति निर्देश)
        </div>
        <div class="action-stepper-track">
            <div class="step-track-item active" id="track-step-1">
                <span class="step-track-num">1</span>
                <div class="step-track-text">
                    <h6>Review Non-Compliance</h6>
                    <p>Verify disqualification</p>
                </div>
            </div>
            <div class="step-track-item" id="track-step-2">
                <span class="step-track-num">2</span>
                <div class="step-track-text">
                    <h6>Rejection Order</h6>
                    <p>Formal legal grounds</p>
                </div>
            </div>
            <div class="step-track-item" id="track-step-3">
                <span class="step-track-num">3</span>
                <div class="step-track-text">
                    <h6>Identity Verification</h6>
                    <p>Two-factor OTP gate</p>
                </div>
            </div>
            <div class="step-track-item" id="track-step-4">
                <span class="step-track-num">4</span>
                <div class="step-track-text">
                    <h6>Execute Rejection</h6>
                    <p>Close file permanently</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. MAIN ACTION CONTAINER -->
    <div class="action-main-card">
        <div class="action-card-header">
            <div class="action-header-left">
                <div class="action-header-icon">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div>
                    <h5 class="action-header-title">Reject Application Docket</h5>
                    <p class="action-header-subtitle">Provide comprehensive legal grounds and execute formal rejection of application</p>
                </div>
            </div>
            <div class="action-header-actions">
                <a href="{{ route($routePrefix . '.applications.show', $application) }}" class="btn-header-back">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Review
                </a>
            </div>
        </div>

        <div class="action-card-body">
            <div class="alert alert-danger d-flex align-items-center mb-4 border-danger">
                <i class="fa-solid fa-triangle-exclamation fs-3 me-3 text-danger"></i>
                <div>
                    <strong>Permanent Action Warning:</strong> Rejection is an irreversible administrative decision. This application will be permanently closed and notice will be served to the applicant.
                </div>
            </div>

            <form action="{{ route($routePrefix . '.applications.action', $application) }}" method="POST" id="rejectActionForm">
                @csrf
                <input type="hidden" name="action_type" value="reject">

                <!-- SECTION 1: SECRETARIAT REJECTION NOTING SHEET -->
                <div class="secretariat-noting-box">
                    <div class="noting-box-header">
                        <div class="noting-title">
                            <i class="fa-solid fa-file-excel"></i>
                            <span>1. Formal Rejection Grounds & Order (अस्वीकृति आदेश एवं विधिक कारण) <span class="text-danger">*</span></span>
                        </div>
                        <!-- Language Selector -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted fw-bold">Typing Script:</span>
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check font-family-selector" name="font_family" id="font_normal_hindi_note" value="normalhindi" checked>
                                <label class="btn btn-outline-danger" for="font_normal_hindi_note">Normal Hindi</label>

                                <input type="radio" class="btn-check font-family-selector" name="font_family" id="font_english_note" value="english">
                                <label class="btn btn-outline-danger" for="font_english_note">English</label>

                                <input type="radio" class="btn-check font-family-selector" name="font_family" id="font_hindi_note" value="krutidev">
                                <label class="btn btn-outline-danger" for="font_hindi_note">Kruti Dev</label>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Rejection Grounds Chips -->
                    <div class="quick-noting-chips">
                        <span class="small text-muted me-1 fw-bold"><i class="fa-solid fa-bolt text-danger"></i> Rejection Grounds:</span>
                        <button type="button" class="noting-chip" onclick="insertNotingTemplate('बोर्ड की विहित आवंटन नियमावली एवं विज्ञापन शर्तों के अनुरूप पात्रता मापदंड पूर्ण न होने के कारण यह आवेदन निरस्त किया जाता है।')">
                            + पात्रता मापदंड अपूर्ण
                        </button>
                        <button type="button" class="noting-chip" onclick="insertNotingTemplate('विहित समय-सीमा एवं नोटिस निर्गमन के उपरांत भी आवेदक द्वारा अनिवार्य अभिलेख/बकाया राशि जमा नहीं की गई, अतः निरस्त।')">
                            + अनुपालन न करने पर निरस्त
                        </button>
                        <button type="button" class="noting-chip" onclick="insertNotingTemplate('भौतिक स्थल सत्यापन एवं जांच में असत्य/भ्रामक सूचना पाए जाने के फलस्वरूप आवेदन तात्कालिक प्रभाव से खारिज किया जाता है।')">
                            + भ्रामक सूचना पर खारिज
                        </button>
                    </div>

                    <div class="p-3">
                        <textarea id="summernote" name="remarks" required></textarea>
                    </div>
                </div>

                <!-- SECTION 2: IDENTITY AUTHENTICATION GATE (GLOBAL OTP) -->
                <div class="mt-4">
                    <x-global-otp-verify purpose="reject_application" buttonText="Send OTP to Authorize Rejection" />
                </div>

                <!-- SECTION 3: DISPATCH & SUBMIT -->
                <div class="dispatch-action-footer">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark border p-2">
                            <i class="fa-solid fa-user-shield text-danger me-1"></i> Authorized Officer: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->roleRelation->name ?? auth()->user()->user_type }})
                        </span>
                    </div>

                    <div>
                        <input type="hidden" name="otp_verified" id="otp_verified_input" value="0">
                        <button type="submit" id="rejectSubmitBtn" class="btn-dispatch-reject" disabled>
                            <i class="fa-solid fa-times-circle"></i>
                            <span>Execute Rejection & Close Docket</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('components.partials.summernote-editor')
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
        document.addEventListener('otpVerified:reject_application', function() {
            const btn = document.getElementById('rejectSubmitBtn');
            if (btn) {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
            }
            const hiddenInput = document.getElementById('otp_verified_input');
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
