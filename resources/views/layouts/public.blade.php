<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('panel.organization', 'Jharkhand State Housing Board'))</title>

    <link rel="stylesheet" href="{{ asset('css/font/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/all.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(config('panel.faviconIcon', 'favicon.ico')) }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    @stack('styles')
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-inner">
            <a href="{{ route('landing') }}" class="nav-brand" style="text-decoration: none; color: inherit;">
                <img src="{{ asset(config('panel.logo')) }}" alt="JSHB Logo" class="nav-logo" onerror="this.src='https://placehold.co/80x80/ffffff/1f7b4d?text=JH'">
                <div class="nav-title">
                    <small>{{ config('panel.organization_hindi', 'झारखण्ड राज्य आवास बोर्ड') }}</small>
                    <strong>{{ config('panel.organization', 'Jharkhand State Housing Board') }}</strong>
                </div>
            </a>

            <div class="nav-links">
                <a href="{{ route('about') }}" class="nav-link">About Us</a>
                <a href="{{ route('schemes') }}" class="nav-link">Schemes</a>
                <a href="{{ route('elottery') }}" class="nav-link">E-Lottery</a>
                <a href="{{ route('tenders') }}" class="nav-link">Tenders & Notices</a>
                <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                
                <a href="https://portal.adms.jshb.computered.co.in/" target="_blank" class="btn-maroon">
                    <i class="fa-solid fa-house-user"></i> Allottee Portal
                </a>
                
                <a href="{{ route('login') }}" class="btn-primary">
                    <i class="fa-solid fa-user-shield"></i> Official Login
                </a>
            </div>

            <!-- Mobile Login Buttons -->
            <div class="mobile-login-container">
                <a href="https://portal.adms.jshb.computered.co.in/" target="_blank" class="btn-maroon" style="padding: 0.5rem; font-size: 0.75rem; border-radius: 0.5rem;">
                    <i class="fa-solid fa-house-user"></i>
                </a>
                <a href="{{ route('login') }}" class="btn-primary" style="padding: 0.5rem; font-size: 0.75rem; border-radius: 0.5rem;">
                    <i class="fa-solid fa-user-shield"></i>
                </a>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-grid">

                <!-- Brand Info -->
                <div class="footer-brand">
                    <a href="{{ route('landing') }}" class="footer-logo" style="text-decoration: none; color: inherit;">
                        <img src="{{ asset(config('panel.logo')) }}" alt="Logo" onerror="this.src='https://placehold.co/80x80/ffffff/1f7b4d?text=JH'">
                        <span>{{ config('panel.organization', 'Jharkhand State Housing Board') }}</span>
                    </a>
                    <p>Empowering the citizens of Jharkhand with transparent, digital, and efficient housing solutions and public infrastructure management.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="footer-heading">Quick Links</h4>
                    <ul class="footer-menu">
                        <li><a href="{{ route('about') }}"><i class="fa-solid fa-angle-right" style="margin-right:8px; font-size:0.8em;"></i> About Us</a></li>
                        <li><a href="{{ route('schemes') }}"><i class="fa-solid fa-angle-right" style="margin-right:8px; font-size:0.8em;"></i> Latest Schemes</a></li>
                        <li><a href="{{ route('grievance') }}"><i class="fa-solid fa-angle-right" style="margin-right:8px; font-size:0.8em;"></i> Register Grievance</a></li>
                        <li><a href="{{ route('tenders') }}"><i class="fa-solid fa-angle-right" style="margin-right:8px; font-size:0.8em;"></i> Tenders & Notices</a></li>
                        <li><a href="{{ route('contact') }}"><i class="fa-solid fa-angle-right" style="margin-right:8px; font-size:0.8em;"></i> Contact Us</a></li>
                        <li><a href="{{ route('login') }}"><i class="fa-solid fa-angle-right" style="margin-right:8px; font-size:0.8em;"></i> Member Login</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="footer-heading">Contact Us</h4>
                    <ul class="footer-menu footer-contact">
                        <li>
                            <i class="fa-solid fa-location-dot"></i>
                            <span>Harmu Housing Colony,<br>Ranchi, Jharkhand 834002</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-phone"></i>
                            <span>+91 1800-XXX-XXXX</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-envelope"></i>
                            <span>support@jshb.gov.in</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom" style="border-top: 1px solid #334155; padding-top: 2rem;">
                <div>&copy; {{ date('Y') }} {{ config('panel.organization', 'Jharkhand State Housing Board') }}. All rights reserved.</div>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
