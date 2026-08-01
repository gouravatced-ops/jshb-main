@extends('layouts.main')

@section('title', 'Approve Application | JSHB')

@section('content')
<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-green">
            <span><i class="fa-solid fa-check" style="margin-right: 8px;"></i> Approve Application <span style="opacity: 0.7; font-size: 14px; font-weight: 500; margin-left: 5px;">| No: {{ $application->application_no }}</span></span>
            <div>
                <button type="button" onclick="openQrModal()" style="font-weight: 600; font-size: 13px; padding: 6px 12px; border-radius: 6px; border: none; color: #0d47a1; background: #e3f2fd; cursor: pointer; margin-right: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><i class="fa-solid fa-mobile-screen-button" style="margin-right: 5px;"></i> Live Image Upload</button>
                <a href="{{ route('operator.applications.show', $application) }}" class="btn btn-outline-primary btn-sm" style="background: rgba(255,255,255,0.7); font-weight: 600; color: #0d47a1; border-color: #0d47a1;"><i class="fa-solid fa-arrow-left"></i> Back to Review</a>
            </div>
        </div>
        <div class="compact-card-body">

            <div style="background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 5px solid #ffeeba; margin-bottom: 20px; color: #856404;">
                <i class="fa-solid fa-info-circle"></i> <strong>Note:</strong> You are granting final approval. Please provide your official approval noting below.
            </div>

            <form action="{{ route('operator.applications.action', $application) }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" value="approve">

                <div class="form-group mb-4 summernote-wrapper">
                    <label style="font-weight: 600; font-size: 16px; margin-bottom: 10px; display: block; color: #333;"><i class="fa-solid fa-pen-fancy" style="color: #155724;"></i> Approval Noting / Remarks <span class="text-danger">*</span></label>

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

                    <textarea id="summernote" name="remarks" required></textarea>
                </div>

                <hr style="margin: 20px 0; border-top: 1px solid #eaeaea;">

                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

                @if(empty(auth()->user()->internal_password))
                    <div class="alert alert-danger" style="display: flex; align-items: center; justify-content: space-between; padding: 15px;">
                        <div>
                            <i class="fa-solid fa-triangle-exclamation"></i> <strong>Please set an internal password for your profile!</strong> 
                            <br><small>You cannot approve this application without it.</small>
                        </div>
                        @php 
                            $profileRoute = auth()->user()->role === 'user' ? route('profile') : route(auth()->user()->role . '.profile'); 
                        @endphp
                        <a href="{{ $profileRoute }}" class="btn btn-sm btn-danger">Set Internal Password</a>
                    </div>
                @else
                    <div class="form-group mb-4">
                        <label style="font-weight: 600; font-size: 16px; margin-bottom: 10px; display: block; color: #333;"><i class="fa-solid fa-lock" style="color: #155724;"></i> Internal Password <span class="text-danger">*</span></label>
                        <input type="password" name="internal_password" class="form-control" placeholder="Enter your internal password to confirm approval" required style="border-radius: 6px; padding: 10px 15px;">
                        <small class="text-muted">For security, please verify your identity by entering your internal password before generating the official allotment document.</small>
                        @error('internal_password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="text-align: right;">
                        <button type="submit" class="btn btn-success" style="font-size: 15px; padding: 8px 20px; font-weight: 600;"><i class="fa-solid fa-check-circle"></i> Submit Noting & Approve</button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    @include('components.partials.qr-scanner-modal')
    @include('components.partials.summernote-editor')
    @endsection
