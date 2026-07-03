<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification - {{ $appName }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 30px auto;
            background: #e9ecef; /* Gray background for the body content */
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        /* ─── Header with JSHB Theme ─── */
        .header {
            background: #1B2A4A; /* Navy blue */
            padding: 15px 20px;
            display: flex;
            align-items: center;
            border-bottom: 4px solid #17A673; /* Teal green accent */
        }
        .header table {
            width: 100%;
        }
        .header td {
            vertical-align: middle;
        }
        .header-logo {
            width: 50px;
            height: 50px;
            background: #ffffff;
            border-radius: 50%;
            padding: 4px;
        }
        .header h1 {
            color: #ffffff;
            margin: 0 0 0 15px;
            font-size: 18px;
            font-weight: 700;
            display: block;
            line-height: 1.2;
        }
        .header-subtitle {
            color: #8CB4E0;
            font-size: 12px;
            margin: 2px 0 0 15px;
            display: block;
            text-transform: uppercase;
            font-weight: 600;
        }
        /* ─── Teal accent bar ─── */
        .accent-bar {
            background: #17A673;
            padding: 8px 20px;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        /* ─── Body ─── */
        .body-content {
            padding: 25px 30px;
            background: #e9ecef; /* Match the gray background */
        }
        .greeting {
            font-size: 15px;
            color: #1B2A4A;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .message-text {
            color: #555;
            font-size: 13px;
            margin-bottom: 20px;
        }
        /* ─── OTP Code Box ─── */
        .otp-container {
            background: #f8fafc;
            border: 1px solid #17A673;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            margin: 15px 0 25px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: 800;
            color: #1B2A4A;
            letter-spacing: 12px;
            font-family: 'Courier New', monospace;
            margin: 0;
        }
        /* ─── Warning Note ─── */
        .note {
            background: #FFF3CD;
            border-left: 4px solid #F5A623;
            padding: 10px 15px;
            border-radius: 0 4px 4px 0;
            margin: 15px 0;
            font-size: 12px;
            color: #664d03;
        }
        .note strong {
            color: #E8960C;
        }
        /* ─── Security Info ─── */
        .security-info {
            background: #F0F7FF;
            border-left: 4px solid #1B2A4A;
            padding: 10px 15px;
            border-radius: 0 4px 4px 0;
            margin: 15px 0;
            font-size: 11px;
            color: #1B2A4A;
        }
        .security-info ul {
            margin: 6px 0 0 0;
            padding-left: 18px;
        }
        .security-info li {
            margin-bottom: 4px;
        }
        /* ─── Footer ─── */
        .footer {
            background: #1B2A4A;
            padding: 15px 20px;
            text-align: center;
        }
        .footer-text {
            color: #8CB4E0;
            font-size: 10px;
            margin: 3px 0 0 0;
        }
        .footer-brand {
            color: #F5A623;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="header">
            <!-- Using table for reliable email client inline rendering -->
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="60">
                        <img src="https://adms.jshb.computered.co.in/public/img/jshb_logo.png" alt="JSHB Logo" class="header-logo">
                    </td>
                    <td>
                        <h1>ADMS JSHB</h1>
                        <div class="header-subtitle">JHARKHAND STATE HOUSING BOARD</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Accent Bar -->
        <div class="accent-bar">
            OTP Verification Request
        </div>

        <!-- Body -->
        <div class="body-content">
            <p class="greeting">Dear {{ $userName ?? 'User' }},</p>
            <p class="message-text">{!! nl2br($messageBody) !!}</p>

            <!-- OTP Code -->
            <div class="otp-container">
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <!-- Warning Note -->
            <div class="note">
                <strong>⚠ Important:</strong> This OTP is valid for <strong>10 minutes</strong>. Please do not share this code with anyone. JSHB will never ask for your OTP via phone or in person.
            </div>

            <!-- Security Info -->
            <div class="security-info">
                <strong>🛡 Security Tips:</strong>
                <ul>
                    <li>Never share your OTP with anyone</li>
                    <li>JSHB officials will never ask for your OTP</li>
                    <li>If you didn't request this, please ignore this email</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-brand">{{ $appName }} — JSHB</p>
            <p class="footer-text">This is an automated message. Please do not reply to this email.</p>
            <p class="footer-text">&copy; {{ date('Y') }} Jharkhand State Housing Board. All rights reserved.</p>
        </div>
    </div>
</body>
</html>