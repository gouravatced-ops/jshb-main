// ============================================
// STEP 2 HANDLER - Address Details
// ============================================
const Step2Handler = {
    manager: null,
    
    init: function() {
        console.log('Step 2 Handler Initialized');
        this.bindEvents();
        this.initializeAddressCopy();
    },

    bindEvents: function() {
        // State-District cascading
        document.querySelectorAll('.state-select, .state-select-hindi').forEach(select => {
            select.addEventListener('change', this.loadDistricts);
        });

        // Address copy checkboxes
        const copyCheckboxes = [
            'same_as_relation_copy',
            'same_as_present_place_residance',
            'same_as_permanent_address'
        ];

        copyCheckboxes.forEach(id => {
            const checkbox = document.getElementById(id);
            if (checkbox) {
                checkbox.addEventListener('change', (e) => this.handleAddressCopy(e.target));
            }
        });

        // Input restrictions
        this.applyInputRestrictions();
    },

    initializeAddressCopy: function() {
        // Initial state for district dropdowns - No need to load districts here
        // as they're already populated by the server
    },

    loadDistricts: function() {
        const stateId = this.value;
        const targetId = this.dataset.target;
        const districtSelect = document.getElementById(targetId);

        if (!districtSelect) return;

        // Store the currently selected value
        const currentSelectedValue = districtSelect.value;

        districtSelect.innerHTML = '<option value="">-- Select District --</option>';

        if (!stateId) return;

        fetch(`/districts/${stateId}`)
            .then(res => res.json())
            .then(data => {
                const isHindi = targetId.includes('hi');
                
                // Clear and add default option
                districtSelect.innerHTML = isHindi ? 
                    '<option value="">-- जिला चुनें --</option>' : 
                    '<option value="">-- Select District --</option>';

                // Add district options
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = isHindi ? item.name_hi : item.name_en;
                    
                    // Check if this district was previously selected
                    if (currentSelectedValue && currentSelectedValue == item.id) {
                        option.selected = true;
                    }
                    
                    districtSelect.appendChild(option);
                });

                // If we have a selected value from before, make sure it's set
                if (currentSelectedValue) {
                    districtSelect.value = currentSelectedValue;
                }
            })
            .catch(error => console.error('Error loading districts:', error));
    },

    handleAddressCopy: function(checkbox) {
        const fieldMaps = {
            'same_as_relation_copy': [
                ['relation_address', 'present_address'],
                ['relation_address_hindi', 'present_address_hindi'],
                ['relation_state', 'present_state'],
                ['relation_state_hindi', 'present_state_hindi'],
                ['relation_district', 'present_district'],
                ['relation_district_hindi', 'present_district_hindi'],
                ['relation_pincode', 'present_pincode'],
                ['relation_pincode_hindi', 'present_pincode_hindi'],
                ['relation_post_office', 'present_post_office'],
                ['relation_post_office_hindi', 'present_post_office_hindi'],
                ['relation_police_station', 'present_police_station'],
                ['relation_police_station_hindi', 'present_police_station_hindi']
            ],
            'same_as_present_place_residance': [
                ['present_address', 'permanent_address'],
                ['present_address_hindi', 'permanent_address_hindi'],
                ['present_state', 'permanent_state'],
                ['present_state_hindi', 'permanent_state_hindi'],
                ['present_district', 'permanent_district'],
                ['present_district_hindi', 'permanent_district_hindi'],
                ['present_pincode', 'permanent_pincode'],
                ['present_pincode_hindi', 'permanent_pincode_hindi'],
                ['present_post_office', 'permanent_post_office'],
                ['present_post_office_hindi', 'permanent_post_office_hindi'],
                ['present_police_station', 'permanent_police_station'],
                ['present_police_station_hindi', 'permanent_police_station_hindi']
            ],
            'same_as_permanent_address': [
                ['permanent_address', 'correspondence_address'],
                ['permanent_address_hindi', 'correspondence_address_hindi'],
                ['permanent_state', 'correspondence_state'],
                ['permanent_state_hindi', 'correspondence_state_hindi'],
                ['permanent_district', 'correspondence_district'],
                ['permanent_district_hindi', 'correspondence_district_hindi'],
                ['permanent_pincode', 'correspondence_pincode'],
                ['permanent_pincode_hindi', 'correspondence_pincode_hindi'],
                ['permanent_post_office', 'correspondence_post_office'],
                ['permanent_post_office_hindi', 'correspondence_post_office_hindi'],
                ['permanent_police_station', 'correspondence_police_station'],
                ['permanent_police_station_hindi', 'correspondence_police_station_hindi']
            ]
        };

        const fieldMap = fieldMaps[checkbox.id];
        if (!fieldMap) return;

        fieldMap.forEach(([from, to]) => {
            const fromEl = document.querySelector(`[name="${from}"]`);
            const toEl = document.querySelector(`[name="${to}"]`);
            if (fromEl && toEl) {
                if (checkbox.checked) {
                    toEl.value = fromEl.value;
                    
                    // If it's a state select, trigger change to load districts
                    if (to.includes('state')) {
                        toEl.dispatchEvent(new Event('change'));
                    }
                } else {
                    toEl.value = '';
                }
            }
        });
    },

    applyInputRestrictions: function() {
        // Only Numbers (0-9)
        document.querySelectorAll(".only-number").forEach(input => {
            input.addEventListener("input", function() {
                this.value = this.value.replace(/[^0-9]/g, "");
            });
        });

        // Only Alphabets (A-Z + space)
        document.querySelectorAll(".only-alphabet").forEach(input => {
            input.addEventListener("input", function() {
                this.value = this.value.replace(/[^a-zA-Z\s]/g, "");
            });
        });

        // Only Hindi
        document.querySelectorAll(".only-hindi").forEach(input => {
            input.addEventListener("input", function() {
                this.value = this.value.replace(/[^\u0900-\u097F\s]/g, "");
            });
        });

        // Only Email
        document.querySelectorAll(".only-email").forEach(input => {
            input.addEventListener("input", function() {
                this.value = this.value.replace(/[^a-zA-Z0-9@._\-]/g, "");
            });
        });
    },

    destroy: function() {
        console.log('Step 2 Handler Destroyed');
    }
};

// Register with StepManager
if (typeof StepManager !== 'undefined') {
    StepManager.registerHandler(2, Step2Handler);
}