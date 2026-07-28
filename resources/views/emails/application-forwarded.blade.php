<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Application {{ ucfirst(str_replace('_', ' ', $actionType)) }}</title>
    <style>
        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            color: #334155;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .header {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1e293b;
        }

        .message-box {
            background-color: #f1f5f9;
            border-left: 4px solid #3b82f6;
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 4px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .details-table th,
        .details-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .details-table th {
            color: #64748b;
            font-weight: 500;
            width: 40%;
        }

        .details-table td {
            font-weight: 600;
            color: #0f172a;
        }

        .btn-container {
            text-align: center;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #1d4ed8;
        }

        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Application {{ ucfirst(str_replace('_', ' ', $actionType)) }}</h1>
        </div>
        <div class="content">
            <div class="greeting">Hello {{ $receiverName }},</div>
            <p>An application has been {{ str_replace('_', ' ', $actionType) }} for further review and action.</p>

            <table class="details-table">
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

            <p style="margin-top: 30px; color: #475569;">Please log in to the dashboard to review the application details and take necessary action.</p>

            <div class="btn-container">
                <a href="{{ $dashboardUrl }}" class="btn">View Application</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Jharkhand State Housing Board. All rights reserved.
        </div>
    </div>
</body>

</html>
