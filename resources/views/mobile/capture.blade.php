<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Capture Photo</title>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/cropper.min.css') }}" />
    <script src="{{ asset('js/cropper.min.js') }}"></script>
    <style>
        body { margin: 0; padding: 0; background-color: #000; color: #fff; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; display: flex; flex-direction: column; height: 100vh; height: 100dvh; overflow: hidden; }
        .header { padding: 15px; text-align: center; background: #111; font-weight: bold; font-size: 18px; flex-shrink: 0; }
        .video-container { flex: 1; position: relative; overflow: hidden; display: flex; justify-content: center; align-items: center; background: #000; min-height: 50vh; }
        video { width: 100%; height: 100%; object-fit: cover; }
        canvas { display: none; }
        #cropper-container { display: none; width: 100%; height: 100%; }
        #image-to-crop { max-width: 100%; max-height: 100%; display: block; }
        .controls { padding: 20px; text-align: center; background: #111; padding-bottom: calc(20px + env(safe-area-inset-bottom)); flex-shrink: 0; }
        .capture-btn { width: 70px; height: 70px; border-radius: 50%; background: #fff; border: 4px solid #ccc; cursor: pointer; outline: none; transition: transform 0.1s; display: inline-block; }
        .capture-btn:active { transform: scale(0.9); background: #eee; }
        #status-msg { margin-top: 15px; font-size: 14px; color: #aaa; min-height: 20px; }
        .success-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.85); display: none; flex-direction: column; justify-content: center; align-items: center; z-index: 10; }
        .success-overlay svg { width: 80px; height: 80px; color: #4CAF50; margin-bottom: 20px; }
        .success-overlay p { font-size: 20px; font-weight: bold; margin: 0; text-align: center; padding: 0 20px; }
        .success-overlay p.small { font-size: 15px; color: #aaa; margin-top: 10px; font-weight: normal; }
        
        .edit-controls { display: none; padding: 10px 0; overflow-x: auto; white-space: nowrap; margin-bottom: 15px; }
        .filter-btn { background: #333; border: 1px solid #555; color: white; padding: 8px 15px; margin: 0 5px; border-radius: 20px; cursor: pointer; font-size: 14px; }
        .filter-btn.active { background: #4CAF50; border-color: #4CAF50; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">Scan & Document Capture</div>
    
    <div class="video-container">
        <video id="video" autoplay playsinline></video>
        <canvas id="canvas"></canvas>
        <div id="cropper-container">
            <img id="image-to-crop" src="" alt="Picture to crop">
        </div>
        
        <div class="success-overlay" id="success-overlay">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <p>Photo Uploaded!</p>
            <p class="small">The photo has been sent to the desktop application. You can close this window now.</p>
        </div>
    </div>
    
    <div class="controls">
        <div id="error-box" style="display:none; background: #ff4444; color: white; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; text-align: left; max-height: 100px; overflow-y: auto;"></div>
        
        <div id="capture-actions">
            <button class="capture-btn" id="capture-btn"></button>
            <div id="status-msg">Align document and tap to capture</div>
        </div>

        <div id="confirm-actions" style="display:none; width: 100%; flex-direction: column; max-width: 400px; margin: 0 auto;">
            <div class="edit-controls" id="edit-controls">
                <button type="button" class="filter-btn active" data-filter="none">Normal</button>
                <button type="button" class="filter-btn" data-filter="grayscale">B&W Document</button>
                <button type="button" class="filter-btn" data-filter="contrast">High Contrast</button>
                <button type="button" class="filter-btn" data-filter="brightness">Brighten</button>
            </div>
            <div style="display: flex; gap: 15px; justify-content: center; width: 100%;">
                <button type="button" id="retake-btn" style="flex: 1; padding: 12px; border-radius: 25px; border: 2px solid #fff; background: transparent; color: #fff; font-weight: bold; font-size: 16px;">Retake</button>
                <button type="button" id="upload-btn" style="flex: 1; padding: 12px; border-radius: 25px; border: none; background: #4CAF50; color: #fff; font-weight: bold; font-size: 16px;">OK / Upload</button>
            </div>
        </div>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('capture-btn');
        const retakeBtn = document.getElementById('retake-btn');
        const uploadBtn = document.getElementById('upload-btn');
        const statusMsg = document.getElementById('status-msg');
        const successOverlay = document.getElementById('success-overlay');
        const errorBox = document.getElementById('error-box');
        
        const captureActions = document.getElementById('capture-actions');
        const confirmActions = document.getElementById('confirm-actions');
        const cropperContainer = document.getElementById('cropper-container');
        const imageToCrop = document.getElementById('image-to-crop');
        const editControls = document.getElementById('edit-controls');
        const filterBtns = document.querySelectorAll('.filter-btn');
        
        const token = '{{ $token }}';
        
        let cropper = null;
        let currentFilter = 'none';
        let errorTimeout;

        function showError(msg) {
            if (errorTimeout) clearTimeout(errorTimeout);
            errorBox.style.display = 'block';
            errorBox.innerHTML = '<div><strong>Error:</strong> ' + msg + '</div>';
            statusMsg.textContent = "Cannot capture photo.";
            statusMsg.style.color = "#ff4444";
            
            errorTimeout = setTimeout(() => {
                errorBox.style.display = 'none';
                errorBox.innerHTML = '';
                statusMsg.textContent = "Align document and tap to capture";
                statusMsg.style.color = "#aaa";
            }, 3000);
        }

        // Initialize Camera
        async function initCamera() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showError("Camera API not supported. (Needs HTTPS)");
                return;
            }
            
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: 'environment' } 
                });
                video.srcObject = stream;
                
                video.onloadedmetadata = () => {
                    video.play();
                };
            } catch (err) {
                showError("Camera error: " + err.message);
            }
        }

        initCamera();

        function handleCapture(e) {
            e.preventDefault();
            
            if (!video.srcObject) {
                showError("Camera feed not active.");
                return;
            }

            try {
                let vWidth = video.videoWidth || 640;
                let vHeight = video.videoHeight || 480;
                
                canvas.width = vWidth;
                canvas.height = vHeight;
                
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                let capturedImageData = canvas.toDataURL('image/jpeg', 1.0);

                // Pause video and show cropper
                video.pause();
                video.style.display = 'none';
                cropperContainer.style.display = 'block';
                imageToCrop.src = capturedImageData;
                
                if (cropper) {
                    cropper.destroy();
                }
                
                cropper = new Cropper(imageToCrop, {
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 1,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });

                // Switch UI
                captureActions.style.display = 'none';
                confirmActions.style.display = 'flex';
                editControls.style.display = 'block';
                
            } catch (ex) {
                showError("Capture exception: " + ex.message);
            }
        }

        function handleRetake(e) {
            e.preventDefault();
            
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            
            cropperContainer.style.display = 'none';
            video.style.display = 'block';
            video.play();

            // Switch UI
            confirmActions.style.display = 'none';
            editControls.style.display = 'none';
            captureActions.style.display = 'block';
            statusMsg.textContent = "Align document and tap to capture";
            statusMsg.style.color = "#fff";
            
            // Reset filter
            applyFilter('none');
        }

        function applyFilter(filterType) {
            currentFilter = filterType;
            filterBtns.forEach(b => b.classList.remove('active'));
            document.querySelector(`.filter-btn[data-filter="${filterType}"]`).classList.add('active');
            
            // Apply visual filter to cropper container
            const cropperImage = document.querySelector('.cropper-view-box img');
            const cropperCanvas = document.querySelector('.cropper-canvas img');
            
            let cssFilter = 'none';
            if (filterType === 'grayscale') cssFilter = 'grayscale(100%) contrast(120%)';
            if (filterType === 'contrast') cssFilter = 'contrast(150%)';
            if (filterType === 'brightness') cssFilter = 'brightness(130%)';
            
            if (cropperImage) cropperImage.style.filter = cssFilter;
            if (cropperCanvas) cropperCanvas.style.filter = cssFilter;
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                applyFilter(e.target.dataset.filter);
            });
        });

        function handleUpload(e) {
            e.preventDefault();
            
            if (!cropper) return;

            uploadBtn.disabled = true;
            retakeBtn.disabled = true;
            uploadBtn.textContent = "Uploading...";
            uploadBtn.style.opacity = "0.7";

            // Get cropped canvas
            const croppedCanvas = cropper.getCroppedCanvas({
                maxWidth: 2048,
                maxHeight: 2048
            });
            
            // Apply filters to final image
            const finalCanvas = document.createElement('canvas');
            finalCanvas.width = croppedCanvas.width;
            finalCanvas.height = croppedCanvas.height;
            const ctx = finalCanvas.getContext('2d');
            
            if (currentFilter === 'grayscale') {
                ctx.filter = 'grayscale(100%) contrast(120%)';
            } else if (currentFilter === 'contrast') {
                ctx.filter = 'contrast(150%)';
            } else if (currentFilter === 'brightness') {
                ctx.filter = 'brightness(130%)';
            } else {
                ctx.filter = 'none';
            }
            
            ctx.drawImage(croppedCanvas, 0, 0);
            const finalImageData = finalCanvas.toDataURL('image/jpeg', 0.85);

            $.ajax({
                url: `/mobile/capture/${token}/upload`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    image: finalImageData
                },
                success: function(response) {
                    if (response.success) {
                        successOverlay.style.display = 'flex';
                    }
                },
                error: function(xhr) {
                    uploadBtn.disabled = false;
                    retakeBtn.disabled = false;
                    uploadBtn.textContent = "OK / Upload";
                    uploadBtn.style.opacity = "1";
                    
                    let errorMsg = "Upload failed: " + xhr.status;
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    showError(errorMsg);
                }
            });
        }

        captureBtn.addEventListener('click', handleCapture);
        captureBtn.addEventListener('touchstart', handleCapture);
        
        retakeBtn.addEventListener('click', handleRetake);
        retakeBtn.addEventListener('touchstart', handleRetake);
        
        uploadBtn.addEventListener('click', handleUpload);
        uploadBtn.addEventListener('touchstart', handleUpload);
    </script>
</body>
</html>
