@extends('layouts.main')

@section('title', 'Pending Bypass Requests | JSHB')

@section('content')
    <div class="card">
        @if (session('success'))
            <div class="alert alert-success" style="margin: 20px 20px 0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" style="margin: 20px 20px 0;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="card-head">
            <div>
                <div class="card-title">Workflow Bypass Requests</div>
                <div class="card-subtitle">Review and approve requests to skip workflow steps</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Application No</th>
                        <th>Requested By</th>
                        <th>Bypass To (Target)</th>
                        <th>Reason</th>
                        <th>Date</th>
                        <th>Actions</th>
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
                                <div class="table-email">{{ $request->requestedBy->role_display_name ?? '' }}</div>
                            </td>
                            <td>
                                <div class="table-name">{{ $request->targetStep->step_name ?? 'N/A' }}</div>
                                <div class="table-email">Role: {{ $request->targetRole->name ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div style="max-width: 250px; white-space: normal; font-size: 12px;">
                                    {{ $request->reason }}
                                </div>
                            </td>
                            <td>{{ $request->created_at->format('d M, Y h:i A') }}</td>
                            <td>
                                <div class="action-btns" style="display: flex; gap: 8px;">
                                    <button type="button" class="btn btn-primary btn-sm" style="font-size: 12px; padding: 4px 10px; border-radius: 4px;" onclick="viewBypassDetails({{ $request->id }})">
                                        <i class="fa-solid fa-eye"></i> View Details
                                    </button>

                                    <form action="{{ route('admin.bypass-requests.approve', $request->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to approve this bypass? The application will be forwarded automatically.');">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" style="font-size: 12px; padding: 4px 10px; border-radius: 4px;">
                                            <i class="fa-solid fa-check"></i> Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.bypass-requests.reject', $request->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to reject this bypass?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" style="font-size: 12px; padding: 4px 10px; border-radius: 4px;">
                                            <i class="fa-solid fa-xmark"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center" style="padding: 40px 0;">
                                <div style="color: #64748b; margin-bottom: 10px;">
                                    <i class="fa-regular fa-folder-open" style="font-size: 32px; color: #cbd5e1;"></i>
                                </div>
                                <div>No pending bypass requests found.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <!-- Modals for details -->
    @foreach($bypassRequests as $request)
    <div class="modal fade" id="bypassDetailModal{{ $request->id }}" tabindex="-1" aria-labelledby="bypassDetailModalLabel{{ $request->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content" style="border: none; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background: #34495e; color: white; border-top-left-radius: 12px; border-top-right-radius: 12px; padding: 15px 20px;">
                    <h5 class="modal-title" id="bypassDetailModalLabel{{ $request->id }}">
                        <i class="fa-solid fa-file-invoice" style="margin-right: 8px; color: #f1c40f;"></i> 
                        Bypass Request Details - {{ $request->application->application_no ?? 'N/A' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 25px; background-color: #f8f9fa;">
                    
                    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 20px; border-left: 4px solid #f39c12;">
                        <h6 style="margin-top: 0; color: #d35400; font-weight: 700; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
                            <i class="fa-solid fa-comment-dots" style="margin-right: 5px;"></i> Bypass Reason
                        </h6>
                        <p style="margin: 0; font-size: 15px; color: #333; line-height: 1.6;">{{ $request->reason }}</p>
                    </div>

                    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #3498db;">
                        <h6 style="margin-top: 0; color: #2980b9; font-weight: 700; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
                            <i class="fa-solid fa-list-check" style="margin-right: 5px;"></i> Recent Application Notes
                        </h6>
                        
                        @if($request->application && $request->application->notes && $request->application->notes->count() > 0)
                            <div style="max-height: 300px; overflow-y: auto; padding-right: 10px;">
                                @foreach($request->application->notes as $note)
                                    <div style="margin-bottom: 15px; padding: 12px; background: #f8f9fa; border-radius: 6px; border: 1px solid #e9ecef;">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                            <strong style="color: #2c3e50; font-size: 13px;">
                                                <i class="fa-solid fa-user-tie" style="color: #7f8c8d; margin-right: 5px;"></i> 
                                                {{ $note->user->name ?? 'Unknown' }} 
                                                <span style="font-weight: normal; color: #7f8c8d;">({{ $note->user->role_display_name ?? '' }})</span>
                                            </strong>
                                            <span style="font-size: 11px; color: #95a5a6;">
                                                <i class="fa-regular fa-clock"></i> {{ $note->created_at->format('d M Y, h:i A') }}
                                            </span>
                                        </div>
                                        <div style="font-size: 14px; color: #444;">
                                            {!! $note->remarks !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p style="margin: 0; font-style: italic; color: #7f8c8d;">No notes found for this application.</p>
                        @endif
                    </div>

                </div>
                <div class="modal-footer" style="border-top: 1px solid #eee; padding: 15px 20px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <style>
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: translate(0, -50px);
        }
        .modal.show .modal-dialog {
            transform: none;
        }
    </style>

    <script>
        function viewBypassDetails(id) {
            var myModal = new bootstrap.Modal(document.getElementById('bypassDetailModal' + id), {
                keyboard: true
            });
            myModal.show();
        }
    </script>
@endsection
