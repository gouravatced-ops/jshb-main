@extends('layouts.main')

@section('title', 'My Notifications | JSHB')

@section('content')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3>My Notifications</h3>
            <p class="text-muted">View and manage all your notifications.</p>
        </div>
        <div>
            <button class="btn btn-primary" onclick="markAllNotificationsRead(); setTimeout(() => location.reload(), 500);">Mark All as Read</button>
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 50px;">Status</th>
                        <th style="width: 150px;">Date & Time</th>
                        <th>Subject</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notif)
                    <tr style="{{ $notif->is_read ? 'opacity: 0.7; background-color: #f9f9f9;' : 'font-weight: 600;' }}">
                        <td class="text-center">
                            @if($notif->notification_type == 'success')
                                <i class="fa-solid fa-circle-check text-success" style="font-size: 1.2rem;"></i>
                            @elseif($notif->notification_type == 'warning' || $notif->notification_type == 'document_request')
                                <i class="fa-solid fa-triangle-exclamation text-warning" style="font-size: 1.2rem;"></i>
                            @else
                                <i class="fa-solid fa-bell text-info" style="font-size: 1.2rem;"></i>
                            @endif
                        </td>
                        <td>
                            <div>{{ $notif->created_at->format('d M, Y') }}</div>
                            <small class="text-muted">{{ $notif->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            {{ $notif->subject }}
                            @if(!$notif->is_read)
                                <span class="badge bg-danger ms-2 unread-indicator" style="font-size: 10px; padding: 3px 6px;">New</span>
                            @endif
                        </td>
                        <td>
                            @if($notif->link)
                                <a href="{{ $notif->link }}" style="text-decoration: none; color: inherit;">
                                    {{ $notif->message }}
                                </a>
                            @else
                                {{ $notif->message }}
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">You have no notifications.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($notifications->hasPages())
            <div class="mt-4 d-flex justify-content-end">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
