@extends('layouts.main')

@section('title', 'My Extension Requests | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div class="card-title">My Extension Requests</div>
        <div class="card-subtitle">List of extension requests submitted by you</div>
    </div>

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Application No</th>
                    <th>Reason</th>
                    <th>Submitted At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td><strong>#{{ $req->id }}</strong></td>
                    <td>{{ $req->application->application_no ?? 'N/A' }}</td>
                    <td>{!! Str::limit(strip_tags($req->request_reason), 50) !!}</td>
                    <td>{{ $req->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        @if($req->status === 'approved')
                            <span class="badge-status active"><i class="fa-solid fa-circle-check"></i> Approved</span>
                        @elseif($req->status === 'rejected')
                            <span class="badge-status inactive"><i class="fa-solid fa-circle-xmark"></i> Rejected</span>
                        @else
                            <span class="badge-status" style="color: #856404; background: #fff3cd;"><i class="fa-solid fa-clock"></i> Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <x-no-data colspan="5" message="No extension requests found." description="You haven't submitted any extension requests." icon="fa-clock-rotate-left" />
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($requests->total() > 0)
    <div class="table-pagination">
        <span>
            Showing <strong>{{ $requests->firstItem() }}</strong> to
            <strong>{{ $requests->lastItem() }}</strong> of <strong>{{ $requests->total() }}</strong>
            requests
        </span>
        <div class="pagination-btns">
            @if ($requests->onFirstPage())
            <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-left"></i></span>
            @else
            <a class="pag-btn" href="{{ $requests->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
            @endif

            @foreach ($requests->getUrlRange(1, $requests->lastPage()) as $page => $url)
            <a class="pag-btn {{ $page === $requests->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
            @endforeach

            @if ($requests->hasMorePages())
            <a class="pag-btn" href="{{ $requests->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
            @else
            <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
