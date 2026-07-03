@props(['purpose', 'buttonText' => 'Send OTP for Verification'])

<div class="global-otp-wrapper" id="otp-wrapper-{{ $purpose }}" style="margin-bottom: 15px; padding: 15px; border: 1px dashed #cbd5e1; border-radius: 8px; background-color: #f8fafc;">
    
    <!-- State 1: Send OTP -->
    <div id="otp-state-send-{{ $purpose }}">
        <p style="font-size: 13px; color: #64748b; margin-bottom: 10px;">For security, please verify your identity via email OTP before proceeding.</p>
        <button type="button" class="btn btn-outline-primary w-100" id="btn-send-otp-{{ $purpose }}" onclick="sendGlobalOtp('{{ $purpose }}')" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
            <i class="fa-regular fa-envelope"></i> <span id="text-send-otp-{{ $purpose }}">{{ $buttonText }}</span>
        </button>
        <div id="otp-send-error-{{ $purpose }}" style="color: #ef4444; font-size: 12px; margin-top: 5px; display: none;"></div>
    </div>

    <!-- State 2: Verify OTP -->
    <div id="otp-state-verify-{{ $purpose }}" style="display: none;">
        <p style="font-size: 13px; color: #10b981; margin-bottom: 10px;">OTP sent! Please check your email.</p>
        <div class="form-group" style="margin-bottom: 10px;">
            <input type="text" id="input-otp-{{ $purpose }}" class="form-control text-center" placeholder="Enter 6-digit OTP" maxlength="6" style="letter-spacing: 5px; font-weight: bold; font-size: 18px;">
        </div>
        <button type="button" class="btn btn-primary w-100" id="btn-verify-otp-{{ $purpose }}" onclick="verifyGlobalOtp('{{ $purpose }}')" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
            <i class="fa-solid fa-check-circle"></i> <span id="text-verify-otp-{{ $purpose }}">Verify OTP</span>
        </button>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
            <div id="otp-verify-error-{{ $purpose }}" style="color: #ef4444; font-size: 12px; display: none;"></div>
            <button type="button" onclick="sendGlobalOtp('{{ $purpose }}')" style="background: none; border: none; color: #3b82f6; font-size: 12px; cursor: pointer; padding: 0;">Resend OTP</button>
        </div>
    </div>

    <!-- State 3: Verified -->
    <div id="otp-state-verified-{{ $purpose }}" style="display: none; text-align: center; color: #10b981; padding: 10px 0;">
        <i class="fa-solid fa-shield-check" style="font-size: 24px; margin-bottom: 5px;"></i>
        <p style="font-size: 14px; font-weight: 600; margin: 0;"><i class="fa-solid fa-check"></i> Identity Verified</p>
    </div>

</div>

<script>
    if (typeof window.sendGlobalOtp === 'undefined') {
        window.sendGlobalOtp = function(purpose) {
            const btn = document.getElementById('btn-send-otp-' + purpose);
            const textSpan = document.getElementById('text-send-otp-' + purpose);
            const errorDiv = document.getElementById('otp-send-error-' + purpose);
            const originalText = textSpan.innerText;

            btn.disabled = true;
            textSpan.innerText = 'Sending...';
            errorDiv.style.display = 'none';

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
                btn.disabled = false;
                textSpan.innerText = originalText;
                
                if (data.success) {
                    document.getElementById('otp-state-send-' + purpose).style.display = 'none';
                    document.getElementById('otp-state-verify-' + purpose).style.display = 'block';
                } else {
                    errorDiv.innerText = data.message || 'Failed to send OTP.';
                    errorDiv.style.display = 'block';
                }
            })
            .catch(error => {
                btn.disabled = false;
                textSpan.innerText = originalText;
                errorDiv.innerText = 'An error occurred. Please try again.';
                errorDiv.style.display = 'block';
            });
        };
    }

    if (typeof window.verifyGlobalOtp === 'undefined') {
        window.verifyGlobalOtp = function(purpose) {
            const btn = document.getElementById('btn-verify-otp-' + purpose);
            const textSpan = document.getElementById('text-verify-otp-' + purpose);
            const errorDiv = document.getElementById('otp-verify-error-' + purpose);
            const otpInput = document.getElementById('input-otp-' + purpose).value;
            const originalText = textSpan.innerText;

            if (!otpInput || otpInput.length !== 6) {
                errorDiv.innerText = 'Please enter a valid 6-digit OTP.';
                errorDiv.style.display = 'block';
                return;
            }

            btn.disabled = true;
            textSpan.innerText = 'Verifying...';
            errorDiv.style.display = 'none';

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
                btn.disabled = false;
                textSpan.innerText = originalText;
                
                if (data.success) {
                    document.getElementById('otp-state-verify-' + purpose).style.display = 'none';
                    document.getElementById('otp-state-verified-' + purpose).style.display = 'block';
                    
                    // Dispatch event for listeners
                    document.dispatchEvent(new CustomEvent('otpVerified:' + purpose));
                } else {
                    errorDiv.innerText = data.message || 'Invalid OTP.';
                    errorDiv.style.display = 'block';
                }
            })
            .catch(error => {
                btn.disabled = false;
                textSpan.innerText = originalText;
                errorDiv.innerText = 'An error occurred. Please try again.';
                errorDiv.style.display = 'block';
            });
        };
    }
</script>
