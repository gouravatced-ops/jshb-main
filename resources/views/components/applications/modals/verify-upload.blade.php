<div class="modal fade" id="verifyUploadDocModal" tabindex="-1" aria-labelledby="verifyUploadDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content upload-modal-content">
            <div class="modal-header upload-modal-header" style="background: #17a2b8;">
                <h5 class="modal-title upload-modal-title" id="verifyUploadDocModalLabel" style="color: white;">
                    <i class="fa-solid fa-file-signature"></i> Verify & Upload Document
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route($routePrefix . '.applications.verify-upload', $application) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body upload-modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="upload-input-group">
                                <label>Select File <span class="text-danger">*</span></label>
                                <div class="upload-file-wrapper">
                                    <input type="file" name="document_file" class="upload-file-input" required accept=".pdf,.jpg,.jpeg,.png">
                                    <div class="upload-file-icon">
                                        <i class="fa-regular fa-file-pdf"></i>
                                    </div>
                                    <p class="upload-file-text">Click to browse or drag file</p>
                                    <p class="upload-file-subtext">Supported formats: PDF, JPG, PNG (Max 5MB)</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="upload-input-group">
                                <label>Verification Notes <span class="text-danger">*</span></label>
                                <textarea id="summernote" name="remarks" required></textarea>
                            </div>
                            <div class="upload-input-group mt-3" style="display: none;">
                                <label>Font Family</label>
                                <select name="font_family" class="form-select">
                                    <option value="english" selected>English</option>
                                    <option value="hindi">Hindi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer upload-modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border: 1px solid #dce1e6; font-weight: 500;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: #17a2b8; border: none; font-weight: 500; padding: 8px 20px;">
                        <i class="fa-solid fa-check"></i> Verify & Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
