@extends('layouts.main')

@section('title', 'Extension Request History | Admin')

@section('content')
<div class="card">
    <div class="card-head">
        <div class="card-title">Extension Request History</div>
    </div>

    @if(session('success'))
    <div class="alert alert-success" style="margin: 15px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger" style="margin: 15px;">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Application No</th>
                    <th>Engineer</th>
                    <th>Reason</th>
                    <th>Submitted At</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td><strong>#{{ $req->id }}</strong></td>
                    <td>{{ $req->application->application_no ?? 'N/A' }}</td>
                    <td>{{ $req->requestedBy->name ?? 'Unknown' }}</td>
                    <td>{!! Str::limit(strip_tags($req->request_reason), 60) !!}
                    </td>
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
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <span style="color: #6c757d; font-size: 13px;">Processed <br>{{ $req->approved_at ? $req->approved_at->format('d M, h:i A') : $req->updated_at->format('d M, h:i A') }}</span>
                            @if($req->extension_days)
                                <strong style="font-size: 12px; color: #155724;">+{{ $req->extension_days }} Days</strong>
                            @endif
                            
                            <a href="{{ route('admin.extensions.show', \Illuminate\Support\Facades\Crypt::encryptString($req->id)) }}" class="btn-primary" style="margin-top: 5px; padding: 4px 10px; font-size: 12px; text-decoration: none; background: #0f172a; color: white; display: inline-block; border-radius: 4px; text-align: center;">
                                <i class="fa-solid fa-eye"></i> View Details
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <x-no-data colspan="7" message="No extension requests found." description="There are no pending or past extension requests to show." icon="fa-clock-rotate-left" />
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($requests->total() > 0)
    <div class="table-pagination" style="padding: 15px; border-top: 1px solid #eaeaea;">
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

<script>
    function showReason(content) {
        alert("Full Reason:\n\n" + content);
    }
</script>
@endsection
