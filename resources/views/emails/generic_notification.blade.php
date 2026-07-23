<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $mailSubject ?? 'Notification' }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #0f1b2d; border-bottom: 2px solid #f0f2f5; padding-bottom: 10px; margin-top: 0;">{{ $mailSubject ?? 'Notification' }}</h2>
        
        <div style="color: #4a5568; line-height: 1.6; font-size: 16px; margin: 20px 0;">
            {!! nl2br(e($mailBody)) !!}
        </div>

        @if(isset($link) && $link)
            <div style="margin-top: 30px; text-align: center;">
                <a href="{{ $link }}" style="display: inline-block; padding: 12px 24px; background-color: #1a7a6e; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold;">View Details</a>
            </div>
        @endif

        <div style="margin-top: 40px; border-top: 1px solid #f0f2f5; padding-top: 20px; font-size: 12px; color: #a0aec0; text-align: center;">
            <p>This is an automated notification from JSHB. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
