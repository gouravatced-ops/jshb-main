// ============================================
// STEP 3 HANDLER - Name Transfer Allottee Details
// ============================================
const Step3Handler = {
    manager: null,
    
    init: function () {
        console.log("Step 3 Handler Initialized - Name Transfer Allottee Details");
        
        // Retrieve state from step 2
        this.retrieveState();
        
        this.bindEvents();
        this.initializeForm();
    },

    retrieveState: function () {
        const savedState = sessionStorage.getItem('step3State');
        if (savedState) {
            this.step3State = JSON.parse(savedState);
            console.log("Retrieved step 3 state:", this.step3State);
        }
    },

    bindEvents: function () {
        // Input validations
        this.applyInputRestrictions();

        // Cancel/Back button
        const backBtn = document.getElementById("backToDocumentsBtn");
        if (backBtn) {
            backBtn.addEventListener("click", () => this.goBackToDocuments());
        }
    },

    initializeForm: function () {
        // Set applicant ID if available
        if (this.step3State?.applicantId) {
            const applicantIdField = document.getElementById("applicant_id");
            if (applicantIdField) {
                applicantIdField.value = this.step3State.applicantId;
            }
        }

        // Pre-fill any data if needed
    },

    applyInputRestrictions: function () {
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

        // Email validation
        document.querySelectorAll(".only-email").forEach(input => {
            input.addEventListener("input", function() {
                this.value = this.value.replace(/[^a-zA-Z0-9@._\-]/g, "");
            });
        });
    },

    goBackToDocuments: function () {
        if (confirm("Go back to documents? Any unsaved data will be lost.")) {
            sessionStorage.removeItem('step3State');
            if (this.manager && typeof this.manager.loadStep === 'function') {
                this.manager.loadStep(3);
            }
        }
    },

    destroy: function () {
        console.log("Step 3 Handler Destroyed");
    }
};

// Register with StepManager
StepManager.registerHandler(3, Step3Handler);