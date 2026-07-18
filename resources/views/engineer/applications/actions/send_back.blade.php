@extends('layouts.main')

@section('title', 'Send Back Application | JSHB')

@section('content')
@include('components.partials.compact-css')
<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-yellow">
            <span><i class="fa-solid fa-reply" style="margin-right: 8px;"></i> Send Back Application <span style="opacity: 0.7; font-size: 14px; font-weight: 500; margin-left: 5px;">| No: {{ $application->application_no }}</span></span>
            <a href="{{ route('engineer.applications.show', $application) }}" class="btn btn-outline-warning btn-sm" style="background: rgba(255,255,255,0.7); font-weight: 600; color: #856404; border-color: #856404;"><i class="fa-solid fa-arrow-left"></i> Back to Review</a>
        </div>
        <div class="compact-card-body">
        
        <div style="background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 5px solid #ffeeba; margin-bottom: 20px; color: #856404;">
            <i class="fa-solid fa-info-circle"></i> <strong>Note:</strong> Provide detailed objections or reasons for sending back this application. Your noting is digitally recorded as part of the official file.
        </div>

        <form action="{{ route('engineer.applications.action', $application) }}" method="POST">
            @csrf
            <input type="hidden" name="action_type" value="send_back">
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label style="font-weight: 600; font-size: 15px; margin-bottom: 8px; display: block; color: #333;">Send Back To <span class="text-danger">*</span></label>
                    @if($nextStep)
                        <div style="background: #fff8e1; border: 1px solid #ffecb3; border-radius: 6px; padding: 12px 15px;">
                            <span style="font-weight: 600; color: #856404;"><i class="fa-solid fa-arrow-left" style="margin-right: 8px;"></i>{{ $nextStep->step_name }}</span>
                            <div style="margin-top: 4px; font-size: 13px; color: #856404; opacity: 0.8;">
                                Role: <strong>{{ $nextStep->role ? $nextStep->role->name : 'Unknown Role' }}</strong>
                            </div>
                            <input type="hidden" name="to_role_id" value="{{ $nextStep->role_id }}">
                        </div>
                    @else
                        <div class="alert alert-danger" style="margin-bottom: 0; padding: 10px 15px;">
                            <i class="fa-solid fa-triangle-exclamation"></i> Cannot send back. No previous steps defined in workflow.
                        </div>
                    @endif
                </div>
                <div class="col-md-6 mb-4" style="display: flex; align-items: flex-end; justify-content: flex-end;">
                    <button type="button" onclick="openQrModal()" style="font-weight: 600; font-size: 14px; padding: 10px 20px; border-radius: 8px; border: 2px solid #856404; color: #856404; background: #fff3cd; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 6px rgba(133, 100, 4, 0.1);">
                        <i class="fa-solid fa-mobile-screen-button" style="margin-right: 8px; font-size: 16px;"></i> Live Image Upload
                    </button>
                </div>
            </div>
            
            <div class="form-group mb-4 summernote-wrapper">
                <label style="font-weight: 600; font-size: 16px; margin-bottom: 10px; display: block; color: #333;"><i class="fa-solid fa-pen-fancy" style="color: #856404;"></i> Objection Noting / Remarks <span class="text-danger">*</span></label>
                <textarea id="summernote" name="remarks" required></textarea>
            </div>
            
            <hr style="margin: 20px 0; border-top: 1px solid #eaeaea;">
            
            <div style="text-align: right;">
                <button type="submit" class="btn btn-warning text-dark" style="font-size: 15px; padding: 8px 20px; font-weight: 600;"><i class="fa-solid fa-reply"></i> Submit Noting & Send Back</button>
            </div>
        </form>
    </div>
</div>

@include('components.partials.summernote-editor')
@include('components.partials.qr-scanner-modal')
@endsection
