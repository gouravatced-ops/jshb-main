<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mailSubject ?? 'Notification' }} - ADMS JSHB</title>
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
            /* Gray background for the body content */
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* ─── Header with JSHB Theme ─── */
        .header {
            background: #1B2A4A;
            /* Navy blue */
            padding: 15px 20px;
            display: flex;
            align-items: center;
            border-bottom: 4px solid #17A673;
            /* Teal green accent */
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
            background: #e9ecef;
            /* Match the gray background */
        }

        .message-text {
            color: #555;
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .action-btn-container {
            text-align: center;
            margin: 30px 0 15px 0;
        }

        .action-btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #17A673;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            letter-spacing: 0.5px;
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
                        <h1>JSHB Portal</h1>
                        <div class="header-subtitle">JHARKHAND STATE HOUSING BOARD</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Accent Bar -->
        <div class="accent-bar">
            {{ $mailSubject ?? 'Notification' }}
        </div>

        <!-- Body -->
        <div class="body-content">
            <div class="message-text">
                {!! nl2br(e($mailBody ?? $message)) !!}
            </div>

            @if(isset($link) && $link)
            <div class="action-btn-container">
                <a href="{{ $link }}" class="action-btn">View Details</a>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-brand">ADMS JSHB</p>
            <p class="footer-text">This is an automated notification from JSHB. Please do not reply to this email.</p>
            <p class="footer-text">&copy; {{ date('Y') }} Jharkhand State Housing Board. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
