@extends('layouts.main')

@section('title', 'Request Extension | JSHB')

@section('content')
<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-blue" style="display: flex; justify-content: space-between; align-items: center;">
            <span>
                <i class="fa-solid fa-calendar-plus" style="margin-right: 8px; color: #0d47a1;"></i> Request Timeline Extension 
                <span class="badge" style="background: #0d47a1; color: white; margin-left: 10px;">{{ $application->application_no }}</span>
            </span>
            <div>
                <a href="{{ route('engineer.applications.index') }}" class="btn btn-sm btn-outline-primary" style="font-weight: 600;"><i class="fa-solid fa-arrow-left"></i> Back to Pending List</a>
            </div>
        </div>
        
        <div class="compact-card-body">
            <div style="background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 5px solid #ffeeba; margin-bottom: 25px; color: #856404; display: flex; gap: 15px; align-items: flex-start;">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 20px; color: #f59e0b;"></i>
                <div>
                    <h4 style="margin: 0 0 5px 0; font-size: 15px; font-weight: 600; color: #b45309;">Action Required</h4>
                    <p style="margin: 0; font-size: 14px; line-height: 1.5; color: #b45309;">This application's deadline has passed or is expiring very soon. Submitting an extension request will pause further escalations until an administrator reviews and approves your request.</p>
                </div>
            </div>

            @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 25px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('engineer.extensions.store', \Illuminate\Support\Facades\Crypt::encryptString($application->id)) }}" method="POST">
                @csrf

                <h5 style="font-size: 16px; font-weight: 600; color: #334155; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                    <i class="fa-solid fa-pen-clip" style="color: #64748b; margin-right: 8px;"></i> Justification Details
                </h5>

                <div class="form-group mb-4 summernote-wrapper">
                    <label style="font-weight: 600; font-size: 14px; margin-bottom: 10px; display: block; color: #475569; text-transform: uppercase;">Please explain why you need an extension <span class="text-danger">*</span></label>
                    <textarea id="summernote" name="request_reason" required>{{ old('request_reason') }}</textarea>
                </div>

                <h5 style="font-size: 16px; font-weight: 600; color: #334155; margin-top: 35px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                    <i class="fa-solid fa-shield-halved" style="color: #64748b; margin-right: 8px;"></i> Security Verification
                </h5>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 25px; margin-bottom: 25px;">
                    <x-global-otp-verify purpose="extension_request" buttonText="Send Verification OTP" />
                </div>

                <div style="text-align: right; border-top: 1px solid #e2e8f0; padding-top: 25px;">
                    <input type="hidden" name="otp_verified" id="otp_verified_input" value="0">
                    <button type="submit" id="extensionSubmitBtn" class="btn-primary" disabled data-originally-disabled="false" style="padding: 10px 24px; font-size: 15px; opacity: 0.6; cursor: not-allowed;">
                        <i class="fa-solid fa-paper-plane me-2"></i> Submit Request for Approval
                    </button>
                </div>
                
                <script>
                    document.addEventListener('otpVerified:extension_request', function() {
                        const btn = document.getElementById('extensionSubmitBtn');
                        if (btn && btn.getAttribute('data-originally-disabled') !== 'true') {
                            btn.disabled = false;
                            btn.style.opacity = '1';
                            btn.style.cursor = 'pointer';
                        }
                        const hiddenInput = document.getElementById('otp_verified_input');
                        if(hiddenInput) hiddenInput.value = '1';
                    });
                </script>
            </form>
        </div>
    </div>
</div>

@include('components.partials.summernote-editor')
@endsection
