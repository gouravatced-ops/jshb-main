const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");
const passwordcnfirm = document.getElementById("password_confirmation");

if (togglePassword && password) {
    togglePassword.addEventListener("click", function () {
        const type = password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);
        if(passwordcnfirm){
            passwordcnfirm.setAttribute("type", type);
        }

        // Toggle icon
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
}

(function () {
    // CAPTCHA GENERATOR (fully functional & case-sensitive)
    const captchaDiv = document.getElementById('captchaCode');
    const refreshBtn = document.getElementById('refreshCaptcha');

    function generateCaptcha() {
        const uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        const lowercase = 'abcdefghjkmnpqrstuvwxyz';
        const numbers = '23456789';
        const chars = uppercase + lowercase + numbers;
        let captcha = '';
        for (let i = 0; i < 5; i++) {
            captcha += chars[Math.floor(Math.random() * chars.length)];
        }
        if (captchaDiv) captchaDiv.innerText = captcha;
        return captcha;
    }
    if (captchaDiv && refreshBtn) {
        generateCaptcha();
        const captchaInput = document.getElementById('captcha_input');
        const loginBtn = document.getElementById('loginBtn');

        function validateCaptcha() {
            if (!captchaDiv || !captchaInput) return;
            const currentCaptcha = captchaDiv.innerText.trim();
            const entered = captchaInput.value.trim();
            if (loginBtn) {
                if (entered === currentCaptcha && entered !== '') {
                    loginBtn.disabled = false;
                } else {
                    loginBtn.disabled = true;
                }
            }
        }

        refreshBtn.addEventListener('click', () => {
            generateCaptcha();
            validateCaptcha();
        });

        captchaInput.addEventListener('input', validateCaptcha);
    }

    // CAROUSEL BACKGROUND SLIDER (auto smooth)
    const slides = document.querySelectorAll('.bg-slide');
    let currentSlide = 0;
    if (slides.length > 0) {
        setInterval(() => {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 4800);
    }

    // dynamically handle otp stage (enable login button for OTP stage by default)
    const loginForm = document.querySelector('.login-form');
    const otpStage = document.querySelector('input[name="otp_stage"]');
    const loginBtnForm = document.getElementById('loginBtn');
    if (otpStage && otpStage.value === '1') {
        if (loginBtnForm) loginBtnForm.disabled = false;
    } else {
        // if captcha exists and fields empty, leave disabled initially; but if captcha pre-filled? we call validation once
        if (captchaDiv && document.getElementById('captcha_input')) {
            const captchaInp = document.getElementById('captcha_input');
            if (captchaInp && captchaInp.value.trim() !== '') {
                const curr = captchaDiv.innerText.trim();
                if (curr && captchaInp.value.trim() === curr) {
                    if (loginBtnForm) loginBtnForm.disabled = false;
                }
            }
        }
    }

    const allLoginForms = document.querySelectorAll('.login-form');
    allLoginForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Guard: Validate captcha matches case-sensitively before submitting
            const captchaBox = this.querySelector('#captchaCode');
            const captchaField = this.querySelector('#captcha_input');
            if (captchaBox && captchaField) {
                if (captchaField.value.trim() !== captchaBox.innerText.trim()) {
                    e.preventDefault();
                    if (loginBtnForm) loginBtnForm.disabled = true;
                    return false;
                }
            }

            if (this.checkValidity()) {
                // Find the submit button within this form
                const btn = this.querySelector('.btn-submit');
                if (btn) {
                    const originalText = btn.innerHTML;
                    let loadingText = 'Processing...';
                    if (originalText.includes('Login')) loadingText = 'Logging in...';
                    else if (originalText.includes('Verify')) loadingText = 'Verifying...';
                    else if (originalText.includes('Send')) loadingText = 'Sending...';
                    else if (originalText.includes('Reset')) loadingText = 'Resetting...';
                    else if (originalText.includes('Proceed')) loadingText = 'Proceeding...';

                    // Using setTimeout ensures the form still submits correctly in all browsers
                    // even if the button gets disabled.
                    setTimeout(() => {
                        btn.disabled = true;
                        btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> ${loadingText}`;
                    }, 10);
                }
            }
        });
    });
})();

// ─── RESEND OTP COOLDOWN TIMER ────────────────────────────────
(function () {
    function startResendTimer(btnId, timerId, seconds) {
        const btn = document.getElementById(btnId);
        const timer = document.getElementById(timerId);
        if (!btn) return;

        let remaining = seconds;
        btn.disabled = true;

        const interval = setInterval(() => {
            if (timer) timer.textContent = `Wait ${remaining}s`;
            remaining--;

            if (remaining < 0) {
                clearInterval(interval);
                btn.disabled = false;
                if (timer) timer.textContent = '';
            }
        }, 1000);
    }

    // Start 60s cooldown on page load if Resend button is present (prevents spam)
    if (document.getElementById('btnResendLogin')) {
        startResendTimer('btnResendLogin', 'resendTimerLogin', 60);
    }
    if (document.getElementById('btnResendForgot')) {
        startResendTimer('btnResendForgot', 'resendTimerForgot', 60);
    }
})();

(function () {
    // Toggle logic (Default: Password login; Click: Toggle 2FA)
    const toggleBtn = document.getElementById('toggle-login-mode');
    const toggleText = document.getElementById('toggle-text');
    const toggleIcon = toggleBtn ? toggleBtn.querySelector('i') : null;
    const usernameSection = document.getElementById('username-login-section');
    const emailLabel = document.getElementById('email-label');
    const loginMethodInput = document.getElementById('login_method');
    const submitBtnText = document.getElementById('submit-btn-text');
    const passwordInput = document.getElementById('password');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const isPasswordMode = loginMethodInput ? loginMethodInput.value === 'username' : true;
            
            if (isPasswordMode) {
                // Switch from Password to 2FA Mode
                if (usernameSection) usernameSection.classList.remove('show');
                toggleBtn.classList.add('toggled');
                if (toggleIcon) toggleIcon.className = 'fa-solid fa-lock';
                if (emailLabel) emailLabel.innerHTML = '<i class="fa-regular fa-envelope"></i> Email Address';
                if (loginMethodInput) loginMethodInput.value = '2fa_only';
                if (submitBtnText) submitBtnText.innerText = 'Proceed';
                if (passwordInput) {
                    passwordInput.required = false;
                    passwordInput.value = '';
                }
                if (toggleText) toggleText.innerText = 'Login with Password instead';
            } else {
                // Switch from 2FA to Password Mode
                if (usernameSection) usernameSection.classList.add('show');
                toggleBtn.classList.remove('toggled');
                if (toggleIcon) toggleIcon.className = 'fa-solid fa-shield-halved';
                if (emailLabel) emailLabel.innerHTML = '<i class="fa-regular fa-envelope"></i> Email or Username';
                if (loginMethodInput) loginMethodInput.value = 'username';
                if (submitBtnText) submitBtnText.innerText = 'Login to Account';
                if (passwordInput) passwordInput.required = true;
                if (toggleText) toggleText.innerText = 'Login with 2FA instead';
            }
        });
    }

    // Digit Box Logic
    const digitBoxes = document.querySelectorAll('.digit-box');
    const otpHidden = document.getElementById('otp_code_hidden');
    const loginBtn = document.getElementById('loginBtn');

    if (digitBoxes.length > 0) {
        digitBoxes.forEach((box, index) => {
            box.addEventListener('keyup', (e) => {
                if (e.key >= '0' && e.key <= '9') {
                    if (box.dataset.next) {
                        document.getElementById(box.dataset.next).focus();
                    }
                    updateHiddenOtp();
                } else if (e.key === 'Backspace') {
                    if (box.dataset.previous && box.value === '') {
                        document.getElementById(box.dataset.previous).focus();
                    }
                    updateHiddenOtp();
                } else {
                    box.value = ''; // Ensure only numbers are entered
                }
            });

            // Prevent copy/paste completely via JS as fallback
            box.addEventListener('paste', e => e.preventDefault());
            box.addEventListener('copy', e => e.preventDefault());
        });

        function updateHiddenOtp() {
            let val = '';
            digitBoxes.forEach(b => val += b.value);
            if (otpHidden) otpHidden.value = val;
            
            // Auto submit on last digit
            if (val.length === 6) {
                if (!loginBtn.disabled) {
                    loginBtn.click();
                }
            }
        }
    }
})();