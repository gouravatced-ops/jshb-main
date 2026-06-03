// ============================================
// STEP 1 HANDLER - Allottee Details
// ============================================
const Step1Handler = {
    manager: null, // Will be set by StepManager

    init: function () {
        console.log('Step 1 Handler Initialized');
        this.bindEvents();
        this.initializeFields();
    },

    bindEvents: function () {

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

        // Year validation
        const yearInput = document.getElementById("allotmentYear");
        const applicationYearSelect = document.getElementById("application_year");
        const errorText = document.getElementById("yearError");

        if (yearInput) {
            yearInput.addEventListener("input", (e) => this.validateYear(e, applicationYearSelect, errorText));
        }

        // Application year change
        if (applicationYearSelect) {
            applicationYearSelect.addEventListener("change", (e) => this.handleApplicationYearChange(e));
        }

        // Allotment year change (for PAN/Aadhar toggle)
        // const allotmentYear = document.getElementById('allotment_year');
        // if (allotmentYear) {
        //     allotmentYear.addEventListener('change', () => this.togglePanAadhar());
        // }
        this.togglePanAadhar();

        // DOB calculation
        ['date_of_birth_day', 'date_of_birth_month', 'date_of_birth_year'].forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.addEventListener('change', () => this.calculateAge());
            }
        });

        // Input restrictions
        this.applyInputRestrictions();
    },

    initializeFields: function () {
        // Initial PAN/Aadhar toggle
        this.togglePanAadhar();

        // Initial age calculation if DOB exists
        this.calculateAge();
    },

    loadSubDivision: function () {
        const divisionId = this.value;
        const subDivisionSelect = document.getElementById('subdivisionSelect')
        if (!divisionId) return;
        const currentSelectedValue = divisionId;
        subDivisionSelect.innerHTML = '<option value="">Loading ...</option>';

        fetch(`/get-sub-divisions/${divisionId}`)
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
        fetch(`/get-property-types/${pCategoryId}`)
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
        fetch(`/get-property-sub-types/${pTypeId}`)
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

            const response = await fetch('/scheme-list', {
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

    selectScheme: function (option) {
        const searchInput =
            document.getElementById('schemeSearch');

        const customOptions =
            document.getElementById('customOptions');

        const selectedSchemeId =
            document.getElementById('selected_scheme_id');

        // Remove old selected option
        document.querySelectorAll('.custom-option')
            .forEach(el => el.classList.remove('selected'));

        // Add selected class
        option.classList.add('selected');

        // Get dataset values
        const schemeId = option.dataset.value;
        const schemeCode = option.dataset.schemeCode;
        const schemeName = option.dataset.schemeName;

        // Set hidden scheme id
        selectedSchemeId.value = schemeId;

        // searchInput.value =
        //     `Code : ${schemeCode} Scheme :${schemeName}`;

        searchInput.value = `${schemeName}`;

        selectedSchemeId.dispatchEvent(
            new Event('change')
        );

        customOptions.classList.remove('show');
    },

    validateYear: function (e, applicationYearSelect, errorText) {
        let value = e.target.value.trim().replace(/[^0-9]/g, '');
        e.target.value = value;

        if (value.length !== 4) {
            e.target.classList.remove("invalid-year");
            if (errorText) errorText.textContent = "";
            return;
        }

        const currentYear = new Date().getFullYear();
        const minYear = 1950;
        const appYear = parseInt(applicationYearSelect?.value || 0);
        const allotYear = parseInt(value);

        if (allotYear < minYear || allotYear > currentYear) {
            e.target.classList.add("invalid-year");
            if (errorText) {
                errorText.textContent = `Year must be between ${minYear} and ${currentYear}`;
            }
            return;
        }

        if (appYear && allotYear < appYear) {
            e.target.classList.add("invalid-year");
            if (errorText) {
                errorText.textContent = `Allotment Year cannot be less than Application Year (${appYear})`;
            }
            return;
        }

        e.target.classList.remove("invalid-year");
        if (errorText) errorText.textContent = "";
    },

    handleApplicationYearChange: function (e) {
        let appYear = parseInt(e.target.value);
        let allotSelect = document.getElementById("allotment_year");

        if (!appYear) return;

        Array.from(allotSelect.options).forEach(option => {
            if (!option.value) return;
            let allotYear = parseInt(option.value);
            option.hidden = allotYear < appYear;
        });

        if (parseInt(allotSelect.value) < appYear) {
            allotSelect.value = "";
        }
    },

    togglePanAadhar: function () {
        const yearInput = document.getElementById('allotment_year');
        const panField = document.getElementById('pan-field');
        const aadharField = document.getElementById('aadhar-field');

        if (!yearInput || !panField || !aadharField) return;

        const year = yearInput.value ? parseInt(yearInput.value) : null;
        const show = year && year >= 2009;

        panField.style.display = show ? 'none' : 'block';
        aadharField.style.display = show ? 'none' : 'block';
    },

    calculateAge: function () {
        const day = document.querySelector('[name="date_of_birth_day"]')?.value;
        const month = document.querySelector('[name="date_of_birth_month"]')?.value;
        const year = document.querySelector('[name="date_of_birth_year"]')?.value;

        if (!day || !month || !year) return;

        const dob = new Date(year, month - 1, day);
        const today = new Date();

        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();

        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        const ageField = document.getElementById("current_age");
        if (ageField) {
            ageField.value = age + " years";
            ageField.readOnly = true;
        }
    },

    applyInputRestrictions: function () {
        // Only Numbers (0-9)
        document.querySelectorAll(".only-number").forEach(input => {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^0-9]/g, "");
            });
        });

        // Only Alphabets (A-Z + space)
        document.querySelectorAll(".only-alphabet").forEach(input => {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^a-zA-Z\s]/g, "");
            });
        });

        // Only Hindi
        document.querySelectorAll(".only-hindi").forEach(input => {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^\u0900-\u097F\s]/g, "");
            });
        });

        // PAN Card Format: ABCDE1234F
        document.querySelectorAll(".pan-input").forEach((input) => {
            input.addEventListener("input", function () {
                // Convert to uppercase
                this.value = this.value.toUpperCase();

                // Remove invalid characters
                this.value = this.value.replace(/[^A-Z0-9]/g, "");

                // Limit length to 10
                if (this.value.length > 10) {
                    this.value = this.value.slice(0, 10);
                }
            });
        });
    },

    validate: function () {
        const form = document.querySelector('#step1Form');
        if (!form) return true;
        let valid = true;
        let firstInvalid = null;
        // Remove old validation classes
        form.querySelectorAll('.is-invalid')
            .forEach(field => {
                field.classList.remove('is-invalid');
            });
        // Required fields
        const requiredFields = [
            'application_no',
            'allotment_no',
            'allotment_day',
            'allotment_month',
            'allotment_year',
            'allottee_name',
            'application_day',
            'application_month',
            'application_year',
            'allotmentYear',
            'allottee_category',
            'allottee_religion',
            'date_of_birth_day',
            'date_of_birth_month',
            'date_of_birth_year',
            'relation_prefix',
            'relation_name',
            'year'
        ];
        // Common required validation
        requiredFields.forEach(fieldName => {
            const field =
                form.querySelector(`[name="${fieldName}"]`);

            if (!field) return;
            const value =
                field.value?.trim();
            if (!value) {
                field.classList.add('is-invalid');
                valid = false;
                if (!firstInvalid) {
                    firstInvalid = field;
                }
            }
        });
        // PAN & Aadhar validation only for 2009+
        // const allotmentYear =
        //     parseInt(
        //         form.querySelector('[name="allotment_year"]')
        //             ?.value || 0
        //     );
        // if (allotmentYear >= 2009) {
        //     ['pan_card_number', 'aadhar_card_number']
        //         .forEach(fieldName => {
        //             const field =
        //                 form.querySelector(
        //                     `[name="${fieldName}"]`
        //                 );
        //             if (!field) return;
        //             const value =
        //                 field.value?.trim();
        //             if (!value) {
        //                 field.classList.add('is-invalid');
        //                 valid = false;
        //                 if (!firstInvalid) {
        //                     firstInvalid = field;
        //                 }
        //             }
        //         });
        // }

        // Invalid year check
        const yearField =
            document.getElementById('allotmentYear');

        if (yearField && yearField.classList.contains('invalid-year')) {
            yearField.classList.add('is-invalid');
            valid = false;
            if (!firstInvalid) {
                firstInvalid = yearField;
            }
        }

        // Scroll to first invalid field
        if (firstInvalid) {
            firstInvalid.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            firstInvalid.focus();
        }
        return valid;
    },

    destroy: function () {
        console.log('Step 1 Handler Destroyed');
        // Clean up any global event listeners if needed
    }
};

// Register with StepManager
StepManager.registerHandler(1, Step1Handler);