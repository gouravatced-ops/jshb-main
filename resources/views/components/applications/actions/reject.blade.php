@props(['application', 'routePrefix'])

<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-red">
            <span><i class="fa-solid fa-times" style="margin-right: 8px;"></i> Reject Application <span style="opacity: 0.7; font-size: 14px; font-weight: 500; margin-left: 5px;">| No: {{ $application->application_no }}</span></span>
            <a href="{{ route($routePrefix . '.applications.show', $application) }}" class="btn btn-outline-danger btn-sm" style="background: rgba(255,255,255,0.7); font-weight: 600; color: #721c24; border-color: #721c24;"><i class="fa-solid fa-arrow-left"></i> Back to Review</a>
        </div>
        <div class="compact-card-body">

            <div style="background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 5px solid #ffeeba; margin-bottom: 20px; color: #856404;">
                <i class="fa-solid fa-info-circle"></i> <strong>Note:</strong> Rejection is a permanent action. Provide clear justification noting for rejecting this application.
            </div>

            <form action="{{ route($routePrefix . '.applications.action', $application) }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" value="reject">

                <div class="form-group mb-4 summernote-wrapper">
                    <label style="font-weight: 600; font-size: 16px; margin-bottom: 10px; display: block; color: #333;"><i class="fa-solid fa-pen-fancy" style="color: #721c24;"></i> Rejection Noting / Remarks <span class="text-danger">*</span></label>

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

                <div style="text-align: right;">
                    <button type="submit" class="btn btn-danger" style="font-size: 15px; padding: 8px 20px; font-weight: 600;"><i class="fa-solid fa-times"></i> Submit Noting & Reject</button>
                </div>
            </form>
        </div>
    </div>

    @include('components.partials.summernote-editor')
</div>
