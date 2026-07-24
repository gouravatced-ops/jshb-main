@extends('layouts.public')
@section('title', 'Housing Schemes - ' . config('panel.organization', 'Jharkhand State Housing Board'))

@push('styles')
<style>
    .schemes-grid {
        display: grid;
        gap: 2rem;
        grid-template-columns: 1fr;
    }
</style>
@endpush

@section('content')
<!-- Hero Header -->
<section class="page-hero">
    <div class="container hero-inner">
        <!-- Left Info -->
        <div class="hero-left">
            <div class="hero-icon-circle">
                <i class="fa-solid fa-city"></i>
            </div>
            <div class="hero-titles">
                <h1>HOUSING SCHEMES</h1>
                <h2>Active Projects & Allotments</h2>
                <p>Explore the latest housing projects by JSHB.<br>Find your dream home today.</p>
            </div>
        </div>

        <!-- Right Features -->
        <div class="hero-features">
            <div class="hero-feature-item">
                <i class="fa-solid fa-hand-holding-dollar feature-icon"></i>
                <div class="feature-text">
                    <h4>Affordable Housing</h4>
                    <p>Special schemes for EWS, LIG, MIG and HIG</p>
                </div>
            </div>
            <div class="hero-feature-item">
                <i class="fa-solid fa-shield-halved feature-icon"></i>
                <div class="feature-text">
                    <h4>Transparent Allotment</h4>
                    <p>100% digital and transparent lottery process</p>
                </div>
            </div>
            <div class="hero-feature-item">
                <i class="fa-solid fa-file-invoice-dollar feature-icon"></i>
                <div class="feature-text">
                    <h4>Easy Financing</h4>
                    <p>Flexible EMI options and bank tie-ups</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="content-wrapper">
    <br>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2 style="color: var(--secondary); font-weight: 800; font-size: 1.5rem;">Current Active Schemes</h2>

            <div style="display: flex; gap: 1rem;">
                <select style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #cbd5e1; font-family: inherit; font-weight: 600; color: #475569;">
                    <option value="">All Divisions</option>
                    <option value="1">Ranchi</option>
                    <option value="2">Jamshedpur</option>
                    <option value="3">Dhanbad</option>
                </select>

                <select style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #cbd5e1; font-family: inherit; font-weight: 600; color: #475569;">
                    <option value="">All Categories</option>
                    <option value="ews">EWS</option>
                    <option value="lig">LIG</option>
                    <option value="mig">MIG</option>
                    <option value="hig">HIG</option>
                </select>
            </div>
        </div>

        <div class="schemes-grid">

            <!-- Static Scheme Card 1 -->
            <div class="scheme-card">
                <div class="scheme-header">
                    <div class="scheme-title">
                        <div class="scheme-sno">1</div>
                        <div class="scheme-name-section">
                            <h3>Harmu Housing Complex Phase II</h3>
                            <p class="scheme-name-hindi">हरमू हाउसिंग कॉम्प्लेक्स फेज II</p>
                            <div class="scheme-code">
                                SCH-HARMU-002
                            </div>
                        </div>
                    </div>
                    <div class="scheme-badges">
                        <span class="badge-status active">
                            <i class="fa-solid fa-circle"></i> Active
                        </span>
                        <span class="badge-lease">
                            <i class="fa-regular fa-clock"></i> 99 Years
                        </span>
                    </div>
                </div>

                <div class="scheme-body">
                    <div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-building"></i> Division</span>
                            <span class="info-value">Ranchi</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-layer-group"></i> Sub Division</span>
                            <span class="info-value">Harmu</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-tag"></i> Category</span>
                            <span class="info-value">Middle Income Group (MIG)</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-cube"></i> Type</span>
                            <span class="info-value">Flat / 3 BHK</span>
                        </div>
                    </div>

                    <div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-chart-line"></i> Total Units</span>
                            <span class="info-value">120 units</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-indian-rupee-sign"></i> Est. Price</span>
                            <span class="info-value price-value">₹45,50,000</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"> <i class="fa-solid fa-indian-rupee-sign"></i> Down Payment <span style="font-size: 12px !important; color:red !important; font-weight:600 !important;">(20%)</span></span>
                            <span class="downpayment down-price-value">₹9,10,000</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-indian-rupee-sign"></i> EMI</span>
                            <span class="info-value emi-price-value">₹25,000 × 180</span>
                        </div>
                    </div>

                    <div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-regular fa-calendar"></i> Start Date</span>
                            <span class="info-value">Jul 01, 2026</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-regular fa-calendar-check"></i> End Date</span>
                            <span class="info-value">Oct 31, 2026</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-regular fa-clock"></i> Initiation Year</span>
                            <span class="info-value">2026</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-user"></i> Quarter Type</span>
                            <span class="info-value">Residential</span>
                        </div>
                    </div>
                </div>

                <div class="scheme-footer">
                    <div class="created-info">
                        <i class="fa-regular fa-calendar-alt"></i> Added on: Jul 01, 2026
                    </div>
                    <div>
                        <a href="#" class="apply-btn">
                            Apply Now <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Static Scheme Card 2 -->
            <div class="scheme-card">
                <div class="scheme-header">
                    <div class="scheme-title">
                        <div class="scheme-sno">2</div>
                        <div class="scheme-name-section">
                            <h3>Adityapur Residential Layout</h3>
                            <p class="scheme-name-hindi">आदित्यपुर आवासीय लेआउट</p>
                            <div class="scheme-code">
                                SCH-ADIT-005
                            </div>
                        </div>
                    </div>
                    <div class="scheme-badges">
                        <span class="badge-status active">
                            <i class="fa-solid fa-circle"></i> Active
                        </span>
                        <span class="badge-lease">
                            <i class="fa-regular fa-clock"></i> 30 Years
                        </span>
                    </div>
                </div>

                <div class="scheme-body">
                    <div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-building"></i> Division</span>
                            <span class="info-value">Jamshedpur</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-layer-group"></i> Sub Division</span>
                            <span class="info-value">Adityapur</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-tag"></i> Category</span>
                            <span class="info-value">Lower Income Group (LIG)</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-cube"></i> Type</span>
                            <span class="info-value">Plot / Residential</span>
                        </div>
                    </div>

                    <div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-chart-line"></i> Total Units</span>
                            <span class="info-value">250 units</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-indian-rupee-sign"></i> Est. Price</span>
                            <span class="info-value price-value">₹15,00,000</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"> <i class="fa-solid fa-indian-rupee-sign"></i> Down Payment <span style="font-size: 12px !important; color:red !important; font-weight:600 !important;">(15%)</span></span>
                            <span class="downpayment down-price-value">₹2,25,000</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-indian-rupee-sign"></i> EMI</span>
                            <span class="info-value emi-price-value">₹12,500 × 120</span>
                        </div>
                    </div>

                    <div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-regular fa-calendar"></i> Start Date</span>
                            <span class="info-value">Aug 15, 2026</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-regular fa-calendar-check"></i> End Date</span>
                            <span class="info-value">Nov 30, 2026</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-regular fa-clock"></i> Initiation Year</span>
                            <span class="info-value">2026</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fa-solid fa-user"></i> Quarter Type</span>
                            <span class="info-value">Plot</span>
                        </div>
                    </div>
                </div>

                <div class="scheme-footer">
                    <div class="created-info">
                        <i class="fa-regular fa-calendar-alt"></i> Added on: Jul 15, 2026
                    </div>
                    <div>
                        <a href="#" class="apply-btn">
                            Apply Now <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <br>
</div>
@endsection
