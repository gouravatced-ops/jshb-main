<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <title>Login | Jharkhand Housing Board</title>
  <meta name="description" content="Jharkhand Housing Board - Official management login portal" />
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset(config('panel.faviconIcon')) }}">
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
            <small>{{ config('panel.organization_label') }}</small>
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
          Jharkhand Housing Board – comprehensive digital management for allotments, schemes, public works.
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
          <span class="badge-text">SECURE LOGIN</span>
        </div>
        <!-- <h1 class="login-title">Welcome </h1> -->
        <p class="login-sub">Sign in to access housing schemes, engineer dashboard & official records.</p>

        <!-- session flash messages (demo dynamic) -->
        @if (session('error'))
        <div class="status-box error">{{ session('error') }}</div>
        @endif
        @if (session('success'))
        <div class="status-box success">{{ session('success') }}</div>
        @endif

        @php
        $otpRequired = session('otp_required', false);
        $emailValue = old('email', session('email', ''));
        @endphp

        <form method="POST" action="{{ route('login.post') }}" class="login-form">
          @csrf
          <input type="hidden" name="otp_stage" value="{{ $otpRequired ? 1 : 0 }}">

          <div class="field">
            <label for="email"><i class="fa-regular fa-envelope"></i> Email or Username</label>
            <input id="email" name="email" type="text" value="{{ $emailValue }}" placeholder="user@jharkhand.gov.in" required>
          </div>

          @if (! $otpRequired)
          <div class="field">
            <label for="password"><i class="fa-solid fa-lock"></i> Password</label>
            <input id="password" name="password" type="password" placeholder="··········" required>
            <!-- Eye Icon -->
            <i id="togglePassword" class="fa-solid fa-eye"></i>
          </div>

          <div class="field captcha-field">
            <label for="captcha_input">Security Captcha</label>
            <div class="captcha-row">
              <div id="captchaCode" class="captcha-code">JH42K</div>
              <button type="button" class="captcha-refresh" id="refreshCaptcha" aria-label="Refresh captcha"><i class="fa-solid fa-arrows-rotate"></i></button>
              <input id="captcha_input" name="captcha_input" type="text" placeholder="Enter code" autocomplete="off" required>
            </div>
          </div>
          @endif

          @if ($otpRequired)
          <div class="field">
            <label for="otp_code"><i class="fa-solid fa-key"></i> OTP Verification</label>
            <input id="otp_code" name="otp_code" type="text" placeholder="6-digit OTP" inputmode="numeric" pattern="\d*" required>
          </div>
          <p class="login-note" style="font-size:0.8rem">A secure OTP has been sent to your registered email.</p>
          @endif

          <div class="form-foot">
            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
          </div>

          <button type="submit" class="btn-submit" id="loginBtn" @if(! $otpRequired) disabled @endif>
            <i class="fa-solid fa-arrow-right-to-bracket"></i> {{ $otpRequired ? 'Verify & Login' : 'Login to Account' }}
          </button>
        </form>

        <div class="note-link">
          <a href="/"><i class="fa-solid fa-globe"></i> Housing Board Portal</a>
        </div>

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
              <!-- <div class="govt-icon secondimage">
                <a href="https://jharkhand.gov.in/" target="_blank" rel="noopener noreferrer">
                  <img src="{{ asset(config('panel.govermentLogo')) }}" alt="Govt of Jharkhand">
                </a>
              </div> -->
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