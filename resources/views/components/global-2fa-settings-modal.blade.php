<!-- 2FA Settings Modal -->
<div id="global2faSettingsModal" class="password-reset-modal" style="display: none;">
    <div class="password-reset-overlay" onclick="close2faSettingsModal()"></div>
    <div class="password-reset-container">
        <!-- Modal Header -->
        <div class="password-reset-header">
            <div class="password-reset-title-section">
                <h2 class="password-reset-title">
                    <i class="fa-solid fa-shield-halved"></i> Global 2FA Settings
                </h2>
                <p class="password-reset-subtitle">Configure Two-Factor Authentication enforcement across the project</p>
            </div>
            <button type="button" class="password-reset-close" id="global2faSettingsClose" onclick="close2faSettingsModal()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="password-reset-body">
            <form id="global2faSettingsForm">
                @csrf

                @php
                    $currentSetting = \App\Models\Setting::getVal('global_2fa_enforcement', 'optional');
                @endphp

                <div class="password-form-group">
                    <label for="enforcement" class="password-form-label">
                        <i class="fa-solid fa-gear"></i> 2FA Enforcement Policy
                    </label>
                    <div style="font-size: 11px; color: #6b7280; margin-bottom: 8px;">
                        Select how Google Authenticator TOTP is enforced for all users.
                    </div>
                    <select id="enforcement" name="enforcement" class="password-form-input" style="padding-right: 15px;">
                        <option value="disabled" {{ $currentSetting === 'disabled' ? 'selected' : '' }}>Disabled (Bypass 2FA completely, use Email OTP)</option>
                        <option value="mandatory" {{ $currentSetting === 'mandatory' ? 'selected' : '' }}>Mandatory (Force all users to set up 2FA)</option>
                    </select>
                </div>

                <x-global-otp-verify purpose="update_2fa_settings" buttonText="Send OTP to Verify" />

                <!-- Captcha -->
                <div class="password-form-group" id="twoFaCaptchaGroup">
                    <label class="password-form-label">
                        <i class="fa-solid fa-shield-alt"></i> Security Question
                    </label>
                    <div class="captcha-container">
                        <div class="captcha-question" id="twoFaCaptchaQuestion">
                            <i class="fa-solid fa-spinner fa-spin"></i> Loading...
                        </div>
                        <input
                            type="number"
                            id="twoFaCaptchaAnswer"
                            class="password-form-input"
                            placeholder="Enter your answer"
                            required
                        >
                        <button
                            type="button"
                            class="captcha-refresh-btn"
                            onclick="refresh2FACaptcha()"
                            title="Generate new question"
                        >
                            <i class="fa-solid fa-redo"></i>
                        </button>
                    </div>
                    <span class="password-form-error" id="twoFaCaptchaError"></span>
                </div>

            </form>

            <div id="twoFaSettingsMessage" class="password-reset-message" style="display: none;"></div>
        </div>

        <!-- Modal Footer -->
        <div class="password-reset-footer">
            <button type="button" class="password-reset-btn-cancel" onclick="close2faSettingsModal()">
                Cancel
            </button>
            <button type="button" class="password-reset-btn-submit" id="save2faBtn" onclick="submit2faSettings()" disabled style="opacity: 0.6; cursor: not-allowed;" onmouseover="if(this.disabled) this.style.cursor='not-allowed'; else this.style.cursor='pointer';" onmouseout="if(this.disabled) this.style.cursor='not-allowed';">
                <i class="fa-solid fa-check-circle"></i> Save Settings
            </button>
        </div>

    </div>
</div>

<script>
    function open2faSettingsModal() {
        document.getElementById('global2faSettingsModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';

        document.getElementById('twoFaCaptchaAnswer').value = '';
        refresh2FACaptcha();
        clear2faSettingsErrors();

        // Reset OTP state
        document.getElementById('save2faBtn').disabled = true;
        document.getElementById('save2faBtn').style.opacity = '0.6';
        if (document.getElementById('otp-state-send-update_2fa_settings')) {
            document.getElementById('otp-state-send-update_2fa_settings').style.display = 'block';
            document.getElementById('otp-state-verify-update_2fa_settings').style.display = 'none';
            document.getElementById('otp-state-verified-update_2fa_settings').style.display = 'none';
            document.getElementById('input-otp-update_2fa_settings').value = '';
            document.getElementById('btn-send-otp-update_2fa_settings').disabled = false;
            document.getElementById('text-send-otp-update_2fa_settings').innerText = 'Send OTP to Verify';
        }
    }

    function close2faSettingsModal() {
        document.getElementById('global2faSettingsModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function refresh2FACaptcha() {
        const questionDiv = document.getElementById('twoFaCaptchaQuestion');
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
            document.getElementById('twoFaCaptchaAnswer').value = '';
        })
        .catch(err => {
            questionDiv.innerHTML = '<span style="color: red;">Error. Refresh</span>';
        });
    }

    function clear2faSettingsErrors() {
        document.querySelectorAll('#global2faSettingsModal .password-form-error').forEach(el => el.textContent = '');
        document.getElementById('twoFaSettingsMessage').style.display = 'none';
    }

    function show2faSettingsMessage(msg, isError = false) {
        const msgDiv = document.getElementById('twoFaSettingsMessage');
        msgDiv.textContent = msg;
        msgDiv.className = isError ? 'password-reset-message error' : 'password-reset-message success';
        msgDiv.style.display = 'block';
    }

    function submit2faSettings() {
        clear2faSettingsErrors();

        const enforcement = document.getElementById('enforcement').value;
        const captchaAnswer = document.getElementById('twoFaCaptchaAnswer').value;

        let hasError = false;

        if (!captchaAnswer) {
            document.getElementById('twoFaCaptchaError').textContent = 'Please answer the security question.';
            hasError = true;
        }

        if (hasError) return;

        const btn = document.getElementById('save2faBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

        fetch('/admin/settings/2fa', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                enforcement: enforcement,
                captcha_answer: captchaAnswer
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                show2faSettingsMessage(data.message || 'Settings updated successfully!');
                setTimeout(() => {
                    close2faSettingsModal();
                    if(typeof showToast === 'function') {
                        showToast('success', 'Success', data.message);
                    }
                    window.location.reload();
                }, 1500);
            } else {
                show2faSettingsMessage(data.message || 'An error occurred', true);
                refresh2FACaptcha();
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Save Settings';
            }
        })
        .catch(err => {
            show2faSettingsMessage('A server error occurred. Please try again.', true);
            refresh2FACaptcha();
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Save Settings';
        });
    }

    document.addEventListener('otpVerified:update_2fa_settings', function() {
        const btn = document.getElementById('save2faBtn');
        if (btn) {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
        }
    });
</script>
