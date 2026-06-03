{{-- resources/views/admin/scheme/create.blade.php --}}
@extends('layouts.main')

@section('title', 'Create New Scheme | JSHB')

@section('content')
<div class="form-container">
    <div class="form-wrapper">
        <div class="form-main">
            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-header">
                <div>
                    <h4>Create New Scheme</h4>
                    <p>Fill in the details below to create a new scheme</p>
                </div>
                <a href="#" class="btn-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Back
                </a>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data" id="schemeForm">
                @csrf

                <div class="form-container">
                    <!-- Basic Information Section -->
                    <div class="form-section">
                        <h5 class="section-title">Basic Information</h5>
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label>Scheme Name <span class="required">*</span></label>
                                <input type="text" name="scheme_name" class="form-control" value="{{ old('scheme_name') }}" placeholder="Enter scheme name" required>
                            </div>
                            <div class="form-group full-width">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Enter scheme description">{{ old('description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Scheme Code</label>
                                <input type="text" name="scheme_code" class="form-control" value="{{ old('scheme_code') }}" placeholder="Unique scheme code">
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="agriculture" {{ old('category') == 'agriculture' ? 'selected' : '' }}>Agriculture</option>
                                    <option value="education" {{ old('category') == 'education' ? 'selected' : '' }}>Education</option>
                                    <option value="health" {{ old('category') == 'health' ? 'selected' : '' }}>Health</option>
                                    <option value="business" {{ old('category') == 'business' ? 'selected' : '' }}>Business</option>
                                    <option value="welfare" {{ old('category') == 'welfare' ? 'selected' : '' }}>Social Welfare</option>
                                    <option value="infrastructure" {{ old('category') == 'infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Scheme Details Section -->
                    <div class="form-section">
                        <h5 class="section-title">Scheme Details</h5>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                            </div>
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                            </div>
                            <div class="form-group">
                                <label>Budget Amount</label>
                                <input type="number" name="budget_amount" class="form-control" value="{{ old('budget_amount') }}" placeholder="0.00" step="0.01">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Location Section -->
                    <div class="form-section">
                        <h5 class="section-title">Location Details</h5>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>State</label>
                                <input type="text" name="state" class="form-control" value="{{ old('state') }}" placeholder="State">
                            </div>
                            <div class="form-group">
                                <label>District</label>
                                <input type="text" name="district" class="form-control" value="{{ old('district') }}" placeholder="District">
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city') }}" placeholder="City">
                            </div>
                            <div class="form-group">
                                <label>Country</label>
                                <select name="country" class="form-control">
                                    <option value="India" {{ old('country', 'India') == 'India' ? 'selected' : '' }}>India</option>
                                    <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>United States</option>
                                    <option value="United Kingdom" {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                    <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                                    <option value="Germany" {{ old('country') == 'Germany' ? 'selected' : '' }}>Germany</option>
                                    <option value="France" {{ old('country') == 'France' ? 'selected' : '' }}>France</option>
                                    <option value="Japan" {{ old('country') == 'Japan' ? 'selected' : '' }}>Japan</option>
                                    <option value="China" {{ old('country') == 'China' ? 'selected' : '' }}>China</option>
                                    <option value="Brazil" {{ old('country') == 'Brazil' ? 'selected' : '' }}>Brazil</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>PIN Code</label>
                                <input type="text" name="pin_code" class="form-control" value="{{ old('pin_code') }}" placeholder="Postal / PIN code">
                            </div>
                        </div>
                    </div>

                    <!-- Eligibility Section -->
                    <div class="form-section">
                        <h5 class="section-title">Eligibility Criteria</h5>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Minimum Age</label>
                                <input type="number" name="min_age" class="form-control" value="{{ old('min_age') }}" placeholder="18">
                            </div>
                            <div class="form-group">
                                <label>Maximum Age</label>
                                <input type="number" name="max_age" class="form-control" value="{{ old('max_age') }}" placeholder="60">
                            </div>
                            <div class="form-group">
                                <label>Income Limit (Annual)</label>
                                <input type="number" name="income_limit" class="form-control" value="{{ old('income_limit') }}" placeholder="0.00" step="0.01">
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="all" {{ old('gender') == 'all' ? 'selected' : '' }}>All</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label>Additional Eligibility</label>
                                <textarea name="eligibility_criteria" class="form-control" rows="3" placeholder="List any additional eligibility requirements...">{{ old('eligibility_criteria') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="form-section">
                        <h5 class="section-title">Required Documents</h5>
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label>Documents Required</label>
                                <textarea name="documents_required" class="form-control" rows="3" placeholder="List all required documents...">{{ old('documents_required') }}</textarea>
                            </div>
                            <div class="form-group full-width">
                                <label>Scheme Document/PDF</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="scheme_document" id="schemeDocument" class="file-input" accept=".pdf,.doc,.docx">
                                    <label for="schemeDocument" class="file-label" style="color:white;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                        Upload Document
                                    </label>
                                    <span class="file-name" id="documentFileName">No file chosen</span>
                                </div>
                                <small>Allowed: PDF, DOC, DOCX. Max size 5MB.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="form-section">
                        <h5 class="section-title">Contact Information</h5>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Contact Person</label>
                                <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}" placeholder="Contact person name">
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="tel" name="contact_number" class="form-control" value="{{ old('contact_number') }}" placeholder="Phone number">
                            </div>
                            <div class="form-group">
                                <label>Contact Email</label>
                                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email') }}" placeholder="Email address">
                            </div>
                            <div class="form-group">
                                <label>Website URL</label>
                                <input type="url" name="website_url" class="form-control" value="{{ old('website_url') }}" placeholder="https://example.com">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-footer">
                    <button type="reset" class="btn-reset" id="resetBtn">Reset Form</button>
                    <button type="submit" class="btn-submit">Create Scheme</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // File name display for document upload
        const documentInput = document.getElementById('schemeDocument');
        const documentFileName = document.getElementById('documentFileName');
        
        if (documentInput) {
            documentInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    documentFileName.textContent = file.name;
                } else {
                    documentFileName.textContent = 'No file chosen';
                }
            });
        }

        // Reset button functionality
        const resetBtn = document.getElementById('resetBtn');
        const schemeForm = document.getElementById('schemeForm');
        
        if (resetBtn && schemeForm) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Reset all form fields? All unsaved data will be lost.')) {
                    schemeForm.reset();
                    documentFileName.textContent = 'No file chosen';
                }
            });
        }

        // Form submit indicator
        const submitBtn = document.querySelector('.btn-submit');
        if (submitBtn && schemeForm) {
            schemeForm.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Creating...';
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Create Scheme';
                }, 3000);
            });
        }
    });
</script>
@endsection