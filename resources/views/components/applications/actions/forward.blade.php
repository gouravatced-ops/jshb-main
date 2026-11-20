@props(['application', 'routePrefix', 'forwardOptions' => [], 'isSiteVerificationStep' => false, 'isSiteVerificationCompleted' => false])

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
            border-left: 5px solid #0d47a1;
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
            color: #0d47a1;
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
            background: #eff6ff;
            border-color: #93c5fd;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.1);
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
            background: #0d47a1;
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
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
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
            background: rgba(255, 255, 255, 0.18);
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
            color: rgba(255, 255, 255, 0.85);
        }
        .action-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
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
            color: #0d47a1;
        }
        .action-card-body {
            padding: 24px;
        }

        /* --- Recipient Selection Cards --- */
        .section-heading-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
        }
        .section-title-text {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .forward-card-radio {
            display: none;
        }
        .forward-card-label {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.25s ease;
            background: #ffffff;
            margin-bottom: 0;
            position: relative;
            overflow: hidden;
            width: 100%;
            height: 100%;
        }
        .forward-card-label:hover {
            border-color: #93c5fd;
            background: #f8fafc;
            transform: translateY(-1px);
        }
        .forward-card-radio:checked + .forward-card-label {
            border-color: #0d47a1;
            background: #f0f7ff;
            box-shadow: 0 4px 12px rgba(13, 71, 161, 0.12);
        }
        .forward-card-radio:checked + .forward-card-label::after {
            content: '\f058';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: #0d47a1;
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 22px;
        }
        .officer-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
            color: #475569;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        .forward-card-radio:checked + .forward-card-label .officer-avatar {
            background: #0d47a1;
            color: #ffffff;
        }
        .officer-details h6 {
            margin: 0 0 3px 0;
            font-size: 14.5px;
            color: #0f172a;
            font-weight: 700;
        }
        .officer-role-tag {
            display: inline-block;
            font-size: 11.5px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
            background: #f1f5f9;
            color: #475569;
            margin-bottom: 4px;
        }
        .officer-email-text {
            margin: 0;
            font-size: 12px;
            color: #64748b;
        }

        /* --- Secretariat Green Noting Sheet --- */
        .secretariat-noting-box {
            background: #ffffff;
            border: 2px solid #a7f3d0;
            border-radius: 10px;
            margin-top: 24px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(16, 185, 129, 0.05);
        }
        .noting-box-header {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-bottom: 1px solid #a7f3d0;
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
            color: #065f46;
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
            color: #065f46;
            background: #e6f4ea;
            border: 1px solid #a7f3d0;
            border-radius: 16px;
            padding: 4px 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .noting-chip:hover {
            background: #065f46;
            color: #ffffff;
            border-color: #065f46;
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
        .btn-dispatch-submit {
            background: linear-gradient(135deg, #15803d 0%, #16a34a 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 11px 26px;
            font-size: 14.5px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .btn-dispatch-submit:hover:not(:disabled) {
            background: linear-gradient(135deg, #166534 0%, #15803d 100%);
            transform: translateY(-1px);
        }
        .btn-dispatch-submit:disabled {
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
                <i class="fa-solid fa-folder-closed"></i>
                <span>Application Docket: {{ $application->application_no }}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1">
                    <i class="fa-solid fa-code-branch me-1"></i> Current Step: {{ $application->currentStep->step_name ?? 'In Review' }}
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
            <i class="fa-solid fa-list-check text-primary"></i> Action Progression Guide (अगला चरण निर्देश)
        </div>
        <div class="action-stepper-track">
            <div class="step-track-item active" id="track-step-1">
                <span class="step-track-num">1</span>
                <div class="step-track-text">
                    <h6>Select Authority</h6>
                    <p>Choose next officer</p>
                </div>
            </div>
            <div class="step-track-item" id="track-step-2">
                <span class="step-track-num">2</span>
                <div class="step-track-text">
                    <h6>Secretariat Noting</h6>
                    <p>Draft official remarks</p>
                </div>
            </div>
            <div class="step-track-item" id="track-step-3">
                <span class="step-track-num">3</span>
                <div class="step-track-text">
                    <h6>Identity Verification</h6>
                    <p>Authenticate via OTP</p>
                </div>
            </div>
            <div class="step-track-item" id="track-step-4">
                <span class="step-track-num">4</span>
                <div class="step-track-text">
                    <h6>Dispatch & Seal</h6>
                    <p>Sign & forward docket</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. MAIN ACTION CONTAINER -->
    <div class="action-main-card">
        <div class="action-card-header">
            <div class="action-header-left">
                <div class="action-header-icon">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
                <div>
                    <h5 class="action-header-title">Forward Application Docket</h5>
                    <p class="action-header-subtitle">Transmit file to the next authorized desk with official secretariat noting</p>
                </div>
            </div>
            <div class="action-header-actions">
                <button type="button" onclick="openQrModal()" class="btn btn-sm btn-light text-primary fw-bold px-3 py-2 shadow-sm">
                    <i class="fa-solid fa-mobile-screen-button me-1"></i> Scan & Attach Photos
                </button>
                <a href="{{ route($routePrefix . '.applications.show', $application) }}" class="btn-header-back">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Review
                </a>
            </div>
        </div>

        <div class="action-card-body">
            @if(isset($errors) && $errors->any())
            <div class="alert alert-danger mb-4">
                <h6 class="fw-bold mb-2"><i class="fa-solid fa-circle-exclamation me-1"></i> Please resolve the following errors:</h6>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Site Verification Prerequisite Alert -->
            @if(isset($isSiteVerificationStep) && $isSiteVerificationStep)
                @if($isSiteVerificationCompleted)
                <div class="alert alert-success d-flex align-items-center mb-4 border-success">
                    <i class="fa-solid fa-circle-check fs-4 me-3 text-success"></i>
                    <div>
                        <strong>Site Inspection Cleared:</strong> Site verification report has been successfully prepared and uploaded. You may forward the file.
                    </div>
                </div>
                @else
                <div class="alert alert-warning d-flex align-items-center mb-4 border-warning">
                    <i class="fa-solid fa-triangle-exclamation fs-4 me-3 text-warning"></i>
                    <div>
                        <strong>Site Inspection Pending:</strong> Site verification is mandatory for this step. Please complete inspection from the "Site Verification" tab before dispatch.
                    </div>
                </div>
                @endif
            @endif

            <form action="{{ route($routePrefix . '.applications.action', $application) }}" method="POST" id="forwardActionForm">
                @csrf
                <input type="hidden" name="action_type" value="forward">

                <!-- SECTION 1: RECIPIENT SELECTION -->
                <div class="mb-4">
                    <div class="section-heading-box">
                        <span class="section-title-text">
                            <i class="fa-solid fa-user-check text-primary"></i> 1. Select Forwarding Authority / Officer <span class="text-danger">*</span>
                        </span>
                        <span class="small text-muted">Click an officer card to select destination</span>
                    </div>

                    @if(isset($approvedBypass) && $approvedBypass)
                    <div class="alert alert-success py-2 px-3 mb-3 small d-flex align-items-center gap-2">
                        <i class="fa-solid fa-badge-check text-success fs-5"></i>
                        <span><strong>Bypass Approved:</strong> Your skip-level bypass to <strong>{{ $approvedBypass->targetStep->step_name ?? 'Higher Authority' }}</strong> was sanctioned.</span>
                    </div>
                    @endif

                    @if(!empty($forwardOptions))
                    <div class="forward-cards-container">
                        @foreach($forwardOptions as $index => $option)
                            @if($index > 0 && count($forwardOptions[0]['engineers']) == 0)
                                @break
                            @endif

                            @if($index == 0)
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-primary text-uppercase px-3 py-1" style="font-size: 11px; letter-spacing: 0.5px;">
                                        <i class="fa-solid fa-arrow-turn-down-right me-1"></i> Sequential Next Step: {{ $option['step']->step_name }} ({{ $option['step']->role->name ?? 'Role' }})
                                    </span>
                                </div>
                            @elseif($index == 1)
                                @if(!isset($approvedBypass) || !$approvedBypass)
                                <div class="my-3">
                                    <div class="p-3 rounded bg-warning-subtle border border-warning">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="requestBypassToggle" name="is_bypass_request" value="1">
                                            <label class="form-check-label fw-bold text-dark" for="requestBypassToggle">
                                                <i class="fa-solid fa-forward-step text-warning me-1"></i> Request Skip-Level Workflow Bypass (Direct to Higher Authority)
                                            </label>
                                        </div>
                                        <p class="small text-muted mb-0 mt-1 ps-4">
                                            Enable this only if the immediate sequential officer is on leave or unavailable. Requires Admin sanction. Pending requests auto-approve after <strong>24 hours</strong>.
                                        </p>
                                    </div>
                                </div>
                                <div id="bypassOptionsContainer" style="display: none; padding-left: 14px; border-left: 3px solid #f59e0b; margin-bottom: 20px;">
                                @else
                                <div id="bypassOptionsContainer" style="display: block; padding-left: 14px; border-left: 3px solid #10b981; margin-bottom: 20px;">
                                @endif
                                <div class="mb-2">
                                    <span class="badge bg-warning text-dark text-uppercase px-3 py-1" style="font-size: 11px;">
                                        <i class="fa-solid fa-angles-right me-1"></i> Bypass Step: {{ $option['step']->step_name }} ({{ $option['step']->role->name ?? 'Role' }})
                                    </span>
                                </div>
                            @else
                                <div class="mb-2">
                                    <span class="badge bg-secondary text-uppercase px-3 py-1" style="font-size: 11px;">
                                        <i class="fa-solid fa-angles-right me-1"></i> Alternate Step: {{ $option['step']->step_name }} ({{ $option['step']->role->name ?? 'Role' }})
                                    </span>
                                </div>
                            @endif

                            <div class="row g-3 mb-3">
                                @if(count($option['engineers']) > 0)
                                    @foreach($option['engineers'] as $engineer)
                                    <div class="col-md-6 col-lg-4">
                                        <input type="radio" name="forward_to_user" id="fwd_{{ $engineer->id }}_{{ $option['step']->id }}"
                                               value="{{ $engineer->id }}|{{ $option['step']->id }}" class="forward-card-radio" required
                                               onchange="onOfficerSelected()">
                                        <label for="fwd_{{ $engineer->id }}_{{ $option['step']->id }}" class="forward-card-label">
                                            <div class="officer-avatar">
                                                {{ strtoupper(substr($engineer->name, 0, 1)) }}
                                            </div>
                                            <div class="officer-details">
                                                <h6>{{ $engineer->name }}</h6>
                                                <span class="officer-role-tag">
                                                    <i class="fa-regular fa-id-badge me-1"></i> {{ $option['step']->role->name ?? 'Designated Role' }}
                                                </span>
                                                <p class="officer-email-text">
                                                    <i class="fa-regular fa-envelope me-1"></i> {{ $engineer->email ?? 'officer@jshb.gov.in' }}
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-warning py-3 px-4 rounded border-warning">
                                            <i class="fa-solid fa-user-xmark me-2 text-danger"></i>
                                            <strong>No Officer Assigned:</strong> No user currently holds the role <strong>{{ $option['step']->role->name ?? 'Role' }}</strong> in this jurisdiction. Please assign an officer in Administration.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        @if(count($forwardOptions) > 1 && count($forwardOptions[0]['engineers']) > 0)
                            </div> <!-- End bypassOptionsContainer -->

                            <!-- Bypass Reason Field -->
                            <div id="bypassReasonContainer" style="display: none;" class="mb-4">
                                <label class="form-label fw-bold text-dark small">
                                    Administrative Justification for Skip-Level Bypass <span class="text-danger">*</span>
                                </label>
                                <textarea name="bypass_reason" id="bypass_reason" class="form-control" rows="3"
                                          placeholder="State clearly why the regular step is being skipped (e.g. Officer on medical leave, urgent cabinet directive)..."></textarea>
                            </div>
                        @endif
                    </div>
                    @else
                    <div class="alert alert-warning py-3 px-4">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> No active workflow routes configured for forwarding this file.
                    </div>
                    @endif
                </div>

                <!-- SECTION 2: SECRETARIAT GREEN NOTING SHEET -->
                <div class="secretariat-noting-box">
                    <div class="noting-box-header">
                        <div class="noting-title">
                            <i class="fa-solid fa-pen-nib"></i>
                            <span>2. Official Secretariat Green Noting (हरित टिप्पणी पत्र) <span id="remarksRequiredAsterisk" class="text-danger">*</span></span>
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

                    <!-- Quick Standard Noting Chips -->
                    <div class="quick-noting-chips">
                        <span class="small text-muted me-1 fw-bold"><i class="fa-solid fa-bolt text-warning"></i> Quick Templates:</span>
                        <button type="button" class="noting-chip" onclick="insertNotingTemplate('दस्तावेज़ों एवं संलग्नकों का विधिवत परीक्षण किया गया, फाइल अग्रेतर आदेशार्थ सादर अग्रेषित।')">
                            + परीक्षण उपरांत अग्रेषित
                        </button>
                        <button type="button" class="noting-chip" onclick="insertNotingTemplate('स्थल सत्यापन प्रतिवेदन एवं माप पुस्तिका संलग्न कर आवश्यक कार्रवाई हेतु प्रस्तुत।')">
                            + स्थल सत्यापन संलग्न
                        </button>
                        <button type="button" class="noting-chip" onclick="insertNotingTemplate('आवेदक द्वारा समस्त वांछित अभिलेख समर्पित कर दिए गए हैं, संस्तुति सहित अग्रेषित।')">
                            + संस्तुति सहित अग्रेषित
                        </button>
                        <button type="button" class="noting-chip" onclick="insertNotingTemplate('सक्षम प्राधिकारी के अवलोकनार्थ एवं अंतिम आदेशार्थ सादर समर्पित।')">
                            + सक्षम प्राधिकारी आदेशार्थ
                        </button>
                    </div>

                    <div class="p-3">
                        <textarea id="summernote" name="remarks" required></textarea>
                        <div class="small text-muted mt-2 d-flex align-items-center justify-content-between">
                            <span><i class="fa-solid fa-shield-halved text-success me-1"></i> Recorded under the IT Act as an official digital noting seal.</span>
                            <span id="remarksOptionalText" style="display: none;" class="text-warning fw-bold">* Remarks optional for bypass request.</span>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: IDENTITY AUTHENTICATION GATE (GLOBAL OTP) -->
                <div class="mt-4">
                    <x-global-otp-verify purpose="forward_application" buttonText="Send OTP to Authorize & Forward" />
                </div>

                <!-- SECTION 4: DISPATCH & SUBMIT -->
                <div class="dispatch-action-footer">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark border p-2">
                            <i class="fa-solid fa-fingerprint text-primary me-1"></i> Signed by: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->roleRelation->name ?? auth()->user()->user_type }})
                        </span>
                    </div>

                    <div>
                        <input type="hidden" name="otp_verified" id="otp_verified_input" value="0">
                        @php
                            $isForwardDisabled = (isset($isSiteVerificationStep) && $isSiteVerificationStep && !$isSiteVerificationCompleted) || (!empty($forwardOptions) && count($forwardOptions[0]['engineers']) == 0);
                        @endphp
                        <button type="submit" id="forwardSubmitBtn" class="btn-dispatch-submit" disabled data-originally-disabled="{{ $isForwardDisabled ? 'true' : 'false' }}">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span id="submitBtnLabel">Submit Noting & Forward Docket</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('components.partials.summernote-editor')
    @include('components.partials.qr-scanner-modal')
</div>

<script>
    // Update visual stepper track on user interaction
    function onOfficerSelected() {
        const step1 = document.getElementById('track-step-1');
        const step2 = document.getElementById('track-step-2');
        if (step1) { step1.classList.remove('active'); step1.classList.add('completed'); }
        if (step2) { step2.classList.add('active'); }
    }

    // Insert standard template into Summernote
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

        const step2 = document.getElementById('track-step-2');
        const step3 = document.getElementById('track-step-3');
        if (step2) { step2.classList.remove('active'); step2.classList.add('completed'); }
        if (step3) { step3.classList.add('active'); }
    }

    // Bypass toggle handler
    document.addEventListener('DOMContentLoaded', function() {
        const bypassToggle = document.getElementById('requestBypassToggle');
        if (bypassToggle) {
            bypassToggle.addEventListener('change', function() {
                const container = document.getElementById('bypassOptionsContainer');
                const reasonContainer = document.getElementById('bypassReasonContainer');
                const reasonInput = document.getElementById('bypass_reason');
                const submitBtn = document.getElementById('forwardSubmitBtn');
                const submitBtnLabel = document.getElementById('submitBtnLabel');

                const summernote = document.getElementById('summernote');
                const asterisk = document.getElementById('remarksRequiredAsterisk');
                const optText = document.getElementById('remarksOptionalText');

                if (this.checked) {
                    if (container) container.style.display = 'block';
                    if (reasonContainer) reasonContainer.style.display = 'block';
                    if (reasonInput) reasonInput.setAttribute('required', 'required');
                    if (submitBtnLabel) submitBtnLabel.innerText = 'Submit Bypass Request';
                    if (submitBtn) submitBtn.style.background = 'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)';

                    if (summernote) summernote.removeAttribute('required');
                    if (asterisk) asterisk.style.display = 'none';
                    if (optText) optText.style.display = 'inline';
                } else {
                    if (container) container.style.display = 'none';
                    if (reasonContainer) reasonContainer.style.display = 'none';
                    if (reasonInput) reasonInput.removeAttribute('required');
                    if (submitBtnLabel) submitBtnLabel.innerText = 'Submit Noting & Forward Docket';
                    if (submitBtn) submitBtn.style.background = 'linear-gradient(135deg, #15803d 0%, #16a34a 100%)';

                    if (summernote) summernote.setAttribute('required', 'required');
                    if (asterisk) asterisk.style.display = 'inline';
                    if (optText) optText.style.display = 'none';

                    // Clear checked radios in bypass
                    if (container) {
                        container.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
                    }
                }
            });
        }

        // Listener when OTP is successfully verified
        document.addEventListener('otpVerified:forward_application', function() {
            const btn = document.getElementById('forwardSubmitBtn');
            if (btn && btn.getAttribute('data-originally-disabled') !== 'true') {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
            }
            const hiddenInput = document.getElementById('otp_verified_input');
            if (hiddenInput) hiddenInput.value = '1';

            // Mark Stepper
            const step3 = document.getElementById('track-step-3');
            const step4 = document.getElementById('track-step-4');
            if (step3) { step3.classList.remove('active'); step3.classList.add('completed'); }
            if (step4) { step4.classList.add('active', 'completed'); }
        });
    });
</script>
