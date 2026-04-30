const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");
const passwordcnfirm = document.getElementById("password_confirmation");

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
})();