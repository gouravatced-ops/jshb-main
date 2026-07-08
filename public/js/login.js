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
    // CAPTCHA GENERATOR (fully functional)
    const captchaDiv = document.getElementById('captchaCode');
    const refreshBtn = document.getElementById('refreshCaptcha');

    function generateCaptcha() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789';
        let captcha = '';
        for (let i = 0; i < 5; i++) {
            captcha += chars[Math.floor(Math.random() * chars.length)];
        }
        if (captchaDiv) captchaDiv.innerText = captcha;
        return captcha;
    }
    if (captchaDiv && refreshBtn) {
        generateCaptcha();
        refreshBtn.addEventListener('click', () => generateCaptcha());
        const captchaInput = document.getElementById('captcha_input');
        const loginBtn = document.getElementById('loginBtn');

        function validateCaptcha() {
            if (!captchaDiv || !captchaInput) return;
            const currentCaptcha = captchaDiv.innerText;
            const entered = captchaInput.value.trim().toUpperCase();
            if (loginBtn) {
                if (entered === currentCaptcha && entered !== '') {
                    loginBtn.disabled = false;
                } else {
                    loginBtn.disabled = true;
                }
            }
        }
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
                const curr = captchaDiv.innerText;
                if (curr && captchaInp.value.trim().toUpperCase() === curr) {
                    if (loginBtnForm) loginBtnForm.disabled = false;
                }
            }
        }
    }

    const allLoginForms = document.querySelectorAll('.login-form');
    allLoginForms.forEach(form => {
        form.addEventListener('submit', function() {
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