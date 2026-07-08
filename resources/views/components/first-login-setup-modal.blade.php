@php
    $user = auth()->user();
    $roleSlug = $user?->roleRelation?->slug ?? '';
    $requiresInternalPassword = in_array($roleSlug, [
        'estate-officer', 
        'revenue-officer', 
        'managing-director', 
        'chief-accounts-officer', 
        'chief-financial-officer', 
        'secretary-chief-engineer'
    ]);
@endphp

@if(auth()->check() && auth()->user()->is_first_login == 0)
<!-- First Login Setup Modal -->
<div id="firstSetupModal" class="password-reset-modal" style="display: flex;">
    <div class="password-reset-overlay"></div>
    <div class="password-reset-container">
        <!-- Modal Header -->
        <div class="password-reset-header">
            <div class="password-reset-title-section">
                <h2 class="password-reset-title" id="firstSetupTitle">
                    @if($requiresInternalPassword)
                        <i class="fa-solid fa-user-shield"></i> Initial Setup
                    @else
                        <i class="fa-solid fa-key"></i> Set Quick PIN
                    @endif
                </h2>
                <p class="password-reset-subtitle" id="firstSetupSubtitle">
                    @if($requiresInternalPassword)
                        Create your internal operation password
                    @else
                        Optional: Set a PIN for quick login
                    @endif
                </p>
            </div>
            <!-- Intentionally no close button to prevent bypassing -->
        </div>

        <!-- Modal Body -->
        <div class="password-reset-body">
            
            <!-- Step 1: Internal Password -->
            <form id="firstSetupStep1Form" style="display: {{ $requiresInternalPassword ? 'block' : 'none' }};">
                <div class="password-form-group">
                    <label for="setupInternalPassword" class="password-form-label">
                        <i class="fa-solid fa-lock"></i> Internal Operation Password
                    </label>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            id="setupInternalPassword" 
                            class="password-form-input"
                            placeholder="Enter internal password (min. 8 chars)"
                            {{ $requiresInternalPassword ? 'required' : '' }}
                            style="padding-right: 40px;"
                        >
                        <button type="button" onclick="const p = document.getElementById('setupInternalPassword'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerHTML = p.type === 'password' ? '<i class=\'fa-regular fa-eye\'></i>' : '<i class=\'fa-regular fa-eye-slash\'></i>';" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; padding: 0;">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <span class="password-form-error" id="setupInternalPasswordError"></span>
                </div>

                <div class="password-form-group">
                    <label for="setupInternalPasswordConfirm" class="password-form-label">
                        <i class="fa-solid fa-check"></i> Confirm Password
                    </label>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            id="setupInternalPasswordConfirm" 
                            class="password-form-input"
                            placeholder="Confirm internal password"
                            {{ $requiresInternalPassword ? 'required' : '' }}
                            style="padding-right: 40px;"
                        >
                        <button type="button" onclick="const p = document.getElementById('setupInternalPasswordConfirm'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerHTML = p.type === 'password' ? '<i class=\'fa-regular fa-eye\'></i>' : '<i class=\'fa-regular fa-eye-slash\'></i>';" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; padding: 0;">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <span class="password-form-error" id="setupInternalPasswordConfirmError"></span>
                </div>

                <!-- Captcha -->
                <div class="password-form-group">
                    <label class="password-form-label">
                        <i class="fa-solid fa-shield-alt"></i> Security Question
                    </label>
                    <div class="captcha-container">
                        <div class="captcha-question" id="setupCaptchaQuestion">
                            <i class="fa-solid fa-spinner fa-spin"></i> Loading...
                        </div>
                        <input 
                            type="number" 
                            id="setupCaptchaAnswer" 
                            class="password-form-input"
                            placeholder="Enter your answer"
                            {{ $requiresInternalPassword ? 'required' : '' }}
                        >
                        <button 
                            type="button" 
                            class="captcha-refresh-btn" 
                            onclick="refreshSetupCaptcha()"
                            title="Generate new question"
                        >
                            <i class="fa-solid fa-redo"></i>
                        </button>
                    </div>
                    <span class="password-form-error" id="setupCaptchaError"></span>
                </div>
            </form>

            <!-- Step 2: Quick PIN (Hidden initially if step 1 is required) -->
            <form id="firstSetupStep2Form" style="display: {{ $requiresInternalPassword ? 'none' : 'block' }};">
                <p style="margin-bottom: 15px; font-size: 14px; color: #4b5563;">
                    Setup a 4-digit quick PIN. You can use this PIN to log in instead of your full password.
                </p>
                <div class="password-form-group">
                    <label for="setupQuickPin" class="password-form-label">
                        <i class="fa-solid fa-key"></i> 4-Digit Quick PIN
                    </label>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            id="setupQuickPin" 
                            class="password-form-input"
                            placeholder="Enter 4-digit PIN"
                            maxlength="4"
                            pattern="\d{4}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);"
                            style="padding-right: 40px;"
                        >
                        <button type="button" onclick="const p = document.getElementById('setupQuickPin'); p.type = p.type === 'password' ? 'text' : 'password'; this.innerHTML = p.type === 'password' ? '<i class=\'fa-regular fa-eye\'></i>' : '<i class=\'fa-regular fa-eye-slash\'></i>';" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; padding: 0;">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <span class="password-form-error" id="setupQuickPinError"></span>
                </div>
            </form>

            <div id="firstSetupMessage" class="password-reset-message" style="display: none;"></div>
        </div>

        <!-- Modal Footer -->
        <div class="password-reset-footer" id="firstSetupStep1Footer" style="display: {{ $requiresInternalPassword ? 'flex' : 'none' }};">
            <button type="button" class="password-reset-btn-submit" id="setupStep1Submit">
                <i class="fa-solid fa-arrow-right"></i> Next Step
            </button>
        </div>

        <div class="password-reset-footer" id="firstSetupStep2Footer" style="display: {{ $requiresInternalPassword ? 'none' : 'flex' }}; justify-content: space-between;">
            <button type="button" class="password-reset-btn-cancel" id="setupStep2Skip">
                Skip for now
            </button>
            <button type="button" class="password-reset-btn-submit" id="setupStep2Submit">
                <i class="fa-solid fa-check-circle"></i> Save PIN & Finish
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    @if($requiresInternalPassword)
        // Generate initial captcha only if needed
        refreshSetupCaptcha();
        document.getElementById('setupStep1Submit').addEventListener('click', submitStep1);
    @endif

    document.getElementById('setupStep2Submit').addEventListener('click', submitStep2);
    document.getElementById('setupStep2Skip').addEventListener('click', skipStep2);

    // Prevent closing modal on overlay click
    document.querySelector('#firstSetupModal .password-reset-overlay').addEventListener('click', (e) => {
        e.stopPropagation();
    });
});

