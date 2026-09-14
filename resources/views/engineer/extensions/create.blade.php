@extends('layouts.main')

@section('title', 'Request Extension | JSHB')

@section('content')
<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-blue" style="display: flex; justify-content: space-between; align-items: center;">
            <span><i class="fa-solid fa-clock-rotate-left" style="margin-right: 8px;"></i> Request Extension <span style="opacity: 0.7; font-size: 14px; font-weight: 500; margin-left: 5px;">| App No: {{ $application->application_no }}</span></span>
            <div>
                <a href="{{ route('engineer.applications.index') }}" class="btn btn-outline-primary btn-sm" style="background: rgba(255,255,255,0.7); font-weight: 600; color: #0d47a1; border-color: #0d47a1;"><i class="fa-solid fa-arrow-left"></i> Back to Pending List</a>
            </div>
        </div>
        <div class="compact-card-body">

            <div style="background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 5px solid #ffeeba; margin-bottom: 20px; color: #856404;">
                <i class="fa-solid fa-info-circle"></i> <strong>Note:</strong> This application is currently overdue. Please provide a detailed reason for requesting a timeline extension. This request will be sent to the Admin for approval.
            </div>

            @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('engineer.extensions.store', $application->id) }}" method="POST">
                @csrf

                <div class="form-group mb-4 summernote-wrapper">
                    <label style="font-weight: 600; font-size: 16px; margin-bottom: 10px; display: block; color: #333;"><i class="fa-solid fa-pen-fancy" style="color: #0d47a1;"></i> Reason for Extension <span class="text-danger">*</span></label>

                    <!-- Rich Text Editor via Summernote -->
                    <textarea id="summernote" name="request_reason" required></textarea>
                </div>

                <hr style="margin: 20px 0; border-top: 1px solid #eaeaea;">

                <x-global-otp-verify purpose="extension_request" buttonText="Send OTP to Request Extension" />

                <div style="text-align: right; margin-top: 20px;">
                    <input type="hidden" name="otp_verified" id="otp_verified_input" value="0">
                    <button type="submit" id="extensionSubmitBtn" class="btn btn-success" style="font-size: 15px; padding: 8px 20px; opacity: 0.6; cursor: not-allowed;" disabled data-originally-disabled="false">
                        <i class="fa-solid fa-paper-plane me-1"></i> Submit Extension Request
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

    @include('components.partials.summernote-editor')
</div>
@endsection
