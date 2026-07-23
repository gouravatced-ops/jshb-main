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

                <div class="glass-card" style="padding: 1rem;">
                    <!-- Carousel Container -->
                    <div class="carousel-container" id="heroCarousel">
                        <div class="carousel-slide active">
                            <img src="{{ asset('img/slider1.png') }}" alt="Slider 1" class="carousel-img" onerror="this.src='https://placehold.co/600x400/f8fafc/94a3b8?text=Jharkhand+Housing'">
                        </div>
                        <div class="carousel-slide">
                            <img src="{{ asset('img/slider_3.png') }}" alt="Slider 3" class="carousel-img" onerror="this.src='https://placehold.co/600x400/f8fafc/94a3b8?text=Digital+Governance'">
                        </div>
                        <div class="carousel-slide">
                            <img src="{{ asset('img/slider2.png') }}" alt="Slider 2" class="carousel-img" onerror="this.src='https://placehold.co/600x400/f8fafc/94a3b8?text=Building+Jharkhand'">
                        </div>
                        <div class="carousel-slide">
                            <img src="{{ asset('img/slider_4.png') }}" alt="Slider 3" class="carousel-img" onerror="this.src='https://placehold.co/600x400/f8fafc/94a3b8?text=Digital+Governance'">
                        </div>

                        <!-- Indicators -->
                        <div class="carousel-indicators">
                            <span class="indicator active" onclick="goToSlide(0)"></span>
                            <span class="indicator" onclick="goToSlide(1)"></span>
                            <span class="indicator" onclick="goToSlide(2)"></span>
                            <span class="indicator" onclick="goToSlide(3)"></span>
                        </div>
                    </div>

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

    <!-- Key Features Section (With Background Pattern) -->
    <section id="schemes" class="section-padding bg-subtle-pattern">
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

    <!-- Allottee Portal Dedicated Section -->
    <section id="allottee" class="section-padding" style="background-color: #7f1d1d; color: white;">
        <div class="container">
            <div class="hero-grid" style="align-items: center;">
                <div>
                    <span class="live-badge" style="background: rgba(255,255,255,0.1); color: var(--primary); border-color: rgba(250, 204, 21, 0.3);">
                        <i class="fa-solid fa-star"></i> For Existing Allottees
                    </span>
                    <h2 class="section-title" style="color: white; margin-bottom: 1rem;">Exclusive Allottee Portal</h2>
                    <p class="hero-desc" style="color: #f1f5f9; margin-bottom: 2rem;">
                        Are you an existing property owner under the Jharkhand State Housing Board? Access your dedicated dashboard to manage your property, pay EMIs, view maintenance dues, and download important documents.
                    </p>
                    <a href="https://portal.adms.jshb.computered.co.in/" target="_blank" class="btn-large btn-dark" style="background: var(--primary); color: var(--secondary-dark);">
                        Go to Allottee Portal <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
                <div style="text-align: right;">
                    <div style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 1rem; padding: 2.5rem; display: inline-block;">
                        <i class="fa-solid fa-house-chimney-user" style="font-size: 6rem; color: var(--primary); margin-bottom: 1.5rem;"></i>
                        <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Manage Your Property</h3>
                        <p style="color: #cbd5e1; font-size: 0.95rem;">Secure, Digital & Transparent</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New Dynamic Section: Latest Announcements -->
    <section id="announcements" class="section-padding bg-soft-emerald">
        <div class="container">
            <div class="section-title-wrapper">
                <span class="section-subtitle" style="color: var(--primary-dark);">Stay Updated</span>
                <h2 class="section-title light">Latest Announcements & <br>Important Notices</h2>
            </div>

            <div class="features-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2.5rem;">
                <!-- News 1 -->
                <div class="news-card">
                    <img src="{{ asset('img/slider2.png') }}" alt="News" class="news-image" onerror="this.src='https://placehold.co/400x250/e2e8f0/64748b?text=News+Update'">
                    <div class="news-content">
                        <span class="news-date">August 15, 2026</span>
                        <h3 class="news-title">New Housing Scheme Launched in Ranchi</h3>
                        <p class="news-desc">The board has announced a new affordable housing scheme for LIG/MIG categories. Apply online before September 30th.</p>
                    </div>
                </div>

                <!-- News 2 -->
                <div class="news-card">
                    <img src="{{ asset('img/slider1.png') }}" alt="News" class="news-image" onerror="this.src='https://placehold.co/400x250/e2e8f0/64748b?text=Digital+Payment'">
                    <div class="news-content">
                        <span class="news-date">July 20, 2026</span>
                        <h3 class="news-title">Online Payment Gateway Integration</h3>
                        <p class="news-desc">Allottees can now pay their monthly EMIs and maintenance dues seamlessly through our newly integrated UPI and Card payment systems.</p>
                    </div>
                </div>

                <!-- News 3 -->
                <div class="news-card">
                    <img src="{{ asset('img/slider_4.png') }}" alt="News" class="news-image" onerror="this.src='https://placehold.co/400x250/e2e8f0/64748b?text=Public+Notice'">
                    <div class="news-content">
                        <span class="news-date">July 10, 2026</span>
                        <h3 class="news-title">Important Notice for Defaulters</h3>
                        <p class="news-desc">Final notice issued for pending dues. Please clear your outstanding balances immediately to avoid legal action and penalty charges.</p>
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 3rem;">
                <a href="#all-news" class="btn-primary" style="padding: 1rem 2.5rem;">View All Updates</a>
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
                        <li><a href="{{ route('grievance') }}"><i class="fa-solid fa-angle-right" style="margin-right:8px; font-size:0.8em;"></i> Register Grievance</a></li>
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

    <!-- Carousel Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.carousel-slide');
            const indicators = document.querySelectorAll('.indicator');
            let currentSlide = 0;
            let slideInterval;

            function showSlide(index) {
                // Remove active class from all
                slides.forEach(slide => slide.classList.remove('active'));
                indicators.forEach(indicator => indicator.classList.remove('active'));

                // Set new active slide
                currentSlide = index;
                if (currentSlide >= slides.length) currentSlide = 0;
                if (currentSlide < 0) currentSlide = slides.length - 1;

                slides[currentSlide].classList.add('active');
                if (indicators[currentSlide]) indicators[currentSlide].classList.add('active');
            }

            function nextSlide() {
                showSlide(currentSlide + 1);
            }

            // Expose goToSlide globally for indicator clicks
            window.goToSlide = function(index) {
                showSlide(index);
                resetInterval();
            };

            function startInterval() {
                slideInterval = setInterval(nextSlide, 4000); // Change image every 4 seconds
            }

            function resetInterval() {
                clearInterval(slideInterval);
                startInterval();
            }

            // Start auto slide
            if (slides.length > 0) {
                startInterval();
            }
        });
    </script>
</body>

</html>
