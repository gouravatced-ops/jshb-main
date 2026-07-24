@extends('layouts.public')
@section('title', 'Contact Us - ' . config('panel.organization', 'Jharkhand State Housing Board'))

@section('content')

<!-- Hero Header -->
<section class="page-hero">
    <div class="container hero-inner">
        <div class="hero-left">
            <div class="hero-icon-circle">
                <i class="fa-solid fa-headset"></i>
            </div>
            <div class="hero-titles">
                <h1>CONTACT US</h1>
                <h2>We're Here to Help</h2>
                <p>Reach out to us for any queries about your allotment,<br>EMI, schemes, or other JSHB matters.</p>
            </div>
        </div>
        <div class="hero-features">
            <div class="hero-feature-item">
                <i class="fa-solid fa-clock feature-icon"></i>
                <div class="feature-text">
                    <h4>Office Hours</h4>
                    <p>Mon – Fri: 10:00 AM to 5:00 PM</p>
                </div>
            </div>
            <div class="hero-feature-item">
                <i class="fa-solid fa-phone-volume feature-icon"></i>
                <div class="feature-text">
                    <h4>Toll-Free Helpline</h4>
                    <p>1800-XXX-XXXX (Available during office hours)</p>
                </div>
            </div>
            <div class="hero-feature-item">
                <i class="fa-solid fa-reply feature-icon"></i>
                <div class="feature-text">
                    <h4>Email Response</h4>
                    <p>Within 2–3 working days</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content -->
<div class="page-section">
    <div class="container">

        <!-- Contact Info Cards Row -->
        <div style="display: grid; gap: 1.25rem; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 3rem;">
            <div class="contact-info-card">
                <div class="contact-info-icon">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <h4>Head Office Address</h4>
                    <p>Harmu Housing Colony,<br>Ranchi, Jharkhand – 834002</p>
                </div>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-icon yellow">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div>
                    <h4>Phone / Helpline</h4>
                    <a href="tel:18001234567">+91 1800-XXX-XXXX</a>
                </div>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                    <h4>Email Address</h4>
                    <a href="mailto:support@jshb.gov.in">support@jshb.gov.in</a>
                </div>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-icon yellow">
                    <i class="fa-solid fa-fax"></i>
                </div>
                <div>
                    <h4>Fax Number</h4>
                    <p>+91 651-XXXXXXX</p>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div style="display: grid; gap: 2rem;" class="contact-main-grid">

            <!-- Contact Form -->
            <div class="content-card">
                <div class="form-header">
                    <i class="fa-solid fa-paper-plane"></i>
                    Send Us a Message
                </div>
                <form>
                    <div class="form-grid two-cols" style="margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Your full name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" placeholder="10-digit mobile number">
                        </div>
                    </div>
                    <div class="form-grid two-cols" style="margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" placeholder="your@email.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <select class="form-control">
                                <option value="">-- Select Subject --</option>
                                <option>Allotment Query</option>
                                <option>EMI / Payment Issue</option>
                                <option>Scheme Information</option>
                                <option>Grievance Follow-up</option>
                                <option>General Inquiry</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label">Allottee ID <span class="text-muted" style="font-weight:500;">(If applicable)</span></label>
                        <input type="text" class="form-control" placeholder="e.g. JSHB-2024-XXXXX">
                    </div>
                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label class="form-label">Your Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" placeholder="Describe your query in detail..."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-paper-plane"></i> Send Message
                        </button>
                        <button type="reset" class="btn-reset">Reset Form</button>
                    </div>

                    <div class="alert-info" style="margin-top: 1.5rem;">
                        <i class="fa-solid fa-circle-info"></i>
                        <p>For urgent grievances, please use the dedicated <a href="{{ route('grievance') }}" style="color:var(--secondary); font-weight:700;">Grievance Portal</a> for faster response.</p>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Office Locations -->
                <div class="sidebar-box box-navy" style="margin-bottom: 1.5rem;">
                    <div class="box-header">
                        <i class="fa-solid fa-map-pin" style="color:var(--primary);"></i>
                        Regional Offices
                    </div>
                    <ul class="info-list">
                        <li><strong style="color:#facc15;">Ranchi (HQ)</strong> – Harmu Housing Colony</li>
                        <li><strong style="color:#facc15;">Jamshedpur</strong> – JSHB Regional Office, Adityapur</li>
                        <li><strong style="color:#facc15;">Dhanbad</strong> – Near Bank More, Main Road</li>
                        <li><strong style="color:#facc15;">Bokaro</strong> – JSHB Division Office, Sector-4</li>
                        <li><strong style="color:#facc15;">Hazaribagh</strong> – JSHB Sub-Division Office</li>
                    </ul>
                </div>

                <!-- Map -->
                <div class="map-wrapper">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3661.4!2d85.3095!3d23.3444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDIwJzM4LjAiTiA4NcKwMTgnMzQuMiJF!5e0!3m2!1sen!2sin!4v1234567890"
                        allowfullscreen="" loading="lazy">
                    </iframe>
                </div>

                <div class="sidebar-box box-yellow" style="margin-top: 1.5rem;">
                    <div class="box-header">
                        <i class="fa-solid fa-clock"></i>
                        Working Hours
                    </div>
                    <ul class="info-list" style="color: var(--secondary-dark);">
                        <li>Monday – Friday: <strong>10:00 AM – 5:00 PM</strong></li>
                        <li>Saturday: <strong>10:00 AM – 2:00 PM</strong></li>
                        <li>Sunday & Public Holidays: <strong>Closed</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (min-width: 1024px) {
        .contact-main-grid {
            grid-template-columns: 1fr 380px;
        }
    }
</style>

@endsection
