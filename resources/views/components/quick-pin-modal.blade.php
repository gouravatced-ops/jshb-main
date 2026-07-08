@php
    $hasPin = !empty(auth()->user()->secure_pin);
@endphp

<!-- Quick PIN Settings Modal -->
<div id="quickPinSettingsModal" class="password-reset-modal" style="display: none;">
    <div class="password-reset-overlay" onclick="closeQuickPinModal()"></div>
    <div class="password-reset-container">
        <!-- Modal Header -->
        <div class="password-reset-header">
            <div class="password-reset-title-section">
                <h2 class="password-reset-title">
                    <i class="fa-solid fa-th-large"></i> {{ $hasPin ? 'Update Quick PIN' : 'Set Quick PIN' }}
                </h2>
                <p class="password-reset-subtitle">
                    {{ $hasPin ? 'Change your 4-digit quick login PIN' : 'Set a new 4-digit quick login PIN' }}
                </p>
            </div>
            <button type="button" class="password-reset-close" onclick="closeQuickPinModal()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="password-reset-body">
            <form id="quickPinSettingsForm">
                
                @if($hasPin)
                <!-- Current PIN -->
                <div class="password-form-group" id="qpCurrentPinGroup">
                    <label for="qpCurrentPin" class="password-form-label">
                        <i class="fa-solid fa-key"></i> Current PIN
                    </label>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            id="qpCurrentPin" 
                            class="password-form-input"
                            placeholder="Enter your current 4-digit PIN"
                            maxlength="4"
                            pattern="\d{4}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);"
                            required
                            style="padding-right: 40px;"
                        >
                        <button type="button" onclick="const p = document.getElementById('qpCurrentPin'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerHTML = p.type === 'password' ? '<i class=\'fa-regular fa-eye\'></i>' : '<i class=\'fa-regular fa-eye-slash\'></i>';" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; padding: 0;">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <span class="password-form-error" id="qpCurrentPinError"></span>
                </div>
                @endif

                <!-- New PIN -->
                <div class="password-form-group">
                    <label for="qpNewPin" class="password-form-label">
                        <i class="fa-solid fa-lock"></i> New 4-Digit PIN
                    </label>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            id="qpNewPin" 
                            class="password-form-input"
                            placeholder="Enter new 4-digit PIN"
                            maxlength="4"
                            pattern="\d{4}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);"
                            required
                            style="padding-right: 40px;"
                        >
                        <button type="button" onclick="const p = document.getElementById('qpNewPin'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerHTML = p.type === 'password' ? '<i class=\'fa-regular fa-eye\'></i>' : '<i class=\'fa-regular fa-eye-slash\'></i>';" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; padding: 0;">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <span class="password-form-error" id="qpNewPinError"></span>
                </div>

                <x-global-otp-verify purpose="update_quick_pin" buttonText="Send OTP to Verify" />

                <!-- Captcha (For both Set and Update) -->
                <div class="password-form-group" id="qpCaptchaGroup">
                    <label class="password-form-label">
                        <i class="fa-solid fa-shield-alt"></i> Security Question
                    </label>
                    <div class="captcha-container">
                        <div class="captcha-question" id="qpCaptchaQuestion">
                            <i class="fa-solid fa-spinner fa-spin"></i> Loading...
                        </div>
                        <input 
                            type="number" 
                            id="qpCaptchaAnswer" 
                            class="password-form-input"
                            placeholder="Enter your answer"
                            required
                        >
                        <button 
                            type="button" 
                            class="captcha-refresh-btn" 
                            onclick="refreshQPCaptcha()"
                            title="Generate new question"
                        >
                            <i class="fa-solid fa-redo"></i>
                        </button>
                    </div>
                    <span class="password-form-error" id="qpCaptchaError"></span>
                </div>

            </form>

            <div id="quickPinMessage" class="password-reset-message" style="display: none;"></div>
        </div>

        <!-- Modal Footer -->
        <div class="password-reset-footer">
            <button type="button" class="password-reset-btn-cancel" onclick="closeQuickPinModal()">
                Cancel
            </button>
            <button type="button" class="password-reset-btn-submit" id="quickPinSubmitBtn" onclick="submitQuickPinSettings()" disabled style="opacity: 0.6; cursor: not-allowed;" onmouseover="if(this.disabled) this.style.cursor='not-allowed'; else this.style.cursor='pointer';" onmouseout="if(this.disabled) this.style.cursor='not-allowed';">
                <i class="fa-solid fa-check-circle"></i> Save PIN
            </button>
        </div>
    </div>
</div>

