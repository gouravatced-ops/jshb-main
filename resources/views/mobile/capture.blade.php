<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Capture Photo</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body { margin: 0; padding: 0; background-color: #000; color: #fff; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
        .header { padding: 15px; text-align: center; background: #111; font-weight: bold; font-size: 18px; }
        .video-container { flex: 1; position: relative; overflow: hidden; display: flex; justify-content: center; align-items: center; background: #000; }
        video { width: 100%; height: 100%; object-fit: cover; }
        canvas { display: none; }
        .controls { padding: 20px; text-align: center; background: #111; padding-bottom: calc(20px + env(safe-area-inset-bottom)); }
        .capture-btn { width: 70px; height: 70px; border-radius: 50%; background: #fff; border: 4px solid #ccc; cursor: pointer; outline: none; transition: transform 0.1s; display: inline-block; }
        .capture-btn:active { transform: scale(0.9); background: #eee; }
        #status-msg { margin-top: 15px; font-size: 14px; color: #aaa; min-height: 20px; }
        .success-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.85); display: none; flex-direction: column; justify-content: center; align-items: center; z-index: 10; }
        .success-overlay svg { width: 80px; height: 80px; color: #4CAF50; margin-bottom: 20px; }
        .success-overlay p { font-size: 20px; font-weight: bold; margin: 0; text-align: center; padding: 0 20px; }
        .success-overlay p.small { font-size: 15px; color: #aaa; margin-top: 10px; font-weight: normal; }
    </style>
</head>
<body>

    <div class="header">Scan & Document Capture</div>
    
    <div class="video-container">
        <video id="video" autoplay playsinline></video>
        <canvas id="canvas"></canvas>
        
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
        <div id="error-box" style="display:none; background: #ff4444; color: white; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; text-align: left;"></div>
        <button class="capture-btn" id="capture-btn"></button>
        <div id="status-msg">Align document and tap to capture</div>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('capture-btn');
        const statusMsg = document.getElementById('status-msg');
        const successOverlay = document.getElementById('success-overlay');
        const errorBox = document.getElementById('error-box');
        const token = '{{ $token }}';

        function showError(msg) {
            errorBox.style.display = 'block';
            errorBox.innerHTML = '<strong>Error:</strong> ' + msg;
            statusMsg.textContent = "Cannot capture photo.";
            statusMsg.style.color = "#ff4444";
        }

        // Initialize Camera
        async function initCamera() {
            // First check if browser supports mediaDevices
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showError("Camera API not supported. This usually happens if you are not using HTTPS, or if you opened this link inside a QR Scanner's built-in browser. Please open this link in standard Chrome or Safari.");
                return;
            }
            
            try {
                // Try to get rear camera first
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: 'environment' } 
                });
                video.srcObject = stream;
            } catch (err) {
                console.error("Camera access denied or unavailable", err);
                showError("Could not access camera. Please check your browser permissions. (" + err.message + ")");
            }
        }

        initCamera();

        function handleCapture(e) {
            e.preventDefault(); // Prevent double firing
            
            if (!video.srcObject) {
                showError("Camera feed is not active. Please ensure camera permissions are granted and you are using a secure HTTPS connection.");
                return;
            }

            statusMsg.textContent = "Capturing and uploading...";
            captureBtn.disabled = true;
            captureBtn.style.opacity = '0.5';

            // Set canvas size to video's actual resolution
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Draw video frame to canvas
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Convert to base64 (jpeg, 0.8 quality)
            const imageData = canvas.toDataURL('image/jpeg', 0.8);

            // Upload via AJAX
            $.ajax({
                url: `/mobile/capture/${token}/upload`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    image: imageData
                },
                success: function(response) {
                    if (response.success) {
                        successOverlay.style.display = 'flex';
                        statusMsg.textContent = "Upload successful!";
                    }
                },
                error: function(xhr) {
                    captureBtn.disabled = false;
                    captureBtn.style.opacity = '1';
                    statusMsg.textContent = "Upload failed. Please try again.";
                    statusMsg.style.color = "#ff4444";
                }
            });
        }
        captureBtn.addEventListener('click', handleCapture);
        captureBtn.addEventListener('touchstart', handleCapture);
    </script>
</body>
</html>
