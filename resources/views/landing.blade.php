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

    <!-- Key Features Section -->
    <section id="schemes" class="section-padding bg-slate-50">
        <div class="container">
            <div class="section-title-wrapper">
                <span class="section-subtitle">Our Services</span>
                <h2 class="section-title">Everything you need to <br>manage your housing journey</h2>
            </div>
            
            <div class="features-grid">
                <!-- Feature 1 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fa-solid fa-house-user"></i>
                    </div>
                    <h3>Digital Allotments</h3>
                    <p>Apply for housing schemes, track your application status, and manage your property allotments completely online without visiting the office.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <h3>Online Payments</h3>
                    <p>Securely pay your EMI, maintenance charges, and processing fees through our integrated digital payment gateway.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fa-solid fa-helmet-safety"></i>
                    </div>
                    <h3>Public Works</h3>
                    <p>Track ongoing construction, view public works tenders, and monitor the progress of housing board development projects.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h3>Support & Grievances</h3>
                    <p>Lodge complaints, submit requests for property transfers, and communicate directly with the housing board officials.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-grid">
                
                <!-- Brand Info -->
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="{{ asset(config('panel.logo')) }}" alt="Logo" onerror="this.src='https://placehold.co/80x80/ffffff/1f7b4d?text=JH'">
                        <span>{{ config('panel.organization', 'Jharkhand State Housing Board') }}</span>
                    </div>
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
                        <li><a href="#about"><i class="fa-solid fa-angle-right" style="margin-right:8px; font-size:0.8em;"></i> About Us</a></li>
                        <li><a href="#schemes"><i class="fa-solid fa-angle-right" style="margin-right:8px; font-size:0.8em;"></i> Latest Schemes</a></li>
                        <li><a href="#"><i class="fa-solid fa-angle-right" style="margin-right:8px; font-size:0.8em;"></i> Tenders & Notices</a></li>
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

</body>

</html>
