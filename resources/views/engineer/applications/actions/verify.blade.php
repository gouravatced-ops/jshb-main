@extends('layouts.main')

@section('title', 'Verify Application | JSHB')

@section('content')
<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-cyan">
            <span><i class="fa-solid fa-check-double" style="margin-right: 8px;"></i> Verify Application <span style="opacity: 0.7; font-size: 14px; font-weight: 500; margin-left: 5px;">| No: {{ $application->application_no }}</span></span>
            <a href="{{ route('engineer.applications.show', $application) }}" class="btn btn-outline-info btn-sm" style="background: rgba(255,255,255,0.7); font-weight: 600; color: #0c5460; border-color: #0c5460;"><i class="fa-solid fa-arrow-left"></i> Back to Review</a>
        </div>
        <div class="compact-card-body">
        
        <div style="background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 5px solid #ffeeba; margin-bottom: 20px; color: #856404;">
            <i class="fa-solid fa-info-circle"></i> <strong>Note:</strong> Record your verification findings below. This noting will be attached to the application workflow.
        </div>

        <form action="{{ route('engineer.applications.action', $application) }}" method="POST">
            @csrf
            <input type="hidden" name="action_type" value="verify">
            
            <div class="form-group mb-4 summernote-wrapper">
                <label style="font-weight: 600; font-size: 16px; margin-bottom: 10px; display: block; color: #333;"><i class="fa-solid fa-pen-fancy" style="color: #0c5460;"></i> Verification Noting / Remarks <span class="text-danger">*</span></label>
                <textarea id="summernote" name="remarks" required></textarea>
            </div>
            
            <hr style="margin: 20px 0; border-top: 1px solid #eaeaea;">
            
            <div style="text-align: right;">
                <button type="submit" class="btn btn-info text-white" style="font-size: 15px; padding: 8px 20px; font-weight: 600;"><i class="fa-solid fa-check"></i> Submit Noting & Verify</button>
            </div>
        </form>
    </div>
</div>

@include('components.partials.summernote-editor')
@endsection
