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
        // Sub Division cascading
        document.querySelectorAll('.division-select').forEach(select => {
            select.addEventListener('change', this.loadSubDivision);
        });
        // Property Type cascading
        document.querySelectorAll('.property-category-select').forEach(select => {
            select.addEventListener('change', this.loadPropertyType);
        });
        // Quater Type FIlter cascading
        document.querySelectorAll('.property-cat-type-select').forEach(select => {
            select.addEventListener('change', this.quaterTypeFilter);
        });
        // Property Sub Type cascading
        document.querySelectorAll('.property-cat-type-select').forEach(select => {
            select.addEventListener('change', this.loadPropertySubType);
        });
        // SchemeList cascading
        document.querySelectorAll('.quater-type-option').forEach(select => {
            select.addEventListener('change', this.schemeListOption);
        });
        // Show dropdown on focus
        const searchInput =
            document.getElementById('schemeSearch');
        const customOptions =
            document.getElementById('customOptions');
        if (searchInput && customOptions) {
            // Open dropdown
            searchInput.addEventListener('focus', () => {
                customOptions.classList.add('show');
            });
            // Close dropdown outside click
            document.addEventListener('click', (e) => {
                const isInside =
                    searchInput.contains(e.target) ||
                    customOptions.contains(e.target);
                if (!isInside) {
                    customOptions.classList.remove('show');
                }
            });
            // Dynamic option click
            document.addEventListener('click', (e) => {
                const option =
                    e.target.closest('.custom-option');
                if (option) {
                    this.selectScheme(option);
                }
            });
        }
        if (searchInput && customOptions) {
            // Live search
            searchInput.addEventListener('input', function () {
                const search =
                    this.value.toLowerCase();
                let visibleCount = 0;
                document.querySelectorAll('.custom-option')
                    .forEach(option => {
                        const text =
                            option.dataset.search || '';
                        const matched =
                            text.includes(search);
                        option.style.display =
                            matched ? 'block' : 'none';
                        if (matched) visibleCount++;
                    });
                // Auto open dropdown
                customOptions.classList.add('show');
                // No result
                if (visibleCount === 0) {
                    customOptions.innerHTML = `
                <div class="custom-option text-danger text-center">
                    No scheme found
                </div>
            `;
                }
            });
        }
    },
    loadSubDivision: function () {
        const divisionId = this.value;
        const subDivisionSelect = document.getElementById('subdivisionSelect')
        if (!divisionId) return;
        const currentSelectedValue = divisionId;
        subDivisionSelect.innerHTML = '<option value="">Loading ...</option>';
        fetch(`/public/get-sub-divisions/${divisionId}`)
            .then(res => res.json())
            .then(data => {
                console.log(data);
                subDivisionSelect.innerHTML = '<option value="">Select Sub Division</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.sub_dv_en_id;
                    option.textContent = item.subdivision_code + ' - ' + item.name;
                    if (currentSelectedValue == item.id) {
                        option.selected = true;
                    }
                    subDivisionSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error Loading Sub Division:', error));
    },
    loadPropertyType: function () {
        const pCategoryId = this.value;
        const propertyTypeSelect = document.getElementById('PropertyCatType');
        // console.log('FORAPI: ' + pCategoryId);
        if (!pCategoryId) return;
        if (!propertyTypeSelect) return;
        propertyTypeSelect.innerHTML = '<option> Loading ... </option>';
        fetch(`/public/get-property-types/${pCategoryId}`)
            .then(res => res.json())
            .then(data => {
                propertyTypeSelect.innerHTML = '<option> -- Select Property Type -- </option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.pt_en_id;
                    option.dataset.target = (item.name).toLowerCase();
                    option.textContent = item.name;
                    propertyTypeSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error Loading Property Type:', error));
    },
    loadPropertySubType: function () {
        const pTypeId = this.value;
        const propertySubTypeSelect = document.getElementById('property_sub_type');
        // console.log('FORAPI: ' + pTypeId);
        if (!pTypeId) return;
        if (!propertySubTypeSelect) return;
        propertySubTypeSelect.innerHTML = '<option> Loading ... </option>';
        fetch(`/public/get-property-sub-types/${pTypeId}`)
            .then(res => res.json())
            .then(data => {
                propertySubTypeSelect.innerHTML = '<option> -- Select Sub Property Type -- </option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.pctm_en_id;
                    option.textContent = item.name;
                    propertySubTypeSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error Loading Property Type:', error));
    },
    quaterTypeFilter: function (event) {
        const select = event.target;
        const selectedOption = select.options[select.selectedIndex];
        const quaterSelect = document.getElementById('quaterTypeOption');
        const selectOptionValue = selectedOption.value;
        const selectOptionTextValue = selectedOption.dataset.target;
        if (!quaterSelect) return;
        Array.from(quaterSelect.options).forEach(option => {
            const optionText = option.text.toLowerCase();
            if (selectOptionTextValue == 'plot') {
                if (optionText.includes('mig') || optionText.includes('hig')) {
                    option.hidden = false;
                } else {
                    option.hidden = true;
                }
            } else {
                option.hidden = false;
            }
        })
    },
    schemeListOption: async function () {
        const fields = {
            division_id: document.getElementById('divisionId'),
            subdivision_id: document.getElementById('subdivisionSelect'),
            pcategory_id: document.getElementById('pCategory'),
            property_type_id: document.getElementById('PropertyCatType'),
            property_sub_type_id: document.getElementById('property_sub_type'),
            quarter_id: document.getElementById('quaterTypeOption')
        };
        const formData = new FormData();
        Object.entries(fields).forEach(([key, el]) => {
            const value = el?.value || '';
            // encode value
            formData.append(key, value);
        });
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');
        try {
            const response = await fetch('/public/scheme-list', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
            });
            const data = await response.json();
            const schemeSelect =
                document.getElementById('customOptions');
            // Remove previous selected option
            document.querySelectorAll('.custom-option')
                .forEach(option => {
                    option.classList.remove('selected');
                });
            // Reset selected scheme input
            const selectedSchemeId =
                document.getElementById('selected_scheme_id');
            if (selectedSchemeId) {
                selectedSchemeId.value = '';
            }
            // Clear search input
            const searchInput =
                document.getElementById('schemeSearch');
            if (searchInput) {
                searchInput.value = '';
            }
            schemeSelect.innerHTML = `
                <div class="custom-option text-center text-muted">
                    Loading schemes...
                </div>
            `;
            if (!schemeSelect) return;
            console.log(data);
            schemeSelect.innerHTML = '';
            // If no scheme found
            if (data.length === 0) {
                schemeSelect.innerHTML = `
                    <div class="custom-option text-center text-danger fw-semibold">
                        No scheme found
                    </div>
                `;
                // Close dropdown
                schemeSelect.classList.add('show');
                return;
            }
            // If schemes found → open dropdown
            schemeSelect.classList.add('show');
            // Show scheme count
            document.getElementById('searchResultCount').innerText =
                `${data.length} schemes available`;
            data.forEach(item => {
                // Main option div
                const optionDiv = document.createElement('div');
                optionDiv.className = 'custom-option';
                // Dataset attributes
                optionDiv.dataset.value = item.id;
                optionDiv.dataset.propertyType = item.p_type_id;
                optionDiv.dataset.schemeCode = item.scheme_code;
                optionDiv.dataset.schemeName = item.scheme_name;
                optionDiv.dataset.schemeHindi = item.scheme_name_hindi;
                optionDiv.dataset.schemeValue = item.total_units;
                optionDiv.dataset.search = `
                    ${item.scheme_name}
                    ${item.scheme_name_hindi}
                    ${item.scheme_code}
                `.toLowerCase();
                // Inner HTML
                optionDiv.innerHTML = `
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary me-2">
                            ${item.scheme_code}
                        </span>
                        <div>
                            <strong>${item.scheme_name}</strong>
                            <span class="ms-2" style="font-size:16px;">
                                (${item.scheme_name_hindi})
                            </span>
                        </div>
                    </div>
                `;
                document.getElementById('schemeSearch').innerHTML = '--select scheme---';
                // Append to container
                schemeSelect.appendChild(optionDiv);
            });
        } catch (error) {
            console.error('Scheme load error:', error);
        }
    },

    selectScheme: async function (option) {
        const searchInput =
            document.getElementById('schemeSearch');
        const customOptions =
            document.getElementById('customOptions');
        const selectedSchemeId =
            document.getElementById('selected_scheme_id');
        const paymentDisplay =
            document.getElementById('payment_amount_display');
        const paymentHidden =
            document.getElementById('payment_amount');
        // Remove old selected option
        document.querySelectorAll('.custom-option')
            .forEach(el => el.classList.remove('selected'));
        // Add selected class
        option.classList.add('selected');
        // Dataset values
        const schemeId = option.dataset.value;
        const schemeName = option.dataset.schemeName;
        // Set scheme id
        selectedSchemeId.value = schemeId;
        // Set scheme name
        searchInput.value = `${schemeName}`;
        try {
            // API call using scheme id
            const response = await fetch(
                `/public/get-scheme-details/${schemeId}`
            );
            if (!response.ok) {
                throw new Error('Failed to fetch scheme');
            }
            const data = await response.json();
            // Property value from API
            const propertyValue = parseFloat(
                data.data.lottery_amount || 0
            );
            // Calculate 10%
            const paymentAmount = propertyValue;
            // Set values
            paymentDisplay.value =
                paymentAmount.toFixed(2);
            paymentHidden.value =
                paymentAmount.toFixed(2);
        } catch (error) {
            console.error(error);
            paymentDisplay.value = '';
            paymentHidden.value = '';
        }
        selectedSchemeId.dispatchEvent(
            new Event('change')
        );
        customOptions.classList.remove('show');
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
        const requiredNames = ['payment_amount', 'payment_date', 'payment_mode', 'division_id', 'subdivision_id', 'pcategory_id', 'property_type_id', 'quarter_id', 'scheme_id'];
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
