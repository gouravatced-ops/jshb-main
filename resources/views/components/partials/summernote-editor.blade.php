<!-- Custom Font Kruti Dev -->
<style>
/* Shared Action Layout Styles */
.compact-wrapper {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 20px;
    margin-top: 15px;
}
.compact-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #eef0f2;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.compact-card-header {
    padding: 15px 20px;
    font-size: 16px;
    font-weight: 600;
    border-bottom: 1px solid #eaeaea;
    display: flex;
    justify-content: space-between;
    align-items: center;
    letter-spacing: 0.3px;
}
.header-blue { background: linear-gradient(135deg, #e3f2fd, #bbdefb); color: #0d47a1; border-bottom-color: #90caf9; }
.header-orange { background: linear-gradient(135deg, #fff3e0, #ffe0b2); color: #e65100; border-bottom-color: #ffcc80; }
.header-yellow { background: linear-gradient(135deg, #fff3cd, #ffecb3); color: #856404; border-bottom-color: #ffeeba; }
.header-red { background: linear-gradient(135deg, #f8d7da, #f5c6cb); color: #721c24; border-bottom-color: #f5c6cb; }
.header-cyan { background: linear-gradient(135deg, #d1ecf1, #b8daff); color: #0c5460; border-bottom-color: #b8daff; }
.header-green { background: linear-gradient(135deg, #d4edda, #c3e6cb); color: #155724; border-bottom-color: #c3e6cb; }
.header-purple { background: linear-gradient(135deg, #f4f6f9, #e9ecef); color: #4a148c; border-bottom-color: #dee2e6; }

.compact-card-body {
    padding: 20px;
    flex-grow: 1;
    color: #444;
}
.badge-compact {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}
.col-span-12 { grid-column: span 12; }

@font-face {
    font-family: 'KrutiDev011';
    src: url("{{ asset('font/KrutiDev011.ttf') }}") format('truetype');
}
.summernote-wrapper .note-editor {
    border-radius: 8px;
    border: 1px solid #ced4da;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.summernote-wrapper .note-toolbar {
    background-color: #f8f9fa;
    border-bottom: 1px solid #ced4da;
    border-radius: 8px 8px 0 0;
}
/* Fix double carets caused by Bootstrap 5 conflict */
.note-editor .dropdown-toggle::after {
    display: none !important;
}
/* Force ALL items in the font dropdown to render in Arial to prevent KrutiDev gibberish */
.note-dropdown-menu.dropdown-fontname {
    font-family: Arial, sans-serif !important;
}
.note-dropdown-menu.dropdown-fontname * {
    font-family: Arial, sans-serif !important;
}
</style>

<link href="{{ asset('plugins/summernote/summernote-lite.min.css') }}" rel="stylesheet">
<script src="{{ asset('plugins/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('plugins/summernote/summernote-lite.min.js') }}"></script>

@php
    $isEngineer = auth()->check() && (auth()->user()->role === 'engineer' || auth()->user()->user_type === 'engineer');
@endphp

@if($isEngineer)
<!-- Custom Image Modal for Engineers -->
<div id="engineerImageModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:10500; backdrop-filter: blur(5px); overflow-y:auto; padding: 40px 20px;">
    <div class="engineer-modal-content" style="background:#fff; border-radius:16px; width:100%; max-width:750px; margin: 0 auto; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); transform: translateY(-50px); opacity: 0; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); overflow:hidden;">
        
        <!-- Header -->
        <div style="background: linear-gradient(to right, #f8fafc, #f1f5f9); padding: 20px 25px; border-bottom: 1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
            <h4 style="margin:0; font-size:18px; font-weight: 700; color:#1e293b;"><i class="fa-solid fa-image text-primary me-2"></i> Insert Image</h4>
            <button type="button" onclick="closeEngineerModal();" style="background:none; border:none; font-size:24px; line-height:1; cursor:pointer; color:#94a3b8; transition: color 0.2s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#94a3b8'">&times;</button>
        </div>
        
        <div style="padding: 25px;">
            <ul class="nav nav-pills nav-fill mb-4" id="imageModalTabs" role="tablist" style="background: #f1f5f9; padding: 4px; border-radius: 10px;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-panel" type="button" role="tab" style="font-weight:600; border-radius: 8px;"><i class="fa-solid fa-desktop me-1"></i> From PC</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="assets-tab" data-bs-toggle="tab" data-bs-target="#assets-panel" type="button" role="tab" style="font-weight:600; border-radius: 8px;"><i class="fa-solid fa-stamp me-1"></i> My Saved Assets</button>
                </li>
            </ul>
            
            <div class="tab-content" id="imageModalTabsContent" style="display:block;min-height: 220px;">
                <!-- Upload from PC Tab -->
                <div class="tab-pane fade show active" id="upload-panel" role="tabpanel">
                    <div style="padding: 20px 0;">
                        <label class="form-label" style="font-weight:600; color:#333;">Choose an image from your PC</label>
                        <input type="file" id="customSummernoteImageInput" accept="image/*" class="form-control form-control-lg mb-3">
                        
                        <button type="button" id="uploadFileBtn" class="btn btn-primary w-100" style="border-radius: 8px; font-weight: 600; padding: 12px;" onclick="insertLocalImage()">
                            Insert Selected Image
                        </button>
                    </div>
                </div>
                
                <!-- Saved Assets Tab -->
                <div class="tab-pane fade" id="assets-panel" role="tabpanel">
                    <div id="assetsLoader" style="text-align:center; padding: 60px 0; color: #94a3b8; display:none;">
                        <i class="fa-solid fa-circle-notch fa-spin fa-2x mb-2"></i><br>Fetching your assets...
                    </div>
                    <div id="assetsGallery" style="grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 15px; max-height: 300px; overflow-y: auto; padding: 5px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.engineerAssetsLoaded = false;
    
    function openEngineerModal() {
        $('#engineerImageModal').fadeIn(200);
        setTimeout(() => {
            $('.engineer-modal-content').css({
                'transform': 'translateY(0)',
                'opacity': '1'
            });
        }, 10);
    }
    function closeEngineerModal() {
        $('.engineer-modal-content').css({
            'transform': 'translateY(-50px)',
            'opacity': '0'
        });
        setTimeout(() => {
            $('#engineerImageModal').fadeOut(200);
        }, 300);
    }
</script>
@endif

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 350,
            placeholder: 'Write your official noting here... You can type in English or select KrutiDev011 for Hindi typing.',
            fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'KrutiDev011'],
            fontNamesIgnoreCheck: ['KrutiDev011'],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '28', '36', '48', '64'],
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                @if($isEngineer)
                ['insert', ['customImageInsert', 'link']],
                @else
                ['insert', ['picture', 'link']],
                @endif
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            @if($isEngineer)
            buttons: {
                customImageInsert: function(context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<i class="note-icon-picture"></i>',
                        tooltip: 'Insert Image / Signature',
                        click: function () {
                            openEngineerModal();
                        }
                    });
                    return button.render();
                }
            },
            @endif
            callbacks: {
                onInit: function() {
                    $('.note-editable').css({
                        'background-color': '#f1f8e9',
                        'font-family': 'Arial, sans-serif',
                        'font-size': '15px',
                        'line-height': '1.6',
                        'color': '#000080'
                    });
                    
                    // Forcefully fix the KrutiDev name in the dropdown using JS
                    setTimeout(function() {
                        $('.note-dropdown-menu a').each(function() {
                            if ($(this).attr('data-value') === 'KrutiDev011' || $(this).text().trim() === 'KrutiDev011') {
                                $(this).css('font-family', 'Arial, sans-serif');
                            }
                        });
                    }, 500);

                    // Attach tab switch event listener for fetching assets
                    @if($isEngineer)
                    $('#assets-tab').on('show.bs.tab', function (e) {
                        if(!window.engineerAssetsLoaded) {
                            $('#assetsLoader').show();
                            $('#assetsGallery').hide();
                            $.ajax({
                                url: '{{ route("engineer.api.assets") }}',
                                type: 'GET',
                                success: function(data) {
                                    window.engineerAssetsLoaded = true;
                                    $('#assetsLoader').hide();
                                    $('#assetsGallery').empty().css('display', 'grid');
                                    if(data.length === 0) {
                                        $('#assetsGallery').html('<div style="grid-column: 1/-1; text-align:center; color:#888;">No assets found. You can upload them from the My Assets page.</div>');
                                    } else {
                                        data.forEach(function(asset) {
                                            var item = $('<div style="border: 1px solid #ddd; border-radius: 6px; padding: 5px; cursor: pointer; text-align:center; transition: all 0.2s;" onmouseover="this.style.borderColor=\'#0d47a1\'; this.style.backgroundColor=\'#f8f9fa\'" onmouseout="this.style.borderColor=\'#ddd\'; this.style.backgroundColor=\'transparent\'" onclick="insertAssetImage(\'' + asset.full_url + '\')"></div>');
                                            item.append('<div style="height: 80px; display:flex; align-items:center; justify-content:center; overflow:hidden;"><img src="' + asset.full_url + '" style="max-height:100%; max-width:100%; object-fit:contain;"></div>');
                                            item.append('<div style="font-size:11px; margin-top:5px; text-transform:capitalize; font-weight:600;">' + asset.asset_type + '</div>');
                                            $('#assetsGallery').append(item);
                                        });
                                    }
                                },
                                error: function() {
                                    $('#assetsLoader').hide();
                                    $('#assetsGallery').show().html('<div style="color:red; text-align:center; grid-column:1/-1;">Failed to fetch assets. Please try again later.</div>');
                                }
                            });
                        }
                    });
                    @endif
                }
            }
        });
    });

    // Listen for font family changes from radio buttons outside summernote
    $(document).on('change', '.font-family-selector', function() {
        var selectedFont = $(this).val();
        if (selectedFont === 'krutidev') {
            $('.note-editable').css('font-family', 'KrutiDev011, sans-serif');
            $('#summernote').summernote('fontName', 'KrutiDev011');
        } else {
            $('.note-editable').css('font-family', 'Arial, sans-serif');
            $('#summernote').summernote('fontName', 'Arial');
        }
    });

    @if($isEngineer)
    function insertLocalImage() {
        var input = document.getElementById('customSummernoteImageInput');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#summernote').summernote('insertImage', e.target.result);
                closeEngineerModal();
                input.value = ''; // clear input
                document.getElementById('uploadFileBtn').setAttribute('disabled', 'disabled');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function insertAssetImage(url) {
        $('#summernote').summernote('insertImage', url);
        closeEngineerModal();
    }
    @endif
</script>
