@extends('layouts.public')
@section('title', 'Grievance Portal - ' . config('panel.organization', 'Jharkhand State Housing Board'))

@push('styles')
<style>
    /* Grievance-only: form card alias */
    .form-card { background-color: white; border-radius: 0.75rem; padding: 2rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
    /* Main content grid with sidebar */
    .grievance-layout { padding: 3rem 0; display: grid; gap: 2rem; background-color: #f1f5f9; }
    @media (min-width: 992px) { .grievance-layout { grid-template-columns: 300px 1fr; align-items: start; } }
</style>
@endpush

@section('content')

<!-- Grievance Hero Header -->
<section class="page-hero">
    <div class="container hero-inner">
        <!-- Left Info -->
        <div class="hero-left">
            <div class="hero-icon-circle">
                <i class="fa-solid fa-file-contract"></i>
            </div>
            <div class="hero-titles">
                <h1>DIGITAL GRIEVANCE</h1>
                <h2>Your Grievance, Our Commitment</h2>
                <p>Register your grievance online and track the status<br>in real time.</p>
            </div>
        </div>

        <!-- Right Features -->
        <div class="hero-features">
            <div class="hero-feature-item">
                <i class="fa-solid fa-file-signature feature-icon"></i>
                <div class="feature-text">
                    <h4>Easy & Quick Registration</h4>
                    <p>Submit your grievance in few simple steps</p>
                </div>
            </div>
            <div class="hero-feature-item">
                <i class="fa-solid fa-magnifying-glass feature-icon"></i>
                <div class="feature-text">
                    <h4>Track Your Grievance</h4>
                    <p>Get updates on the status in real time</p>
                </div>
            </div>
            <div class="hero-feature-item">
                <i class="fa-solid fa-handshake feature-icon"></i>
                <div class="feature-text">
                    <h4>Transparent & Timely Resolution</h4>
                    <p>We are committed to resolve your issues</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content Area -->
<section style="background-color:#f1f5f9; padding: 3rem 0;">
<div class="container grievance-layout">

    <!-- Left Sidebar -->
    <aside>
        <!-- Important Info -->
        <div class="sidebar-box box-navy">
            <div class="box-header">
                <i class="fa-solid fa-circle-info" style="color: var(--primary);"></i>
                IMPORTANT INFORMATION
            </div>
            <ul class="info-list">
                <li>Provide accurate details of your grievance.</li>
                <li>Attach relevant documents (if any).</li>
                <li>Keep your registration details safe for tracking.</li>
                <li>You will receive updates on your registered mobile number / email.</li>
            </ul>
        </div>

        <!-- Contact Us -->
        <div class="sidebar-box box-yellow">
            <div class="box-header">
                <i class="fa-solid fa-headset"></i>
                CONTACT US
            </div>
            <ul class="contact-list">
                <li>
                    <i class="fa-solid fa-phone"></i>
                    <div>
                        1800-123-0000<br>
                        <span style="font-weight: 500; font-size: 0.75rem;">(Toll Free)</span>
                    </div>
                </li>
                <li>
                    <i class="fa-solid fa-envelope"></i>
                    <div>grievance@jshb.in</div>
                </li>
                <li>
                    <i class="fa-solid fa-clock"></i>
                    <div>
                        10:00 AM - 5:00 PM<br>
                        <span style="font-weight: 500; font-size: 0.75rem;">(Monday to Friday)</span>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Track Grievance -->
        <div class="sidebar-box box-light">
            <div class="box-header">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--secondary);"></i>
                TRACK GRIEVANCE
            </div>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">
                Already registered your grievance?<br>
                Track the status using your Registration Number.
            </p>
            <a href="#" class="btn-outline-dark">Track Grievance</a>
        </div>
    </aside>

    <!-- Right Form Area -->
    <main>
        <div class="form-card">
            <div class="form-header">
                <i class="fa-solid fa-pen-to-square"></i>
                REGISTER GRIEVANCE
            </div>

            <form action="#" method="POST" enctype="multipart/form-data">
                <div class="form-grid two-cols">
                    <div class="form-group">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter your full name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter 10 digit mobile number">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email ID</label>
                        <input type="email" class="form-control" placeholder="Enter your email address">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter your complete address">
                    </div>

                    <div class="form-group">
                        <label class="form-label">District <span class="text-danger">*</span></label>
                        <select class="form-control">
                            <option>-- Select District --</option>
                            <option>Ranchi</option>
                            <option>Jamshedpur</option>
                            <option>Dhanbad</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Division / Circle <span class="text-danger">*</span></label>
                        <select class="form-control">
                            <option>-- Select Division / Circle --</option>
                            <option>Ranchi Circle</option>
                            <option>Santhal Pargana</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Project / Scheme <span class="text-muted" style="font-weight: 500;">(If applicable)</span></label>
                        <select class="form-control">
                            <option>-- Select Project / Scheme --</option>
                            <option>Harmu Housing</option>
                            <option>Argora Housing</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Flat / Plot No. <span class="text-muted" style="font-weight: 500;">(If applicable)</span></label>
                        <input type="text" class="form-control" placeholder="Enter Flat / Plot No.">
                    </div>
                </div>

                <div class="form-grid" style="margin-top: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Grievance Category <span class="text-danger">*</span></label>
                        <select class="form-control">
                            <option>-- Select Category --</option>
                            <option>Allotment Issue</option>
                            <option>Maintenance & Repair</option>
                            <option>Payment / Dues</option>
                            <option>Encroachment</option>
                            <option>Others</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Grievance Subject <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter brief subject of your grievance">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Grievance Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" placeholder="Describe your grievance in detail"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Upload Documents <span class="text-muted" style="font-weight: 500;">(If any)</span></label>
                        <!-- Custom File Input -->
                        <div style="position: relative;">
                            <input type="file" id="documentUpload" style="position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer; z-index: 2;">
                            <div class="file-upload-wrapper">
                                <div class="file-upload-btn">
                                    <i class="fa-solid fa-upload"></i> Choose File
                                </div>
                                <div class="file-upload-text">No file chosen</div>
                            </div>
                        </div>
                        <div class="file-hint">Allowed file types: PDF, JPG, PNG (Max size: 2MB)</div>
                    </div>
                </div>

                <div class="checkbox-wrapper">
                    <input type="checkbox" id="declaration">
                    <label for="declaration">I hereby declare that the above information is true to the best of my knowledge.</label>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-submit">
                        Submit Grievance <i class="fa-solid fa-paper-plane" style="font-size: 0.9em; margin-left: 0.25rem;"></i>
                    </button>
                    <button type="reset" class="btn-reset">
                        Reset
                    </button>
                </div>

                <div class="alert-warning">
                    <i class="fa-solid fa-shield-halved"></i>
                    <p><strong>Note:</strong> Please ensure your grievance does not contain any abusive or defamatory content. Grievances with inappropriate content may be rejected.</p>
                </div>
            </form>
        </div>
    </main>

</div>
</section>

@endsection

@push('scripts')
<script>
    // Simple script to update the file input text
    document.getElementById('documentUpload').addEventListener('change', function(e) {
        const fileName = e.target.files.length > 0 ? e.target.files[0].name : 'No file chosen';
        document.querySelector('.file-upload-text').textContent = fileName;
    });
</script>
@endpush
