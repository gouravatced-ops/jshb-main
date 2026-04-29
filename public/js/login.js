const slides = document.querySelector('.slides');
const slideItems = document.querySelectorAll('.slide');

let index = 0;
const totalSlides = slideItems.length;

function showSlide(i) {
    slides.style.transform = `translateX(-${i * 33.33}%)`;
}

function nextSlide() {
    index = (index + 1) % totalSlides;
    showSlide(index);
}

setInterval(nextSlide, 4000);
showSlide(index);

function generateCaptchaValue(length = 6) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUV987654321';
    let value = '';
    for (let i = 0; i < length; i++) {
        value += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return value;
}

const captchaInput = document.getElementById('captcha_input');
const captchaCode = document.getElementById('captchaCode');
const refreshCaptcha = document.getElementById('refreshCaptcha');
const loginBtn = document.getElementById('loginBtn');
const captchaMessage = document.getElementById('captchaMessage');
let currentCaptcha = '';

function updateCaptchaStatus() {
    if (!captchaInput || !loginBtn || !captchaCode) return;
    const value = captchaInput.value.trim().toUpperCase();
    if (value === currentCaptcha && value.length > 0) {
        loginBtn.disabled = false;
        captchaMessage.textContent = 'Captcha verified. You can now log in.';
        captchaMessage.style.color = '#16a34a';
    } else {
        loginBtn.disabled = true;
        captchaMessage.textContent = '';
        captchaMessage.style.color = '#64748b';
    }
}

function refreshCaptchaValue() {
    currentCaptcha = generateCaptchaValue();
    if (captchaCode) captchaCode.textContent = currentCaptcha;
    if (captchaInput) captchaInput.value = '';
    updateCaptchaStatus();
}

if (captchaInput) {
    refreshCaptchaValue();
    captchaInput.addEventListener('input', updateCaptchaStatus);
}

if (refreshCaptcha) {
    refreshCaptcha.addEventListener('click', refreshCaptchaValue);
}