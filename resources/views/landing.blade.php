<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('panel.organization', 'Jharkhand State Housing Board') }}</title>

    <link rel="stylesheet" href="{{ asset('css/font/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/all.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(config('panel.faviconIcon', 'favicon.ico')) }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-inner">
            <div class="nav-brand">
                <img src="{{ asset(config('panel.logo')) }}" alt="JSHB Logo" class="nav-logo" onerror="this.src='https://placehold.co/80x80/ffffff/1f7b4d?text=JH'">
                <div class="nav-title">
                    <small>{{ config('panel.organization_hindi', 'झारखण्ड राज्य आवास बोर्ड') }}</small>
                    <strong>{{ config('panel.organization', 'Jharkhand State Housing Board') }}</strong>
                </div>
            </div>

            <div class="nav-links">
                <a href="#about" class="nav-link">About Us</a>
                <a href="#schemes" class="nav-link">Schemes</a>
                <a href="#contact" class="nav-link">Contact</a>
                <a href="{{ route('login') }}" class="btn-primary">
                    <span>Member Portal</span>
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </a>
            </div>

            <!-- Mobile Login Button -->
            <div class="mobile-login">
                <a href="{{ route('login') }}" class="btn-primary" style="padding: 0.5rem 1rem; font-size: 0.75rem;">
                    Login <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg-gradient"></div>
        <div class="hero-bg-pattern"></div>

        <div class="container hero-grid">
            <div>
                <div class="live-badge">
                    <span class="ping">
                        <span class="ping-circle"></span>
                        <span class="ping-dot"></span>
                    </span>
                    Official Portal Live
                </div>

                <h1 class="hero-title">
                    Building Homes, <br>
                    <span class="text-gradient">Building Jharkhand.</span>
                </h1>

                <p class="hero-desc">
                    The comprehensive digital platform for allotments, housing schemes, and public works management. Access your member portal for a seamless digital experience.
                </p>

                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="btn-large btn-dark">
                        Access Portal <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="#schemes" class="btn-large btn-outline">
                        View Schemes
                    </a>
                </div>
            </div>

            <div class="hero-image-wrapper">
                <div class="blob blob-1"></div>
                <div class="blob blob-2"></div>

                <div class="glass-card">
                    <img src="{{ asset('img/slider1.png') }}" alt="Housing Board" class="glass-img" onerror="this.src='https://placehold.co/600x400/f8fafc/94a3b8?text=Jharkhand+Housing'">

                    <div class="stats-grid">
                        <div class="stat-box">
                            <i class="fa-solid fa-building-user stat-icon"></i>
                            <h3 class="stat-value">Digital</h3>
                            <p class="stat-label">Allotments</p>
                        </div>
                        <div class="stat-box blue">
                            <i class="fa-solid fa-file-signature stat-icon"></i>
                            <h3 class="stat-value">Secure</h3>
                            <p class="stat-label">Applications</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-top">
                <div class="footer-logo">
                    <img src="{{ asset(config('panel.logo')) }}" alt="Logo" onerror="this.src='https://placehold.co/80x80/ffffff/1f7b4d?text=JH'">
                    <span>{{ config('panel.organization', 'Jharkhand State Housing Board') }}</span>
                </div>
                <div class="social-links">
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#"><i class="fa-solid fa-envelope"></i></a>
                </div>
            </div>

            <div class="footer-bottom">
                <div>&copy; {{ date('Y') }} {{ config('panel.organization', 'Jharkhand State Housing Board') }}. All rights reserved.</div>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>
