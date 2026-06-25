<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 text-light">
            <i class="fa-solid fa-ruler-combined me-2"></i>Extra Construction
        </h5>
    </div>
    <div class="card-body p-4">
        <form id="extraConstructionForm" action="{{ route('admin.allottees.extra-construction.store', $allottee->id) }}" data-csrf="{{ csrf_token() }}" onsubmit="event.preventDefault(); window.saveExtraConstruction();">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-bold">Is there any extra construction on the site?</label>
                <div class="d-flex gap-4 mt-2">
                    <div class="form-check custom-radio">
                        <input class="form-check-input" type="radio" name="extra_construction" id="extraYes" value="yes" required>
                        <label class="form-check-label" for="extraYes">
                            <i class="fa-solid fa-check text-success me-1"></i> Yes
                        </label>
                    </div>
                    <div class="form-check custom-radio">
                        <input class="form-check-input" type="radio" name="extra_construction" id="extraNo" value="no" checked>
                        <label class="form-check-label" for="extraNo">
                            <i class="fa-solid fa-xmark text-danger me-1"></i> No
                        </label>
                    </div>
                </div>
            </div>

            <div id="extraConstructionDetails" style="display: none;" class="bg-light p-4 rounded mb-4 border">
                <p class="text-muted mb-0"><i class="fa-solid fa-info-circle me-2"></i> Additional details for extra construction can be added here.</p>
                <!-- Further form fields for 'yes' can go here -->
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" id="saveExtraBtn" class="btn btn-success px-5 py-2 fs-5 rounded-pill shadow-sm">
                    <i class="fa-solid fa-save me-2"></i> Save & Continue
                </button>
            </div>
</div>
<style>
    .custom-radio .form-check-input {
        width: 1.5em;
        height: 1.5em;
        margin-top: 0.1em;
        cursor: pointer;
    }
    .custom-radio .form-check-label {
        font-size: 1.1em;
        padding-top: 0.2em;
        padding-left: 0.5em;
        cursor: pointer;
    }
</style>
