@extends('layouts.main')

@section('title', 'My Extension Requests | JSHB')

@section('content')
<div class="card">
    <div class="card-head" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <div class="card-title">My Extension Requests</div>
            <div class="card-subtitle">List of extension requests submitted by you</div>
        </div>
        <div>
            <a href="{{ route('engineer.applications.index') }}" class="btn-primary" style="padding: 8px 16px; font-size: 14px; background: #0f172a; border-radius: 4px; border: none; cursor: pointer; color: white; text-decoration: none;">
                <i class="fa-solid fa-arrow-left"></i> Back to Pending List
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Application No</th>
                    <th>Reason for Extension</th>
                    <th>Submitted At</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td><strong>#{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>
                        @if($req->application)
                        <a href="{{ route('engineer.applications.show', $req->application) }}" style="text-decoration: none; font-weight: 600; color: #2563eb;">
                            {{ $req->application->application_no }}
                        </a>
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 13px; line-height: 1.5; color: #475569;">
                            {!! Str::limit(strip_tags($req->request_reason), 75) !!}
                        </div>
                    </td>
                    <td>
                        <div>{{ $req->created_at->format('d M Y') }}</div>
                        <small class="text-muted">{{ $req->created_at->format('h:i A') }}</small>
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                        @if($req->status === 'approved')
                            <span class="badge-status active"><i class="fa-solid fa-check-circle"></i> Approved</span>
                            @if($req->extension_days)
                                <strong style="font-size: 12px; color: #155724;">+{{ $req->extension_days }} Days</strong>
                            @endif
                        @elseif($req->status === 'rejected')
                            <span class="badge-status inactive"><i class="fa-solid fa-times-circle"></i> Rejected</span>
                        @else
                            <span class="badge-status" style="background: #fef3c7; color: #d97706; border: 1px solid #fde68a;"><i class="fa-solid fa-clock"></i> Pending</span>
                        @endif
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('engineer.extensions.show', \Illuminate\Support\Facades\Crypt::encryptString($req->id)) }}" class="btn-primary" style="padding: 4px 10px; font-size: 12px; text-decoration: none; background: #0f172a; color: white; display: inline-block; border-radius: 4px; text-align: center;">
                            <i class="fa-solid fa-eye"></i> View Details
                        </a>
                    </td>
                </tr>
                @empty
                <x-no-data colspan="6" message="No extension requests found." description="You haven't submitted any extension requests yet." icon="fa-clock-rotate-left" />
                @endforelse
            </tbody>
        </table>
    </div>

    @if($requests->hasPages())
    <div style="padding: 15px 20px; border-top: 1px solid #eaeaea;">
        {{ $requests->links() }}
    </div>
    @endif
</div>
@endsection
