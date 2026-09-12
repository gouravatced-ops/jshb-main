@extends('layouts.main')

@section('title', 'Manage Extension Requests | Admin')

@section('content')
<div class="card">
    <div class="card-head">
        <div class="card-title">Extension Requests</div>
        <div class="card-subtitle">Manage timeline extensions requested by engineers</div>
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
                        @if(strlen(strip_tags($req->request_reason)) > 60)
                            <button type="button" class="btn btn-link btn-sm" onclick="showReason('{{ addslashes($req->request_reason) }}')">Read more</button>
                        @endif
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
                        @if($req->status === 'pending')
                            <div style="display: flex; gap: 5px; flex-direction: column;">
                                <!-- Approve Form -->
                                <form action="{{ route('admin.extensions.approve', $req->id) }}" method="POST" style="background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0; margin-bottom: 5px;">
                                    @csrf
                                    <div style="margin-bottom: 8px;">
                                        <label style="font-size: 12px; font-weight: 600; display: block; margin-bottom: 4px;">Days to Extend:</label>
                                        <select name="extension_days" class="form-select form-select-sm" required style="width: 100%; padding: 4px;">
                                            @for($i=1; $i<=6; $i++)
                                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }} Day{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div style="margin-bottom: 8px;">
                                        <input type="text" name="remarks" class="form-control form-control-sm" placeholder="Approval Remarks (optional)" style="width: 100%; padding: 4px; font-size: 12px;">
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm w-100" onclick="return confirm('Approve this extension?');"><i class="fa-solid fa-check"></i> Approve</button>
                                </form>

                                <!-- Reject Form -->
                                <form action="{{ route('admin.extensions.reject', $req->id) }}" method="POST" style="background: #fdf2f2; padding: 10px; border-radius: 6px; border: 1px solid #fbd5d5;">
                                    @csrf
                                    <div style="margin-bottom: 8px;">
                                        <input type="text" name="remarks" class="form-control form-control-sm" placeholder="Rejection Reason" required style="width: 100%; padding: 4px; font-size: 12px;">
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Reject this extension?');"><i class="fa-solid fa-xmark"></i> Reject</button>
                                </form>
                            </div>
                        @else
                            <span style="color: #6c757d; font-size: 13px;">Processed <br>{{ $req->approved_at ? $req->approved_at->format('d M, h:i A') : $req->updated_at->format('d M, h:i A') }}</span>
                            @if($req->extension_days)
                                <br><strong style="font-size: 12px; color: #155724;">+{{ $req->extension_days }} Days</strong>
                            @endif
                        @endif
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
