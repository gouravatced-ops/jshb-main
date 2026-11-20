@props(['purpose', 'buttonText' => 'Send OTP for Verification'])

@php
    $user = auth()->user();
    $rawEmail = $user->email ?? '';
    if ($rawEmail && str_contains($rawEmail, '@')) {
        [$userPart, $domainPart] = explode('@', $rawEmail, 2);
        $maskedUser = strlen($userPart) > 2 ? substr($userPart, 0, 2) . str_repeat('*', max(3, strlen($userPart) - 3)) . substr($userPart, -1) : $userPart . '***';
        $maskedEmail = $maskedUser . '@' . $domainPart;
    } else {
        $maskedEmail = 'your registered email';
    }
@endphp

<div class="gov-otp-container" id="otp-wrapper-{{ $purpose }}">
    <style>
        .gov-otp-container {
            margin: 20px 0;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .gov-otp-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e2e8f0;
        }
        .gov-otp-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        .gov-otp-body {
            padding: 20px;
        }
        .gov-otp-stepper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 18px;
        }
        .gov-step-pill {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            color: #64748b;
            background: #f1f5f9;
            transition: all 0.3s ease;
        }
        .gov-step-pill.active {
            color: #0d47a1;
            background: #e3f2fd;
            border: 1px solid #90caf9;
        }
        .gov-step-pill.completed {
            color: #15803d;
            background: #dcfce7;
            border: 1px solid #86efac;
        }
        .gov-step-pill .step-circle {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            background: #cbd5e1;
            color: #ffffff;
        }
        .gov-step-pill.active .step-circle {
            background: #0d47a1;
        }
        .gov-step-pill.completed .step-circle {
            background: #16a34a;
        }
        .gov-step-arrow {
            color: #cbd5e1;
            font-size: 10px;
        }
        /* State 1: Send */
        .otp-notice-card {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .otp-notice-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #e0f2fe;
            color: #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .otp-notice-text {
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
            margin: 0;
        }
        .otp-email-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #e2e8f0;
            color: #1e293b;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: monospace;
            font-weight: 600;
            font-size: 12px;
        }
        .btn-gov-otp-send {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(13, 71, 161, 0.25);
            transition: all 0.2s ease;
        }
        .btn-gov-otp-send:hover {
            background: linear-gradient(135deg, #1565c0 0%, #1976d2 100%);
            box-shadow: 0 4px 10px rgba(13, 71, 161, 0.35);
            transform: translateY(-1px);
        }
        .btn-gov-otp-send:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        /* State 2: Verify Boxes */
        .otp-boxes-grid {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 16px 0;
        }
        .otp-box-digit {
            width: 48px;
            height: 52px;
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            background: #ffffff;
            transition: all 0.2s ease;
        }
        .otp-box-digit:focus {
            outline: none;
            border-color: #0d47a1;
            box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.15);
            background: #f8fafc;
        }
        .btn-gov-otp-verify {
            background: linear-gradient(135deg, #15803d 0%, #16a34a 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
            transition: all 0.2s ease;
        }
        .btn-gov-otp-verify:hover {
            background: linear-gradient(135deg, #166534 0%, #15803d 100%);
            transform: translateY(-1px);
        }
        .otp-countdown-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 12px;
            font-size: 12.5px;
            color: #64748b;
        }
        .btn-resend-link {
            background: none;
            border: none;
            color: #0d47a1;
            font-weight: 600;
            font-size: 12.5px;
            padding: 0;
            cursor: pointer;
            text-decoration: underline;
        }
        .btn-resend-link:disabled {
            color: #94a3b8;
            cursor: not-allowed;
            text-decoration: none;
        }
        /* State 3: Verified */
        .otp-verified-seal {
            display: flex;
            align-items: center;
            gap: 16px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 2px solid #86efac;
            border-radius: 10px;
            padding: 14px 20px;
        }
        .verified-seal-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #16a34a;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            box-shadow: 0 4px 8px rgba(22, 163, 74, 0.3);
        }
        .verified-seal-details h6 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #14532d;
        }
        .verified-seal-details p {
            margin: 3px 0 0 0;
            font-size: 12px;
            color: #15803d;
        }
    </style>

    <!-- Header Banner -->
    <div class="gov-otp-header">
        <div class="gov-otp-title">
            <i class="fa-solid fa-shield-halved text-primary"></i>
            <span>Two-Factor Official Identity Verification</span>
        </div>
        <span class="badge bg-light text-dark border" style="font-size: 11px;">
            <i class="fa-solid fa-lock text-secondary me-1"></i> Security Gate
        </span>
    </div>

    <!-- Stepper Navigation -->
    <div class="gov-otp-body">
        <div class="gov-otp-stepper">
            <div class="gov-step-pill active" id="step-pill-1-{{ $purpose }}">
                <span class="step-circle">1</span>
                <span>Send Code</span>
            </div>
            <i class="fa-solid fa-chevron-right gov-step-arrow"></i>
            <div class="gov-step-pill" id="step-pill-2-{{ $purpose }}">
                <span class="step-circle">2</span>
                <span>Enter 6-Digit OTP</span>
            </div>
            <i class="fa-solid fa-chevron-right gov-step-arrow"></i>
            <div class="gov-step-pill" id="step-pill-3-{{ $purpose }}">
                <span class="step-circle">3</span>
                <span>Digital Seal</span>
            </div>
        </div>

        <!-- STATE 1: Send OTP -->
        <div id="otp-state-send-{{ $purpose }}">
            <div class="otp-notice-card">
                <div class="otp-notice-icon">
                    <i class="fa-solid fa-envelope-shield"></i>
                </div>
                <div class="otp-notice-text">
                    For legal & administrative non-repudiation, please verify your official credentials before signing or moving this file. An OTP will be dispatched to your registered secretariat email:
                    <div class="mt-1">
                        <span class="otp-email-badge">
                            <i class="fa-regular fa-envelope me-1"></i> {{ $maskedEmail }}
                        </span>
                    </div>
                </div>
            </div>

            <button type="button" class="btn-gov-otp-send" id="btn-send-otp-{{ $purpose }}" onclick="sendGlobalOtp('{{ $purpose }}')">
                <i class="fa-solid fa-paper-plane" id="icon-send-otp-{{ $purpose }}"></i>
                <span id="text-send-otp-{{ $purpose }}">{{ $buttonText }}</span>
            </button>

            <div id="otp-send-error-{{ $purpose }}" class="alert alert-danger mt-2 py-2 px-3 small" style="display: none;"></div>
        </div>

        <!-- STATE 2: Verify OTP -->
        <div id="otp-state-verify-{{ $purpose }}" style="display: none;">
            <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded bg-light border">
                <span class="small text-success fw-bold">
                    <i class="fa-solid fa-circle-check me-1"></i> 6-Digit code sent to <span class="font-monospace text-dark">{{ $maskedEmail }}</span>
                </span>
                <span class="badge bg-primary-subtle text-primary small">Valid for 10 Mins</span>
            </div>

            <!-- 6 Digit Input Group -->
            <div class="otp-boxes-grid" id="otp-boxes-{{ $purpose }}">
                <input type="text" maxlength="1" class="otp-box-digit" data-idx="0" inputmode="numeric" autocomplete="one-time-code">
                <input type="text" maxlength="1" class="otp-box-digit" data-idx="1" inputmode="numeric">
                <input type="text" maxlength="1" class="otp-box-digit" data-idx="2" inputmode="numeric">
                <input type="text" maxlength="1" class="otp-box-digit" data-idx="3" inputmode="numeric">
                <input type="text" maxlength="1" class="otp-box-digit" data-idx="4" inputmode="numeric">
                <input type="text" maxlength="1" class="otp-box-digit" data-idx="5" inputmode="numeric">
            </div>

            <!-- Unified hidden input for submission -->
            <input type="hidden" id="input-otp-{{ $purpose }}" name="otp_code_{{ $purpose }}" value="">

            <button type="button" class="btn-gov-otp-verify" id="btn-verify-otp-{{ $purpose }}" onclick="verifyGlobalOtp('{{ $purpose }}')">
                <i class="fa-solid fa-circle-check"></i>
                <span id="text-verify-otp-{{ $purpose }}">Verify & Authenticate Action</span>
            </button>

            <div class="otp-countdown-wrap">
                <span id="otp-timer-msg-{{ $purpose }}">
                    <i class="fa-regular fa-clock me-1"></i> Resend code in <strong id="otp-seconds-{{ $purpose }}">60</strong>s
                </span>
                <button type="button" id="btn-resend-{{ $purpose }}" class="btn-resend-link" onclick="sendGlobalOtp('{{ $purpose }}')" disabled>
                    <i class="fa-solid fa-rotate-right me-1"></i> Resend OTP
                </button>
            </div>

            <div id="otp-verify-error-{{ $purpose }}" class="alert alert-danger mt-2 py-2 px-3 small" style="display: none;"></div>
        </div>

        <!-- STATE 3: Verified Digital Seal -->
        <div id="otp-state-verified-{{ $purpose }}" style="display: none;">
            <div class="otp-verified-seal">
                <div class="verified-seal-icon">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="verified-seal-details">
                    <h6>Identity Verified & Authenticated</h6>
                    <p>
                        Authorized by: <strong>{{ $user->name ?? 'Officer' }}</strong> | Status: <span class="badge bg-success">Digital Stamp Attached</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Timer storage per purpose
    window._govOtpTimers = window._govOtpTimers || {};

    function startOtpCountdown(purpose) {
        clearInterval(window._govOtpTimers[purpose]);
        let seconds = 60;
        const secondsSpan = document.getElementById('otp-seconds-' + purpose);
        const timerMsg = document.getElementById('otp-timer-msg-' + purpose);
        const resendBtn = document.getElementById('btn-resend-' + purpose);

        if (resendBtn) resendBtn.disabled = true;
        if (timerMsg) timerMsg.style.display = 'inline';

        window._govOtpTimers[purpose] = setInterval(() => {
            seconds--;
            if (secondsSpan) secondsSpan.innerText = seconds;
            if (seconds <= 0) {
                clearInterval(window._govOtpTimers[purpose]);
                if (timerMsg) timerMsg.style.display = 'none';
                if (resendBtn) resendBtn.disabled = false;
            }
        }, 1000);
    }

    // Auto-advance between the 6 digit inputs
    function setupOtpDigitInputs(purpose) {
        const container = document.getElementById('otp-boxes-' + purpose);
        if (!container) return;
        const inputs = container.querySelectorAll('.otp-box-digit');
        const hiddenInput = document.getElementById('input-otp-' + purpose);

        function updateHiddenValue() {
            let val = '';
            inputs.forEach(inp => val += inp.value);
            hiddenInput.value = val;
            if (val.length === 6) {
                // Auto trigger verification when 6 digits are typed
                verifyGlobalOtp(purpose);
            }
        }

        inputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                // Keep only numeric
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length >= 1) {
                    this.value = this.value.charAt(0);
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                }
                updateHiddenValue();
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
                const digits = pasteData.replace(/[^0-9]/g, '').slice(0, 6);
                for (let i = 0; i < digits.length; i++) {
                    if (inputs[i]) inputs[i].value = digits[i];
                }
                if (digits.length < 6 && inputs[digits.length]) {
                    inputs[digits.length].focus();
                }
                updateHiddenValue();
            });
        });
    }

    if (typeof window.sendGlobalOtp === 'undefined') {
        window.sendGlobalOtp = function(purpose) {
            const btn = document.getElementById('btn-send-otp-' + purpose);
            const icon = document.getElementById('icon-send-otp-' + purpose);
            const textSpan = document.getElementById('text-send-otp-' + purpose);
            const errorDiv = document.getElementById('otp-send-error-' + purpose);
            const originalText = textSpan ? textSpan.innerText : 'Send OTP';

            if (btn) btn.disabled = true;
            if (icon) icon.className = 'fa-solid fa-spinner fa-spin';
            if (textSpan) textSpan.innerText = 'Transmitting OTP...';
            if (errorDiv) errorDiv.style.display = 'none';

            fetch('{{ route("global-otp.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ purpose: purpose })
            })
            .then(response => response.json())
            .then(data => {
                if (btn) btn.disabled = false;
                if (icon) icon.className = 'fa-solid fa-paper-plane';
                if (textSpan) textSpan.innerText = originalText;
                
                if (data.success) {
                    document.getElementById('otp-state-send-' + purpose).style.display = 'none';
                    document.getElementById('otp-state-verify-' + purpose).style.display = 'block';

                    // Update Stepper
                    const pill1 = document.getElementById('step-pill-1-' + purpose);
                    const pill2 = document.getElementById('step-pill-2-' + purpose);
                    if (pill1) { pill1.classList.remove('active'); pill1.classList.add('completed'); }
                    if (pill2) { pill2.classList.add('active'); }

                    setupOtpDigitInputs(purpose);
                    startOtpCountdown(purpose);

                    // Focus first digit
                    const firstInput = document.querySelector('#otp-boxes-' + purpose + ' input[data-idx="0"]');
                    if (firstInput) setTimeout(() => firstInput.focus(), 100);
                } else {
                    if (errorDiv) {
                        errorDiv.innerText = data.message || 'Failed to dispatch OTP. Please try again.';
                        errorDiv.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                if (btn) btn.disabled = false;
                if (icon) icon.className = 'fa-solid fa-paper-plane';
                if (textSpan) textSpan.innerText = originalText;
                if (errorDiv) {
                    errorDiv.innerText = 'Network communication failure. Please check connection and retry.';
                    errorDiv.style.display = 'block';
                }
            });
        };
    }

    if (typeof window.verifyGlobalOtp === 'undefined') {
        window.verifyGlobalOtp = function(purpose) {
            const btn = document.getElementById('btn-verify-otp-' + purpose);
            const textSpan = document.getElementById('text-verify-otp-' + purpose);
            const errorDiv = document.getElementById('otp-verify-error-' + purpose);
            const otpInput = document.getElementById('input-otp-' + purpose).value;
            const originalText = textSpan ? textSpan.innerText : 'Verify OTP';

            if (!otpInput || otpInput.length !== 6) {
                if (errorDiv) {
                    errorDiv.innerText = 'Please enter all 6 digits of the OTP.';
                    errorDiv.style.display = 'block';
                }
                return;
            }

            if (btn) btn.disabled = true;
            if (textSpan) textSpan.innerText = 'Validating Signature...';
            if (errorDiv) errorDiv.style.display = 'none';

            fetch('{{ route("global-otp.verify") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ purpose: purpose, otp: otpInput })
            })
            .then(response => response.json())
            .then(data => {
                if (btn) btn.disabled = false;
                if (textSpan) textSpan.innerText = originalText;
                
                if (data.success) {
                    document.getElementById('otp-state-verify-' + purpose).style.display = 'none';
                    document.getElementById('otp-state-verified-' + purpose).style.display = 'block';

                    // Update Stepper
                    const pill2 = document.getElementById('step-pill-2-' + purpose);
                    const pill3 = document.getElementById('step-pill-3-' + purpose);
                    if (pill2) { pill2.classList.remove('active'); pill2.classList.add('completed'); }
                    if (pill3) { pill3.classList.add('active', 'completed'); }

                    // Dispatch event for buttons
                    document.dispatchEvent(new CustomEvent('otpVerified:' + purpose));
                } else {
                    if (errorDiv) {
                        errorDiv.innerText = data.message || 'The OTP entered is incorrect or expired.';
                        errorDiv.style.display = 'block';
                    }
                    // Clear digit boxes for fresh try
                    const inputs = document.querySelectorAll('#otp-boxes-' + purpose + ' .otp-box-digit');
                    inputs.forEach(i => i.value = '');
                    document.getElementById('input-otp-' + purpose).value = '';
                    if (inputs[0]) inputs[0].focus();
                }
            })
            .catch(error => {
                if (btn) btn.disabled = false;
                if (textSpan) textSpan.innerText = originalText;
                if (errorDiv) {
                    errorDiv.innerText = 'An error occurred during verification. Please try again.';
                    errorDiv.style.display = 'block';
                }
            });
        };
    }
</script>