function refreshSetupCaptcha() {
    const questionDiv = document.getElementById('setupCaptchaQuestion');
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
        document.getElementById('setupCaptchaAnswer').value = '';
    })
    .catch(err => {
        questionDiv.innerHTML = '<span style="color: red;">Error. Refresh</span>';
    });
}

function clearSetupErrors() {
    document.querySelectorAll('#firstSetupModal .password-form-error').forEach(el => el.textContent = '');
    document.getElementById('firstSetupMessage').style.display = 'none';
}

function showSetupMessage(msg, isError = false) {
    const msgDiv = document.getElementById('firstSetupMessage');
    msgDiv.textContent = msg;
    msgDiv.className = isError ? 'password-reset-message error' : 'password-reset-message success';
    msgDiv.style.display = 'block';
}

function submitStep1() {
    clearSetupErrors();
    const internalPass = document.getElementById('setupInternalPassword').value;
    const confirmPass = document.getElementById('setupInternalPasswordConfirm').value;
    const captcha = document.getElementById('setupCaptchaAnswer').value;
    
    let hasError = false;
    
    if (!internalPass || internalPass.length < 8) {
        document.getElementById('setupInternalPasswordError').textContent = 'Password must be at least 8 characters.';
        hasError = true;
    }
    if (internalPass !== confirmPass) {
        document.getElementById('setupInternalPasswordConfirmError').textContent = 'Passwords do not match.';
        hasError = true;
    }
    if (!captcha) {
        document.getElementById('setupCaptchaError').textContent = 'Please answer the security question.';
        hasError = true;
    }
    
    if (hasError) return;
    
    const btn = document.getElementById('setupStep1Submit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
    
    fetch('/first-setup/internal-password', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            internal_password: internalPass,
            internal_password_confirmation: confirmPass,
            captcha_answer: captcha
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Switch to step 2
            document.getElementById('firstSetupStep1Form').style.display = 'none';
            document.getElementById('firstSetupStep1Footer').style.display = 'none';
            
            document.getElementById('firstSetupStep2Form').style.display = 'block';
            document.getElementById('firstSetupStep2Footer').style.display = 'flex';
            
            document.getElementById('firstSetupTitle').innerHTML = '<i class="fa-solid fa-key"></i> Set Quick PIN';
            document.getElementById('firstSetupSubtitle').textContent = 'Optional: Set a PIN for quick login';
        } else {
            showSetupMessage(data.message || 'An error occurred', true);
            refreshSetupCaptcha();
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-arrow-right"></i> Next Step';
        }
    })
    .catch(err => {
        showSetupMessage('A server error occurred. Please try again.', true);
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-arrow-right"></i> Next Step';
    });
}

function submitStep2() {
    clearSetupErrors();
    const pin = document.getElementById('setupQuickPin').value;
    
    if (!pin || pin.length !== 4) {
        document.getElementById('setupQuickPinError').textContent = 'Please enter a 4-digit PIN.';
        return;
    }
    
    const btn = document.getElementById('setupStep2Submit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
    
    fetch('/first-setup/quick-pin', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ secure_pin: pin })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('firstSetupModal').style.display = 'none';
        } else {
            showSetupMessage(data.message || 'An error occurred', true);
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Save PIN & Finish';
        }
    })
    .catch(err => {
        showSetupMessage('A server error occurred. Please try again.', true);
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Save PIN & Finish';
    });
}

function skipStep2() {
    const btn = document.getElementById('setupStep2Skip');
    btn.disabled = true;
    
    fetch('/first-setup/skip-pin', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('firstSetupModal').style.display = 'none';
        } else {
            btn.disabled = false;
        }
    })
    .catch(err => {
        btn.disabled = false;
    });
}
</script>
@endif
