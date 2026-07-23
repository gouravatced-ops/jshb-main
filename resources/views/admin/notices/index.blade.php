@extends('layouts.main')

@section('title', 'Notices & Announcements | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <h4 class="card-title">Notices & Announcements</h4>
            <p class="card-subtitle">Manage system wide notices</p>
        </div>
        <div>
            <a href="{{ route('admin.notices.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Create Notice
            </a>
        </div>
    </div>
    
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Target</th>
                        <th>Validity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notices as $notice)
                        <tr>
                            <td>
                                <strong>{{ $notice->title }}</strong>
                                <br><small class="text-muted">{{ Str::limit(strip_tags(html_entity_decode($notice->message)), 50) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($notice->notice_type) }}</span>
                            </td>
                            <td>
                                @if(!$notice->target_user_type && !$notice->target_division_id && !$notice->target_user_id)
                                    <span class="badge bg-primary">All Users</span>
                                @else
                                    @if($notice->target_user_type) <span class="badge bg-info">{{ ucfirst($notice->target_user_type) }}</span> @endif
                                    @if($notice->division) <span class="badge bg-warning">{{ $notice->division->name }}</span> @endif
                                    @if($notice->target_user_id) 
                                        @php
                                            $ids = json_decode($notice->target_user_id, true);
                                            $count = is_array($ids) ? count($ids) : 1;
                                        @endphp
                                        <span class="badge bg-dark">{{ $count }} Specific Member(s)</span> 
                                    @endif
                                @endif
                            </td>
                            <td>
                                <small>
                                    <strong>Start:</strong> {{ $notice->start_date ? $notice->start_date->format('d M Y') : 'N/A' }}<br>
                                    <strong>End:</strong> {{ $notice->end_date ? $notice->end_date->format('d M Y') : 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('admin.notices.edit', $notice) }}" class="btn-primary btn-sm" style="padding: 4px 8px; margin-right: 4px; text-decoration: none;">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notice?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm" style="padding: 4px 8px;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No notices found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $notices->links() }}
        </div>
    </div>
</div>
@endsection
