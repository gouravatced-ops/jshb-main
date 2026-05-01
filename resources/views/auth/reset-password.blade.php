<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <title>Reset Password | {{ config('panel.portal_name') }}</title>
    <meta name="description" content="Jharkhand Housing Board - Official management login portal" />
    <!-- Google Fonts + Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/font/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
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
                        <small>Set a new password</small>
                    </div>

                    <!-- Right Side: Government Logo -->
                    <div class="govt-logo-circle">
                        <a href="https://jharkhand.gov.in/" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset(config('panel.govermentLogo')) }}" alt="Government Logo">
                        </a>
                    </div>
                </div>

                <!-- modern carousel background (slide images as dynamic backdrop) -->
                <div class="bg-slider-wrapper" id="bgCarousel">
                    <div class="bg-slides" id="slidesContainer">
                        <div class="bg-slide active" style="background-image: url('img/slider1.png');">
                            <div class="slide-overlay"></div>
                            <div class="carousel-caption-text">Fill the Application</div>
                        </div>
                        <div class="bg-slide" style="background-image: url('img/slider2.png');">
                            <div class="slide-overlay"></div>
                            <div class="carousel-caption-text">Manage Panel</div>
                        </div>
                    </div>
                </div>

                <div class="hero-description">
                    Jharkhand Housing Board – OTP verification is complete. Create a fresh password to regain access to the JSHB portal.
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
                    <span class="badge-text">RESET PASSWORD</span>
                </div>
                <!-- <h1 class="login-title">Welcome </h1> -->
                <p class="login-sub">Choose a strong password with at least 8 characters.</p>

                @if ($errors->any())
                <div class="status-box error">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('password.store') }}" class="login-form">
                    @csrf

                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $email) }}" readonly required>
                    </div>

                    <div class="field">
                        <label for="password">New Password</label>
                        <input id="password" name="password" type="password" placeholder="Enter new password" required>
                        <!-- Eye Icon -->
                        <i id="togglePassword" class="fa-solid fa-eye"></i>
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm new password" required>
                    </div>

                    <button type="submit" class="btn-submit">Reset Password</button>
                </form>

                <p class="note-link" style="text-align:center;">Back to <a href="{{ route('login') }}">login</a></p>

                <!-- Government & Bank logos, partner section -->
                <div class="login-footer">

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
    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>