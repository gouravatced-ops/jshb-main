@extends('layouts.public')
@section('title', 'Housing Schemes - ' . config('panel.organization', 'Jharkhand State Housing Board'))

@push('styles')
    <style>
        /* Hero Section */
        .schemes-hero {
            background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--secondary) 100%);
            padding: 3rem 0;
            color: white;
            border-bottom: 5px solid var(--primary);
        }

        .hero-inner {
            display: grid;
            gap: 2rem;
            align-items: center;
        }

        @media (min-width: 992px) {
            .hero-inner {
                grid-template-columns: 1fr 1fr;
            }
        }

        .hero-left {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .hero-icon-circle {
            width: 5rem;
            height: 5rem;
            border-radius: 50%;
            border: 3px solid var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            flex-shrink: 0;
        }

        .hero-titles h1 {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
            letter-spacing: 0.02em;
        }

        .hero-titles h2 {
            color: var(--primary);
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .hero-titles p {
            font-size: 0.875rem;
            color: #cbd5e1;
            line-height: 1.4;
        }

        .hero-features {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .hero-feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .feature-icon {
            color: var(--primary);
            font-size: 1.5rem;
            margin-top: 0.25rem;
        }

        .feature-text h4 {
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .feature-text p {
            font-size: 0.8rem;
            color: #94a3b8;
            margin: 0;
        }

        /* Scheme Grid Styles (from Admin) */
        .content-wrapper {
            padding: 3rem 0;
            background-color: #f1f5f9;
        }
        
        .schemes-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .scheme-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
        }

        .scheme-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
            border-color: var(--secondary);
        }

        .scheme-header {
            padding: 18px 24px;
            background: #fafbfc;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .scheme-title {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .scheme-sno {
            width: 36px;
            height: 36px;
            background: var(--secondary);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .scheme-name-section h3 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
        }

        .scheme-name-hindi {
            font-size: 14px;
            color: #068d23;
            margin: 0;
            font-weight: 700;
        }

        .scheme-code {
            font-size: 14px;
            color: white;
            font-family: monospace;
            background: #10b981;
            padding: 4px 12px;
            border-radius: 6px;
            margin-top: 6px;
            font-weight: 600;
            display: inline-block;
        }

        .scheme-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-status.active {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-lease {
            background: #e0e7ff;
            color: #3730a3;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .scheme-body {
            padding: 20px 24px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #f0f0f0;
        }

        .info-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 600;
        }

        .info-value {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
        }

        .price-value {
            color: #059669;
            font-size: 14px;
            font-weight: 800;
        }

        .down-price-value {
            color: #cc050f;
            font-size: 14px;
            font-weight: 800;
        }

        .emi-price-value {
            color: #084ee7;
            font-size: 14px;
            font-weight: 800;
        }

        .scheme-footer {
            padding: 14px 24px;
            background: #fafbfc;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .created-info {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
        }

        .apply-btn {
            background-color: var(--primary);
            color: var(--secondary-dark);
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .apply-btn:hover {
            background-color: #eab308;
            color: var(--secondary-dark);
        }

        @media (max-width: 768px) {
            .scheme-body {
                grid-template-columns: 1fr;
            }

            .scheme-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Hero Header -->
    <section class="schemes-hero">
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
    </div>
@endsection
