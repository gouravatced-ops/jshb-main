// ============================================
// STEP 0 HANDLER - Payment details
// ============================================
const Step0Handler = {
    manager: null,

    init: function () {
        this.bindEvents();
    },

    bindEvents: function () {
        const fileInput = document.getElementById('payment_receipt');

        if (!fileInput) return;

        fileInput.addEventListener('change', (event) => {
            fileInput.classList.remove('is-invalid');
            this.previewReceipt(event);
        });
    },

    previewReceipt: function (event) {
        const image = document.getElementById('receiptPreview');
        const placeholder = document.getElementById('receiptPlaceholder');

        const file = event.target.files[0];

        if (file) {
            image.src = URL.createObjectURL(file);
            image.style.display = 'block';
            placeholder.style.display = 'none';
        } else {
            image.src = '';
            image.style.display = 'none';
            placeholder.style.display = 'block';
        }
    },

    validate: function () {
        const form = document.querySelector('#step0Form');
        if (!form) return true;

        form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));

        let valid = true;
        let firstInvalid = null;

        const requiredNames = ['payment_amount', 'payment_date', 'payment_mode'];
        requiredNames.forEach((name) => {
            const field = form.querySelector(`[name="${name}"]`);
            if (!field) return;
            const v = (field.value || '').toString().trim();
            if (!v) {
                field.classList.add('is-invalid');
                valid = false;
                if (!firstInvalid) firstInvalid = field;
            }
        });

        const file = form.querySelector('#payment_receipt');
        if (file && file.hasAttribute('required') && (!file.files || !file.files.length)) {
            file.classList.add('is-invalid');
            valid = false;
            if (!firstInvalid) firstInvalid = file;
        }

        const amount = parseFloat(form.querySelector('[name="payment_amount"]')?.value || '0');
        if (amount <= 0) {
            const a = form.querySelector('[name="payment_amount"]');
            if (a) {
                a.classList.add('is-invalid');
                valid = false;
                if (!firstInvalid) firstInvalid = a;
            }
        }

        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
        }
        return valid;
    },

    destroy: function () { },
};

StepManager.registerHandler(0, Step0Handler);
