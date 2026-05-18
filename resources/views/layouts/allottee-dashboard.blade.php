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
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/allottee/dashboard.css') }}">
</head>

<body>
    {{-- TOPBAR --}}
    <header class="topbar">
        <div class="topbar-logo">
            <img src="{{ asset(config('panel.logo')) }}" alt="JESA Logo">
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

            <div class="sidebar-title">
                JSHB Menu
            </div>

            {{-- Overview --}}
            <button type="button"
                class="sidebar-link overview-btn active"
                onclick="loadStep(0, this)">

                <i class="fa-solid fa-gauge"></i>

                <span>Quick Overview</span>

            </button>

            {{-- MENU --}}
            @foreach($steps->groupBy('menu_key') as $menuKey => $menuSteps)

            @php
            $menu = $menuSteps->first();
            $collapseId = 'menu-' . Str::slug($menuKey);
            @endphp

            <div class="sidebar-menu">

                {{-- MAIN MENU --}}
                <button class="sidebar-menu-btn"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}">

                    <span class="menu-left">

                        <i class="fa-solid fa-layer-group"></i>

                        <span>
                            {{ $menu->menu_key }}
                        </span>

                    </span>

                    <i class="fa-solid fa-chevron-down menu-arrow"></i>

                </button>

                {{-- SUB MENU --}}
                <div class="collapse show"
                    id="{{ $collapseId }}">

                    <div class="sidebar-submenu">

                        @foreach($menuSteps as $step)

                        @php
                        $isActive = isset($currentStepNo2)
                        && $currentStepNo2 == $step->step_no;

                        $isLocked = $step->status === 'locked';

                        $isCompleted = $step->status === 'completed';
                        @endphp

                        <button type="button"
                            data-step="{{ $step->step_no }}"
                            class="sidebar-submenu-link
                        {{ $isActive ? 'active' : '' }}
                        {{ $isCompleted ? 'done' : '' }}"
                            onclick="loadStep({{ $step->step_no }}, this)"
                            {{ $isLocked ? 'disabled' : '' }}>

                            <span class="submenu-icon">

                                @if($isCompleted)
                                <i class="fa-solid fa-check"></i>

                                @elseif($isLocked)
                                <i class="fa-solid fa-lock"></i>

                                @else
                                <i class="fa-regular fa-circle"></i>
                                @endif

                            </span>

                            <span>
                                {{ $step->title }}
                            </span>

                        </button>

                        @endforeach

                    </div>

                </div>

            </div>

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
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="signedDocumentForm">
                        <input type="hidden" id="documentId">
                        <input type="hidden" id="allotteeId">
                        <input type="hidden" id="stepNoValue">
                        <div class="row g-3">
                            <!-- DOCUMENT NAME -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    Document Name
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="docTypeSelect"
                                    value=""
                                    readonly
                                    style="
                                        background:#f8fafc;
                                        border:1px solid #dbe3ee;
                                        font-weight:600;
                                        color:#111827;
                                        cursor:not-allowed;
                                    ">
                            </div>
                            <!-- ISSUE DATE -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    Issue Date
                                </label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="docIssueDate"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <!-- DOCUMENT NUMBER -->
                            <div class="col-12">
                                <label class="form-label">
                                    Document Number
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="docNumber"
                                    placeholder="Enter document reference number">
                            </div>
                            <!-- FILE -->
                            <div class="col-12">
                                <label class="form-label">
                                    Upload Signed Copy
                                </label>
                                <div
                                    id="uploadZone"
                                    onclick="document.getElementById('fileInput').click()"
                                    style="
                                    border:2px dashed #d1d5db;
                                    border-radius:16px;
                                    padding:30px;
                                    text-align:center;
                                    cursor:pointer;
                                    background:#f9fafb;
                                ">
                                    <div style="font-size:40px;color:#198754;">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    <div
                                        style="
                                        font-weight:600;
                                        margin-top:10px;
                                    ">
                                        Click to upload signed document
                                    </div>
                                    <div
                                        style="
                                        font-size:13px;
                                        color:#198754;
                                        margin-top:6px;
                                    ">
                                        PDF, JPG, PNG • Max 5 MB
                                    </div>
                                </div>
                                <input
                                    type="file"
                                    id="fileInput"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    style="display:none"
                                    onchange="previewFile(this)">
                            </div>
                        </div>
                        <!-- PREVIEW -->
                        <div
                            id="filePreview"
                            style="display:none;margin-top:20px;">
                            <div
                                style="
                                border:1px solid #e5e7eb;
                                border-radius:14px;
                                padding:15px;
                                display:flex;
                                align-items:center;
                                gap:15px;
                            ">
                                <div
                                    id="previewIcon"
                                    style="
                                    width:55px;
                                    height:55px;
                                    border-radius:12px;
                                    background:#eff6ff;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    font-size:22px;
                                    color:#198754;
                                ">
                                    <i class="fa-solid fa-file"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div
                                        id="previewName"
                                        style="font-weight:600;">
                                        --
                                    </div>
                                    <div
                                        id="previewSize"
                                        style="
                                        font-size:13px;
                                        color:#198754;
                                    ">
                                        --
                                    </div>
                                </div>
                                <div>
                                    <a
                                        href="#"
                                        target="_blank"
                                        id="previewLink"
                                        class="btn btn-sm btn-light">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light"
                                        onclick="clearFile()">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button
                        type="button"
                        class="btn btn-success"
                        onclick="submitDocumentUpload()">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        Upload Signed Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- SUCCESS TOAST --}}
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1100">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fa-solid fa-circle-check me-2"></i> Document uploaded successfully!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        const successToast = new bootstrap.Toast(document.getElementById('successToast'), {
            delay: 3000
        });
        let currentActiveButton = null;

        const dynamicContent = document.getElementById('dynamicContent');

        /* ─────────────────────────────────────────
           ROUTES
        ───────────────────────────────────────── */

        const STEP_ROUTES = {

            overview: @json(route('admin.allottees.section', [
                'allottee' => $allottee,
                'section' => 'overview'
            ])),

            process: @json(route('admin.allottees.process.step', [
                'allottee' => $allottee,
                'stepNo' => '__STEP__'
            ])),
        };

        /* ─────────────────────────────────────────
           LOAD STEP
        ───────────────────────────────────────── */

        async function loadStep(stepNo = 0, element = null) {

            if (!dynamicContent) {
                return;
            }

            setLoading();

            setActiveMenu(element);

            try {

                const url = getStepUrl(stepNo);

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const html = await response.text();

                dynamicContent.innerHTML = html;

                updateUrl(stepNo);

                autoOpenParentMenu(element);

                initializePlugins();

            } catch (error) {

                console.error('Step Load Error:', error);

                showError();

                restoreActiveMenu();
            }
        }

        /* ─────────────────────────────────────────
           HELPERS
        ───────────────────────────────────────── */

        function getStepUrl(stepNo) {

            return stepNo == 0 ?
                STEP_ROUTES.overview :
                STEP_ROUTES.process.replace('__STEP__', stepNo);
        }

        function updateUrl(stepNo) {

            const hash = stepNo == 0 ?
                '#overview' :
                `#step-${stepNo}`;

            history.pushState(null, '', hash);
        }

        function setActiveMenu(element) {

            document
                .querySelectorAll('.sidebar-submenu-link, .sidebar-link')
                .forEach(btn => btn.classList.remove('active'));

            if (element) {

                element.classList.add('active');

                currentActiveButton = element;
            }
        }

        function restoreActiveMenu() {

            if (currentActiveButton) {
                currentActiveButton.classList.add('active');
            }
        }

        /* ─────────────────────────────────────────
           AUTO OPEN COLLAPSE
        ───────────────────────────────────────── */

        function autoOpenParentMenu(element) {

            if (!element) {
                return;
            }

            const collapse = element.closest('.collapse');

            if (!collapse) {
                return;
            }

            bootstrap.Collapse.getOrCreateInstance(
                collapse, {
                    toggle: false
                }
            ).show();
        }

        /* ─────────────────────────────────────────
           UI STATES
        ───────────────────────────────────────── */

        function setLoading() {

            dynamicContent.innerHTML = `
        <div class="text-center py-5">

            <div class="mb-3">
                <i class="fa-solid fa-spinner fa-spin fa-2x text-muted"></i>
            </div>

            <div class="text-muted">
                Loading section...
            </div>

        </div>
    `;
        }

        function showError() {

            dynamicContent.innerHTML = `
        <div class="alert alert-danger m-3">

            <i class="fa-solid fa-circle-exclamation me-2"></i>

            Failed to load section.
            Please try again.

        </div>
    `;
        }

        /* ─────────────────────────────────────────
           INITIALIZE PLUGINS
        ───────────────────────────────────────── */

        function initializePlugins() {

            initializeTooltip();

            initializeToast();

            initializeSelect2();

            initializeDatePicker();
        }

        function initializeTooltip() {

            document
                .querySelectorAll('[data-bs-toggle="tooltip"]')
                .forEach(el => {
                    new bootstrap.Tooltip(el);
                });
        }

        function initializeToast() {

            document
                .querySelectorAll('.toast')
                .forEach(el => {
                    new bootstrap.Toast(el);
                });
        }

        function initializeSelect2() {

            if (
                typeof $ !== 'undefined' &&
                $.fn.select2
            ) {

                $('.select2').select2({
                    width: '100%',
                });
            }
        }

        function initializeDatePicker() {

            if (typeof flatpickr !== 'undefined') {

                flatpickr('.datepicker', {
                    dateFormat: 'Y-m-d',
                });
            }
        }

        /* ─────────────────────────────────────────
           HANDLE URL NAVIGATION
        ───────────────────────────────────────── */

        window.addEventListener('popstate', handleNavigation);

        function handleNavigation() {

            const hash = window.location.hash;

            if (!hash) {
                return;
            }

            if (hash === '#overview') {

                const overviewBtn = document.querySelector(
                    '.overview-btn'
                );

                loadStep(0, overviewBtn);

                return;
            }

            if (hash.startsWith('#step-')) {

                const stepNo = parseInt(
                    hash.replace('#step-', '')
                );

                if (!isNaN(stepNo)) {

                    const button = document.querySelector(
                        `[data-step="${stepNo}"]`
                    );

                    loadStep(stepNo, button);
                }
            }
        }

        /* ─────────────────────────────────────────
           MENU COLLAPSE ICON ROTATE
        ───────────────────────────────────────── */

        document.addEventListener('click', function(e) {

            const button = e.target.closest('.sidebar-menu-btn');

            if (!button) {
                return;
            }

            const arrow = button.querySelector('.menu-arrow');

            setTimeout(() => {

                const target = document.querySelector(
                    button.dataset.bsTarget
                );

                if (!target) {
                    return;
                }

                if (target.classList.contains('show')) {

                    arrow.style.transform = 'rotate(180deg)';

                } else {

                    arrow.style.transform = 'rotate(0deg)';
                }

            }, 150);
        });

        /* ─────────────────────────────────────────
           PAGE LOAD
        ───────────────────────────────────────── */

        document.addEventListener('DOMContentLoaded', () => {

            initializePlugins();

            const hash = window.location.hash;

            if (hash.startsWith('#step-')) {

                const stepNo = parseInt(
                    hash.replace('#step-', '')
                );

                if (!isNaN(stepNo)) {

                    const button = document.querySelector(
                        `[data-step="${stepNo}"]`
                    );

                    loadStep(stepNo, button);

                    return;
                }
            }

            const activeButton = document.querySelector(
                '.sidebar-submenu-link.active, .sidebar-link.active'
            );

            if (activeButton) {

                currentActiveButton = activeButton;

                autoOpenParentMenu(activeButton);
            }
        });
    </script>
    <script>
        const reuploadModal = new bootstrap.Modal(
            document.getElementById('reuploadModal')
        );

        function openReupload(docName, documentId, allotteeId, stepNo) {
            document.getElementById('documentId').value = documentId;
            document.getElementById('allotteeId').value = allotteeId;
            document.getElementById('stepNoValue').value = stepNo;
            document.getElementById('reuploadModalTitle').innerHTML = `
            <i class="fa-solid fa-file-signature me-2 text-success"></i>
            Upload Signed ${docName.replaceAll('-', ' ')}
        `;
            document.getElementById('docTypeSelect').value = docName;
            clearFile();
            reuploadModal.show();
        }

        function previewFile(input) {
            if (!input.files.length) return;
            const file = input.files[0];
            const preview = document.getElementById('filePreview');
            const previewName = document.getElementById('previewName');
            const previewSize = document.getElementById('previewSize');
            const previewIcon = document.getElementById('previewIcon');
            const previewLink = document.getElementById('previewLink');
            preview.style.display = 'block';
            previewName.innerText = file.name;
            previewSize.innerText =
                `${(file.size / 1024 / 1024).toFixed(2)} MB`;
            const fileUrl = URL.createObjectURL(file);
            previewLink.href = fileUrl;
            if (file.type.includes('pdf')) {
                previewIcon.innerHTML =
                    '<i class="fa-solid fa-file-pdf"></i>';
            } else {
                previewIcon.innerHTML =
                    '<i class="fa-solid fa-image"></i>';
            }
        }

        function clearFile() {
            document.getElementById('fileInput').value = '';
            document.getElementById('filePreview').style.display = 'none';
        }
        async function submitDocumentUpload() {
            const fileInput = document.getElementById('fileInput');
            if (!fileInput.files.length) {
                alert('Please select signed document');
                return;
            }
            const formData = new FormData();
            formData.append(
                'document_id',
                document.getElementById('documentId').value
            );
            formData.append(
                'allottee_id',
                document.getElementById('allotteeId').value
            );
            formData.append(
                'issue_date',
                document.getElementById('docIssueDate').value
            );
            formData.append(
                'document_number',
                document.getElementById('docNumber').value
            );
            formData.append(
                'stepNo',
                document.getElementById('stepNoValue').value
            );
            formData.append(
                'file',
                fileInput.files[0]
            );
            try {
                const response = await fetch(
                    "{{ route('admin.allottees.signed.document.uploads') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    }
                );
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result.message);
                }
                reuploadModal.hide();
                loadStep(document.getElementById('stepNoValue').value);
            } catch (error) {
                alert(error.message || 'Upload failed');
            }
        }
    </script>
    <script>
        async function payInitialPayment(paymentId) {
            try {
                const response = await fetch(
                    "{{ route('admin.allottees.initial.payment.pay') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            payment_id: paymentId
                        })
                    }
                );
                const data = await response.json();
                if (!data.success) {
                    alert(data.message);
                    return;
                }
                window.open(
                    data.receipt_url,
                    '_blank'
                );
                loadStep(4);
            } catch (error) {
                console.error(error);
                alert('Payment failed');
            }
        }
    </script>
</body>

</html>