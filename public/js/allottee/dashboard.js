(function () {
    'use strict';

    const config = window.AppConfig || {};
    const routes = config.routes || {};

    const elements = {
        dynamicContent: null,
        reuploadModal: null,
        successToast: null,
        errorToast: null,
        emiPaymentModal: null // Add this to store the Bootstrap modal instance
    };

    let currentActiveButton = null;
    let currentStepNo = parseInt(config.currentStepNo, 10) || 1;

    function getStepUrl(stepNo, params = '') {
        if (stepNo === 0 || stepNo === 'overview') {
            return routes.overview + params;
        }
        return routes.process.replace('__STEP__', stepNo) + params;
    }

    function updateUrl(stepNo) {
        history.pushState(null, '', stepNo === 0 || stepNo === 'overview' ? '#overview' : `#step-${stepNo}`);
    }

    function setActiveMenu(element) {
        document.querySelectorAll('.sidebar-submenu-link, .sidebar-link').forEach(btn => btn.classList.remove('active'));
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

    function showError(message = 'Failed to load section.') {
        if (elements.dynamicContent) {
            elements.dynamicContent.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> ${message}
                </div>
            `;
        }
    }

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

    async function loadStep(stepNo, element = null, params = '') {
        if (!elements.dynamicContent) return;

        const stepValue = stepNo === 'overview' ? 0 : parseInt(stepNo, 10);

        setLoading();
        setActiveMenu(element);
        currentStepNo = stepValue;

        try {
            const response = await fetch(getStepUrl(stepValue, params), {
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
            attachDynamicEventListeners();

            window.dispatchEvent(new CustomEvent('step-loaded', {
                detail: { stepNo: stepValue }
            }));
        } catch (error) {
            console.error('Load step error:', error);
            showError('Failed to load section. Please try again.');
            restoreActiveMenu();
        }
    }

    function attachDynamicEventListeners() {
        document.querySelectorAll('[onclick*="App."]').forEach(button => {
            const originalOnclick = button.getAttribute('onclick');
            if (originalOnclick && !button.hasAttribute('data-app-bound')) {
                button.setAttribute('data-app-bound', 'true');
                button.setAttribute('onclick', `if(window.App) { ${originalOnclick} } else { console.error('App not ready'); }`);
            }
        });
    }

    function initializePlugins() {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        document.querySelectorAll('.toast').forEach(el => new bootstrap.Toast(el));

        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2').select2({ width: '100%' });
        }

        if (typeof flatpickr !== 'undefined') {
            flatpickr('.datepicker', { dateFormat: 'Y-m-d' });
        }
    }

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
                Upload ${docName.replaceAll('-', ' ')}
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
            previewIcon.innerHTML = file.type.includes('pdf') ? '<i class="fa-solid fa-file-pdf"></i>' : '<i class="fa-solid fa-image"></i>';
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: formData
            });

            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Upload failed');

            if (elements.reuploadModal) {
                elements.reuploadModal.hide();
            }
            showToast('success', 'Successfully!');
            window.location.reload();
        } catch (error) {
            console.error('Upload error:', error);
            showToast('error', error.message || 'Upload failed. Please try again.');
        }
    }

    async function payInitialPayment(paymentId) {
        const button = document.querySelector('.btn-brand');
        if (!button) return;

        const originalHtml = button.innerHTML;
        button.disabled = true;
        button.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin"></i>
            Processing...
        `;

        try {
            const response = await fetch(routes.initialPayment, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ payment_id: paymentId })
            });

            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Payment failed');
            }

            if (data.receipt_url) {
                window.open(data.receipt_url, '_blank');
            }
            window.location.reload();
        } catch (error) {
            console.error('Payment error:', error);
            showToast('error', error.message || 'Payment failed. Please try again.');
            button.disabled = false;
            button.innerHTML = originalHtml;
        }
    }

    async function oneTimePayment(paymentId) {
        const button = document.querySelector('.btn-brand');
        if (!button) return;

        const originalHtml = button.innerHTML;
        button.disabled = true;
        button.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin"></i>
            Processing...
        `;

        try {
            const response = await fetch(routes.oneTimePayment, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ payment_id: paymentId })
            });

            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Payment failed');
            }

            if (data.receipt_url) {
                window.open(data.receipt_url, '_blank');
            }
            window.location.reload();
        } catch (error) {
            console.error('Payment error:', error);
            showToast('error', error.message || 'Payment failed. Please try again.');
            button.disabled = false;
            button.innerHTML = originalHtml;
        }
    }

    function payCurrentEmi(id, outstandingAmount) {
        // Open modal-based payment; modal form posts to server
        openEmiModal(id, outstandingAmount);
    }

    function showDummyGateway(id) {
        // Open modal and set payment mode to gateway
        openEmiModal(id, '');
        const pm = document.getElementById('modal_payment_mode');
        if (pm) pm.value = 'gateway';
    }

    function openEmiModal(demandId, amount) {
        const demandEl = document.getElementById('modal_demand_id');
        const amountEl = document.getElementById('modal_amount');
        if (demandEl) demandEl.value = demandId || '';
        if (amountEl) amountEl.value = amount ? parseFloat(amount).toFixed(2) : '';

        // Use the Bootstrap modal instance to show the modal
        if (elements.emiPaymentModal) {
            elements.emiPaymentModal.show();
        } else {
            console.error('EMI Payment Modal not initialized.');
        }
    }

    function closeEmiModal() {
        // Use the Bootstrap modal instance to hide the modal
        if (elements.emiPaymentModal) {
            elements.emiPaymentModal.hide();
        }
    }

    async function submitEmiPayment(e) {
        if (e) e.preventDefault();

        const form = document.getElementById('emiPaymentForm');
        if (!form) return;

        const btn = form.querySelector('button[type="submit"]');
        const loader = document.getElementById('secondary-loader-overlay');

        const demandId = document.getElementById('modal_demand_id')?.value;
        const amount = document.getElementById('modal_amount')?.value;
        const paymentMode = form.querySelector('[name="payment_mode"]')?.value || 'gateway';

        if (!demandId || !amount) {
            showToast('error', 'Please enter the amount');
            return;
        }

        // Show Loader
        if (loader) loader.classList.add('show');
        if (btn) btn.disabled = true;

        try {
            const response = await fetch(routes.emiProcessPayment, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    demand_id: demandId,
                    amount: amount,
                    payment_mode: paymentMode
                })
            });

            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Payment failed');

            closeEmiModal();
            showToast('success', result.message);
            loadStep(currentStepNo, document.querySelector('.sidebar-submenu-link.active, .sidebar-link.active'));
        } catch (error) {
            showToast('error', error.message);
        } finally {
            if (loader) loader.classList.remove('show');
            if (btn) btn.disabled = false;
        }
    }

    function payEmi(id) {
        console.log('Pay EMI', id);
    }

    function prePayment(id) {
        console.log('Pre Payment', id);
    }

    function closeLoan(id) {
        if (confirm('Close this loan account ?')) {
            console.log('Close Loan', id);
        }
    }

    function viewDocument(docName) {
        alert('Viewing: ' + docName);
    }

    function submitAllDocuments() {
        alert('Submitting all documents for verification');
    }

    function initMenuArrowRotation() {
        document.addEventListener('click', function (e) {
            const button = e.target.closest('.sidebar-menu-btn');
            if (!button) return;
            const arrow = button.querySelector('.menu-arrow');
            setTimeout(() => {
                const target = document.querySelector(button.getAttribute('data-bs-target'));
                if (arrow && target) {
                    arrow.style.transform = target.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
                }
            }, 150);
        });
    }

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

    function init() {
        elements.dynamicContent = document.getElementById('dynamicContent');

        const modalEl = document.getElementById('reuploadModal');
        if (modalEl) elements.reuploadModal = new bootstrap.Modal(modalEl);

        // Initialize emiPaymentModal as a Bootstrap modal instance
        const emiModalEl = document.getElementById('emiPaymentModal');
        if (emiModalEl) elements.emiPaymentModal = new bootstrap.Modal(emiModalEl);

        const successEl = document.getElementById('successToast');
        if (successEl) elements.successToast = successEl;
        const errorEl = document.getElementById('errorToast');
        if (errorEl) elements.errorToast = errorEl;

        initializePlugins();
        initMenuArrowRotation();

        window.addEventListener('popstate', handleNavigation);

        const activeButton = document.querySelector('.sidebar-submenu-link.active, .sidebar-link.active');
        if (activeButton) {
            currentActiveButton = activeButton;
            autoOpenParentMenu(activeButton);
            const stepNo = activeButton.getAttribute('data-step');
            if (stepNo && (!elements.dynamicContent || elements.dynamicContent.innerHTML.trim() === '')) {
                loadStep(parseInt(stepNo, 10), activeButton);
            }
        } else {
            const defaultButton = document.querySelector('[data-step="1"]');
            if (defaultButton) {
                loadStep(1, defaultButton);
            }
        }

        handleNavigation();
        attachDynamicEventListeners();

        // Attach Pay Now button if present (opens EMI modal)
        const payBtn = document.getElementById('btnPayNow');
        if (payBtn) {
            payBtn.addEventListener('click', function () {
                const id = this.dataset.demand;
                const amt = this.dataset.amount;
                openEmiModal(id, amt);
            });
        }

        const emiForm = document.getElementById('emiPaymentForm');
        if (emiForm) {
            emiForm.addEventListener('submit', submitEmiPayment);
        }
    }

    window.App = {
        loadStep,
        openReupload,
        previewFile,
        clearFile,
        submitDocumentUpload,
        payInitialPayment,
        oneTimePayment,
        init
    };

    window.openReupload = function (docName, documentType, documentId, allotteeId, stepNo) {
        if (window.App) {
            window.App.openReupload(docName, documentType, documentId, allotteeId, stepNo);
        } else {
            console.error('App not ready yet');
        }
    };

    window.previewFile = function (input) {
        if (window.App) {
            window.App.previewFile(input);
        } else {
            console.error('App not ready yet');
        }
    };

    window.clearFile = function () {
        if (window.App) {
            window.App.clearFile();
        } else {
            console.error('App not ready yet');
        }
    };

    window.submitDocumentUpload = function () {
        if (window.App) {
            window.App.submitDocumentUpload();
        } else {
            console.error('App not ready yet');
        }
    };

    window.payInitialPayment = function (paymentId) {
        if (window.App) {
            window.App.payInitialPayment(paymentId);
        } else {
            console.error('App not ready yet');
        }
    };

    window.oneTimePayment = function (paymentId) {
        if (window.App) {
            window.App.oneTimePayment(paymentId);
        } else {
            console.error('App not ready yet');
        }
    };

    window.payCurrentEmi = payCurrentEmi;
    window.showDummyGateway = showDummyGateway;
    window.openEmiModal = openEmiModal;
    window.closeEmiModal = closeEmiModal;
    window.payEmi = payEmi;
    window.prePayment = prePayment;
    window.closeLoan = closeLoan;
    window.viewDocument = viewDocument;
    window.submitAllDocuments = submitAllDocuments;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            if (window.App) window.App.init();
        });
    } else {
        if (window.App) window.App.init();
    }
})();
