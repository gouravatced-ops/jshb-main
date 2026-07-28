<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Notes - {{ $application->application_no }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(config('panel.faviconIcon')) }}">
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2a5298;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #1e3c72;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #555;
        }
        .note-block {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .note-header {
            background: #f4f4f4;
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
            font-weight: bold;
            color: #444;
        }
        .note-header .date {
            float: right;
            color: #666;
            font-weight: normal;
        }
        .note-body {
            padding: 12px;
            font-size: 12px;
        }
        .note-body p {
            margin-top: 0;
            margin-bottom: 8px;
        }
        .note-body img {
            max-width: 100%;
            height: auto;
        }
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        @font-face {
            font-family: 'KrutiDev011';
            src: url("{{ public_path('font/KrutiDev011.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'notosansdevanagari';
            src: url("{{ public_path('font/NotoSansDevanagari.ttf') }}") format('truetype');
        }
        
        ul, ol, li {
            list-style-type: disc;
            list-style-image: none;
            list-style-position: outside;
        }
        
        li {
            margin-left: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Application Notes</h2>
        <p>Application No: {{ $application->application_no }}</p>
    </div>

    @if($application->notes && $application->notes->count() > 0)
        @foreach($application->notes as $note)
        <div class="note-block">
            <div class="note-header">
                By: {{ $note->user ? $note->user->name : 'System' }} 
                @if($note->role) ({{ $note->role->name }}) @endif
                
                <span class="date">{{ $note->created_at ? $note->created_at->format('d-M-Y h:i A') : '' }}</span>
            </div>
            @php
                $noteFontFamily = "";
                if (isset($note->font_family) && $note->font_family === 'krutidev') {
                    $noteFontFamily = "font-family: 'KrutiDev011', sans-serif;";
                } else if (isset($note->font_family) && $note->font_family === 'normalhindi') {
                    $noteFontFamily = "font-family: 'notosansdevanagari', sans-serif;";
                }
            @endphp
            <div class="note-body" style="{{ $noteFontFamily }}">
                {!! $note->remarks !!}
            </div>
        </div>
        @endforeach
    @else
        <p style="text-align: center; color: #777;">No notes available for this application.</p>
    @endif

    <div class="footer">
        Generated on {{ date('d-M-Y h:i A') }} | JSHB
    </div>
</body>
</html>
