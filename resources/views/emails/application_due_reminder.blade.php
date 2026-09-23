<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Due Reminder - JSHB Portal</title>
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* ─── Header with JSHB Theme ─── */
        .header {
            background: #1B2A4A;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            border-bottom: 4px solid #17A673;
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

        /* ─── Warning / Danger accent bar ─── */
        .accent-bar {
            background: #E74A3B; /* Red warning color for due dates */
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
        }

        .message-text {
            color: #333;
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        ul {
            background: #ffffff;
            border-radius: 5px;
            padding: 15px 15px 15px 30px;
            margin: 15px 0;
            border-left: 4px solid #F6C23E; /* Yellow warning accent */
        }

        li {
            margin-bottom: 5px;
        }

        .days-remaining {
            background: #FFEAEA;
            border: 1px solid #FFC5C5;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
            font-size: 15px;
        }

        .days-remaining.urgent {
            background: #E74A3B;
            color: white;
            border: none;
            font-weight: bold;
            font-size: 16px;
        }

        .note-text {
            color: #777;
            font-size: 13px;
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 15px;
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
            ACTION REQUIRED: DUE DATE APPROACHING
        </div>

        <!-- Body -->
        <div class="body-content">
            <div class="message-text">
                <p>Dear <strong>{{ $userName }}</strong>,</p>
                <p>This is an automated reminder regarding a pending application assigned to you.</p>

                <ul>
                    <li><strong>Application No:</strong> {{ $applicationNo }}</li>
                    <li><strong>Action Required:</strong> Please review and process this application immediately.</li>
                    <li><strong>Due Date:</strong> {{ $dueDateFormatted }}</li>
                </ul>

                @if ($diffDays == 0)
                    <div class="days-remaining urgent">
                        TODAY is the last day to process this application!
                    </div>
                @else
                    <div class="days-remaining">
                        You have <strong style="color:#E74A3B;">{{ $diffDays }} days remaining</strong> to process this application before the due date.
                    </div>
                @endif

                <div class="note-text">
                    <strong>Note:</strong> If the application is not processed by the due date, it will be locked and you will not be able to view or process it. After locking, you must submit a formal request to the administration with a valid reason to unlock it.
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-brand">JSHB Portal</p>
            <p class="footer-text">This is an automated notification from JSHB. Please do not reply to this email.</p>
            <p class="footer-text">&copy; {{ date('Y') }} Jharkhand State Housing Board. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
