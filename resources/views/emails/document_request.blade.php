<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Request - {{ $appName }}</title>
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
            background: #e9ecef;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        /* ─── Header with JSHB Theme ─── */
        .header {
            background: #1B2A4A;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            border-bottom: 4px solid #F5A623;
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
        /* ─── Accent bar ─── */
        .accent-bar {
            background: #F5A623;
            padding: 8px 20px;
            color: #1B2A4A;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        /* ─── Body ─── */
        .body-content {
            padding: 25px 30px;
            background: #e9ecef;
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
        /* ─── Document Request Box ─── */
        .doc-container {
            background: #f8fafc;
            border: 1px solid #1B2A4A;
            border-radius: 6px;
            padding: 15px;
            margin: 15px 0 25px 0;
        }
        .doc-item {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .doc-item:last-child {
            margin-bottom: 0;
        }
        .doc-label {
            color: #555;
            font-weight: 600;
            display: inline-block;
            width: 130px;
        }
        .doc-value {
            color: #1B2A4A;
            font-weight: 700;
        }
        /* ─── Action Button ─── */
        .btn-wrap {
            text-align: center;
            margin: 25px 0;
        }
        .btn {
            background: #17A673;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 25px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 14px;
            display: inline-block;
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
            Action Required: Document Request
        </div>

        <!-- Body -->
        <div class="body-content">
            <p class="greeting">Dear {{ $userName ?? 'Allottee' }},</p>
            <p class="message-text">{!! nl2br($messageBody) !!}</p>

            <!-- Document Request Box -->
            <div class="doc-container">
                <div class="doc-item">
                    <span class="doc-label">Required Document:</span>
                    <span class="doc-value">{{ $documentName }}</span>
                </div>
                <div class="doc-item">
                    <span class="doc-label">Submit By:</span>
                    <span class="doc-value" style="color: #E8960C;">{{ $dueDate }}</span>
                </div>
            </div>

            <!-- Login Button -->
            <div class="btn-wrap">
                <a href="{{ $dashboardUrl }}" class="btn">Login to Dashboard</a>
            </div>

            <!-- Warning Note -->
            <div class="note">
                <strong>⚠ Important:</strong> Failure to submit the requested document by the due date may cause a delay in processing your application.
            </div>
            
            <p class="message-text" style="font-size: 12px; margin-top: 30px;">
                Regards,<br>
                <strong>Jharkhand State Housing Board (JSHB)</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-brand">ADMS JSHB</div>
            <p class="footer-text">This is an automated notification. Please do not reply to this email.</p>
            <p class="footer-text">&copy; {{ date('Y') }} Jharkhand State Housing Board. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
