@extends('layouts.public')
@section('title', 'Tenders & Notices - ' . config('panel.organization', 'Jharkhand State Housing Board'))

@section('content')

<!-- Hero Header -->
<section class="page-hero">
    <div class="container hero-inner">
        <div class="hero-left">
            <div class="hero-icon-circle">
                <i class="fa-solid fa-gavel"></i>
            </div>
            <div class="hero-titles">
                <h1>TENDERS & NOTICES</h1>
                <h2>Official Procurement & Public Notices</h2>
                <p>View all active tenders, bid documents, results<br>and important public notices from JSHB.</p>
            </div>
        </div>
        <div class="hero-features">
            <div class="hero-feature-item">
                <i class="fa-solid fa-file-contract feature-icon"></i>
                <div class="feature-text">
                    <h4>Active Tenders</h4>
                    <p>Open bids for construction and services</p>
                </div>
            </div>
            <div class="hero-feature-item">
                <i class="fa-solid fa-bullhorn feature-icon"></i>
                <div class="feature-text">
                    <h4>Public Notices</h4>
                    <p>Important announcements for allottees & public</p>
                </div>
            </div>
            <div class="hero-feature-item">
                <i class="fa-solid fa-trophy feature-icon"></i>
                <div class="feature-text">
                    <h4>Tender Results</h4>
                    <p>View awarded bids and selected contractors</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content -->
