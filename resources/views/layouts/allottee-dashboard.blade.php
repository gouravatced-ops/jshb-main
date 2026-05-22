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
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/allottee/dashboard.css') }}">
    <style>
        /* Critical inline styles for better perceived performance */
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
            {{ config('panel.app_name') }}
        </div>
        <span class="topbar-spacer"></span>
        <span class="topbar-badge"><i class="fa-solid fa-circle-check me-1"></i>Applicant Account</span>
        <div class="topbar-avatar" title="{{ $allottee->allottee_name ?? 'User' }}">
            {{ strtoupper(substr($allottee->allottee_name ?? 'U', 0, 2)) }}
        </div>
    </header>

    <div class="page-wrap">
        {{-- SIDEBAR --}}
        <aside class="sidebar">
            <div class="sidebar-title">JSHB Menu</div>

            @php
                $paymentOption = $allottee->payment_option;
            @endphp

            {{-- PROCESS MENUS --}}
            @foreach ($steps->groupBy('menu_key') as $menuKey => $menuSteps)
                @php
                    $menu = $menuSteps->first();

                    /*
            |--------------------------------------------------------------------------
            | MENU VISIBILITY CONDITIONS
            |--------------------------------------------------------------------------
            */

                    // Hide Choose Payment Option if payment option already selected
                    if ($menuKey === 'choose-payment-option' && !is_null($paymentOption)) {
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

                    /*
            |--------------------------------------------------------------------------
            | SIDEBAR STATES
            |--------------------------------------------------------------------------
            */

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
                                    <i class="fa-solid fa-lock text-muted"></i>
                                @endif

                                <i class="fa-solid fa-chevron-down menu-arrow"></i>
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
                                                <i class="fa-solid fa-lock text-muted"></i>
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
                                <i class="fa-solid fa-lock text-muted"></i>
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
                <div class="toast-body"><i class="fa-solid fa-circle-check me-2"></i> Document uploaded successfully!
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

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Global App namespace - prevents polluting global scope
        window.App = (function() {
            'use strict';

            // DOM Elements
            const elements = {
                dynamicContent: document.getElementById('dynamicContent'),
                reuploadModal: null,
                successToast: null,
                errorToast: null
            };

            // State
            let currentActiveButton = null;
            let currentStepNo = {{ $currentStepNo ?? 1 }};

            // Routes (injected from Laravel)
            const routes = {
                overview: @json(route('admin.allottees.section', ['allottee' => $allottee, 'section' => 'overview'])),
                process: @json(route('admin.allottees.process.step', ['allottee' => $allottee, 'stepNo' => '__STEP__'])),
                uploadSigned: @json(route('admin.allottees.signed.document.uploads')),
                initialPayment: @json(route('admin.allottees.initial.payment.pay'))
            };

            // Helper: Get step URL
            function getStepUrl(stepNo) {
                if (stepNo === 0 || stepNo === 'overview') {
                    return routes.overview;
                }
                return routes.process.replace('__STEP__', stepNo);
            }

            // Helper: Update URL hash
            function updateUrl(stepNo) {
                history.pushState(null, '', stepNo === 0 || stepNo === 'overview' ? '#overview' :
                    `#step-${stepNo}`);
            }

            // Helper: Set active menu button
            function setActiveMenu(element) {
                document.querySelectorAll('.sidebar-submenu-link, .sidebar-link').forEach(btn => btn.classList
                    .remove('active'));
                if (element) {
                    element.classList.add('active');
                    currentActiveButton = element;
                }
            }

            // Helper: Restore active menu (on error)
            function restoreActiveMenu() {
                if (currentActiveButton) {
                    currentActiveButton.classList.add('active');
                }
            }

            // Helper: Auto open parent collapse menu
            function autoOpenParentMenu(element) {
                if (!element) return;
                const collapse = element.closest('.collapse');
                if (collapse && !collapse.classList.contains('show')) {
                    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapse, {
                        toggle: false
                    });
                    bsCollapse.show();
                }
            }

            // UI: Show loading state
            function setLoading() {
                if (elements.dynamicContent) {
                    elements.dynamicContent.innerHTML = `
                    <div class="text-center py-5">
                        <div class="mb-3"><i class="fa-solid fa-spinner fa-spin fa-2x text-muted"></i></div>
                        <div class="text-muted">Loading section...</div>
                    </div>
                `;
                }
            }

            // UI: Show error state
            function showError(message = 'Failed to load section.') {
                if (elements.dynamicContent) {
                    elements.dynamicContent.innerHTML = `
                    <div class="alert alert-danger m-3">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> ${message}
                    </div>
                `;
                }
            }

            // UI: Show toast message
            function showToast(type, message) {
                if (type === 'success' && elements.successToast) {
                    const toast = bootstrap.Toast.getOrCreateInstance(elements.successToast);
                    toast.show();
                } else if (type === 'error' && elements.errorToast) {
                    const msgSpan = document.getElementById('errorToastMsg');
                    if (msgSpan) msgSpan.textContent = message;
                    const toast = bootstrap.Toast.getOrCreateInstance(elements.errorToast);
                    toast.show();
                }
            }

            // Main: Load step content
            async function loadStep(stepNo, element = null) {
                if (!elements.dynamicContent) return;

                // Handle string 'overview' or number 0
                const stepValue = stepNo === 'overview' ? 0 : parseInt(stepNo);

                setLoading();
                setActiveMenu(element);
                currentStepNo = stepValue;

                try {
                    const response = await fetch(getStepUrl(stepValue), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    });

                    if (!response.ok) throw new Error(`HTTP ${response.status}`);

                    elements.dynamicContent.innerHTML = await response.text();
                    updateUrl(stepValue);
                    autoOpenParentMenu(element);
                    initializePlugins();

                    // Re-attach event listeners to dynamically loaded buttons
                    attachDynamicEventListeners();

                    // Dispatch custom event for step loaded
                    window.dispatchEvent(new CustomEvent('step-loaded', {
                        detail: {
                            stepNo: stepValue
                        }
                    }));
                } catch (error) {
                    console.error('Load step error:', error);
                    showError('Failed to load section. Please try again.');
                    restoreActiveMenu();
                }
            }

            // Attach event listeners to dynamically loaded buttons
            function attachDynamicEventListeners() {
                // Find all buttons with data-step attribute or onclick that might need App methods
                document.querySelectorAll('[onclick*="App."]').forEach(button => {
                    // Store original onclick
                    const originalOnclick = button.getAttribute('onclick');
                    if (originalOnclick && !button.hasAttribute('data-app-bound')) {
                        button.setAttribute('data-app-bound', 'true');
                        // Replace with a safer wrapper that checks if App exists
                        button.setAttribute('onclick',
                            `if(window.App) { ${originalOnclick} } else { console.error('App not ready'); }`
                        );
                    }
                });
            }

            // Initialize all third-party plugins
            function initializePlugins() {
                // Tooltips
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

                // Toasts
                document.querySelectorAll('.toast').forEach(el => new bootstrap.Toast(el));

                // Select2
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $('.select2').select2({
                        width: '100%'
                    });
                }

                // Flatpickr
                if (typeof flatpickr !== 'undefined') {
                    flatpickr('.datepicker', {
                        dateFormat: 'Y-m-d'
                    });
                }
            }

            // ========== RE-UPLOAD MODAL FUNCTIONS ==========
            function openReupload(docName, documentType, documentId, allotteeId, stepNo) {
                const docNameElement = document.getElementById('docTypeSelect');
                const documentIdElement = document.getElementById('documentId');
                const allotteeIdElement = document.getElementById('allotteeId');
                const documentTypeElement = document.getElementById('documentType');
                const stepNoElement = document.getElementById('stepNoValue');
                const modalTitleElement = document.getElementById('reuploadModalTitle');

                if (documentIdElement) documentIdElement.value = documentId;
                if (allotteeIdElement) allotteeIdElement.value = allotteeId;
                if (documentTypeElement) documentTypeElement.value = documentType;
                if (stepNoElement) stepNoElement.value = stepNo;

                if (modalTitleElement) {
                    modalTitleElement.innerHTML = `
                    <i class="fa-solid fa-file-signature me-2 text-success"></i>
                    Upload Signed ${docName.replaceAll('-', ' ')}
                `;
                }

                if (docNameElement) docNameElement.value = docName;
                clearFile();

                if (!elements.reuploadModal) {
                    const modalEl = document.getElementById('reuploadModal');
                    if (modalEl) elements.reuploadModal = new bootstrap.Modal(modalEl);
                }
                if (elements.reuploadModal) elements.reuploadModal.show();
            }

            function previewFile(input) {
                if (!input?.files?.length) return;

                const file = input.files[0];
                const preview = document.getElementById('filePreview');
                const previewName = document.getElementById('previewName');
                const previewSize = document.getElementById('previewSize');
                const previewIcon = document.getElementById('previewIcon');
                const previewLink = document.getElementById('previewLink');

                if (preview) preview.style.display = 'block';
                if (previewName) previewName.innerText = file.name;
                if (previewSize) previewSize.innerText = `${(file.size / 1024 / 1024).toFixed(2)} MB`;

                const fileUrl = URL.createObjectURL(file);
                if (previewLink) previewLink.href = fileUrl;

                if (previewIcon) {
                    previewIcon.innerHTML = file.type.includes('pdf') ?
                        '<i class="fa-solid fa-file-pdf"></i>' :
                        '<i class="fa-solid fa-image"></i>';
                }
            }

            function clearFile() {
                const fileInput = document.getElementById('fileInput');
                if (fileInput) fileInput.value = '';
                const filePreview = document.getElementById('filePreview');
                if (filePreview) filePreview.style.display = 'none';
            }

            async function submitDocumentUpload() {
                const fileInput = document.getElementById('fileInput');
                if (!fileInput?.files?.length) {
                    showToast('error', 'Please select a signed document to upload');
                    return;
                }

                const documentId = document.getElementById('documentId')?.value;
                const docTypeSelect = document.getElementById('docTypeSelect')?.value;
                const documentType = document.getElementById('documentType')?.value;
                const allotteeId = document.getElementById('allotteeId')?.value;
                const docIssueDate = document.getElementById('docIssueDate')?.value;
                const docNumber = document.getElementById('docNumber')?.value;
                const stepNo = document.getElementById('stepNoValue')?.value;

                const formData = new FormData();
                formData.append('document_id', documentId);
                formData.append('document_name', docTypeSelect);
                formData.append('document_type', documentType);
                formData.append('allottee_id', allotteeId);
                formData.append('issue_date', docIssueDate || new Date().toISOString().split('T')[0]);
                formData.append('document_number', docNumber || '');
                formData.append('stepNo', stepNo);
                formData.append('file', fileInput.files[0]);

                try {
                    const response = await fetch(routes.uploadSigned, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                ?.content || ''
                        },
                        body: formData
                    });

                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || 'Upload failed');

                    if (elements.reuploadModal) {
                        elements.reuploadModal.hide();
                    }
                    showToast('success', 'Document uploaded successfully!');

                    // Refresh current step
                    if (currentStepNo !== null) {
                        loadStep(currentStepNo);
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    showToast('error', error.message || 'Upload failed. Please try again.');
                }
            }

            // ========== PAYMENT FUNCTION ==========
            async function payInitialPayment(paymentId) {
                try {
                    const response = await fetch(routes.initialPayment, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                ?.content || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            payment_id: paymentId
                        })
                    });

                    const data = await response.json();
                    if (!data.success) throw new Error(data.message || 'Payment failed');

                    if (data.receipt_url) window.open(data.receipt_url, '_blank');
                    loadStep(3);
                } catch (error) {
                    console.error('Payment error:', error);
                    showToast('error', error.message || 'Payment failed. Please try again.');
                }
            }

            // ========== MENU ARROW ROTATION ==========
            function initMenuArrowRotation() {
                document.addEventListener('click', function(e) {
                    const button = e.target.closest('.sidebar-menu-btn');
                    if (!button) return;

                    const arrow = button.querySelector('.menu-arrow');
                    setTimeout(() => {
                        const target = document.querySelector(button.getAttribute(
                            'data-bs-target'));
                        if (arrow && target) {
                            arrow.style.transform = target.classList.contains('show') ?
                                'rotate(180deg)' : 'rotate(0deg)';
                        }
                    }, 150);
                });
            }

            // ========== URL HASH NAVIGATION ==========
            function handleNavigation() {
                const hash = window.location.hash;
                if (!hash || hash === '#') {
                    return;
                }

                if (hash === '#overview') {
                    const overviewBtn = document.querySelector('[data-step="overview"]');
                    if (overviewBtn) loadStep(0, overviewBtn);
                } else if (hash.startsWith('#step-')) {
                    const stepNo = parseInt(hash.replace('#step-', ''), 10);
                    if (!isNaN(stepNo)) {
                        const stepBtn = document.querySelector(`[data-step="${stepNo}"]`);
                        if (stepBtn) loadStep(stepNo, stepBtn);
                    }
                }
            }

            // ========== INITIALIZATION ==========
            function init() {
                // Cache modal and toast instances
                const modalEl = document.getElementById('reuploadModal');
                if (modalEl) elements.reuploadModal = new bootstrap.Modal(modalEl);

                const successEl = document.getElementById('successToast');
                if (successEl) elements.successToast = successEl;
                const errorEl = document.getElementById('errorToast');
                if (errorEl) elements.errorToast = errorEl;

                initializePlugins();
                initMenuArrowRotation();

                // Popstate event listener
                window.addEventListener('popstate', handleNavigation);

                // Handle initial active button or load default step
                const activeButton = document.querySelector('.sidebar-submenu-link.active, .sidebar-link.active');
                if (activeButton) {
                    currentActiveButton = activeButton;
                    autoOpenParentMenu(activeButton);
                    // Load the active step content if not already loaded
                    const stepNo = activeButton.getAttribute('data-step');
                    if (stepNo && (!elements.dynamicContent || elements.dynamicContent.innerHTML.trim() === '')) {
                        loadStep(parseInt(stepNo), activeButton);
                    }
                } else {
                    // Load default step (step 1) on initial load
                    const defaultButton = document.querySelector('[data-step="1"]');
                    if (defaultButton) {
                        loadStep(1, defaultButton);
                    }
                }

                handleNavigation();

                // Attach initial event listeners
                attachDynamicEventListeners();
            }

            // Public API - expose all needed functions
            return {
                loadStep,
                openReupload,
                previewFile,
                clearFile,
                submitDocumentUpload,
                payInitialPayment,
                init
            };
        })();

        // Make functions globally available for inline onclick handlers
        // Check if App is defined before using it
        window.openReupload = function(docName, documentType, documentId, allotteeId, stepNo) {
            if (window.App) {
                window.App.openReupload(docName, documentType, documentId, allotteeId, stepNo);
            } else {
                console.error('App not ready yet');
            }
        };

        window.previewFile = function(input) {
            if (window.App) {
                window.App.previewFile(input);
            } else {
                console.error('App not ready yet');
            }
        };

        window.clearFile = function() {
            if (window.App) {
                window.App.clearFile();
            } else {
                console.error('App not ready yet');
            }
        };

        window.submitDocumentUpload = function() {
            if (window.App) {
                window.App.submitDocumentUpload();
            } else {
                console.error('App not ready yet');
            }
        };

        window.payInitialPayment = function(paymentId) {
            if (window.App) {
                window.App.payInitialPayment(paymentId);
            } else {
                console.error('App not ready yet');
            }
        };

        // Initialize on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                if (window.App) window.App.init();
            });
        } else {
            if (window.App) window.App.init();
        }
    </script>
</body>

</html>
