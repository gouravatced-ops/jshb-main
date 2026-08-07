<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                <h5 class="modal-title" id="documentModalLabel" style="font-weight: 600; color: #333;">View Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: 70vh; padding: 0; display: flex; justify-content: center; align-items: center; background: #e9ecef;">
                <iframe id="documentIframe" src="" style="width: 100%; height: 100%; border: none; display: none;"></iframe>
                <img id="documentImage" src="" style="max-width: 100%; max-height: 100%; object-fit: contain; display: none;" alt="Document">
            </div>
            <div class="modal-footer" style="padding: 10px;">
                <a id="documentDownloadBtn" href="#" target="_blank" class="btn btn-primary btn-sm"><i class="fa-solid fa-download"></i> Download</a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewDocument(url, name) {
        document.getElementById('documentModalLabel').innerText = name;
        document.getElementById('documentDownloadBtn').href = url;

        var iframe = document.getElementById('documentIframe');
        var image = document.getElementById('documentImage');

        // Detect file type based on extension
        var lowerUrl = url.toLowerCase();
        if (lowerUrl.match(/\.(jpeg|jpg|gif|png|webp)(\?.*)?$/) != null) {
            // It's an image
            iframe.style.display = 'none';
            iframe.src = '';

            image.style.display = 'block';
            image.src = url;
        } else {
            // Assume PDF or other embeddable document
            image.style.display = 'none';
            image.src = '';

            iframe.style.display = 'block';
            iframe.src = url;
        }

        // Use Bootstrap modal instance
        var docModal = new bootstrap.Modal(document.getElementById('documentModal'));
        docModal.show();
    }

    // Clear iframe and image src when modal is closed to stop rendering overhead
    document.getElementById('documentModal').addEventListener('hidden.bs.modal', function(event) {
        document.getElementById('documentIframe').src = '';
        document.getElementById('documentImage').src = '';
    });
</script>
