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
            this.step5State = JSON.parse(savedState);
            console.log("Retrieved step 3 state:", this.step5State);
        }
    },

    bindEvents: function () {
        // Form submission
        const saveBtn = document.getElementById("saveNameTransferBtn");
        if (saveBtn) {
            saveBtn.addEventListener("click", () => this.saveAllotteeDetails());
        }

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
        if (this.step5State?.applicantId) {
            const applicantIdField = document.getElementById("applicant_id");
            if (applicantIdField) {
                applicantIdField.value = this.step5State.applicantId;
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

    validateForm: function () {
        const form = document.getElementById("nameTransferForm");
        const inputs = form.querySelectorAll("input, select, textarea");
        let isValid = true;
        let firstInvalid = null;

        inputs.forEach(input => {
            if (input.hasAttribute("required") && !input.value.trim()) {
                input.classList.add("is-invalid");
                isValid = false;
                if (!firstInvalid) firstInvalid = input;
            } else {
                input.classList.remove("is-invalid");
            }
        });

        // Email validation
        const emailField = form.querySelector('[name="email"]');
        if (emailField && emailField.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailField.value)) {
            emailField.classList.add("is-invalid");
            isValid = false;
            if (!firstInvalid) firstInvalid = emailField;
        }

        // Mobile validation
        const mobileField = form.querySelector('[name="mobile"]');
        if (mobileField && mobileField.value && !/^[0-9]{10}$/.test(mobileField.value)) {
            mobileField.classList.add("is-invalid");
            isValid = false;
            if (!firstInvalid) firstInvalid = mobileField;
        }

        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });
        }

        return isValid;
    },

    saveAllotteeDetails: function () {
        if (!this.validateForm()) {
            alert("Please fill all required fields correctly");
            return;
        }

        const form = document.getElementById("nameTransferForm");
        const formData = new FormData(form);
        
        // Add applicant ID from step 5 state
        if (this.step5State?.applicantId) {
            formData.append("applicant_id", this.step5State.applicantId);
        }

        const saveBtn = document.getElementById("saveNameTransferBtn");
        const originalText = saveBtn.textContent;

        saveBtn.disabled = true;
        saveBtn.textContent = "Saving...";

        fetch("/applicant/save-name-transfer-allottee", {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || ""
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Allottee details saved successfully");
                
                // Clear stored state
                sessionStorage.removeItem('step5State');
                
                // Return to step 5 and load name transfer documents
                if (this.manager && typeof this.manager.loadStep === 'function') {
                    this.manager.loadStep(5);
                    
                    // Set a flag to load name transfer documents
                    setTimeout(() => {
                        const step5Handler = this.manager.currentHandler;
                        if (step5Handler && typeof step5Handler.loadNameTransferDocuments === 'function') {
                            step5Handler.loadNameTransferDocuments();
                        }
                    }, 500);
                }
            } else {
                alert(data.message || "Error saving details");
                saveBtn.disabled = false;
                saveBtn.textContent = originalText;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Error saving details");
            saveBtn.disabled = false;
            saveBtn.textContent = originalText;
        });
    },

    goBackToDocuments: function () {
        if (confirm("Go back to documents? Any unsaved data will be lost.")) {
            sessionStorage.removeItem('step5State');
            if (this.manager && typeof this.manager.loadStep === 'function') {
                this.manager.loadStep(5);
            }
        }
    },

    validate: function () {
        return this.validateForm();
    },

    destroy: function () {
        console.log("Step 3 Handler Destroyed");
    }
};

// Register with StepManager
StepManager.registerHandler(3, Step3Handler);