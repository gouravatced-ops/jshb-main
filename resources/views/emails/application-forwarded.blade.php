<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application {{ ucfirst(str_replace('_', ' ', $actionType)) }}</title>
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
        }

        .greeting {
            font-size: 15px;
            color: #1B2A4A;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .message-box {
            background: #FFF3CD;
            border-left: 4px solid #F5A623;
            padding: 15px;
            border-radius: 0 4px 4px 0;
            margin: 15px 0 25px 0;
            font-size: 13px;
            color: #664d03;
        }

        .details-table {
            width: 100%;
            background: #f8fafc;
            border: 1px solid #17A673;
            border-radius: 6px;
            border-collapse: separate;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .details-table th,
        .details-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }

        .details-table tr:last-child th,
        .details-table tr:last-child td {
            border-bottom: none;
        }

        .details-table th {
            color: #1B2A4A;
            font-weight: 600;
            width: 40%;
            background: #e2e8f0;
        }

        .details-table td {
            color: #333;
        }

        .btn-container {
            text-align: center;
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #17A673;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 2px solid #107c55;
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
            Application {{ ucfirst(str_replace('_', ' ', $actionType)) }}
        </div>

        <!-- Body -->
        <div class="body-content">
            <div class="greeting">Dear {{ $receiverName }},</div>

            @if(!empty($customMessage))
            <p style="font-size: 14px; color: #1B2A4A; margin-bottom: 20px; font-weight: 500;">
                {{ $customMessage }}
            </p>
            @elseif(!empty($remarks) && !in_array($actionType, ['forward', 'send_back', 'reject', 'approve', 'document_verify_upload']))
            <div class="message-box">
                {!! $remarks !!}
            </div>
            @else
            <p style="font-size: 13px; color: #555; margin-bottom: 20px;">An application has been {{ str_replace('_', ' ', $actionType) }} for further review and action.</p>
            @endif

            <table class="details-table" cellspacing="0">
                <tr>
                    <th>Application No.</th>
                    <td>{{ $applicationNo }}</td>
                </tr>
                <tr>
                    <th>Action By</th>
                    <td>{{ $senderName }}</td>
                </tr>
                <tr>
                    <th>Date & Time</th>
                    <td>{{ now()->format('d M Y, h:i A') }}</td>
                </tr>
            </table>

            <div style="font-size: 12px; color: #664d03; background: #FFF3CD; border-left: 4px solid #F5A623; padding: 10px 15px; border-radius: 0 4px 4px 0; margin: 15px 0;">
                Please log in to the dashboard to review the application details and take necessary action.
            </div>

            <div class="btn-container">
                <a href="{{ $dashboardUrl }}" class="btn">View Application / Login</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-brand">JSHB Portal</p>
            <p class="footer-text">This is an automated message. Please do not reply to this email.</p>
            <p class="footer-text">&copy; {{ date('Y') }} Jharkhand State Housing Board. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
