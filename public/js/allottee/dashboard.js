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
                    'Accept': 'application/json',
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

            if (result.account_status === 'closed') {
                window.location.hash = '#step-12';
                window.location.reload();
            } else {
                loadStep(currentStepNo, document.querySelector('.sidebar-submenu-link.active, .sidebar-link.active'));
            }
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

    window.generateSiteMap = function () {
        const canvas = document.getElementById('siteMapCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        // Get values
        const plotNo = document.getElementById('mapPlotNo') ? document.getElementById('mapPlotNo').value : '';
        const nDim = parseFloat(document.getElementById('mapNorth') ? document.getElementById('mapNorth').value : 0) || 0;
        const nLbl = document.getElementById('mapNorthLabel') ? document.getElementById('mapNorthLabel').value : '';
        const sDim = parseFloat(document.getElementById('mapSouth') ? document.getElementById('mapSouth').value : 0) || 0;
        const sLbl = document.getElementById('mapSouthLabel') ? document.getElementById('mapSouthLabel').value : '';
        const eDim = parseFloat(document.getElementById('mapEast') ? document.getElementById('mapEast').value : 0) || 0;
        const eLbl = document.getElementById('mapEastLabel') ? document.getElementById('mapEastLabel').value : '';
        const wDim = parseFloat(document.getElementById('mapWest') ? document.getElementById('mapWest').value : 0) || 0;
        const wLbl = document.getElementById('mapWestLabel') ? document.getElementById('mapWestLabel').value : '';

        // Clear Canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Draw Compass (Top Left)
        ctx.save();
        ctx.translate(60, 70);
        ctx.strokeStyle = '#1e293b';
        ctx.lineWidth = 1;
        ctx.beginPath();
        // Circle
        ctx.arc(0, 0, 10, 0, 2 * Math.PI);
        // Crosshair
        ctx.moveTo(0, -30); ctx.lineTo(0, 30);
        ctx.moveTo(-30, 0); ctx.lineTo(30, 0);
        ctx.stroke();

        // Arrow head pointing Left for N
        ctx.beginPath();
        ctx.moveTo(-30, 0);
        ctx.lineTo(-20, -5);
        ctx.lineTo(-20, 5);
        ctx.closePath();
        ctx.fillStyle = '#1e293b';
        ctx.fill();
        ctx.stroke();

        // Text
        ctx.font = '14px Arial';
        ctx.fillStyle = '#1e293b';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('N', -45, 0);
        ctx.fillText('S', 45, 0);
        ctx.fillText('E', 0, -45);
        ctx.fillText('W', 0, 45);
        ctx.restore();

        // Calculate dimensions to fit properly on canvas
        const cx = canvas.width / 2 + 20; // Shift right slightly to accommodate compass
        const cy = canvas.height / 2;

        const maxDim = Math.max(nDim, sDim, eDim, wDim, 1);
        const scaleFactor = 200 / maxDim; // Map fits within 200x200 max box

        // Calculate dynamic dimensions
        // With N on Left: Top is East, Bottom is West, Left is North, Right is South
        const nScaled = nDim * scaleFactor; // Left edge height
        const sScaled = sDim * scaleFactor; // Right edge height
        const eScaled = eDim * scaleFactor; // Top edge width
        const wScaled = wDim * scaleFactor; // Bottom edge width

        // Draw grid lines (optional for aesthetic)
        ctx.strokeStyle = '#f1f5f9';
        ctx.lineWidth = 1;
        for (let i = 0; i < canvas.width; i += 20) {
            ctx.beginPath(); ctx.moveTo(i, 0); ctx.lineTo(i, canvas.height); ctx.stroke();
        }
        for (let j = 0; j < canvas.height; j += 20) {
            ctx.beginPath(); ctx.moveTo(0, j); ctx.lineTo(canvas.width, j); ctx.stroke();
        }

        // Points for the dynamic shape (centered)
        const pTL = { x: cx - eScaled / 2, y: cy - nScaled / 2 }; // Top-Left
        const pTR = { x: cx + eScaled / 2, y: cy - sScaled / 2 }; // Top-Right
        const pBR = { x: cx + wScaled / 2, y: cy + sScaled / 2 }; // Bottom-Right
        const pBL = { x: cx - wScaled / 2, y: cy + nScaled / 2 }; // Bottom-Left

        // Shadow for plot
        ctx.shadowColor = 'rgba(0,0,0,0.15)';
        ctx.shadowBlur = 10;
        ctx.shadowOffsetX = 5;
        ctx.shadowOffsetY = 5;

        // Draw Plot Shape
        ctx.beginPath();
        ctx.moveTo(pTL.x, pTL.y);
        ctx.lineTo(pTR.x, pTR.y);
        ctx.lineTo(pBR.x, pBR.y);
        ctx.lineTo(pBL.x, pBL.y);
        ctx.closePath();
        ctx.fillStyle = '#fef3c7'; // warm yellow
        ctx.fill();

        // Reset shadow for borders
        ctx.shadowColor = 'transparent';
        ctx.shadowBlur = 0;
        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 0;

        // Border
        ctx.lineWidth = 3;
        ctx.strokeStyle = '#1e293b';
        ctx.stroke();

        // Plot Inner Label
        ctx.fillStyle = '#1e293b';
        ctx.font = 'bold 16px "Segoe UI", Arial, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(plotNo, cx, cy);

        ctx.font = 'bold 14px "Segoe UI", Arial, sans-serif';

        // East Data (Top)
        const topY = (pTL.y + pTR.y) / 2;
        ctx.textBaseline = 'bottom';
        ctx.fillStyle = '#0f172a';
        ctx.fillText(eDim + "'0''", cx, topY - 10);
        ctx.font = '14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#64748b';
        ctx.fillText(eLbl, cx, topY - 30);

        // West Data (Bottom)
        const bottomY = (pBL.y + pBR.y) / 2;
        ctx.font = 'bold 14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#0f172a';
        ctx.textBaseline = 'top';
        ctx.fillText(wDim + "'0''", cx, bottomY + 10);
        ctx.font = '14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#64748b';
        ctx.fillText(wLbl, cx, bottomY + 30);

        // North Data (Left side)
        const leftX = (pTL.x + pBL.x) / 2;
        ctx.save();
        ctx.translate(leftX - 30, cy);
        ctx.rotate(-Math.PI / 2); // Rotate text so it's readable sideways
        ctx.font = 'bold 14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#0f172a';
        ctx.textBaseline = 'bottom';
        ctx.textAlign = 'center';
        ctx.fillText(nDim + "'0''", 0, 0);
        ctx.font = '14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#64748b';
        ctx.textBaseline = 'top';
        ctx.fillText(nLbl, 0, 5);
        ctx.restore();

        // South Data (Right side)
        const rightX = (pTR.x + pBR.x) / 2;
        ctx.save();
        ctx.translate(rightX + 30, cy);
        ctx.rotate(Math.PI / 2); // Rotate text for right side
        ctx.font = 'bold 14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#0f172a';
        ctx.textBaseline = 'bottom';
        ctx.textAlign = 'center';
        ctx.fillText(sDim + "'0''", 0, 0);
        ctx.font = '14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#64748b';
        ctx.textBaseline = 'top';
        ctx.fillText(sLbl, 0, 5);
        ctx.restore();
    };

    window.downloadMapImage = function () {
        const canvas = document.getElementById('siteMapCanvas');
        if (!canvas) return;
        const link = document.createElement('a');
        link.download = 'site-map-' + document.getElementById('mapPlotNo').value + '.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    };

    window.saveSiteVerification = async function () {
        const btn = document.getElementById('saveSiteVerificationBtn');
        if (!btn) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...';

        const form = document.getElementById('siteVerificationForm');
        const formData = new FormData(form);

        // Append map parameters
        formData.append('mapPlotNo', document.getElementById('mapPlotNo').value);
        formData.append('mapNorth', document.getElementById('mapNorth').value);
        formData.append('mapNorthLabel', document.getElementById('mapNorthLabel').value);
        formData.append('mapSouth', document.getElementById('mapSouth').value);
        formData.append('mapSouthLabel', document.getElementById('mapSouthLabel').value);
        formData.append('mapEast', document.getElementById('mapEast').value);
        formData.append('mapEastLabel', document.getElementById('mapEastLabel').value);
        formData.append('mapWest', document.getElementById('mapWest').value);
        formData.append('mapWestLabel', document.getElementById('mapWestLabel').value);

        // Append map image
        const canvas = document.getElementById('siteMapCanvas');
        if (canvas) {
            formData.append('map_image_data', canvas.toDataURL('image/png'));
        }

        try {
            const url = form.getAttribute('action');
            const token = form.getAttribute('data-csrf');

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: result.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.hash = '#step-17';
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'Something went wrong!'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Server error occurred while saving.'
            });
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-save me-2"></i> Save Site Verification Details';
        }
    };

    // Attach step-loaded event to initialize map inputs
    window.addEventListener('step-loaded', function (e) {
        const inputs = document.querySelectorAll('.map-input');
        if (inputs.length > 0) {
            inputs.forEach(input => {
                input.removeEventListener('input', window.generateSiteMap);
                input.addEventListener('input', window.generateSiteMap);
            });
            setTimeout(window.generateSiteMap, 300);
        }

        // Initialize Extra Construction radio buttons
        const extraRadios = document.querySelectorAll('input[name="extra_construction"]');
        if (extraRadios.length > 0) {
            extraRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const details = document.getElementById('extraConstructionDetails');
                    if (details) {
                        details.style.display = this.value === 'yes' ? 'block' : 'none';
                    }
                });
            });
        }
    });

    window.saveExtraConstruction = async function () {
        const btn = document.getElementById('saveExtraBtn');
        const form = document.getElementById('extraConstructionForm');
        if (!form || !btn) return;

        const formData = new FormData(form);
        const url = form.getAttribute('action');
        const token = form.getAttribute('data-csrf') || document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...';

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: result.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.hash = '#step-18';
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'Something went wrong!'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Server error occurred while saving.'
            });
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-save me-2"></i> Save & Continue';
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            if (window.App) window.App.init();
        });
    } else {
        if (window.App) window.App.init();
    }
})();
