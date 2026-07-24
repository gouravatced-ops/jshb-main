@extends('layouts.public')
@section('title', 'About Us - ' . config('panel.organization', 'Jharkhand State Housing Board'))

@section('content')

<!-- Hero Header -->
<section class="page-hero">
    <div class="container hero-inner">
        <div class="hero-left">
            <div class="hero-icon-circle">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div class="hero-titles">
                <h1>ABOUT JSHB</h1>
                <h2>Building Homes, Building Jharkhand</h2>
                <p>Learn about our mission, vision, and the work we do<br>for the citizens of Jharkhand.</p>
            </div>
        </div>
        <div class="hero-features">
            <div class="hero-feature-item">
                <i class="fa-solid fa-bullseye feature-icon"></i>
                <div class="feature-text">
                    <h4>Our Mission</h4>
                    <p>Providing affordable housing to every citizen</p>
                </div>
            </div>
            <div class="hero-feature-item">
                <i class="fa-solid fa-eye feature-icon"></i>
                <div class="feature-text">
                    <h4>Our Vision</h4>
                    <p>A Jharkhand where every family owns a home</p>
                </div>
            </div>
            <div class="hero-feature-item">
                <i class="fa-solid fa-handshake feature-icon"></i>
                <div class="feature-text">
                    <h4>Our Commitment</h4>
                    <p>Transparency, efficiency and digital governance</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<div class="page-section">
    <div class="container">

        <!-- Stats Grid -->
        <div class="about-stats-grid">
            <div class="about-stat-card">
                <div class="stat-icon"><i class="fa-solid fa-building"></i></div>
                <div class="stat-num">50+</div>
                <div class="stat-label">Housing Schemes</div>
            </div>
            <div class="about-stat-card">
                <div class="stat-icon"><i class="fa-solid fa-house-chimney"></i></div>
                <div class="stat-num">1 Lakh+</div>
                <div class="stat-label">Units Allotted</div>
            </div>
            <div class="about-stat-card">
                <div class="stat-icon"><i class="fa-solid fa-map-location-dot"></i></div>
                <div class="stat-num">24</div>
                <div class="stat-label">Districts Covered</div>
            </div>
            <div class="about-stat-card">
                <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div class="stat-num">1952</div>
                <div class="stat-label">Year Established</div>
            </div>
        </div>

        <!-- Content Grid -->
        <div style="display: grid; gap: 2rem; align-items: start;" class="about-content-grid">

            <!-- Main Content -->
            <div class="content-card">
                <div class="about-prose">
                    <h3><i class="fa-solid fa-circle-info" style="color:var(--primary-dark); margin-right:0.5rem;"></i> Who We Are</h3>
                    <p>
                        The Jharkhand State Housing Board (JSHB) is a statutory body established under the Jharkhand Housing Board Act. It is the primary agency responsible for housing development and construction of residential colonies in the state of Jharkhand.
                    </p>
                    <p>
                        JSHB works under the Housing Department of the Government of Jharkhand with the primary objective of making affordable housing accessible to all sections of society — from Economically Weaker Sections (EWS) to Higher Income Groups (HIG).
                    </p>

                    <h3><i class="fa-solid fa-flag" style="color:var(--primary-dark); margin-right:0.5rem;"></i> Our Objectives</h3>
                    <ul>
                        <li>To provide affordable housing to EWS, LIG, MIG and HIG groups.</li>
                        <li>To develop planned residential colonies and townships across Jharkhand.</li>
                        <li>To maintain transparency and efficiency in housing allotments.</li>
                        <li>To digitize the allotment, EMI collection, and grievance processes.</li>
                        <li>To collaborate with central government schemes like PMAY (Pradhan Mantri Awas Yojana).</li>
                    </ul>

                    <h3><i class="fa-solid fa-landmark" style="color:var(--primary-dark); margin-right:0.5rem;"></i> History & Background</h3>
                    <p>
                        The Board was constituted to address the growing housing needs of the state. Over the decades, JSHB has developed hundreds of housing colonies across major cities including Ranchi, Jamshedpur, Dhanbad, Bokaro, and Hazaribagh. The board has continuously evolved its processes and now offers a fully digital experience to allottees through the ADMS portal.
                    </p>

                    <h3><i class="fa-solid fa-laptop-code" style="color:var(--primary-dark); margin-right:0.5rem;"></i> Digital Initiatives</h3>
                    <p>
                        JSHB has launched the <strong>Allottee Digital Management System (ADMS)</strong> — a comprehensive online portal that enables allottees to view their account details, pay EMIs online, check allotment status, and submit grievances, all from the comfort of their homes.
                    </p>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <div class="sidebar-box box-navy">
                    <div class="box-header">
                        <i class="fa-solid fa-sitemap" style="color:var(--primary);"></i>
                        Administrative Structure
                    </div>
                    <ul class="info-list">
                        <li><strong>Chairman:</strong> IAS Officer (ex-officio)</li>
                        <li><strong>Vice Chairman / CEO:</strong> IAS / JHAS Officer</li>
                        <li><strong>Accounts Department</strong></li>
                        <li><strong>Technical / Engineering Wing</strong></li>
                        <li><strong>Allotment & Revenue Division</strong></li>
                        <li><strong>IT & Digital Initiatives Cell</strong></li>
                    </ul>
                </div>

                <div class="sidebar-box box-yellow">
                    <div class="box-header">
                        <i class="fa-solid fa-link"></i>
                        Useful Links
                    </div>
                    <ul class="info-list" style="color: var(--secondary-dark);">
                        <li><a href="https://portal.adms.jshb.computered.co.in/" target="_blank" style="color:var(--secondary-dark); font-weight:700;">Allottee Portal (ADMS)</a></li>
                        <li><a href="{{ route('schemes') }}" style="color:var(--secondary-dark); font-weight:700;">View Active Schemes</a></li>
                        <li><a href="{{ route('grievance') }}" style="color:var(--secondary-dark); font-weight:700;">Register a Grievance</a></li>
                        <li><a href="{{ route('tenders') }}" style="color:var(--secondary-dark); font-weight:700;">Tenders & Notices</a></li>
                    </ul>
                </div>

                <div class="sidebar-box box-light">
                    <div class="box-header" style="color: var(--secondary);">
                        <i class="fa-solid fa-location-dot" style="color:var(--secondary);"></i>
                        Head Office
                    </div>
                    <ul class="contact-list">
                        <li>
                            <i class="fa-solid fa-map-pin" style="color:var(--secondary);"></i>
                            <span>Harmu Housing Colony, Ranchi, Jharkhand – 834002</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-phone" style="color:var(--secondary);"></i>
                            <span>+91 1800-XXX-XXXX (Toll Free)</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-envelope" style="color:var(--secondary);"></i>
                            <span>support@jshb.gov.in</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @media (min-width: 1024px) {
        .about-content-grid {
            grid-template-columns: 1fr 350px;
        }
    }
</style>

@endsection
