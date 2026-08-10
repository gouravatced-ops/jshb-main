@extends('layouts.public')
@section('title', 'E-Lottery - ' . config('panel.organization', 'Jharkhand State Housing Board'))

@push('styles')
<style>
    .properties-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        margin-top: 2rem;
    }

    .property-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .property-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .property-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-bottom: 3px solid #16a34a;
    }

    .property-body {
        padding: 1.5rem;
    }

    .property-badge {
        display: inline-block;
        background: #e0f2fe;
        color: #0284c7;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 700;
        border-radius: 20px;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .property-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .property-location {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 1rem;
    }

    .property-stats {
        display: flex;
        justify-content: space-between;
        background: #f8fafc;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-label {
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 600;
        display: block;
    }

    .stat-value {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
    }

    .property-actions {
        display: flex;
        gap: 10px;
    }

    .btn-brochure {
        flex: 1;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        padding: 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        text-align: center;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-brochure:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .btn-apply {
        flex: 1;
        background: #16a34a;
        color: #fff;
        border: none;
        padding: 8px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 13px;
        text-align: center;
        transition: background 0.2s;
    }

    .btn-apply:hover {
        background: #15803d;
        color: #fff;
    }
</style>
@endpush

@section('content')
<!-- Hero Header -->
<section class="page-hero">
    <div class="container hero-inner">
        <!-- Left Info -->
        <div class="hero-left">
            <div class="hero-icon-circle" style="background: rgba(22, 163, 74, 0.1);">
                <i class="fa-solid fa-ticket"></i>
            </div>
            <div class="hero-titles">
                <h1>E-LOTTERY PORTAL</h1>
                <h2>Transparent Property Allotment</h2>
                <p>Browse available properties and apply for the electronic draw.<br>100% fair, secure, and digital process.</p>
            </div>
        </div>

        <!-- Right Features -->
        <div class="hero-features">
            <div class="hero-feature-item">
                <i class="fa-solid fa-house-chimney feature-icon" style="color: #16a34a; background: #f0fdf4;"></i>
                <div class="feature-text">
                    <h4>Prime Locations</h4>
                    <p>Properties in highly sought-after areas</p>
                </div>
            </div>
            <div class="hero-feature-item">
                <i class="fa-solid fa-gavel feature-icon" style="color: #16a34a; background: #f0fdf4;"></i>
                <div class="feature-text">
                    <h4>Fair Draw</h4>
                    <p>Computerized randomized allocation system</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="content-wrapper">
    <br>
    <div class="container mb-5">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 1rem;">
            <div>
                <h2 style="color: var(--secondary); font-weight: 800; font-size: 1.5rem; margin: 0;">Mega Housing Lottery 2026</h2>
                <p style="color: #64748b; margin: 5px 0 0 0;"><i class="fa-solid fa-circle text-success" style="font-size: 10px;"></i> Applications Open till 31st October 2026</p>
            </div>
            <div style="text-align: right;">
                <span style="display: block; font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Total Properties Listed</span>
                <span style="font-size: 24px; font-weight: 800; color: #16a34a;">10</span>
            </div>
        </div>

        <div class="properties-grid">
            @for($i = 1; $i <= 10; $i++)
                @php
                $types=['HIG Flat', 'MIG Flat' , 'LIG Flat' , 'Independent House' , 'Commercial Plot' ];
                $locations=['Harmu Housing Colony, Ranchi', 'Bariatu Housing Colony, Ranchi' , 'Adityapur, Jamshedpur' , 'Dhanbad Core Area' ];

                $type=$types[array_rand($types)];
                $location=$locations[array_rand($locations)];
                $price=rand(15, 85) . '.' . rand(10, 99) . ' Lakhs' ;
                $area=rand(600, 2500) . ' sq.ft' ;
                @endphp
                <div class="property-card">
                <img src="https://placehold.co/600x400/f1f5f9/94a3b8?text=Property+Image+{{ $i }}" alt="Property" class="property-img">
                <div class="property-body">
                    <span class="property-badge">{{ $type }}</span>
                    <h3 class="property-title">Property No. {{ chr(rand(65, 90)) }}-{{ rand(100, 999) }}/{{ date('Y') }}</h3>
                    <div class="property-location"><i class="fa-solid fa-location-dot"></i> {{ $location }}</div>

                    <div class="property-stats">
                        <div class="stat-item">
                            <span class="stat-label">Base Price</span>
                            <span class="stat-value">₹{{ $price }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Area</span>
                            <span class="stat-value">{{ $area }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">EMD</span>
                            <span class="stat-value">10%</span>
                        </div>
                    </div>

                    <div class="property-actions">
                        <a href="javascript:void(0)" class="btn-brochure" onclick="alert('Downloading Brochure for Property {{ $i }}...')">
                            <i class="fa-solid fa-file-pdf"></i> Brochure
                        </a>
                        <button class="btn-apply" onclick="openApplyModal('Property {{ $i }}', '{{ $type }}', '₹{{ $price }}')">
                            <i class="fa-solid fa-check-to-slot"></i> Apply Now
                        </button>
                    </div>
                </div>
        </div>
        @endfor
    </div>
</div>
</div>

<!-- Apply Modal -->
<div id="applyLotteryModal" class="custom-modal-overlay">
    <div class="custom-modal">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">
                <i class="fa-solid fa-ticket"></i> Apply for E-Lottery
            </h5>
            <button type="button" class="custom-close-btn" onclick="closeApplyModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="custom-modal-body">
            <div class="property-summary-box">
                <strong class="summary-label">SELECTED PROPERTY</strong>
                <div id="modalPropertyTitle" class="summary-title"></div>
                <div class="summary-details">
                    <span id="modalPropertyType" class="summary-type"></span>
                    <span id="modalPropertyPrice" class="summary-price"></span>
                </div>
            </div>

            <form id="elotteryForm" onsubmit="submitLotteryApplication(event)">
                <div class="custom-form-group">
                    <label class="custom-label">Applicant Name <span style="color: #ef4444;">*</span></label>
                    <input type="text" class="custom-input" required placeholder="Enter full name">
                </div>

                <div class="custom-form-row">
                    <div class="custom-form-group half-width">
                        <label class="custom-label">Mobile Number <span style="color: #ef4444;">*</span></label>
                        <input type="text" class="custom-input" required placeholder="10-digit number">
                    </div>
                    <div class="custom-form-group half-width">
                        <label class="custom-label">Aadhar Number <span style="color: #ef4444;">*</span></label>
                        <input type="text" class="custom-input" required placeholder="12-digit Aadhar">
                    </div>
                </div>

                <div class="custom-form-group">
                    <label class="custom-label">Email Address</label>
                    <input type="email" class="custom-input" placeholder="Optional email">
                </div>

                <div class="info-alert">
                    <i class="fa-solid fa-circle-info"></i> By submitting this form, you agree to pay the EMD amount within 7 days of application approval.
                </div>

                <button type="submit" class="custom-submit-btn">
                    Submit Application
                </button>
            </form>

            <div id="successMessage" class="success-message">
                <i class="fa-solid fa-circle-check" style="color: #16a34a; font-size: 48px; margin-bottom: 15px;"></i>
                <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 10px;">Application Submitted!</h4>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 20px;">Your e-Lottery application has been successfully recorded. An SMS with further instructions will be sent shortly.</p>
                <button type="button" class="custom-submit-btn" style="background: #f1f5f9; color: #0f172a;" onclick="closeApplyModal()">Close</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Custom Modal Styles */
    .custom-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        z-index: 9999;
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .custom-modal-overlay.show {
        display: flex;
        opacity: 1;
    }

    .custom-modal {
        background: #fff;
        width: 100%;
        max-width: 500px;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateY(20px);
        transition: transform 0.3s ease;
        overflow: hidden;
    }

    .custom-modal-overlay.show .custom-modal {
        transform: translateY(0);
    }

    .custom-modal-header {
        background: #f0fdf4;
        padding: 16px 24px;
        border-bottom: 1px solid #bbf7d0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .custom-modal-title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: #16a34a;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .custom-close-btn {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #64748b;
        cursor: pointer;
        padding: 4px;
        line-height: 1;
        transition: color 0.2s;
    }

    .custom-close-btn:hover {
        color: #0f172a;
    }

    .custom-modal-body {
        padding: 24px;
    }

    .property-summary-box {
        background: #f8fafc;
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
    }

    .summary-label {
        display: block;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .summary-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .summary-details {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 600;
    }

    .summary-type {
        color: #0284c7;
    }

    .summary-price {
        color: #16a34a;
    }

    /* Form Styles */
    .custom-form-group {
        margin-bottom: 16px;
    }

    .custom-form-row {
        display: flex;
        gap: 16px;
    }

    .half-width {
        flex: 1;
    }

    .custom-label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        color: #475569;
        margin-bottom: 6px;
    }

    .custom-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
        color: #0f172a;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
        font-family: inherit;
    }

    .custom-input:focus {
        outline: none;
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
    }

    .info-alert {
        background: #fffbeb;
        padding: 12px;
        border-radius: 6px;
        border: 1px solid #fde68a;
        font-size: 12px;
        color: #92400e;
        margin-bottom: 20px;
        line-height: 1.5;
        display: flex;
        gap: 8px;
    }

    .custom-submit-btn {
        width: 100%;
        background: #16a34a;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .custom-submit-btn:hover {
        background: #15803d;
    }

    .custom-submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .success-message {
        display: none;
        text-align: center;
        padding: 10px 0;
    }
</style>
@endpush

@push('scripts')
<script>
    const modalOverlay = document.getElementById('applyLotteryModal');

    function openApplyModal(title, type, price) {
        document.getElementById('modalPropertyTitle').innerText = title;
        document.getElementById('modalPropertyType').innerText = type;
        document.getElementById('modalPropertyPrice').innerText = 'Base Price: ' + price;

        document.getElementById('elotteryForm').style.display = 'block';
        document.getElementById('successMessage').style.display = 'none';
        document.getElementById('elotteryForm').reset();

        modalOverlay.classList.add('show');
    }

    function closeApplyModal() {
        modalOverlay.classList.remove('show');
    }

    // Close modal when clicking outside
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) {
            closeApplyModal();
        }
    });

    function submitLotteryApplication(e) {
        e.preventDefault();

        const btn = e.target.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';
        btn.disabled = true;

        // Simulate API call
        setTimeout(() => {
            document.getElementById('elotteryForm').style.display = 'none';
            document.getElementById('successMessage').style.display = 'block';
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1200);
    }
</script>
@endpush
@endsection
