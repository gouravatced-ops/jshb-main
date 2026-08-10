@extends('layouts.main')

@section('title', 'Bypass Requests History | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">Bypass Requests History</div>
            <div class="card-subtitle">View previously approved or rejected bypass requests</div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Application No</th>
                    <th>Requested By</th>
                    <th>Target Step</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Processed Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bypassRequests as $request)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $request->application->application_no ?? 'N/A' }}</strong><br>
                        <span style="font-size: 11px; color: #666;">Type: {{ ucfirst($request->application->application_type ?? '') }}</span>
                    </td>
                    <td>
                        <div class="table-name">{{ $request->requestedBy->name ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="table-name">{{ $request->targetStep->step_name ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div style="max-width: 250px; white-space: normal; font-size: 12px; color: #555;">
                            {{ \Illuminate\Support\Str::limit($request->reason, 60) }}
                        </div>
                    </td>
                    <td>
                        @if($request->status == 'approved')
                        <span class="badge bg-success" style="padding: 5px 8px; font-size: 11px;"><i class="fa-solid fa-check"></i> Approved</span>
                        @if($request->is_used)
                        <div style="margin-top: 4px;">
                            <span class="badge bg-secondary" style="padding: 3px 6px; font-size: 10px;"><i class="fa-solid fa-check-double"></i> Used</span>
                        </div>
                        @endif
                        @elseif($request->status == 'rejected')
                        <span class="badge bg-danger" style="padding: 5px 8px; font-size: 11px;"><i class="fa-solid fa-xmark"></i> Rejected</span>
                        @endif
                        <div style="font-size: 11px; color: #888; margin-top: 4px;">By: {{ $request->approvedBy->name ?? 'Admin' }}</div>
                    </td>
                    <td>
                        {{ $request->updated_at->format('d M, Y h:i A') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 40px 0;">
                        <div style="color: #64748b; margin-bottom: 10px;">
                            <i class="fa-solid fa-clock-rotate-left" style="font-size: 32px; color: #cbd5e1;"></i>
                        </div>
                        <div>No bypass request history found.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
