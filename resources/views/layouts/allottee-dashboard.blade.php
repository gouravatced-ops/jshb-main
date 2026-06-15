{{-- resources/views/layouts/allottee-dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Allottee Dashboard')</title>
    <meta name="description" content="Jharkhand State Housing Board | Allottee Portal" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(config('panel.faviconIcon')) }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/allottee/dashboard.css') }}">
    <style>
        .toast-container {
            z-index: 1100;
        }

        .upload-zone {
            border: 2px dashed #d1d5db;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            background: #f9fafb;
            transition: all 0.2s ease;
        }

        .upload-zone:hover {
            border-color: #198754;
            background: #f0fdf4;
        }

        .upload-icon {
            font-size: 40px;
            color: #198754;
        }

        .file-preview {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .preview-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            background: #eff6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #198754;
        }

        .menu-arrow {
            transition: transform 0.2s ease;
        }

        .menu-arrow.rotated {
            transform: rotate(180deg);
        }
    </style>
</head>

<body>
    {{-- TOPBAR --}}
    <header class="topbar">
        <div class="topbar-logo">
            <img src="{{ asset(config('panel.logo')) }}" alt="JESA Logo" loading="lazy">

            <div class="topbar-title">
                <span class="app-name">{{ config('panel.app_name') }}</span>
                <span class="app-subtitle">Applicant Portal Dashboard</span>
            </div>
        </div>

        <span class="topbar-spacer"></span>

        <a href="javascript:void(0)" onclick="goToAllottees()" class="topbar-badge">
            <i class="fa-solid fa-house me-1"></i> Dashboard
        </a>

        {{-- <span class="topbar-badge">
            <i class="fa-solid fa-circle-check me-1"></i> Applicant Account
        </span> --}}
        <a href="javascript:void(0)" onclick="closeTab()" class="topbar-badge-close">
            <i class="fa-solid fa-xmark me-1"></i> Close
        </a>

        <div class="topbar-avatar" title="{{ $allottee->allottee_name ?? 'User' }}">
            {{ strtoupper(substr($allottee->allottee_name ?? 'U', 0, 2)) }}
        </div>

    </header>

    <div class="page-wrap">
        {{-- SIDEBAR --}}
        <aside class="sidebar">
            {{-- <div class="sidebar-title">JSHB Menu</div> --}}

            @php
                $paymentOption = $allottee->payment_option;
            @endphp

            {{-- PROCESS MENUS --}}
            @foreach ($steps->groupBy('menu_key') as $menuKey => $menuSteps)
                @php
                    $menu = $menuSteps->first();

                    // MENU VISIBILITY CONDITIONS

                    // Hide Choose Payment Option if payment option already selected
                    if ($menuKey === 'choose-payment-option' && !is_null($paymentOption)) {
                        continue;
                    }

                    // Hide Allotment Cancellation if payment option selected
                    if ($menuKey === 'allotment-cancellation' && !is_null($paymentOption)) {
                        continue;
                    }

                    // Show Property Payment only for one_time
                    if ($menuKey === 'property-payment' && $paymentOption !== 'one_time') {
                        continue;
                    }

                    // Show EMI Management only for emi
                    if ($menuKey === 'emi-management' && $paymentOption !== 'emi') {
                        continue;
                    }

                    // show Final Calculation only for emi
                    if ($menuKey === 'final-calculation' && $paymentOption !== 'emi') {
                        continue;
                    }

                    // SIDEBAR STATES

                    $hasSubmenus = $menuSteps->whereNotNull('sub_menu_key')->count() > 0;

                    $collapseId = 'menu-' . Str::slug($menuKey);

                    $menuCompleted = $menuSteps->every(fn($step) => $step->status === 'completed');

                    $menuPending = $menuSteps->contains(fn($step) => $step->status === 'pending');

                    $menuLocked = $menuSteps->every(fn($step) => $step->status === 'locked');
                @endphp

                {{-- ============================= --}}
                {{-- MENU WITH SUBMENUS --}}
                {{-- ============================= --}}
                @if ($hasSubmenus)
                    <div class="sidebar-menu">

                        <button type="button" class="sidebar-menu-btn" data-bs-toggle="collapse"
                            data-bs-target="#{{ $collapseId }}">

                            <span class="menu-left">
                                <i class="{{ $menu->icons }}"></i>

                                <span>
                                    {{ str($menu->menu_key)->replace('-', ' ')->title() }}
                                </span>
                            </span>

                            <span class="d-flex align-items-center gap-2">

                                @if ($menuCompleted)
                                    <i class="fa-solid fa-circle-check text-success"></i>
                                @elseif($menuPending)
                                    <i class="fa-solid fa-clock text-warning"></i>
                                @elseif($menuLocked)
                                    <i class="fa-solid fa-lock"></i>
                                @endif

                                {{-- <i class="fa-solid fa-chevron-down menu-arrow"></i> --}}
                            </span>

                        </button>

                        <div id="{{ $collapseId }}" class="collapse show">

                            <div class="sidebar-submenu">

                                @foreach ($menuSteps as $step)
                                    @php
                                        $isActive = $currentStepNo == $step->step_no;
                                        $isLocked = $step->status === 'locked';
                                        $isCompleted = $step->status === 'completed';
                                        $isPending = $step->status === 'pending';
                                    @endphp

                                    <button type="button" class="sidebar-submenu-link {{ $isActive ? 'active' : '' }}"
                                        onclick="App.loadStep({{ $step->step_no }}, this)"
                                        {{ $isLocked ? 'disabled' : '' }}>

                                        <span class="submenu-icon">

                                            @if ($isCompleted)
                                                <i class="fa-solid fa-circle-check text-success"></i>
                                            @elseif($isPending)
                                                <i class="fa-solid fa-clock text-warning"></i>
                                            @elseif($isLocked)
                                                <i class="fa-solid fa-lock"></i>
                                            @endif

                                        </span>

                                        <span>{{ $step->title }}</span>

                                    </button>
                                @endforeach

                            </div>

                        </div>

                    </div>

                    {{-- ============================= --}}
                    {{-- SINGLE MENU --}}
                    {{-- ============================= --}}
                @else
                    @php
                        $step = $menuSteps->first();

                        $isActive = $currentStepNo == $step->step_no;
                        $isLocked = $step->status === 'locked';
                        $isCompleted = $step->status === 'completed';
                        $isPending = $step->status === 'pending';
                    @endphp

                    <button type="button" class="sidebar-link {{ $isActive ? 'active' : '' }}"
                        onclick="App.loadStep({{ $step->step_no }}, this)" {{ $isLocked ? 'disabled' : '' }}>

                        <span class="menu-left">

                            <i class="{{ $menu->icons }}"></i>

                            <span>
                                {{ str($menu->menu_key)->replace('-', ' ')->title() }}
                            </span>

                        </span>

                        <span>

                            @if ($isCompleted)
                                <i class="fa-solid fa-circle-check text-success"></i>
                            @elseif($isPending)
                                <i class="fa-solid fa-clock text-warning"></i>
                            @elseif($isLocked)
                                <i class="fa-solid fa-lock"></i>
                            @endif

                        </span>

                    </button>
                @endif
            @endforeach
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="main-content">
            <div id="dynamicContent">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- RE-UPLOAD MODAL --}}
    <div class="modal fade" id="reuploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:620px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reuploadModalTitle">
                        <i class="fa-solid fa-file-signature me-2 text-success"></i>
                        Upload Signed Document
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="signedDocumentForm">
                        <input type="hidden" id="documentId">
                        <input type="hidden" id="documentType">
                        <input type="hidden" id="allotteeId">
                        <input type="hidden" id="stepNoValue">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Document Name</label>
                                <input type="text" class="form-control" id="docTypeSelect" readonly
                                    style="background:#f8fafc; border:1px solid #dbe3ee; font-weight:600;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Issue Date</label>
                                <input type="date" class="form-control" id="docIssueDate"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Document Number</label>
                                <input type="text" class="form-control" id="docNumber"
                                    placeholder="Enter document reference number">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Upload Signed Copy</label>
                                <div id="uploadZone" class="upload-zone"
                                    onclick="document.getElementById('fileInput').click()">
                                    <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                    <div style="font-weight:600; margin-top:10px;">Click to upload signed document
                                    </div>
                                    <div style="font-size:13px; color:#198754; margin-top:6px;">PDF, JPG, PNG • Max 5
                                        MB</div>
                                </div>
                                <input type="file" id="fileInput" accept=".pdf,.jpg,.jpeg,.png"
                                    style="display:none" onchange="previewFile(this)">
                            </div>
                        </div>
                        <div id="filePreview" style="display:none; margin-top:20px;">
                            <div class="file-preview">
                                <div class="preview-icon" id="previewIcon"><i class="fa-solid fa-file"></i></div>
                                <div class="flex-grow-1">
                                    <div id="previewName" style="font-weight:600;">--</div>
                                    <div id="previewSize" style="font-size:13px; color:#198754;">--</div>
                                </div>
                                <div>
                                    <a href="#" target="_blank" id="previewLink"
                                        class="btn btn-sm btn-light"><i class="fa-solid fa-eye"></i></a>
                                    <button type="button" class="btn btn-sm btn-light" onclick="clearFile()"><i
                                            class="fa-solid fa-xmark"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="submitDocumentUpload()">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Upload Signed Copy
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- TOAST CONTAINER --}}
    <div class="position-fixed bottom-0 end-0 p-3 toast-container">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body"><i class="fa-solid fa-circle-check me-2"></i> Successfully!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
            </div>
        </div>
        <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body"><i class="fa-solid fa-circle-exclamation me-2"></i> <span
                        id="errorToastMsg">Operation failed</span></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    {{-- Payment Modal --}}
    <div class="modal fade" id="emiPaymentModal" tabindex="-1" aria-labelledby="emiPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emiPaymentModalLabel">Pay EMI</h5>
                    <button type="button" class="close" onclick="closeEmiModal()">&times;</button>
                </div>
                <form id="emiPaymentForm">
                    <input type="hidden" name="demand_id" id="modal_demand_id" value="">
                    <input type="hidden" name="payment_mode" value="gateway">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Amount (₹)</label>
                            <input type="number" step="0.01" min="1" name="amount" id="modal_amount"
                                class="form-control" required>
                            <small class="form-text text-muted">Enter the amount to pay for this EMI.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-ghost" onclick="closeEmiModal()">Cancel</button>
                        <button type="submit" class="btn-brand">Pay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        window.AppConfig = {
            currentStepNo: @json($currentStepNo ?? 1),
            routes: {
                overview: @json(route('admin.allottees.section', ['allottee' => $allottee, 'section' => 'overview'])),
                process: @json(route('admin.allottees.process.step', ['allottee' => $allottee, 'stepNo' => '__STEP__'])),
                uploadSigned: @json(route('admin.allottees.signed.document.uploads')),
                initialPayment: @json(route('admin.allottees.initial.payment.pay')),
                oneTimePayment: @json(route('admin.allottees.one-time-payment.pay')),
                emiProcessPayment: @json(route('admin.allottee.emi.process-payment', $allottee)),
            }
        };
    </script>
    <script src="{{ asset('js/allottee/dashboard.js') }}"></script>
    <script>
        function goToAllottees() {
            window.location.href = "{{ route('admin.allottees.index') }}";
        }

        function closeTab() {
            window.close();
        }
    </script>
</body>

</html>
