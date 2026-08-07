<style>
    /* Custom animation for modal to slide from top to center with bounce */
    #uploadDocModal.fade .modal-dialog {
        transform: translateY(-50px) scale(0.9);
        opacity: 0;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.4s ease;
    }

    #uploadDocModal.show .modal-dialog {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .upload-modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .upload-modal-header {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: white;
        padding: 18px 24px;
        border-bottom: none;
    }

    .upload-modal-header .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 0.8;
    }

    .upload-modal-header .btn-close:hover {
        opacity: 1;
    }

    .upload-modal-title {
        font-weight: 600;
        font-size: 1.2rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .upload-modal-body {
        padding: 24px;
        background: #fdfdfd;
    }

    .upload-input-group {
        margin-bottom: 20px;
    }

    .upload-input-group label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #34495e;
        margin-bottom: 8px;
        display: block;
    }

    .upload-control {
        border: 1px solid #dce1e6;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: #fff;
        width: 100%;
    }

    .upload-control:focus {
        border-color: #2a5298;
        box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.15);
        outline: none;
    }

    .upload-file-wrapper {
        position: relative;
        border: 2px dashed #cbd3da;
        border-radius: 10px;
        padding: 30px 20px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .upload-file-wrapper:hover {
        border-color: #2a5298;
        background: #f1f4f8;
    }

    .upload-file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .upload-file-icon {
        font-size: 2.5rem;
        color: #2a5298;
        margin-bottom: 12px;
    }

    .upload-file-text {
        color: #495057;
        font-size: 1rem;
        font-weight: 500;
        margin: 0;
    }

    .upload-file-subtext {
        color: #868e96;
        font-size: 0.85rem;
        margin-top: 6px;
    }

    .upload-modal-footer {
        padding: 16px 24px;
        background: #f8f9fa;
        border-top: 1px solid #eaeaea;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
</style>

<div class="modal fade" id="uploadDocModal" tabindex="-1" aria-labelledby="uploadDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content upload-modal-content">
            <div class="modal-header upload-modal-header">
                <h5 class="modal-title upload-modal-title" id="uploadDocModalLabel">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload New Document
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route($routePrefix . '.applications.upload-document', $application) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body upload-modal-body">
                    <div class="upload-input-group">
                        <label>Select File <span class="text-danger">*</span></label>
                        <div class="upload-file-wrapper">
                            <input type="file" name="document_file" class="upload-file-input" required accept=".pdf,.jpg,.jpeg,.png">
                            <div class="upload-file-icon">
                                <i class="fa-regular fa-file-pdf"></i>
                            </div>
                            <p class="upload-file-text">Click to browse or drag file here</p>
                            <p class="upload-file-subtext">Supported formats: PDF, JPG, PNG (Max 5MB)</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer upload-modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border: 1px solid #dce1e6; font-weight: 500;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: #2a5298; border: none; font-weight: 500; padding: 8px 20px;">
                        <i class="fa-solid fa-upload"></i> Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = document.querySelectorAll('.upload-file-input');

        fileInputs.forEach(fileInput => {
            fileInput.addEventListener('change', function(e) {
                const wrapper = this.closest('.upload-file-wrapper');
                if(!wrapper) return;
                const fileText = wrapper.querySelector('.upload-file-text');
                const fileIcon = wrapper.querySelector('.upload-file-icon');

                if (this.files && this.files.length > 0) {
                    const fileName = this.files[0].name;
                    fileText.innerHTML = `<span style="color: #28a745; font-weight: 600;">Selected: ${fileName}</span>`;
                    fileIcon.innerHTML = `<i class="fa-solid fa-file-circle-check" style="color: #28a745;"></i>`;
                } else {
                    fileText.innerHTML = `Click to browse or drag file here`;
                    fileIcon.innerHTML = `<i class="fa-regular fa-file-pdf"></i>`;
                }
            });
        });
    });
</script>
