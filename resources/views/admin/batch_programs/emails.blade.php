@extends('layouts.main')

@section('content')
<style>
    .accordion {
        background-color: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 15px;
        overflow: hidden;
    }
    .accordion-header {
        background-color: #f8fafc;
        padding: 15px 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        border-bottom: 1px solid transparent;
    }
    .accordion-header:hover {
        background-color: #f1f5f9;
    }
    .accordion-body {
        padding: 0 20px;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-top: none;
    }
    .accordion-body.show {
        padding: 20px;
        max-height: 2000px; /* Arbitrary large max-height */
        border-top: 1px solid #e2e8f0;
    }
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }
    .status-badge.sent { background: #dcfce7; color: #166534; }
    .status-badge.failed { background: #fee2e2; color: #991b1b; }
    .status-badge.queued { background: #fef9c3; color: #854d0e; }
    .ep-table th {
        background-color: #0f172a;
        color: #ffffff;
        font-weight: 600;
        padding: 10px 12px;
        font-size: 13px;
        text-transform: uppercase;
    }
    .ep-table td {
        padding: 8px 12px;
        font-size: 13px;
        border-bottom: 1px solid #f1f5f9;
    }
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); 
        z-index: 9999; display: flex; justify-content: center; align-items: center;
        opacity: 0; visibility: hidden; backdrop-filter: blur(4px);
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .modal-overlay.show {
        opacity: 1; visibility: visible;
    }
    .modal-content-box {
        background: #fff; padding: 25px; border-radius: 12px; width: 90%; max-width: 850px; max-height: 85vh; 
        overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        transform: scale(0.95) translateY(20px);
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .modal-overlay.show .modal-content-box {
        transform: scale(1) translateY(0);
    }
    .modal-header-box {
        display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 15px; font-size: 16px; font-weight: bold;
    }
    .modal-close { cursor: pointer; color: #ef4444; font-size: 18px; }
    .btn-xs { padding: 4px 8px; font-size: 11px; border-radius: 4px; border: none; cursor: pointer; }
    .btn-view { background: #3b82f6; color: #fff; }
    .btn-err { background: #ef4444; color: #fff; }
</style>

<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">Batch Email Queues</div>
            <div class="card-subtitle">Monitor scheduled reminder emails sent to engineers</div>
        </div>
    </div>
    
    <div class="card-body" style="padding: 20px;">
        @forelse($batches as $batch)
            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion('batch-{{ $batch->id }}')">
                    <div>
                        <i class="fa-solid fa-layer-group" style="margin-right: 8px; color: var(--primary-color);"></i>
                        Batch #{{ $batch->id }} - {{ $batch->command_name }}
                    </div>
                    <div style="font-size: 14px; font-weight: normal; color: #64748b;">
                        <span><i class="fa-regular fa-clock"></i> {{ $batch->created_at->format('d M Y, h:i A') }}</span>
                        <span style="margin-left: 15px; background: #e2e8f0; padding: 2px 8px; border-radius: 12px;">{{ $batch->total_jobs }} Emails</span>
                    </div>
                </div>
                <div class="accordion-body" id="batch-{{ $batch->id }}">
                    <div class="table-responsive">
                        <table class="ep-table">
                            <thead>
                                <tr>
                                    <th>Application ID</th>
                                    <th>Recipient Email</th>
                                    <th>CC Email</th>
                                    <th>Status</th>
                                    <th>Sent At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($batch->details as $detail)
                                    <tr>
                                        <td>{{ $detail->application_id ?? 'N/A' }}</td>
                                        <td>{{ $detail->recipient_email ?? 'N/A' }}</td>
                                        <td>{{ $detail->cc_email ?? 'N/A' }}</td>
                                        <td>
                                            <span class="status-badge {{ $detail->status }}">{{ ucfirst($detail->status) }}</span>
                                        </td>
                                        <td>{{ $detail->sent_at ? \Carbon\Carbon::parse($detail->sent_at)->format('d M, H:i') : '-' }}</td>
                                        <td>
                                            @if($detail->mail_body)
                                                <button type="button" class="btn-xs btn-view" onclick="openModal('mail-body-{{ $detail->id }}', 'Email Preview')"><i class="fa-solid fa-eye"></i> View</button>
                                                <div id="mail-body-{{ $detail->id }}" style="display:none;">{!! $detail->mail_body !!}</div>
                                            @endif
                                            @if($detail->error_message)
                                                <button type="button" class="btn-xs btn-err" onclick="openModal('error-msg-{{ $detail->id }}', 'Error Details')" title="View Error"><i class="fa-solid fa-triangle-exclamation"></i> Error</button>
                                                <div id="error-msg-{{ $detail->id }}" style="display:none;"><pre style="white-space: pre-wrap; font-family: monospace;">{{ $detail->error_message }}</pre></div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center;">No details found for this batch.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 40px; color: #64748b;">
                <i class="fa-solid fa-inbox fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
                <p>No email batches have been run yet.</p>
            </div>
        @endforelse

        <div style="margin-top: 20px;">
            {{ $batches->links() }}
        </div>
    </div>
</div>

<script>
    function toggleAccordion(id) {
        const body = document.getElementById(id);
        if (body.classList.contains('show')) {
            body.classList.remove('show');
        } else {
            body.classList.add('show');
        }
    }

    function openModal(contentId, title) {
        document.getElementById('modal-title').innerText = title;
        document.getElementById('modal-body').innerHTML = document.getElementById(contentId).innerHTML;
        document.getElementById('custom-modal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('custom-modal').classList.remove('show');
        setTimeout(() => {
            document.getElementById('modal-body').innerHTML = '';
        }, 300);
    }
</script>

<!-- Custom Modal -->
<div class="modal-overlay" id="custom-modal">
    <div class="modal-content-box">
        <div class="modal-header-box">
            <span id="modal-title">Email Preview</span>
            <i class="fa-solid fa-circle-xmark modal-close" onclick="closeModal()"></i>
        </div>
        <div id="modal-body" style="padding: 10px;">
            <!-- Content gets loaded here -->
        </div>
    </div>
</div>
@endsection
