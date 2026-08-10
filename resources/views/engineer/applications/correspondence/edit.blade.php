@extends('layouts.main')

@section('title', 'Edit Office Correspondence | JSHB')

@section('content')
<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-green" style="display: flex; justify-content: space-between; align-items: center; background: #e8f5e9; color: #2e7d32; border-top: 3px solid #4caf50;">
            <span><i class="fa-solid fa-pen-to-square" style="margin-right: 8px;"></i> Edit Office Correspondence <span style="opacity: 0.7; font-size: 14px; font-weight: 500; margin-left: 5px;">| Ref: {{ $correspondence->reference_number }}</span></span>
            <div>
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

            <form action="{{ route('engineer.applications.correspondence.update', [$application, $correspondence]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-4">
                    <div class="form-group mb-4">
                        <label style="font-weight: 600; font-size: 14px; margin-bottom: 8px; display: block; color: #475569;">Correspondence Type <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4">
                            <label class="custom-radio">
                                <input type="radio" name="type" value="LT" required {{ $correspondence->type == 'LT' ? 'checked' : '' }}>
                                <span class="radio-mark"></span>
                                <div class="radio-label">
                                    <i class="fa-solid fa-file-lines text-primary"></i> Letter (LT)
                                </div>
                            </label>
                            <label class="custom-radio">
                                <input type="radio" name="type" value="OO" required {{ $correspondence->type == 'OO' ? 'checked' : '' }}>
                                <span class="radio-mark"></span>
                                <div class="radio-label">
                                    <i class="fa-solid fa-file-signature text-info"></i> Office Order (OO)
                                </div>
                            </label>
                            <label class="custom-radio">
                                <input type="radio" name="type" value="OD" required {{ $correspondence->type == 'OD' ? 'checked' : '' }}>
                                <span class="radio-mark"></span>
                                <div class="radio-label">
                                    <i class="fa-solid fa-file-pen text-secondary"></i> Office Draft (OD)
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label" style="font-weight: 600; font-size: 14px; color: #333;">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control" style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 10px;" placeholder="Enter the subject of this correspondence..." required maxlength="255" value="{{ $correspondence->subject }}">
                </div>

                <div class="form-group mb-4 summernote-wrapper">
                    <label style="font-weight: 600; font-size: 16px; margin-bottom: 10px; display: block; color: #333;"><i class="fa-solid fa-pen-fancy" style="color: #2e7d32;"></i> Content <span class="text-danger">*</span></label>

                    <!-- Font Family Selection -->
                    <div class="mb-3 p-2" style="background: #f8f9fa; border-radius: 6px; border: 1px solid #e9ecef;">
                        <label class="me-3" style="font-weight: 600; font-size: 14px; color: #495057;">Typing Language:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input font-family-selector" type="radio" name="font_family" id="font_english_note" value="english" {{ $correspondence->font_family == 'english' ? 'checked' : '' }}>
                            <label class="form-check-label" for="font_english_note" style="margin:0px;">English (Arial)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input font-family-selector" type="radio" name="font_family" id="font_hindi_note" value="krutidev" {{ $correspondence->font_family == 'krutidev' ? 'checked' : '' }}>
                            <label class="form-check-label" for="font_hindi_note" style="margin:0px;">Hindi (Kruti Dev)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input font-family-selector" type="radio" name="font_family" id="font_normal_hindi_note" value="normalhindi" {{ $correspondence->font_family == 'normalhindi' ? 'checked' : '' }}>
                            <label class="form-check-label" for="font_normal_hindi_note" style="margin:0px;">Normal Hindi</label>
                        </div>
                    </div>

                    <!-- Rich Text Editor via Summernote -->
                    <textarea id="summernote" name="content" required>{!! $correspondence->content !!}</textarea>
                </div>

                <hr style="margin: 20px 0; border-top: 1px solid #eaeaea;">

                <div style="background: #fffbeb; padding: 12px; border-radius: 6px; border: 1px solid #fde68a; font-size: 12px; color: #92400e; margin-bottom: 20px;">
                    <i class="fa-solid fa-triangle-exclamation" style="margin-right: 5px;"></i> <strong>Note:</strong> Once you click "Publish", the correspondence becomes official and cannot be edited. "Save as Draft" allows you to edit it later.
                </div>

                <div style="text-align: right; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="submit" name="status" value="draft" class="btn btn-secondary" style="font-size: 15px; padding: 8px 20px;">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Update Draft
                    </button>
                    <button type="submit" name="status" value="published" class="btn btn-success" style="font-size: 15px; padding: 8px 20px; background-color: #4caf50; border-color: #4caf50;" onclick="return confirm('Are you sure you want to Publish? No changes can be made after publishing.')">
                        <i class="fa-solid fa-paper-plane me-1"></i> Publish
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('components.partials.summernote-editor')
    @include('components.partials.qr-scanner-modal')
</div>
@endsection
