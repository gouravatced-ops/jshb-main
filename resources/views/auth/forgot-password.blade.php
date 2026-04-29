<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password | {{ config('panel.portal_name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
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
                        <p class="hero-subtitle">Password recovery access</p>
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
                <div>
                    <p class="hero-text">Enter your registered email address. We will send a secure verification link so you can reset your password.</p>
                </div>
            </section>

            <section class="panel-card login-card">
                <div class="brand-bar">
                    <span class="brand-dot"></span>
                    <span class="brand-title">FORGOT PASSWORD</span>
                </div>
                <p class="login-subtitle">Use your member email to receive an OTP for password reset verification.</p>

                @if (session('success'))
                <div class="status-box">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                <div class="status-box error">{{ $errors->first() }}</div>
                @endif

                @php
                $otpRequired = session('otp_required', session('password_reset_otp_required', false));
                $emailValue = old('email', session('email', session('password_reset_email', '')));
                @endphp

                <form method="POST" action="{{ $otpRequired ? route('password.verify-otp') : route('password.email') }}" class="login-form">
                    @csrf
                    <div class="field">
                        <label for="email">Registered Email</label>
                        <input id="email" name="email" type="email" value="{{ $emailValue }}" placeholder="yourname@example.com" required>
                    </div>

                    @if($otpRequired)
                    <div class="field">
                        <label for="otp_code">OTP Code</label>
                        <input id="otp_code" name="otp_code" type="text" value="{{ old('otp_code') }}" placeholder="Enter 6 digit OTP" inputmode="numeric" pattern="\d*" required>
                    </div>
                    <button type="submit" class="btn-submit">Verify OTP</button>
                    @else
                    <button type="submit" class="btn-submit">Send OTP</button>
                    @endif
                </form>

                <p class="note-link" style="text-align:center;">Remembered it? <a href="{{ route('login') }}">Back to login</a></p>
            </section>
        </div>
    </main>
    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>