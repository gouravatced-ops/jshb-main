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
            border-left: 5px solid #0284c7;
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
            color: #0284c7;
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
            background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
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
            color: #0369a1;
        }
        .action-card-body {
            padding: 24px;
        }

        /* --- Secretariat Internal Noting Sheet --- */
        .secretariat-noting-box {
            background: #ffffff;
            border: 2px solid #bae6fd;
            border-radius: 10px;
            margin-top: 15px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(2, 132, 199, 0.05);
        }
        .noting-box-header {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-bottom: 1px solid #bae6fd;
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
            color: #0369a1;
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
            color: #0369a1;
            background: #e0f2fe;
            border: 1px solid #bae6fd;
            border-radius: 16px;
            padding: 4px 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .noting-chip:hover {
            background: #0284c7;
            color: #ffffff;
            border-color: #0284c7;
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
        .btn-dispatch-save-note {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 11px 28px;
            font-size: 15px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .btn-dispatch-save-note:hover {
            background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
            transform: translateY(-1px);
        }
    </style>

    <!-- 1. TOP APPLICANT & FILE SNAPSHOT -->
    <div class="file-snapshot-card">
        <div class="snapshot-top-row">
            <div class="file-id-badge">
                <i class="fa-solid fa-comment-dots"></i>
                <span>File Internal Note: {{ $application->application_no }}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1">
                    <i class="fa-solid fa-circle-info me-1"></i> Docket Record Only (No Workflow State Change)
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

    <!-- 2. MAIN ACTION CONTAINER -->
    <div class="action-main-card">
        <div class="action-card-header">
            <div class="action-header-left">
                <div class="action-header-icon">
                    <i class="fa-solid fa-feather-pointed"></i>
                </div>
                <div>
                    <h5 class="action-header-title">Add Internal File Note</h5>
                    <p class="action-header-subtitle">Record official observations, correspondence summary, or internal remarks without moving the file</p>
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

            <div class="alert alert-info d-flex align-items-center mb-4 border-info">
                <i class="fa-solid fa-info-circle fs-3 me-3 text-info"></i>
                <div>
                    <strong>Permanent Record Note:</strong> Notes added here are permanently chronologically stored in the application docket. The current workflow step and desk assignment will remain unchanged.
                </div>
            </div>

            <form action="{{ route($routePrefix . '.applications.action', $application) }}" method="POST" id="addNoteActionForm">
                @csrf
                <input type="hidden" name="action_type" value="add_note">

                <!-- SECTION 1: SECRETARIAT INTERNAL NOTING SHEET -->
                <div class="secretariat-noting-box">
                    <div class="noting-box-header">
                        <div class="noting-title">
                            <i class="fa-solid fa-pen-fancy"></i>
                            <span>Secretariat Internal Note / Observation (आंतरिक टिप्पणी) <span class="text-danger">*</span></span>
                        </div>
                        <!-- Language Selector -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted fw-bold">Typing Script:</span>
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check font-family-selector" name="font_family" id="font_normal_hindi_note" value="normalhindi" checked>
                                <label class="btn btn-outline-primary" for="font_normal_hindi_note">Normal Hindi</label>

                                <input type="radio" class="btn-check font-family-selector" name="font_family" id="font_english_note" value="english">
                                <label class="btn btn-outline-primary" for="font_english_note">English</label>

                                <input type="radio" class="btn-check font-family-selector" name="font_family" id="font_hindi_note" value="krutidev">
                                <label class="btn btn-outline-primary" for="font_hindi_note">Kruti Dev</label>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Internal Noting Chips -->
                    <div class="quick-noting-chips">
                        <span class="small text-muted me-1 fw-bold"><i class="fa-solid fa-bolt text-primary"></i> Note Templates:</span>
                        <button type="button" class="noting-chip" onclick="insertNotingTemplate('आवेदक से दूरभाष पर संपर्क कर लंबित मूल दस्तावेजों की मांग की गई।')">
                            + दूरभाष संपर्क विवरण
                        </button>
                        <button type="button" class="noting-chip" onclick="insertNotingTemplate('बैंक द्वारा चालान सत्यापन प्रतिवेदन प्राप्त हो चुका है, फाइल में सुरक्षित रखा गया।')">
                            + बैंक चालान सत्यापन प्राप्त
                        </button>
                        <button type="button" class="noting-chip" onclick="insertNotingTemplate('अवर प्रमंडल पदाधिकारी द्वारा भौतिक जांच पूर्ण कर ली गई है, अग्रिम आदेश प्रतीक्षित।')">
                            + जांच पूर्णता टिप्पणी
                        </button>
                    </div>

                    <div class="p-3">
                        <textarea id="summernote" name="remarks" required></textarea>
                    </div>
                </div>

                <!-- SECTION 2: DISPATCH & SUBMIT -->
                <div class="dispatch-action-footer">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark border p-2">
                            <i class="fa-solid fa-user-pen text-primary me-1"></i> Recorded by: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->roleRelation->name ?? auth()->user()->user_type }})
                        </span>
                    </div>

                    <div>
                        <button type="submit" id="saveNoteSubmitBtn" class="btn-dispatch-save-note">
                            <i class="fa-solid fa-bookmark"></i>
                            <span>Save Note to File Docket</span>
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
    }
</script>
