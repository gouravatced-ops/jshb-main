@extends('layouts.public')
@section('title', 'Grievance Portal - ' . config('panel.organization', 'Jharkhand State Housing Board'))

@push('styles')
    
    <style>
        /* Grievance Page Specific Styles */
        body {
            background-color: #f1f5f9;
        }

        /* Top Header Area */
        .grievance-hero {
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

        /* Main Content Layout */
        .content-wrapper {
            padding: 3rem 0;
            display: grid;
            gap: 2rem;
        }

        @media (min-width: 992px) {
            .content-wrapper {
                grid-template-columns: 300px 1fr;
                align-items: start;
            }
        }

        /* Sidebar Boxes */
        .sidebar-box {
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .box-navy {
            background-color: var(--secondary);
            color: white;
        }

        .box-yellow {
            background-color: var(--primary);
            color: var(--secondary-dark);
        }

        .box-light {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            color: var(--text-dark);
        }

        .box-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 800;
            font-size: 1.125rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .box-light .box-header {
            border-bottom-color: #e2e8f0;
        }
        
        .box-yellow .box-header {
            border-bottom-color: rgba(0,0,0,0.1);
        }

        .box-header i {
            font-size: 1.25rem;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-list li {
            position: relative;
            padding-left: 1.25rem;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            line-height: 1.5;
            color: #cbd5e1;
        }

        .info-list li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: white;
            font-weight: bold;
        }

        .contact-list {
            list-style: none;
            padding: 0;
        }

        .contact-list li {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .contact-list i {
            font-size: 1.25rem;
            margin-top: 0.1rem;
        }

        .btn-outline-dark {
            display: block;
            width: 100%;
            text-align: center;
            padding: 0.75rem;
            border: 2px solid var(--secondary);
            color: var(--secondary);
            font-weight: 700;
            border-radius: 0.5rem;
            transition: all 0.2s;
            margin-top: 1rem;
        }

        .btn-outline-dark:hover {
            background-color: var(--secondary);
            color: white;
        }

        /* Form Area */
        .form-card {
            background-color: white;
            border-radius: 0.75rem;
            padding: 2rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .form-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--secondary);
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .form-grid {
            display: grid;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .form-grid.two-cols {
                grid-template-columns: 1fr 1fr;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .form-label .text-danger {
            color: #ef4444;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem;
            font-family: inherit;
            font-size: 0.9rem;
            color: var(--text-dark);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        /* File Upload Styling */
        .file-upload-wrapper {
            display: flex;
            align-items: center;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem;
            overflow: hidden;
            background: white;
        }

        .file-upload-btn {
            background-color: #f1f5f9;
            padding: 0.75rem 1rem;
            border-right: 1px solid #cbd5e1;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .file-upload-text {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: var(--text-muted);
            flex-grow: 1;
        }
        
        .file-hint {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        /* Checkbox */
        .checkbox-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-top: 1rem;
            margin-bottom: 2rem;
        }

        .checkbox-wrapper input[type="checkbox"] {
            margin-top: 0.25rem;
            width: 1rem;
            height: 1rem;
            cursor: pointer;
        }

        .checkbox-wrapper label {
            font-size: 0.875rem;
            color: var(--text-dark);
            cursor: pointer;
        }

        /* Form Actions */
        .form-actions {
            display: grid;
            gap: 1rem;
        }
        
        @media (min-width: 576px) {
            .form-actions {
                grid-template-columns: 1fr 1fr;
            }
        }

        .btn-submit {
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 0.5rem;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background-color: var(--secondary-dark);
        }

        .btn-reset {
            background-color: white;
            color: var(--secondary);
            border: 1px solid var(--secondary);
            padding: 1rem;
            border-radius: 0.5rem;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-reset:hover {
            background-color: #f8fafc;
        }

        /* Alert Box */
        .alert-warning {
            background-color: #fef9c3; /* Light yellow */
            border: 1px solid #fde047;
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }
        
        .alert-warning i {
            color: var(--primary-dark);
            font-size: 1.25rem;
            margin-top: 0.1rem;
        }
        
        .alert-warning p {
            margin: 0;
            font-size: 0.875rem;
            color: #854d0e;
            font-weight: 600;
            line-height: 1.5;
        }

    </style>
@endpush

@section('content')

    <!-- Grievance Hero Header -->
    <section class="grievance-hero">
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
    <section class="container content-wrapper">
        
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
