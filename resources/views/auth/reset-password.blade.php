<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password | {{ config('panel.portal_name') }}</title>
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
                        <p class="hero-subtitle">Set a new password</p>
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
                    <p class="hero-text">OTP verification is complete. Create a fresh password to regain access to the JESA member portal.</p>
                </div>
            </section>

            <section class="panel-card login-card">
                <div class="brand-bar">
                    <span class="brand-dot"></span>
                    <span class="brand-title">RESET PASSWORD</span>
                </div>
                <p class="login-subtitle">Choose a strong password with at least 8 characters.</p>

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
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm new password" required>
                    </div>

                    <button type="submit" class="btn-submit">Reset Password</button>
                </form>

                <p class="note-link" style="text-align:center;">Back to <a href="{{ route('login') }}">login</a></p>
            </section>
        </div>
    </main>
    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>