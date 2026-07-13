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

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
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
                ['insert', ['picture', 'link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
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
                }
            }
        });
    });
</script>
