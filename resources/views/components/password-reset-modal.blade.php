<!-- Password Reset Modal -->
<div id="passwordResetModal" class="password-reset-modal" style="display: none;">
    <div class="password-reset-overlay"></div>
    <div class="password-reset-container">
        <!-- Modal Header -->
        <div class="password-reset-header">
            <div class="password-reset-title-section">
                <h2 class="password-reset-title">
                    <i class="fa-solid fa-lock-open"></i> Reset Password
                </h2>
                <p class="password-reset-subtitle">Update your password to continue</p>
            </div>
            <button type="button" class="password-reset-close" id="passwordResetClose" style="display: none;">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="password-reset-body">
            <form id="passwordResetForm" method="POST">
                @csrf
                
                <!-- Old Password -->
                <div class="password-form-group">
                    <label for="oldPassword" class="password-form-label">
                        <i class="fa-solid fa-key"></i> Current Password
                    </label>
                    <input 
                        type="password" 
                        id="oldPassword" 
                        name="old_password" 
                        class="password-form-input"
                        placeholder="Enter your current password"
                        required
                    >
                    <span class="password-form-error" id="oldPasswordError"></span>
                </div>

                <!-- New Password -->
                <div class="password-form-group">
                    <label for="newPassword" class="password-form-label">
                        <i class="fa-solid fa-lock"></i> New Password
                    </label>
                    <input 
                        type="password" 
                        id="newPassword" 
                        name="new_password" 
                        class="password-form-input"
                        placeholder="Enter new password (min. 8 characters)"
                        required
                    >
                    <div class="password-strength-bar">
                        <div class="password-strength-fill" id="passwordStrengthFill"></div>
                    </div>
                    <span class="password-strength-text" id="passwordStrengthText">Weak</span>
                    <span class="password-form-error" id="newPasswordError"></span>
                </div>

                <!-- Confirm Password -->
                <div class="password-form-group">
                    <label for="confirmPassword" class="password-form-label">
                        <i class="fa-solid fa-check"></i> Confirm Password
                    </label>
                    <input 
                        type="password" 
                        id="confirmPassword" 
                        name="new_password_confirmation" 
                        class="password-form-input"
                        placeholder="Confirm your new password"
                        required
                    >
                    <span class="password-form-error" id="confirmPasswordError"></span>
                </div>

                <!-- Captcha Security Question -->
                <div class="password-form-group">
                    <label class="password-form-label">
                        <i class="fa-solid fa-shield-alt"></i> Security Question
                    </label>
                    <div class="captcha-container">
                        <div class="captcha-question" id="captchaQuestion">
                            <i class="fa-solid fa-spinner fa-spin"></i> Loading security question...
                        </div>
                        <input 
                            type="hidden" 
                            id="captcha" 
                            name="captcha"
                        >
                        <input 
                            type="number" 
                            id="captchaAnswer" 
                            name="captcha_answer" 
                            class="password-form-input"
                            placeholder="Enter your answer"
                            required
                        >
                        <button 
                            type="button" 
                            class="captcha-refresh-btn" 
                            onclick="refreshCaptcha()"
                            title="Generate new question"
                        >
                            <i class="fa-solid fa-redo"></i>
                        </button>
                    </div>
                    <span class="password-form-error" id="captchaError"></span>
                </div>

                <input type="hidden" name="captcha_check" value="1">
            </form>

            <div id="passwordResetMessage" class="password-reset-message" style="display: none;"></div>
        </div>

        <!-- Modal Footer -->
        <div class="password-reset-footer">
            <button type="button" class="password-reset-btn-cancel" id="passwordResetCancel" style="display: none;">
                Cancel
            </button>
            <button type="button" class="password-reset-btn-submit" id="passwordResetSubmit">
                <i class="fa-solid fa-check-circle"></i> Update Password
            </button>
        </div>
    </div>
</div>

<style>
.password-reset-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.password-reset-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
}

.password-reset-container {
    position: relative;
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.password-reset-header {
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--sidebar-active) 100%);
    color: white;
    border-radius: 12px 12px 0 0;
}

.password-reset-title-section {
    flex: 1;
}

.password-reset-title {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.password-reset-subtitle {
    margin: 4px 0 0 0;
    font-size: 13px;
    opacity: 0.9;
}

.password-reset-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.password-reset-close:hover {
    background: rgba(255, 255, 255, 0.3);
}

.password-reset-body {
    padding: 24px;
}

.password-form-group {
    margin-bottom: 18px;
}

.password-form-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.password-form-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
    transition: all 0.2s ease;
    box-sizing: border-box;
}

.password-form-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.1);
}

.password-form-error {
    display: block;
    color: #dc2626;
    font-size: 12px;
    margin-top: 4px;
    animation: slideDown 0.2s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-4px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.password-strength-bar {
    height: 4px;
    background: #e5e7eb;
    border-radius: 2px;
    overflow: hidden;
    margin: 6px 0;
}

.password-strength-fill {
    height: 100%;
    width: 0%;
    background: #dc2626;
    transition: width 0.3s ease, background-color 0.3s ease;
    border-radius: 2px;
}

.password-strength-text {
    font-size: 11px;
    color: #6b7280;
    font-weight: 500;
}

.captcha-container {
    position: relative;
}

.captcha-question {
    padding: 12px;
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 8px;
    text-align: center;
    color: var(--primary-color);
    min-height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.captcha-refresh-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 6px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.2s ease;
    margin-top: 14px;
}

.captcha-refresh-btn:hover {
    background: var(--primary-hover);
    transform: translateY(-50%) rotate(180deg);
}

#captchaAnswer {
    padding-right: 36px;
}

.password-reset-message {
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 16px;
    font-size: 13px;
    animation: slideDown 0.2s ease;
}

.password-reset-message.success {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.password-reset-message.error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.password-reset-footer {
    padding: 16px 24px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    background: #f9fafb;
    border-radius: 0 0 12px 12px;
}

.password-reset-btn-cancel,
.password-reset-btn-submit {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

.password-reset-btn-cancel {
    background: #e5e7eb;
    color: #374151;
}

.password-reset-btn-cancel:hover {
    background: #d1d5db;
}

.password-reset-btn-submit {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--sidebar-active) 100%);
    color: white;
}

.password-reset-btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(21, 128, 61, 0.3);
}

.password-reset-btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.password-reset-btn-submit.loading {
    pointer-events: none;
    opacity: 0.8;
}

/* Responsive */
@media (max-width: 600px) {
    .password-reset-container {
        width: 95%;
        max-height: 95vh;
    }

    .password-reset-header,
    .password-reset-body,
    .password-reset-footer {
        padding: 16px;
    }

    .password-reset-title {
        font-size: 18px;
    }

    .password-reset-footer {
        flex-direction: column-reverse;
    }

    .password-reset-btn-cancel,
    .password-reset-btn-submit {
        width: 100%;
        justify-content: center;
    }
}
</style>
