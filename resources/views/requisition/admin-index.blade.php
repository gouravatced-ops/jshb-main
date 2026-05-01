@extends('layouts.main')

@section('title', 'Requisition Management | JSHB')

@section('content')
<div class="card">
    @if(session('success'))
        <div class="alert alert-success" style="margin: 20px 20px 0;">{{ session('success') }}</div>
    @endif

    <div class="card-head">
        <div>
            <div class="card-title">Guest House Requisition Management</div>
            <div class="card-subtitle">Review, approve, or reject engineer stay requests</div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Guest House</th>
                    <th>District / Block</th>
                    <th>Stay Dates</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requisitions as $requisition)
                    <tr>
                        <td>{{ $requisitions->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="table-name">{{ $requisition->user?->name ?: '-' }}</div>
                            <div class="table-email">{{ $requisition->engineer?->department?->name ?: 'Department not set' }}</div>
                        </td>
                        <td>
                            <div class="table-name">{{ $requisition->guest_house_name }}</div>
                            <div class="table-email">{{ \Illuminate\Support\Str::limit($requisition->purpose, 60) }}</div>
                        </td>
                        <td>{{ ($requisition->district?->name_en ?: '-') . ' / ' . ($requisition->block?->block_name_eng ?: '-') }}</td>
                        <td>{{ optional($requisition->stay_from)->format('d M Y') }} - {{ optional($requisition->stay_to)->format('d M Y') }}</td>
                        <td><span class="badge-status {{ $requisition->status === 'approved' ? 'active' : ($requisition->status === 'rejected' ? 'inactive' : '') }}">{{ ucfirst($requisition->status) }}</span></td>
                        <td>
                            @if($requisition->status === 'pending')
                                <form action="{{ route('admin.requisitions.update-status', $requisition) }}" method="POST" style="display:grid;gap:8px;min-width:220px;">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="admin_remarks" class="form-control" rows="2" placeholder="Admin remark"></textarea>
                                    <div style="display:flex;gap:8px;">
                                        <button name="status" value="approved" class="btn-submit" type="submit" style="padding:10px 14px;">Approve</button>
                                        <button name="status" value="rejected" class="btn-reset" type="submit" style="padding:10px 14px;">Reject</button>
                                    </div>
                                </form>
                            @else
                                <div class="table-email">{{ $requisition->admin_remarks ?: 'No remarks' }}</div>
                                <div class="table-email">By {{ $requisition->approver?->name ?: '-' }}</div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px 20px;color:var(--text-light);">No requisitions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($requisitions->total() > 0)
        <div class="table-pagination">
            <span>
                Showing <strong>{{ $requisitions->firstItem() }}</strong> to <strong>{{ $requisitions->lastItem() }}</strong> of <strong>{{ $requisitions->total() }}</strong> requisitions
            </span>
            <div class="pagination-btns">
                @if($requisitions->onFirstPage())
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a class="pag-btn" href="{{ $requisitions->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @endif

                @foreach($requisitions->getUrlRange(1, $requisitions->lastPage()) as $page => $url)
                    <a class="pag-btn {{ $page === $requisitions->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                @endforeach

                @if($requisitions->hasMorePages())
                    <a class="pag-btn" href="{{ $requisitions->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
