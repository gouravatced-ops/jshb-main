<script src="https://js.pusher.com/8.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<!-- QR Code Modal -->
<div id="qrModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:10000; justify-content:center; align-items:center; backdrop-filter: blur(5px);">
    <div style="background:#ffffff; padding:40px; border-radius:16px; text-align:center; box-shadow:0 15px 40px rgba(0,0,0,0.3); max-width: 400px; width: 90%; animation: slideDownQR 0.3s ease-out;">
        <div style="background: #e3f2fd; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fa-solid fa-mobile-screen-button" style="color: #1976d2; font-size: 24px;"></i>
        </div>
        <h3 style="margin:0 0 10px; color:#1e293b; font-weight:700; font-size: 22px;">Scan to Capture</h3>
        <p style="color:#64748b; font-size:15px; margin-bottom:25px; line-height: 1.5;">Point your phone's camera at this QR code. Snap a photo, and it will magically appear right here!</p>
        
        <div id="qrCodeContainer" style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 2px dashed #cbd5e1; display: inline-block; min-width: 200px; min-height: 200px; margin-bottom: 20px;">
            <div id="qrLoader" style="color: #94a3b8; line-height: 160px;"><i class="fa-solid fa-spinner fa-spin fa-2x"></i></div>
            <div id="qrcode" style="display: none; justify-content: center;"></div>
        </div>
        
        <div id="qrActions" style="display:none; margin-bottom: 25px; flex-direction: column; gap: 10px; align-items: center;">
            <div style="display:flex; width: 100%; max-width: 300px; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden;">
                <input type="text" id="qrLinkInput" readonly style="flex:1; border:none; padding: 8px 12px; font-size: 13px; color: #64748b; background: #f8fafc; outline: none;">
                <button type="button" onclick="copyQrLink()" style="border:none; background: #e3f2fd; color: #1976d2; padding: 0 15px; font-weight: 600; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#bbdefb'" onmouseout="this.style.background='#e3f2fd'">
                    <i class="fa-regular fa-copy"></i> Copy
                </button>
            </div>
            <button type="button" onclick="openQrModal()" style="border: none; background: transparent; color: #1976d2; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: underline;">
                <i class="fa-solid fa-rotate-right"></i> Generate New QR Code
            </button>
        </div>
        
        <div id="qrError" style="display:none; color: #ef4444; background: #fef2f2; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
            <div style="margin-bottom: 10px;" id="qrErrorMsg">Failed to load QR code.</div>
            <button type="button" onclick="openQrModal()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #ef4444; background: #fff; color: #ef4444; cursor: pointer; font-size: 13px;">
                <i class="fa-solid fa-rotate-right"></i> Regenerate
            </button>
        </div>

        <div>
            <button type="button" onclick="$('#qrModal').fadeOut();" class="btn btn-secondary" style="padding:10px 24px; border-radius:8px; border:none; background:#f1f5f9; color:#475569; font-weight: 600; cursor:pointer; transition: all 0.2s;">Cancel Scan</button>
        </div>
    </div>
</div>

<style>
@keyframes slideDownQR {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>

<script>
    // Enable pusher logging for debugging
    Pusher.logToConsole = true;

    // Initialize WebSockets using true Pusher
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env("PUSHER_APP_KEY") }}',
        cluster: '{{ env("PUSHER_APP_CLUSTER", "mt1") }}',
        forceTLS: true
    });

    // Global function to open QR modal and inject image into Summernote
    function openQrModal() {
        // Reset Modal State
        $('#qrcode').empty().hide();
        $('#qrActions').hide();
        $('#qrLoader').show();
        $('#qrError').hide();
        $('#qrModal').css('display', 'flex').hide().fadeIn();

        // Generate Session
        $.ajax({
            url: '{{ route("photo-session.generate") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(data) {
                $('#qrLoader').hide();
                $('#qrcode').show();
                $('#qrActions').css('display', 'flex');
                $('#qrLinkInput').val(data.url);
                
                // Generate QR Code locally
                new QRCode(document.getElementById("qrcode"), {
                    text: data.url,
                    width: 200,
                    height: 200,
                    colorDark : "#0f172a",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
                
                // Listen for image upload
                window.Echo.channel('photo-session.' + data.token)
                    .listen('.photo.captured', (e) => {
                        console.log("Photo Captured via Mobile!", e);
                        $('#qrModal').fadeOut();
                        
                        // Check if the Engineer Image Modal exists
                        if (typeof openEngineerModal === 'function') {
                            openEngineerModal();
                            
                            // Switch to My Saved Assets tab
                            var triggerEl = document.querySelector('#assets-tab');
                            if (triggerEl) {
                                var tab = new bootstrap.Tab(triggerEl);
                                tab.show();
                            }
                            
                            // Force gallery reload to fetch the newly saved image
                            window.engineerAssetsLoaded = false;
                            $('#assets-tab').trigger('show.bs.tab');
                        } else {
                            // Fallback if modal is missing
                            $('#summernote').summernote('insertImage', e.imageUrl);
                        }
                    });
            },
            error: function(xhr) {
                $('#qrLoader').hide();
                $('#qrErrorMsg').text('Failed to generate secure token. Please try again.');
                $('#qrError').show();
            }
        });
    }

    function copyQrLink() {
        var copyText = document.getElementById("qrLinkInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        
        // Show temporary feedback
        let originalText = $('#qrLinkInput').next('button').html();
        $('#qrLinkInput').next('button').html('<i class="fa-solid fa-check"></i> Copied');
        setTimeout(() => {
            $('#qrLinkInput').next('button').html(originalText);
        }, 2000);
    }
</script>
