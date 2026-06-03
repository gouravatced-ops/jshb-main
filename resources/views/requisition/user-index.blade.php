@extends('layouts.main')

@section('title', 'My Requisitions | JSHB')

@section('content')
<div class="card">
    @if(session('success'))
        <div class="alert alert-success" style="margin: 20px 20px 0;">{{ session('success') }}</div>
    @endif

    <div class="card-head">
        <div>
            <div class="card-title">My Guest House Requisitions</div>
            <div class="card-subtitle">Track your submitted stay requests and approval status</div>
        </div>
        <div class="card-actions">
            <a class="btn-pink" href="{{ route('requisitions.create') }}"><i class="fa-solid fa-plus"></i> New Requisition</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Guest House</th>
                    <th>District / Block</th>
                    <th>Stay Dates</th>
                    <th>Guests</th>
                    <th>Status</th>
                    <th>Admin Remark</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requisitions as $requisition)
                    <tr>
                        <td>{{ $requisitions->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="table-name">{{ $requisition->guest_house_name }}</div>
                            <div class="table-email">{{ \Illuminate\Support\Str::limit($requisition->purpose, 70) }}</div>
                        </td>
                        <td>{{ ($requisition->district?->name_en ?: '-') . ' / ' . ($requisition->block?->block_name_eng ?: '-') }}</td>
                        <td>{{ optional($requisition->stay_from)->format('d M Y') }} - {{ optional($requisition->stay_to)->format('d M Y') }}</td>
                        <td>{{ $requisition->total_guests }}</td>
                        <td><span class="badge-status {{ $requisition->status === 'approved' ? 'active' : ($requisition->status === 'rejected' ? 'inactive' : '') }}">{{ ucfirst($requisition->status) }}</span></td>
                        <td>{{ $requisition->admin_remarks ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px 20px;color:var(--text-light);">No requisitions submitted yet.</td>
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
