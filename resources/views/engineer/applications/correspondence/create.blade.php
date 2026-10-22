@extends('layouts.main')

@section('title', 'Generate Office Correspondence | JSHB')

@section('content')
<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-green" style="display: flex; justify-content: space-between; align-items: center; background: #e8f5e9; color: #2e7d32; border-top: 3px solid #4caf50;">
            <span><i class="fa-solid fa-envelope-open-text" style="margin-right: 8px;"></i> Generate Office Correspondence <span style="opacity: 0.7; font-size: 14px; font-weight: 500; margin-left: 5px;">| No: {{ $application->application_no }}</span></span>
            <div>
                <button type="button" onclick="openQrModal()" style="font-weight: 600; font-size: 13px; padding: 6px 12px; border-radius: 6px; border: none; color: #0d47a1; background: #e3f2fd; cursor: pointer; margin-right: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><i class="fa-solid fa-mobile-screen-button" style="margin-right: 5px;"></i> Live Image Upload</button>
                <a href="{{ route('engineer.applications.show', $application) }}" class="btn btn-outline-success btn-sm" style="background: rgba(255,255,255,0.7); font-weight: 600; color: #2e7d32; border-color: #2e7d32;"><i class="fa-solid fa-arrow-left"></i> Back to Review</a>
            </div>
        </div>

        <div class="compact-card-body">
            @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('engineer.applications.correspondence.store', $application) }}" method="POST">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 600; font-size: 14px; color: #333;">Correspondence Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-control" style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 10px;" required>
                            <option value="LT">Letter (LT) - Sent to applicant or other party</option>
                            <option value="OO">Office Order (OO) - Official internal order</option>
                            <option value="OD">Office Draft (OD) - Draft note sheet</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 600; font-size: 14px; color: #333;">Application Number</label>
                        <input type="text" class="form-control" value="{{ $application->application_no }}" readonly style="background-color: #f8fafc; border: 2px solid #e2e8f0; border-radius: 8px; padding: 10px; font-weight: 600; color: #475569;">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label" style="font-weight: 600; font-size: 14px; color: #333;">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control" style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 10px;" placeholder="Enter the subject of this correspondence..." required maxlength="255">
                </div>

                <div class="form-group mb-4 summernote-wrapper">
                    <label style="font-weight: 600; font-size: 16px; margin-bottom: 10px; display: block; color: #333;"><i class="fa-solid fa-pen-fancy" style="color: #2e7d32;"></i> Content <span class="text-danger">*</span></label>

                    <!-- Font Family Selection -->
                    <div class="mb-3 p-2" style="background: #f8f9fa; border-radius: 6px; border: 1px solid #e9ecef;">
                        <label class="me-3" style="font-weight: 600; font-size: 14px; color: #495057;">Typing Language:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input font-family-selector" type="radio" name="font_family" id="font_english_note" value="english">
                            <label class="form-check-label" for="font_english_note" style="margin:0px;">English (Arial)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input font-family-selector" type="radio" name="font_family" id="font_hindi_note" value="krutidev">
                            <label class="form-check-label" for="font_hindi_note" style="margin:0px;">Hindi (Kruti Dev)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input font-family-selector" type="radio" name="font_family" id="font_normal_hindi_note" value="normalhindi" checked>
                            <label class="form-check-label" for="font_normal_hindi_note" style="margin:0px;">Normal Hindi</label>
                        </div>
                    </div>

                    <!-- Rich Text Editor via Summernote -->
                    <textarea id="summernote" name="content" required></textarea>
                </div>

                <!-- Structured Publishing & Verification Card -->
                <div class="card border-0 shadow-sm mt-4 mb-3" style="border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; background: #ffffff;">
                    <!-- Notice Header -->
                    <div style="background: #fffbeb; border-bottom: 1px solid #fef3c7; padding: 12px 20px; display: flex; align-items: center; gap: 10px; color: #92400e; font-size: 13px;">
                        <i class="fa-solid fa-triangle-exclamation" style="font-size: 16px; color: #d97706; flex-shrink: 0;"></i>
                        <div>
                            <strong>Important Notice:</strong> Once you click <strong>"Publish Officially"</strong>, the correspondence becomes an official record and cannot be edited. Choosing <strong>"Save as Draft"</strong> allows you to revise it later.
                        </div>
                    </div>

                    <!-- Authorization & OTP Row -->
                    <div style="padding: 20px 24px; background: #f8fafc;">
                        <div class="row align-items-center">
                            <div class="col-lg-7 col-md-6 mb-3 mb-md-0">
                                <div class="d-flex align-items-start gap-3">
                                    <div style="width: 44px; height: 44px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </div>
                                    <div>
                                        <h6 style="font-weight: 700; color: #1e293b; margin-bottom: 4px; font-size: 15px;">Publishing Authorization (OTP Verification)</h6>
                                        <p style="color: #64748b; font-size: 13px; line-height: 1.5; margin-bottom: 0;">
                                            To ensure security and authenticity, an email OTP is required to officially publish this correspondence.
                                            <br>
                                            <span style="display: inline-block; margin-top: 4px; color: #059669; font-weight: 500;">
                                                <i class="fa-solid fa-circle-check me-1"></i> Saving as Draft does not require OTP verification.
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-6">
                                <style>
                                    .global-otp-wrapper {
                                        margin-bottom: 0 !important;
                                        background-color: #ffffff !important;
                                        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                                    }
                                </style>
                                <x-global-otp-verify purpose="publish_correspondence" buttonText="Send OTP to Publish" />
                            </div>
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div style="background: #ffffff; border-top: 1px solid #e2e8f0; padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div>
                            <a href="{{ route('engineer.applications.show', $application) }}" class="btn btn-outline-secondary" style="font-size: 14px; font-weight: 600; padding: 8px 18px; border-radius: 8px;">
                                <i class="fa-solid fa-arrow-left me-1"></i> Back to Review
                            </a>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <input type="hidden" name="otp_verified" id="otp_verified_input" value="0">
                            <button type="submit" name="status" value="draft" class="btn btn-secondary" style="font-size: 14px; font-weight: 600; padding: 9px 22px; border-radius: 8px; background-color: #64748b; border-color: #64748b;">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save as Draft
                            </button>
                            <button type="submit" id="publishSubmitBtn" name="status" value="published" class="btn btn-success" style="font-size: 14px; font-weight: 600; padding: 9px 24px; border-radius: 8px; background-color: #16a34a; border-color: #16a34a; opacity: 0.6; cursor: not-allowed; transition: all 0.2s;" disabled onclick="return confirm('Are you sure you want to Publish? No changes can be made after publishing.')">
                                <i class="fa-solid fa-paper-plane me-1"></i> Publish Officially
                            </button>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('otpVerified:publish_correspondence', function() {
                        const btn = document.getElementById('publishSubmitBtn');
                        if (btn) {
                            btn.disabled = false;
                            btn.style.opacity = '1';
                            btn.style.cursor = 'pointer';
                            btn.style.boxShadow = '0 4px 6px -1px rgba(16, 185, 129, 0.3)';
                        }
                        const hiddenInput = document.getElementById('otp_verified_input');
                        if (hiddenInput) {
                            hiddenInput.value = '1';
                        }
                    });
                </script>
            </form>
        </div>
    </div>

    @include('components.partials.summernote-editor')
    @include('components.partials.qr-scanner-modal')
</div>
@endsection