<div class="page-section">
    <div class="container">

        <!-- Tabs -->
        <div style="display: flex; gap: 0.5rem; margin-bottom: 2rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0; flex-wrap: wrap;">
            <button class="tab-btn active" onclick="switchTab('tenders', this)">
                <i class="fa-solid fa-file-contract"></i> Active Tenders
            </button>
            <button class="tab-btn" onclick="switchTab('notices', this)">
                <i class="fa-solid fa-bullhorn"></i> Public Notices
            </button>
            <button class="tab-btn" onclick="switchTab('results', this)">
                <i class="fa-solid fa-trophy"></i> Results
            </button>
        </div>

        <!-- Active Tenders Tab -->
        <div id="tab-tenders" class="tab-content active">
            <div class="alert-info" style="margin-bottom: 1.5rem;">
                <i class="fa-solid fa-circle-info"></i>
                <p>All interested parties must submit sealed bids before the deadline. Bid documents can be downloaded from this page or collected from the JSHB Head Office.</p>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">

                <div class="tender-card">
                    <div class="tender-icon tender">
                        <i class="fa-solid fa-hard-hat"></i>
                    </div>
                    <div class="tender-body">
                        <h3>Construction of 120 MIG Flats at Harmu Phase II</h3>
                        <p>Inviting tenders from eligible contractors for RCC framed structure residential complex with all internal and external amenities at Harmu Housing Colony, Ranchi.</p>
                        <div class="tender-meta">
                            <span><i class="fa-solid fa-hashtag"></i> Tender No: JSHB/TEN/2026/047</span>
                            <span><i class="fa-solid fa-calendar"></i> Published: 10 Jul 2026</span>
                            <span><i class="fa-solid fa-calendar-xmark" style="color:#dc2626;"></i> Last Date: <strong style="color:#dc2626;">15 Aug 2026</strong></span>
                            <span><i class="fa-solid fa-indian-rupee-sign"></i> EMD: ₹5,00,000</span>
                        </div>
                    </div>
                    <a href="#" class="tender-download">
                        <i class="fa-solid fa-download"></i> Download
                    </a>
                </div>

                <div class="tender-card">
                    <div class="tender-icon tender">
                        <i class="fa-solid fa-road"></i>
                    </div>
                    <div class="tender-body">
                        <h3>Internal Road & Drainage Work – Adityapur Layout</h3>
                        <p>Tender for construction of internal roads, footpaths and storm water drainage system within the Adityapur Residential Layout, Jamshedpur.</p>
                        <div class="tender-meta">
                            <span><i class="fa-solid fa-hashtag"></i> Tender No: JSHB/TEN/2026/051</span>
                            <span><i class="fa-solid fa-calendar"></i> Published: 20 Jul 2026</span>
                            <span><i class="fa-solid fa-calendar-xmark" style="color:#dc2626;"></i> Last Date: <strong style="color:#dc2626;">25 Aug 2026</strong></span>
                            <span><i class="fa-solid fa-indian-rupee-sign"></i> EMD: ₹2,50,000</span>
                        </div>
                    </div>
                    <a href="#" class="tender-download">
                        <i class="fa-solid fa-download"></i> Download
                    </a>
                </div>

                <div class="tender-card">
                    <div class="tender-icon tender">
                        <i class="fa-solid fa-solar-panel"></i>
                    </div>
                    <div class="tender-body">
                        <h3>Solar Power Installation – JSHB Colonies (Phase I)</h3>
                        <p>Empanelment of agencies for supply, installation, testing and commissioning of rooftop solar PV systems in various JSHB residential colonies in Ranchi.</p>
                        <div class="tender-meta">
                            <span><i class="fa-solid fa-hashtag"></i> Tender No: JSHB/TEN/2026/055</span>
                            <span><i class="fa-solid fa-calendar"></i> Published: 23 Jul 2026</span>
                            <span><i class="fa-solid fa-calendar-xmark" style="color:#dc2626;"></i> Last Date: <strong style="color:#dc2626;">30 Aug 2026</strong></span>
                            <span><i class="fa-solid fa-indian-rupee-sign"></i> EMD: ₹1,00,000</span>
                        </div>
                    </div>
                    <a href="#" class="tender-download">
                        <i class="fa-solid fa-download"></i> Download
                    </a>
                </div>

            </div>
        </div>

        <!-- Public Notices Tab -->
        <div id="tab-notices" class="tab-content" style="display:none;">
            <div style="display: flex; flex-direction: column; gap: 1rem;">

                <div class="tender-card">
                    <div class="tender-icon notice">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="tender-body">
                        <h3>Final Notice to Defaulting Allottees – EMI Clearance</h3>
                        <p>All allottees with pending EMIs for more than 3 months are hereby notified to clear outstanding dues by 31st July 2026 to avoid cancellation of allotment and legal proceedings.</p>
                        <div class="tender-meta">
                            <span><i class="fa-solid fa-calendar"></i> Issued: 01 Jul 2026</span>
                            <span><i class="fa-solid fa-calendar-xmark" style="color:#dc2626;"></i> Deadline: <strong style="color:#dc2626;">31 Jul 2026</strong></span>
                        </div>
                    </div>
                    <a href="#" class="tender-download">
                        <i class="fa-solid fa-file-pdf"></i> View PDF
                    </a>
                </div>

                <div class="tender-card">
                    <div class="tender-icon notice">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div class="tender-body">
                        <h3>Notice Regarding Harmu Phase II Lottery Draw</h3>
                        <p>The computerized lottery draw for allotment of MIG flats under the Harmu Phase II scheme will be held on 15th August 2026 at the JSHB Head Office, Ranchi. All registered applicants are invited.</p>
                        <div class="tender-meta">
                            <span><i class="fa-solid fa-calendar"></i> Issued: 10 Jul 2026</span>
                            <span><i class="fa-solid fa-location-dot"></i> Venue: JSHB HQ, Ranchi</span>
                        </div>
                    </div>
                    <a href="#" class="tender-download">
                        <i class="fa-solid fa-file-pdf"></i> View PDF
                    </a>
                </div>

                <div class="tender-card">
                    <div class="tender-icon notice">
                        <i class="fa-solid fa-info-circle"></i>
                    </div>
                    <div class="tender-body">
                        <h3>New Scheme Registration – Now Open Online</h3>
                        <p>Applications for the Adityapur Residential Layout scheme are now being accepted online through the ADMS portal. Physical applications will not be entertained. Last date to apply is 30th August 2026.</p>
                        <div class="tender-meta">
                            <span><i class="fa-solid fa-calendar"></i> Issued: 20 Jul 2026</span>
                            <span><i class="fa-solid fa-calendar-xmark" style="color:#dc2626;"></i> Last Date: <strong style="color:#dc2626;">30 Aug 2026</strong></span>
                        </div>
                    </div>
                    <a href="#" class="tender-download">
                        <i class="fa-solid fa-file-pdf"></i> View PDF
                    </a>
                </div>

            </div>
        </div>

        <!-- Results Tab -->
        <div id="tab-results" class="tab-content" style="display:none;">
            <div style="display: flex; flex-direction: column; gap: 1rem;">

                <div class="tender-card">
                    <div class="tender-icon result">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <div class="tender-body">
                        <h3>Awarded: Dhanbad Colony Renovation Work</h3>
                        <p>Tender No. JSHB/TEN/2026/031. Contract awarded to <strong>M/s Sharma Constructions Pvt. Ltd.</strong> at a value of ₹3.2 Crore. Work commencement date: 01 Aug 2026.</p>
                        <div class="tender-meta">
                            <span><i class="fa-solid fa-hashtag"></i> Tender No: JSHB/TEN/2026/031</span>
                            <span><i class="fa-solid fa-calendar-check"></i> Awarded: 05 Jul 2026</span>
                            <span><i class="fa-solid fa-indian-rupee-sign"></i> Contract Value: ₹3.2 Crore</span>
                        </div>
                    </div>
                    <a href="#" class="tender-download">
                        <i class="fa-solid fa-file-pdf"></i> View Order
                    </a>
                </div>

                <div class="tender-card">
                    <div class="tender-icon result">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <div class="tender-body">
                        <h3>Lottery Result: Bokaro EWS Scheme 2026</h3>
                        <p>The lottery draw for 250 EWS units under the Bokaro Affordable Housing Scheme has been conducted. Selected allottees list is now available. Please check ADMS portal using your application number.</p>
                        <div class="tender-meta">
                            <span><i class="fa-solid fa-calendar-check"></i> Draw Date: 30 Jun 2026</span>
                            <span><i class="fa-solid fa-users"></i> Total Selected: 250 Allottees</span>
                        </div>
                    </div>
                    <a href="#" class="tender-download">
                        <i class="fa-solid fa-list"></i> View List
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
    .tab-btn {
        padding: 0.625rem 1.25rem;
        font-weight: 700;
        font-size: 0.875rem;
        border: none;
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-family: inherit;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        color: var(--secondary);
    }

    .tab-btn.active {
        color: var(--secondary);
        border-bottom-color: var(--secondary);
    }

    .tab-content { animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }
</style>

@push('scripts')
<script>
    function switchTab(tab, btn) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

        // Show selected tab
        document.getElementById('tab-' + tab).style.display = 'flex';
        document.getElementById('tab-' + tab).style.flexDirection = 'column';
        document.getElementById('tab-' + tab).style.gap = '1rem';
        btn.classList.add('active');
    }
</script>
@endpush

@endsection
