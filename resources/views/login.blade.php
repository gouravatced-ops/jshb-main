<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | {{config('panel.portal_name')}}</title>
    <meta name="description" content="Login to the Jharkhand Engineering Service Association portal." />
    <link rel="https://computered.co.in/jesa/apple-touch-icon" sizes="180x180" href="{{asset(config('panel.faviconIcon'))}}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="floating-tools">
        <div class="tool"><i class="fa-solid fa-gear"></i></div>
        <div class="tool"><i class="fa-solid fa-wrench"></i></div>
        <div class="tool"><i class="fa-solid fa-toolbox"></i></div>
        <div class="tool"><i class="fa-solid fa-ruler-combined"></i></div>
        <div class="tool"><i class="fa-solid fa-screwdriver-wrench"></i></div>

        <!-- Engineering / Infrastructure -->
        <!-- <div class="tool"><i class="fa-solid fa-road"></i></div>
        <div class="tool"><i class="fa-solid fa-helmet-safety"></i></div>
        <div class="tool"><i class="fa-solid fa-building"></i></div>
        <div class="tool"><i class="fa-solid fa-bolt"></i></div>
        <div class="tool"><i class="fa-solid fa-industry"></i></div> -->
        <!-- Vehicles / Machinery -->
        <!-- <div class="tool"><i class="fa-solid fa-truck"></i></div>
        <div class="tool"><i class="fa-solid fa-truck-pickup"></i></div>
        <div class="tool"><i class="fa-solid fa-bus"></i></div>
        <div class="tool"><i class="fa-solid fa-car"></i></div>  -->
        <!-- Construction -->
        <!-- <div class="tool"><i class="fa-solid fa-trowel-bricks"></i></div>
        <div class="tool"><i class="fa-solid fa-drafting-compass"></i></div> v> -->
        <main class="page-shell">
            <div class="login-panel">
                <section class="panel-card hero-card">
                    <div class="hero-header">
                        <div class="hero-logo">
                            <img src="{{ asset(config('panel.logo')) }}" alt="JESA Logo">
                        </div>
                        <div class="hero-copy">
                            <p class="hero-hindi">{{ config('panel.organization_hindi') }}</p>
                            <p class="hero-company">{{ config('panel.organization') }}</p>
                            <p class="hero-subtitle">{{ config('panel.organization_label') }}</p>
                        </div>
                    </div>

                    <div class="carousel-frame">
                        <div class="slides">
                            <article class="slide">
                                <img class="slide-image" src="{{ asset('slider/main-slider-1-3.jpg')}}">
                            </article>

                            <article class="slide">
                                <img class="slide-image" src="{{ asset('slider/main-slider-1-2.jpg')}}">
                            </article>

                            <article class="slide">
                                <img class="slide-image" src="{{ asset('slider/main-slider-1-1.jpg')}}">
                            </article>
                        </div>
                    </div>
                    <!-- <div class="carousel-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div> -->
                    <div>
                        <!-- <p class="brand-title">Jharkhand Engineering Service Association</p> -->
                        <p class="hero-text">Jharkhand Engineering Services Association <span class="note-link"><a href="https://computered.co.in/jesa/" target="blank">(JESA)</a></span>, established in 2000 and headquartered in Ranchi, represents Graduate Engineers from key state departments including Road Construction, Water Resources, Drinking Water & Sanitation, Rural Works, and Building Construction.</p>
                    </div>
                </section>

                <section class="panel-card login-card">
                    <div class="mobile-login-header">
                        <div class="hero-logo">
                            <img src="{{ asset('img/jesa_logo.png')}}" alt="JESA Logo">
                        </div>
                        <div class="hero-copy">
                            <p class="hero-hindi">{{ config('panel.organization_hindi') }}</p>
                            <p class="hero-company">{{ config('panel.organization') }}</p>
                        </div>
                    </div>
                    <div class="brand-bar">
                        <span class="brand-dot"></span>
                        <span class="brand-title">JESA MEMBER LOGIN</span>
                    </div>
                    <!-- <h2 class="login-title">Welcome back</h2> -->
                    <p class="login-subtitle">Enter your credentials below to continue to the member area.</p>

                    @if (session('error'))
                    <div class="status-box error">{{ session('error') }}</div>
                    @endif

                    @if (session('success'))
                    <div class="status-box">{{ session('success') }}</div>
                    @endif

                    @php
                    $otpRequired = session('otp_required', false);
                    $emailValue = old('email', session('email', ''));
                    @endphp

                    <form method="POST" action="{{ route('login.post') }}" class="login-form">
                        @csrf
                        <input type="hidden" name="otp_stage" value="{{ $otpRequired ? 1 : 0 }}">

                        <div class="field">
                            <label for="email">Email or Username</label>
                            <input id="email" name="email" type="text" value="{{ $emailValue }}" placeholder="yourname@example.com" required>
                        </div>

                        @if (! $otpRequired)
                        <div class="field">
                            <label for="password">Password</label>
                            <input id="password" name="password" type="password" placeholder="Enter password" required>
                        </div>
                        <div class="field captcha-field">
                            <label for="captcha_input">Captcha</label>
                            <div class="captcha-row">
                                <div id="captchaCode" class="captcha-code"></div>
                                <button type="button" class="captcha-refresh" id="refreshCaptcha" aria-label="Refresh captcha">↻</button>
                                <input id="captcha_input" name="captcha_input" type="text" placeholder="Enter captcha code" autocomplete="off" required>
                            </div>
                            <div id="captchaMessage" class="captcha-message">Captcha is not case-sensitive. Enter the text shown above to enable login.</div>
                        </div>
                        @endif

                        @if ($otpRequired)
                        <div class="field">
                            <label for="otp_code">OTP Code</label>
                            <input id="otp_code" name="otp_code" type="text" value="{{ old('otp_code') }}" placeholder="Enter OTP code" inputmode="numeric" pattern="\d*" required>
                        </div>
                        <p class="login-note">An OTP code has been generated for your account. Enter it here to complete login.</p>
                        @endif

                        <div class="form-foot">
                            <label class="remember"></label>
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                        </div>
                        <button type="submit" class="btn-submit" id="loginBtn" @if(! $otpRequired) disabled @endif>Login to Account</button>
                    </form>

                    <p class="note-link" style="text-align:center;">Go Website? <a href="/">Visit</a></p>
                    <div class="login-footer">
                        <div class="login-footer-label">Technology Partner</div>
                        <div class="partner-row">
                            <div class="partner-logo">
                                <img src="{{ asset(config('panel.patrnterLogo')) }}" alt="COMPUTER Ed. Logo" onerror="this.style.display='none'">
                            </div>
                            <div class="partner-name"><a href="https://www.computered.in/" target="_blank" rel="noopener noreferrer">COMPUTER Ed.</a></div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
        <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>