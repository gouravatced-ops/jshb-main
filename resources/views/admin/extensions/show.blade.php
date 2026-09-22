@extends('layouts.main')

@section('title', 'Review Extension Request | Admin')

@section('content')
<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-blue" style="display: flex; justify-content: space-between; align-items: center;">
            <span>
                <i class="fa-solid fa-eye" style="margin-right: 8px; color: #0d47a1;"></i> Review Extension Request 
                <span class="badge" style="background: #0d47a1; color: white; margin-left: 10px;">{{ $extension->application->application_no ?? 'N/A' }}</span>
            </span>
            <div>
                <a href="{{ route('admin.extensions.index') }}" class="btn btn-sm btn-outline-primary" style="font-weight: 600;"><i class="fa-solid fa-arrow-left"></i> Back to Pending List</a>
            </div>
        </div>
        
        <div class="compact-card-body">
            @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 25px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Request Details -->
            <h5 style="font-size: 16px; font-weight: 600; color: #334155; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                <i class="fa-solid fa-circle-info" style="color: #64748b; margin-right: 8px;"></i> Request Information
            </h5>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Requested By (Engineer)</div>
                    <div style="font-size: 15px; color: #1e293b; font-weight: 600; margin-top: 4px;">{{ $extension->requestedBy->name ?? 'Unknown' }}</div>
                </div>
                <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Submitted At</div>
                    <div style="font-size: 15px; color: #1e293b; font-weight: 600; margin-top: 4px;">{{ $extension->created_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>

            <div style="background: #f1f5f9; padding: 20px; border-radius: 6px; border-left: 4px solid #3b82f6; margin-bottom: 35px;">
                <div style="font-size: 13px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 10px;">Engineer's Justification</div>
                <div style="font-size: 15px; color: #334155; line-height: 1.6; background: white; padding: 15px; border-radius: 4px; border: 1px solid #e2e8f0;">
                    {!! $extension->request_reason !!}
                </div>
            </div>

            @if($extension->status === 'pending')
            <!-- Action Form -->
            <form action="{{ route('admin.extensions.process', \Illuminate\Support\Facades\Crypt::encryptString($extension->id)) }}" method="POST">
                @csrf

                <h5 style="font-size: 16px; font-weight: 600; color: #334155; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                    <i class="fa-solid fa-gavel" style="color: #64748b; margin-right: 8px;"></i> Admin Action
                </h5>

                <div style="display: flex; gap: 30px; margin-bottom: 25px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="action" value="approve" required onchange="toggleActionFields()" style="width: 18px; height: 18px; accent-color: #10b981;">
                        <span style="font-size: 15px; font-weight: 600; color: #10b981;">Approve Request</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="action" value="reject" required onchange="toggleActionFields()" style="width: 18px; height: 18px; accent-color: #ef4444;">
                        <span style="font-size: 15px; font-weight: 600; color: #ef4444;">Reject Request</span>
                    </label>
                </div>

                <div id="approveFields" style="display: none; margin-bottom: 25px;">
                    <label style="font-weight: 600; font-size: 14px; margin-bottom: 8px; display: block; color: #475569;">Days to Extend <span class="text-danger">*</span></label>
                    <select name="extension_days" id="extension_days" class="form-select" style="max-width: 300px; padding: 10px; border-radius: 6px;">
                        <option value="">Select number of days...</option>
                        @for($i=1; $i<=6; $i++)
                            <option value="{{ $i }}" {{ old('extension_days') == $i ? 'selected' : '' }}>{{ $i }} Day{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group mb-4 summernote-wrapper">
                    <label style="font-weight: 600; font-size: 14px; margin-bottom: 10px; display: block; color: #475569;">Admin Remarks <span class="text-danger">*</span></label>
                    <textarea id="summernote" name="remarks" required>{{ old('remarks') }}</textarea>
                </div>

                <h5 style="font-size: 16px; font-weight: 600; color: #334155; margin-top: 35px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                    <i class="fa-solid fa-shield-halved" style="color: #64748b; margin-right: 8px;"></i> Security Verification
                </h5>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 25px; margin-bottom: 25px;">
                    <x-global-otp-verify purpose="extension_action" buttonText="Send Verification OTP" />
                </div>

                <div style="text-align: right; border-top: 1px solid #e2e8f0; padding-top: 25px;">
                    <input type="hidden" name="otp_verified" id="otp_verified_input" value="0">
                    <button type="submit" id="extensionSubmitBtn" class="btn-primary" disabled data-originally-disabled="false" style="padding: 10px 24px; font-size: 15px; opacity: 0.6; cursor: not-allowed;">
                        <i class="fa-solid fa-paper-plane me-2"></i> Submit Decision
                    </button>
                </div>
                
                <script>
                    function toggleActionFields() {
                        const action = document.querySelector('input[name="action"]:checked').value;
                        const approveFields = document.getElementById('approveFields');
                        const extensionDays = document.getElementById('extension_days');
                        
                        if (action === 'approve') {
                            approveFields.style.display = 'block';
                            extensionDays.required = true;
                        } else {
                            approveFields.style.display = 'none';
                            extensionDays.required = false;
                        }
                    }

                    document.addEventListener('otpVerified:extension_action', function() {
                        const btn = document.getElementById('extensionSubmitBtn');
                        if (btn && btn.getAttribute('data-originally-disabled') !== 'true') {
                            btn.disabled = false;
                            btn.style.opacity = '1';
                            btn.style.cursor = 'pointer';
                        }
                        const hiddenInput = document.getElementById('otp_verified_input');
                        if(hiddenInput) hiddenInput.value = '1';
                    });

                    // Trigger on load if there's old input
                    document.addEventListener('DOMContentLoaded', function() {
                        if(document.querySelector('input[name="action"]:checked')) {
                            toggleActionFields();
                        }
                    });
                </script>
            </form>
            @else
            <!-- View Mode (Processed) -->
            <h5 style="font-size: 16px; font-weight: 600; color: #334155; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                <i class="fa-solid fa-gavel" style="color: #64748b; margin-right: 8px;"></i> Admin Decision
            </h5>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Status</div>
                    <div style="font-size: 15px; font-weight: 600; margin-top: 4px;">
                        @if($extension->status === 'approved')
                            <span style="color: #10b981;"><i class="fa-solid fa-circle-check"></i> Approved</span>
                        @elseif($extension->status === 'rejected')
                            <span style="color: #ef4444;"><i class="fa-solid fa-circle-xmark"></i> Rejected</span>
                        @endif
                    </div>
                </div>
                <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Action Date</div>
                    <div style="font-size: 15px; color: #1e293b; font-weight: 600; margin-top: 4px;">
                        {{ $extension->approved_at ? $extension->approved_at->format('d M Y, h:i A') : $extension->updated_at->format('d M Y, h:i A') }}
                    </div>
                </div>
            </div>

            @if($extension->status === 'approved' && $extension->extension_days)
            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <span style="color: #065f46; font-weight: 600;"><i class="fa-solid fa-calendar-plus" style="margin-right: 8px;"></i> Extended By: {{ $extension->extension_days }} Day(s)</span>
            </div>
            @endif

            <div style="background: #f8fafc; padding: 20px; border-radius: 6px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                <div style="font-size: 13px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 10px;">Admin Remarks</div>
                <div style="font-size: 15px; color: #334155; line-height: 1.6;">
                    {!! $extension->remarks !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@include('components.partials.summernote-editor')
@endsection
