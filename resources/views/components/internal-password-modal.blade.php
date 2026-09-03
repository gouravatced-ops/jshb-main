<!-- Update Internal Password Modal -->
<div id="internalPasswordModal" class="password-reset-modal" style="display: none;">
    <div class="password-reset-overlay" onclick="closeInternalPasswordModal()"></div>
    <div class="password-reset-container">
        <!-- Modal Header -->
        <div class="password-reset-header">
            <div class="password-reset-title-section">
                <h2 class="password-reset-title">
                    <i class="fa-solid fa-user-shield"></i> Update Internal Password
                </h2>
                <p class="password-reset-subtitle">Change your internal operation password</p>
            </div>
            <button type="button" class="password-reset-close" onclick="closeInternalPasswordModal()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="password-reset-body">
            <form id="internalPasswordForm">
                
                <div class="password-form-group">
                    <label for="upCurrentInternalPassword" class="password-form-label">
                        <i class="fa-solid fa-key"></i> Current Internal Password
                    </label>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            id="upCurrentInternalPassword" 
                            class="password-form-input"
                            placeholder="Enter current internal password"
                            required
                            style="padding-right: 40px;"
                        >
                        <button type="button" onclick="const p = document.getElementById('upCurrentInternalPassword'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerHTML = p.type === 'password' ? '<i class=\'fa-regular fa-eye\'></i>' : '<i class=\'fa-regular fa-eye-slash\'></i>';" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; padding: 0;">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <span class="password-form-error" id="upCurrentInternalPasswordError"></span>
                </div>

                <div class="password-form-group">
                    <label for="upNewInternalPassword" class="password-form-label">
                        <i class="fa-solid fa-lock"></i> New Internal Password
                    </label>
                    <div style="font-size: 11px; color: #6b7280; margin-bottom: 8px;">
                        Note: Password must be at least 8 characters, containing at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.
                    </div>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            id="upNewInternalPassword" 
                            class="password-form-input"
                            placeholder="Enter new internal password (min. 8 chars)"
                            required
                            style="padding-right: 40px;"
                        >
                        <button type="button" onclick="const p = document.getElementById('upNewInternalPassword'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerHTML = p.type === 'password' ? '<i class=\'fa-regular fa-eye\'></i>' : '<i class=\'fa-regular fa-eye-slash\'></i>';" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; padding: 0;">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <span class="password-form-error" id="upNewInternalPasswordError"></span>
                </div>

                <div class="password-form-group">
                    <label for="upConfirmInternalPassword" class="password-form-label">
                        <i class="fa-solid fa-check"></i> Confirm New Password
                    </label>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            id="upConfirmInternalPassword" 
                            class="password-form-input"
                            placeholder="Confirm new internal password"
                            required
                            style="padding-right: 40px;"
                        >
                        <button type="button" onclick="const p = document.getElementById('upConfirmInternalPassword'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerHTML = p.type === 'password' ? '<i class=\'fa-regular fa-eye\'></i>' : '<i class=\'fa-regular fa-eye-slash\'></i>';" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; padding: 0;">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <span class="password-form-error" id="upConfirmInternalPasswordError"></span>
                </div>

                <x-global-otp-verify purpose="update_internal_password" buttonText="Send OTP to Verify" />

                <!-- Captcha -->
                <div class="password-form-group">
                    <label class="password-form-label">
                        <i class="fa-solid fa-shield-alt"></i> Security Question
                    </label>
                    <div class="captcha-container">
                        <div class="captcha-question" id="upCaptchaQuestion">
                            <i class="fa-solid fa-spinner fa-spin"></i> Loading...
                        </div>
                        <input 
                            type="number" 
                            id="upCaptchaAnswer" 
                            class="password-form-input"
                            placeholder="Enter your answer"
                            required
                        >
                        <button 
                            type="button" 
                            class="captcha-refresh-btn" 
                            onclick="refreshUPCaptcha()"
                            title="Generate new question"
                        >
                            <i class="fa-solid fa-redo"></i>
                        </button>
                    </div>
                    <span class="password-form-error" id="upCaptchaError"></span>
                </div>
            </form>

            <div id="internalPasswordMessage" class="password-reset-message" style="display: none;"></div>
        </div>

        <!-- Modal Footer -->
        <div class="password-reset-footer">
            <button type="button" class="password-reset-btn-cancel" onclick="closeInternalPasswordModal()">
                Cancel
            </button>
            <button type="button" class="password-reset-btn-submit" id="internalPasswordSubmitBtn" onclick="submitInternalPasswordSettings()" disabled style="opacity: 0.6; cursor: not-allowed;" onmouseover="if(this.disabled) this.style.cursor='not-allowed'; else this.style.cursor='pointer';" onmouseout="if(this.disabled) this.style.cursor='not-allowed';">
                <i class="fa-solid fa-check-circle"></i> Save Password
            </button>
        </div>
    </div>
