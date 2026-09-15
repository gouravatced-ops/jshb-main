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
        padding: 20px;
        display: none;
        border-top: 1px solid #e2e8f0;
    }
    .accordion-body.show {
        display: block;
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
                                    <th>Recipient Role</th>
                                    <th>Recipient Name</th>
                                    <th>Status</th>
                                    <th>Sent At</th>
                                    <th>Error (If any)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($batch->details as $detail)
                                    <tr>
                                        <td>{{ $detail->application_id }}</td>
                                        <td>{{ $detail->target_role_id }}</td>
                                        <td>User #{{ $detail->target_user_id }}</td>
                                        <td>
                                            <span class="status-badge {{ $detail->status }}">{{ ucfirst($detail->status) }}</span>
                                        </td>
                                        <td>{{ $detail->sent_at ? \Carbon\Carbon::parse($detail->sent_at)->format('d M, H:i') : '-' }}</td>
                                        <td style="color: red; font-size: 12px;">{{ $detail->error_message ?? '-' }}</td>
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
</script>
@endsection