<script>
const hasPin = {{ $hasPin ? 'true' : 'false' }};

function openQuickPinModal(event) {
    if (event) event.preventDefault();
    document.getElementById('quickPinSettingsModal').style.display = 'flex';
    
    if (hasPin) {
        document.getElementById('qpCurrentPin').value = '';
    }
    
    document.getElementById('qpNewPin').value = '';
    document.getElementById('qpCaptchaAnswer').value = '';
    refreshQPCaptcha();
    clearQuickPinErrors();
    
    // Close profile dropdown if open
    const profileDropdown = document.getElementById('profileDropdown');
    if (profileDropdown) profileDropdown.classList.remove('show');

    // Reset OTP state
    document.getElementById('quickPinSubmitBtn').disabled = true;
    document.getElementById('quickPinSubmitBtn').style.opacity = '0.6';
    if (document.getElementById('otp-state-send-update_quick_pin')) {
        document.getElementById('otp-state-send-update_quick_pin').style.display = 'block';
        document.getElementById('otp-state-verify-update_quick_pin').style.display = 'none';
        document.getElementById('otp-state-verified-update_quick_pin').style.display = 'none';
        document.getElementById('input-otp-update_quick_pin').value = '';
        document.getElementById('btn-send-otp-update_quick_pin').disabled = false;
        document.getElementById('text-send-otp-update_quick_pin').innerText = 'Send OTP to Verify';
    }
}

function closeQuickPinModal() {
    document.getElementById('quickPinSettingsModal').style.display = 'none';
}

function refreshQPCaptcha() {
    const questionDiv = document.getElementById('qpCaptchaQuestion');
    if (!questionDiv) return;
    
    questionDiv.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';
    
    fetch('/password/generate-captcha', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    })
    .then(res => res.json())
    .then(data => {
        questionDiv.textContent = data.question;
        document.getElementById('qpCaptchaAnswer').value = '';
    })
    .catch(err => {
        questionDiv.innerHTML = '<span style="color: red;">Error. Refresh</span>';
    });
}

function clearQuickPinErrors() {
    document.querySelectorAll('#quickPinSettingsModal .password-form-error').forEach(el => el.textContent = '');
    document.getElementById('quickPinMessage').style.display = 'none';
}

function showQuickPinMessage(msg, isError = false) {
    const msgDiv = document.getElementById('quickPinMessage');
    msgDiv.textContent = msg;
    msgDiv.className = isError ? 'password-reset-message error' : 'password-reset-message success';
    msgDiv.style.display = 'block';
}

function submitQuickPinSettings() {
    clearQuickPinErrors();
    
    let currentPin = '';
    const newPin = document.getElementById('qpNewPin').value;
    const captchaAnswer = document.getElementById('qpCaptchaAnswer').value;
    
    let hasError = false;
    
    if (hasPin) {
        currentPin = document.getElementById('qpCurrentPin').value;
        if (!currentPin || currentPin.length !== 4) {
            document.getElementById('qpCurrentPinError').textContent = 'Please enter your current 4-digit PIN.';
            hasError = true;
        }
    }
    
    if (!newPin || newPin.length !== 4) {
        document.getElementById('qpNewPinError').textContent = 'Please enter a new 4-digit PIN.';
        hasError = true;
    }

    if (!captchaAnswer) {
        document.getElementById('qpCaptchaError').textContent = 'Please answer the security question.';
        hasError = true;
    }
    
    if (hasError) return;
    
    const btn = document.getElementById('quickPinSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
    
    const bodyData = { 
        secure_pin: newPin,
        captcha_answer: captchaAnswer
    };
    if (hasPin) {
        bodyData.current_pin = currentPin;
    }
    
    fetch('/password/update-quick-pin', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(bodyData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showQuickPinMessage(data.message || 'PIN updated successfully!');
            setTimeout(() => {
                closeQuickPinModal();
                if(typeof showToast === 'function') {
                    showToast('success', 'Success', 'Your Quick PIN has been updated.');
                }
                // Reload to reflect state change from Set to Update
                if (!hasPin) {
                    window.location.reload();
                }
            }, 1500);
        } else {
            showQuickPinMessage(data.message || 'An error occurred', true);
            if (!hasPin) refreshQPCaptcha();
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Save PIN';
        }
    })
    .catch(err => {
        showQuickPinMessage('A server error occurred. Please try again.', true);
        if (!hasPin) refreshQPCaptcha();
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Save PIN';
    });
}

document.addEventListener('otpVerified:update_quick_pin', function() {
    const btn = document.getElementById('quickPinSubmitBtn');
    if (btn) {
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
    }
});
</script>
