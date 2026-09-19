<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <title>2FA Verification | {{ config('panel.portal_name') }}</title>
    <meta name="description" content="Jharkhand Housing Board - Official management login portal" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(config('panel.faviconIcon')) }}">
    <!-- Google Fonts + Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/font/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        .totp-input-container {
            margin-top: 1.5rem;
            text-align: center;
        }
        .totp-input {
            width: 100%;
            font-size: 2rem;
            letter-spacing: 12px;
            text-align: center;
            font-weight: 600;
            color: #334155;
            padding: 15px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            background: #f8fafc;
            transition: all 0.3s ease;
        }
        .totp-input:focus {
            border-color: #279f64;
            box-shadow: 0 0 0 4px rgba(39, 159, 100, 0.15);
            outline: none;
        }
        .timer-box {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>

    <div class="floating-bg">
        <div class="float-item" style="top: 12%; left: 3%;"><i class="fa-solid fa-hard-hat"></i></div>
        <div class="float-item" style="top: 70%; right: 5%; width: 90px; height: 90px;"><i class="fa-solid fa-building-columns"></i></div>
        <div class="float-item" style="bottom: 15%; left: 8%;"><i class="fa-solid fa-ruler-combined"></i></div>
        <div class="float-item" style="top: 40%; right: 12%; width: 55px; height: 55px;"><i class="fa-solid fa-trowel-bricks"></i></div>
    </div>

    <div class="login-container">
        <div class="glass-panel">
            <!-- left side - hero + slider background -->
            <div class="hero-side">
                <div class="brand-header">
                    <!-- Left Side: Organization Logo -->
                    <div class="logo-circle">
                        <img src="{{ asset(config('panel.logo')) }}" alt="JH Housing Board Logo" style="background:white; border-radius:12px;" onerror="this.src='https://placehold.co/80x80/ffffff/1f7b4d?text=JH'">
                    </div>

                    <!-- Center: Organization Titles -->
                    <div class="org-titles">
                        <h4>{{ config('panel.organization_hindi') }}</h4>
                        <h2>{{ config('panel.organization') }}</h2>
                        <small>{{ config('panel.organization_label') }}</small>
                    </div>

                    <!-- Right Side: Government Logo -->
                    <div class="govt-logo-circle">
                        <a href="https://jharkhand.gov.in/" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset(config('panel.govermentLogo')) }}" alt="Government Logo">
                        </a>
                    </div>
                </div>

                <!-- modern carousel background -->
                <div class="bg-slider-wrapper" id="bgCarousel">
                    <div class="bg-slides" id="slidesContainer">
                        <div class="bg-slide active" style="background-image: url('{{ asset('img/slider1.png') }}');">
                            <div class="slide-overlay"></div>
                            <div class="carousel-caption-text">Secure Access</div>
                        </div>
                    </div>
                </div>

                <div class="hero-description">
                    Jharkhand State Housing Board – comprehensive digital management for allotments, schemes.
                </div>
            </div>

            <!-- right side: login form -->
            <div class="login-side">
                <div class="mobile-brand">
                    <div class="logo-circle" style="width: 50px; height: 50px;">
                        <img src="{{ asset(config('panel.logo')) }}" style="width: 100%;">
                    </div>
                    <div>
                        <h4 style="font-size: 1rem; color: var(--yellow-dark);">{{ config('panel.organization') }}</h4>
                        <strong>Member Portal</strong>
                    </div>
                </div>

                <div class="badge-login">
                    <span class="badge-dot"></span>
                    <span class="badge-text">2FA VERIFICATION</span>
                </div>

                <p class="login-sub">Open Google Authenticator or Microsoft Authenticator app and enter the 6-digit code.</p>

                <!-- session flash messages -->
                @if (session('error'))
                <div class="status-box error">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                <div class="status-box success">{{ session('success') }}</div>
                @endif

                <div style="text-align: center;">
                    <div class="timer-box">
                        <i class="fa-regular fa-clock"></i> Expires in: <span id="countdown">--</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('2fa.verify.post', ['token' => $token]) }}" class="login-form">
                    @csrf

                    <div class="totp-input-container">
                        <input type="text" class="totp-input" id="totp" name="totp" required maxlength="6" autocomplete="off" placeholder="000000" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);" autofocus>
                    </div>

                    <div style="margin-top: 2rem;">
                        <button type="submit" class="btn-submit" id="verifyBtn">
                            <i class="fa-solid fa-shield-check"></i> <span id="submit-btn-text">Verify Code</span>
                        </button>
                    </div>
                </form>

                <div class="form-foot" style="justify-content: center; margin-top: 1.5rem;">
                    <a href="{{ route('login') }}" class="forgot-link" style="color: #64748b;"><i class="fa-solid fa-arrow-left"></i> Cancel and return to login</a>
                </div>

                <!-- Government & Bank logos, partner section -->
                <div class="login-footer" style="margin-top: 3rem;">
                    <!-- Govt / Bank Section -->
                    <div class="footer-block">
                        <span class="footer-label">Powered by</span>
                        <div class="govt-logos">
                            <div class="govt-icon">
                                <a href="https://indianbank.bank.in/" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset(config('panel.patrnterLogo')) }}" alt="Bank">
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Tech Partner -->
                    <div class="footer-block">
                        <span class="footer-label">Tech Partner</span>
                        <a href="https://www.computered.in/" target="_blank" rel="noopener noreferrer" class="partner-badge">
                            <img src="{{ asset(config('panel.techpatrnterLogo')) }}" alt="Computer Ed">
                        </a>
                    </div>
                </div>
                <p class="footer-note">© Jharkhand Housing Board | Secured by Govt. Infrastructure</p>
            </div>
        </div>
    </div>

    <script>
        // Server provided timestamp of when token expires
        const expiresAt = {{ $expiresAt ?? (time() + 10) }};
        const countdownEl = document.getElementById('countdown');
        const inputEl = document.getElementById('totp');
        const btnEl = document.getElementById('verifyBtn');

        function updateTimer() {
            const now = Math.floor(Date.now() / 1000);
            const remaining = expiresAt - now;

            if (remaining <= 0) {
                countdownEl.innerText = "00:00";
                countdownEl.parentElement.style.background = 'rgba(220, 38, 38, 0.2)';
                inputEl.disabled = true;
                btnEl.disabled = true;
                btnEl.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Expired. Redirecting...';

                // Redirect back to login
                setTimeout(() => {
                    window.location.href = "{{ route('login') }}";
                }, 1500);
                return;
            }

            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            countdownEl.innerText = (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
        }

        updateTimer();
        setInterval(updateTimer, 1000);

        // Auto submit when 6 digits are entered
        inputEl.addEventListener('input', function() {
            if (this.value.length === 6) {
                btnEl.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verifying...';
                btnEl.disabled = true;
                this.closest('form').submit();
            }
        });
    </script>
</body>

</html>
