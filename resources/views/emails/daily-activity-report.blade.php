<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JSHB Daily Activity Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .email-wrapper {
            max-width: 800px;
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

        h3 {
            color: #1B2A4A;
            font-size: 15px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
        }

        h3:first-child {
            margin-top: 0;
        }

        .stat-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .stat-box-wrap {
            display: table-cell;
            padding: 0 5px;
            width: 33.33%;
        }

        .stat-box {
            background: #ffffff;
            padding: 15px;
            text-align: center;
            border-radius: 6px;
            border: 1px solid #d1d5db;
        }

        .stat-box h2 {
            margin: 0;
            font-size: 24px;
            color: #1B2A4A;
        }

        .stat-box p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #555;
            font-weight: 600;
        }

        p.summary-text {
            font-size: 13px;
            color: #333;
            margin-bottom: 10px;
        }

        /* ─── Tables ─── */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            background: #ffffff;
            font-size: 12px;
        }

        table.data-table th, table.data-table td {
            border: 1px solid #d1d5db;
            padding: 8px 12px;
            text-align: left;
        }

        table.data-table th {
            background-color: #f8fafc;
            color: #1B2A4A;
            font-weight: 700;
        }

        table.data-table tr:nth-child(even) {
            background-color: #fcfcfc;
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
            Daily Activity Report - {{ $reportDate }}
        </div>

        <!-- Body -->
        <div class="body-content">
            <h3>1. Overall Application Stats</h3>
            <div class="stat-container">
                <div class="stat-box-wrap">
                    <div class="stat-box">
                        <h2>{{ $reportData['total_created'] }}</h2>
                        <p>New Applications</p>
                    </div>
                </div>
                <div class="stat-box-wrap">
                    <div class="stat-box">
                        <h2>{{ $reportData['total_completed'] }}</h2>
                        <p>Applications Completed</p>
                    </div>
                </div>
                <div class="stat-box-wrap">
                    <div class="stat-box">
                        <h2>{{ $reportData['total_docs_generated'] }}</h2>
                        <p>Documents Generated</p>
                    </div>
                </div>
            </div>

            <h3>2. Engineer Performance Report</h3>
            <!-- <p class="summary-text">Total movements processed yesterday: <strong>{{ $reportData['total_movements'] }}</strong></p> -->
            <table class="data-table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>Engineer Name</th>
                        <th>Role</th>
                        <th>App Movements</th>
                        <th>Letters Generated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportData['engineer_report'] as $eng)
                        <tr>
                            <td>{{ $eng['name'] }}</td>
                            <td>{{ $eng['role'] }}</td>
                            <td>{{ $eng['movements'] }}</td>
                            <td>{{ $eng['letters'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;">No engineer activity recorded yesterday.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <h3>3. Bypass Requests Report</h3>
            <table class="data-table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>Application No</th>
                        <th>Requested By</th>
                        <th>Sent To</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportData['bypass_report'] as $bypass)
                        <tr>
                            <td>{{ $bypass['app_no'] }}</td>
                            <td>{{ $bypass['requested_by'] }}</td>
                            <td>{{ $bypass['sent_to'] }}</td>
                            <td>{{ $bypass['reason'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;">No applications were bypassed yesterday.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-brand">JSHB Portal — JSHB</p>
            <p class="footer-text">This is an automated system report generated by JSHB Batch Scheduler. Please do not reply to this email.</p>
            <p class="footer-text">&copy; {{ date('Y') }} Jharkhand State Housing Board. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
