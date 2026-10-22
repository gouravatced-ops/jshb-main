<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JSHB Automated Approvals Report</title>
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
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }

        .header {
            background: #1B2A4A;
            padding: 20px 24px;
            border-bottom: 4px solid #17A673;
        }

        .header table {
            width: 100%;
        }

        .header-title {
            color: #ffffff;
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .header-subtitle {
            color: #93c5fd;
            font-size: 13px;
            margin-top: 4px;
        }

        .content {
            padding: 24px;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 24px;
        }

        .stat-card {
            display: table-cell;
            width: 33.33%;
            padding: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            text-align: center;
        }

        .stat-card.primary {
            border-top: 4px solid #2563eb;
        }

        .stat-card.success {
            border-top: 4px solid #16a34a;
        }

        .stat-card.amber {
            border-top: 4px solid #d97706;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 4px;
        }

        .stat-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #64748b;
            font-weight: 600;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin: 24px 0 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f1f5f9;
            display: flex;
            align-items: center;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .data-table th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 700;
            text-align: left;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .data-table td {
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            color: #334155;
            vertical-align: top;
        }

        .data-table tr:nth-child(even) td {
            background: #fbfcfe;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 4px;
        }

        .badge-success {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .empty-text {
            color: #94a3b8;
            font-style: italic;
            text-align: center;
            padding: 20px;
        }

        .footer {
            background: #f8fafc;
            padding: 16px 24px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }

        .btn-link {
            display: inline-block;
            background: #1B2A4A;
            color: #ffffff !important;
            text-decoration: none;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 4px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="header">
            <table>
                <tr>
                    <td>
                        <h1 class="header-title">Jharkhand State Housing Board (JSHB)</h1>
                        <div class="header-subtitle">Automated Scheduled Approvals Report &bull; {{ $reportDate }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Body Content -->
        <div class="content">
            <p style="margin-top: 0; color: #334155;">
                Dear,<br>
                The scheduled automated processing job has successfully executed. Below is the summary of <strong>Bypass Requests</strong> and <strong>Extension Requests</strong> processed and approved.
            </p>

            <!-- Metrics Overview -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-label">Bypass Approved</div>
                    <div class="stat-value">{{ count($reportData['bypass_approved'] ?? []) }}</div>
                </div>
                <div class="stat-card success" style="margin-left: 10px;">
                    <div class="stat-label">Extensions Approved</div>
                    <div class="stat-value">{{ count($reportData['extension_approved'] ?? []) }}</div>
                </div>
                <div class="stat-card amber" style="margin-left: 10px;">
                    <div class="stat-label">Default Days Granted</div>
                    <div class="stat-value">{{ $reportData['default_days'] ?? 3 }} Days</div>
                </div>
            </div>

            <!-- Bypass Requests Section -->
            <div class="section-title">
                1. Workflow Bypass Requests Approved ({{ count($reportData['bypass_approved'] ?? []) }})
            </div>
            @if(!empty($reportData['bypass_approved']) && count($reportData['bypass_approved']) > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Application No</th>
                            <th>Requested By</th>
                            <th>Forwarded / Sent To</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['bypass_approved'] as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item['app_no'] ?? 'N/A' }}</strong></td>
                                <td>{{ $item['requested_by'] ?? 'N/A' }}</td>
                                <td>{{ $item['sent_to'] ?? 'N/A' }}</td>
                                <td>{{ $item['reason'] ?? 'N/A' }}</td>
                                <td><span class="badge badge-success">Approved</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-text">No pending bypass requests were found during this run.</div>
            @endif

            <!-- Extension Requests Section -->
            <div class="section-title">
                2. Application Extension Requests Approved ({{ count($reportData['extension_approved'] ?? []) }})
            </div>
            @if(!empty($reportData['extension_approved']) && count($reportData['extension_approved']) > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Application No</th>
                            <th>Requested By</th>
                            <th>Days Granted</th>
                            <th>New Due Date</th>
                            <th>Reason / Remarks</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['extension_approved'] as $index => $ext)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $ext['app_no'] ?? 'N/A' }}</strong></td>
                                <td>{{ $ext['requested_by'] ?? 'N/A' }}</td>
                                <td><span class="badge badge-blue">+{{ $ext['extension_days'] ?? 3 }} Days</span></td>
                                <td><strong>{{ $ext['new_due_date'] ?? 'N/A' }}</strong></td>
                                <td>{{ $ext['reason'] ?? 'N/A' }}</td>
                                <td><span class="badge badge-success">Approved</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-text">No pending extension requests were found during this run.</div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            This is an automated system notification from the <strong>ADMS Portal - Jharkhand State Housing Board</strong>.<br>
            Server Timestamp: {{ now()->format('d M Y, h:i:s A') }}
        </div>
    </div>
</body>
</html>
