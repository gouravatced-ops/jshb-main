@extends('layouts.main')

@section('title', 'Forward Application | JSHB')

@section('content')
<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-blue">
            <span><i class="fa-solid fa-arrow-right-long" style="margin-right: 8px;"></i> Forward Application <span style="opacity: 0.7; font-size: 14px; font-weight: 500; margin-left: 5px;">| No: {{ $application->application_no }}</span></span>
            <a href="{{ route('engineer.applications.show', $application) }}" class="btn btn-outline-primary btn-sm" style="background: rgba(255,255,255,0.7); font-weight: 600; color: #0d47a1; border-color: #0d47a1;"><i class="fa-solid fa-arrow-left"></i> Back to Review</a>
        </div>
        <div class="compact-card-body">
        
        <div style="background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 5px solid #ffeeba; margin-bottom: 20px; color: #856404;">
            <i class="fa-solid fa-info-circle"></i> <strong>Note:</strong> The official noting you provide below will be permanently recorded in the file history. This is equivalent to signing and stamping a physical green noting sheet.
        </div>

        <form action="{{ route('engineer.applications.action', $application) }}" method="POST">
            @csrf
            <input type="hidden" name="action_type" value="forward">
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label style="font-weight: 600; font-size: 15px; margin-bottom: 8px; display: block; color: #333;">Forward To <span class="text-danger">*</span></label>
                    @if($nextStep)
                        <div style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 12px 15px;">
                            <span style="font-weight: 600; color: #0d47a1;"><i class="fa-solid fa-arrow-right-to-bracket" style="margin-right: 8px;"></i>{{ $nextStep->step_name }}</span>
                            <div style="margin-top: 4px; font-size: 13px; color: #6c757d;">
                                Role: <strong>{{ $nextStep->role ? $nextStep->role->name : 'Unknown Role' }}</strong>
                            </div>
                            <!-- Hidden input not strictly necessary since controller auto-calculates, but good for completeness -->
                            <input type="hidden" name="to_role_id" value="{{ $nextStep->role_id }}">
                        </div>
                    @else
                        <div class="alert alert-warning" style="margin-bottom: 0; padding: 10px 15px;">
                            <i class="fa-solid fa-triangle-exclamation"></i> No further steps defined in workflow.
                        </div>
                    @endif
                </div>
                <div class="col-md-6 mb-4" style="display: flex; align-items: flex-end; justify-content: flex-end;">
                    <button type="button" onclick="openQrModal()" style="font-weight: 600; font-size: 14px; padding: 10px 20px; border-radius: 8px; border: 2px solid #0d47a1; color: #0d47a1; background: #e3f2fd; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 6px rgba(13, 71, 161, 0.1);">
                        <i class="fa-solid fa-mobile-screen-button" style="margin-right: 8px; font-size: 16px;"></i> Live Image Upload
                    </button>
                </div>
            </div>

            <div class="form-group mb-4 summernote-wrapper">
                <label style="font-weight: 600; font-size: 16px; margin-bottom: 10px; display: block; color: #333;"><i class="fa-solid fa-pen-fancy" style="color: #0d47a1;"></i> Official Noting / Remarks <span class="text-danger">*</span></label>
                <!-- Rich Text Editor via Summernote -->
                <textarea id="summernote" name="remarks" required></textarea>
            </div>
            
            <hr style="margin: 20px 0; border-top: 1px solid #eaeaea;">
            
            <div style="text-align: right;">
                <button type="submit" class="btn btn-success" style="font-size: 15px; padding: 8px 20px;"><i class="fa-solid fa-paper-plane"></i> Submit Noting & Forward</button>
            </div>
        </form>
    </div>
</div>

@include('components.partials.summernote-editor')
@include('components.partials.qr-scanner-modal')
@endsection
