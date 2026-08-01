@extends('layouts.main')

@section('title', 'Add Note to Application | JSHB')

@section('content')
@include('components.partials.compact-css')
<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-blue">
            <span><i class="fa-solid fa-comment-dots" style="margin-right: 8px;"></i> Add Note to Application <span style="opacity: 0.7; font-size: 14px; font-weight: 500; margin-left: 5px;">| No: {{ $application->application_no }}</span></span>
            <a href="{{ route('operator.applications.show', $application) }}" class="btn btn-outline-primary btn-sm" style="background: rgba(255,255,255,0.7); font-weight: 600; color: #0d47a1; border-color: #0d47a1;"><i class="fa-solid fa-arrow-left"></i> Back to Review</a>
        </div>
        <div class="compact-card-body">

            <div style="background: #e3f2fd; padding: 15px; border-radius: 6px; border-left: 5px solid #0d47a1; margin-bottom: 20px; color: #0d47a1;">
                <i class="fa-solid fa-info-circle"></i> <strong>Note:</strong> The note you provide below will be permanently recorded in the file history but will not change the current workflow step.
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

            <form action="{{ route('operator.applications.action', $application) }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" value="add_note">

                <div class="row">
                    <div class="col-md-12 mb-4" style="display: flex; align-items: flex-end; justify-content: flex-end;">
                        <button type="button" onclick="openQrModal()" style="font-weight: 600; font-size: 14px; padding: 10px 20px; border-radius: 8px; border: 2px solid #0d47a1; color: #0d47a1; background: #e3f2fd; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 6px rgba(13, 71, 161, 0.1);">
                            <i class="fa-solid fa-mobile-screen-button" style="margin-right: 8px; font-size: 16px;"></i> Live Image Upload
                        </button>
                    </div>
                </div>

                <div class="form-group mb-4 summernote-wrapper">
                    <label style="font-weight: 600; font-size: 16px; margin-bottom: 10px; display: block; color: #333;"><i class="fa-solid fa-pen-fancy" style="color: #0d47a1;"></i> Application Note / Remarks <span class="text-danger">*</span></label>

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
                    <textarea id="summernote" name="remarks" required></textarea>
                </div>

                <hr style="margin: 20px 0; border-top: 1px solid #eaeaea;">

                <div style="text-align: right;">
                    <button type="submit" class="btn btn-success" style="font-size: 15px; padding: 8px 20px;"><i class="fa-solid fa-paper-plane"></i> Save Note</button>
                </div>
            </form>
        </div>
    </div>

    @include('components.partials.summernote-editor')
    @include('components.partials.qr-scanner-modal')
    @endsection
