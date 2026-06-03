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

document.querySelectorAll(".only-alphanumeric").forEach(input => {
    input.addEventListener("input", function () {
        this.value = this.value.replace(/[^a-zA-Z0-9\s]/g, "");
    });
});

// Only Hindi
document.querySelectorAll(".only-hindi").forEach(input => {
    input.addEventListener("input", function() {
        this.value = this.value.replace(/[^\u0900-\u097F\s]/g, "");
    });
});

document.querySelectorAll(".email-validation").forEach(input => {
    input.addEventListener("input", function () {
        const email = this.value.trim();

        // Simple & reliable email regex
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email === "") {
            this.setCustomValidity("Email is required");
        } else if (!regex.test(email)) {
            this.setCustomValidity("Enter a valid email address");
        } else {
            this.setCustomValidity("");
        }
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