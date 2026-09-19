@extends('layouts.main')

@section('title', 'Activity Tracker - Admin Panel')

@section('content')
<style>
    .activity-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        padding: 20px;
        margin-bottom: 20px;
    }
    .header-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    .header-box h2 {
        font-size: 20px;
        color: #1e293b;
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .header-box h2 i {
        color: #3b82f6;
    }
    .log-table {
        width: 100%;
        border-collapse: collapse;
    }
    .log-table th {
        background: #0f172a;
        color: #fff;
        padding: 12px 15px;
        text-align: left;
        font-size: 13px;
        text-transform: uppercase;
        font-weight: 600;
    }
    .log-table th:first-child { border-top-left-radius: 8px; }
    .log-table th:last-child { border-top-right-radius: 8px; }
    .log-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #334155;
        vertical-align: top;
    }
    .log-table tr:hover { background: #f8fafc; }
    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-created { background: #dcfce7; color: #166534; }
    .badge-updated { background: #fef9c3; color: #854d0e; }
    .badge-deleted { background: #fee2e2; color: #991b1b; }

    .prop-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px;
        font-family: monospace;
        font-size: 12px;
        white-space: pre-wrap;
        word-break: break-all;
        max-height: 150px;
        overflow-y: auto;
    }
</style>

<div class="activity-container">
    <div class="header-box">
        <h2><i class="fa-solid fa-clock-rotate-left"></i> System Activity Tracker</h2>
    </div>

    <div style="overflow-x: auto;">
        <table class="log-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>User (Causer)</th>
                    <th>Event</th>
                    <th>Subject Model</th>
                    <!-- <th>Subject ID</th> -->
                    <th style="width: 40%">Changes / Properties</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                        <td>
                            @if($log->causer)
                                <strong>{{ $log->causer->name }}</strong><br>
                                <small style="color: #64748b;">{{ $log->causer->email }}</small>
                            @else
                                <span style="color:#94a3b8;">System / Guest</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $log->event }}">
                                {{ $log->event }}
                            </span>
                        </td>
                        <td>{{ class_basename($log->subject_type) ?? 'N/A' }}</td>
                        <!-- <td>{{ $log->subject_id ?? 'N/A' }}</td> -->
                        <td>
                            @if($log->properties && count($log->properties) > 0)
                                <div class="prop-box">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</div>
                            @else
                                <span style="color:#94a3b8;">No specific changes recorded.</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px; color: #64748b;">
                            <i class="fa-solid fa-folder-open" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                            No activities logged yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $activities->links() }}
    </div>
</div>
@endsection