</div>

<script>
function openInternalPasswordModal(event) {
    if (event) event.preventDefault();
    document.getElementById('internalPasswordModal').style.display = 'flex';
    document.getElementById('upCurrentInternalPassword').value = '';
    document.getElementById('upNewInternalPassword').value = '';
    document.getElementById('upConfirmInternalPassword').value = '';
    document.getElementById('upCaptchaAnswer').value = '';
    
    // Reset OTP state
    document.getElementById('internalPasswordSubmitBtn').disabled = true;
    document.getElementById('internalPasswordSubmitBtn').style.opacity = '0.6';
    if (document.getElementById('otp-state-send-update_internal_password')) {
        document.getElementById('otp-state-send-update_internal_password').style.display = 'block';
        document.getElementById('otp-state-verify-update_internal_password').style.display = 'none';
        document.getElementById('otp-state-verified-update_internal_password').style.display = 'none';
        document.getElementById('input-otp-update_internal_password').value = '';
        document.getElementById('btn-send-otp-update_internal_password').disabled = false;
        document.getElementById('text-send-otp-update_internal_password').innerText = 'Send OTP to Verify';
    }
    
    refreshUPCaptcha();
    clearInternalPasswordErrors();
    
    const profileDropdown = document.getElementById('profileDropdown');
    if (profileDropdown) profileDropdown.classList.remove('show');
}

function closeInternalPasswordModal() {
    document.getElementById('internalPasswordModal').style.display = 'none';
}

function refreshUPCaptcha() {
    const questionDiv = document.getElementById('upCaptchaQuestion');
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
        document.getElementById('upCaptchaAnswer').value = '';
    })
    .catch(err => {
        questionDiv.innerHTML = '<span style="color: red;">Error. Refresh</span>';
    });
}

function clearInternalPasswordErrors() {
    document.querySelectorAll('#internalPasswordModal .password-form-error').forEach(el => el.textContent = '');
    document.getElementById('internalPasswordMessage').style.display = 'none';
}

function showInternalPasswordMessage(msg, isError = false) {
    const msgDiv = document.getElementById('internalPasswordMessage');
    msgDiv.textContent = msg;
    msgDiv.className = isError ? 'password-reset-message error' : 'password-reset-message success';
    msgDiv.style.display = 'block';
}

function submitInternalPasswordSettings() {
    clearInternalPasswordErrors();
    
    const currentPassword = document.getElementById('upCurrentInternalPassword').value;
    const newPassword = document.getElementById('upNewInternalPassword').value;
    const confirmPassword = document.getElementById('upConfirmInternalPassword').value;
    const captchaAnswer = document.getElementById('upCaptchaAnswer').value;
    
    let hasError = false;
    
    if (!currentPassword) {
        document.getElementById('upCurrentInternalPasswordError').textContent = 'Please enter your current internal password.';
        hasError = true;
    }
    
    const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/;
    if (!newPassword || !passRegex.test(newPassword)) {
        document.getElementById('upNewInternalPasswordError').textContent = 'Password must be at least 8 characters, with 1 uppercase, 1 lowercase, 1 number, and 1 special character.';
        hasError = true;
    }
    
    if (newPassword !== confirmPassword) {
        document.getElementById('upConfirmInternalPasswordError').textContent = 'Passwords do not match.';
        hasError = true;
    }

    if (!captchaAnswer) {
        document.getElementById('upCaptchaError').textContent = 'Please answer the security question.';
        hasError = true;
    }
    
    if (hasError) return;
    
    const btn = document.getElementById('internalPasswordSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
    
    fetch('/password/update-internal-password', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            current_internal_password: currentPassword,
            internal_password: newPassword,
            internal_password_confirmation: confirmPassword,
            captcha_answer: captchaAnswer
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showInternalPasswordMessage(data.message || 'Internal Password updated successfully!');
            setTimeout(() => {
                closeInternalPasswordModal();
                if(typeof showToast === 'function') {
                    showToast('success', 'Success', 'Your Internal Password has been updated.');
                }
            }, 1500);
        } else {
            showInternalPasswordMessage(data.message || 'An error occurred', true);
            refreshUPCaptcha();
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Save Password';
        }
    })
    .catch(err => {
        showInternalPasswordMessage('A server error occurred. Please try again.', true);
        refreshUPCaptcha();
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Save Password';
    });
}

document.addEventListener('otpVerified:update_internal_password', function() {
    const btn = document.getElementById('internalPasswordSubmitBtn');
    if (btn) {
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
    }
});
</script>
