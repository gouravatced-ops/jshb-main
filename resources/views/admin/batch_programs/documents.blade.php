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
    .status-badge.completed { background: #dcfce7; color: #166534; }
    .status-badge.failed { background: #fee2e2; color: #991b1b; }
    .status-badge.pending { background: #fef9c3; color: #854d0e; }
    .status-badge.processing { background: #dbeafe; color: #1e40af; }
</style>

<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">Document Generation Queues</div>
            <div class="card-subtitle">Monitor PDF generation requests grouped by Date</div>
        </div>
    </div>
    
    <div class="card-body" style="padding: 20px;">
        @forelse($groupedDocuments as $date => $documents)
            <div class="accordion">
                <div class="accordion-header" onclick="toggleAccordion('date-{{ $date }}')">
                    <div>
                        <i class="fa-regular fa-calendar-days" style="margin-right: 8px; color: var(--primary-color);"></i>
                        Date: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                    </div>
                    <div style="font-size: 14px; font-weight: normal; color: #64748b;">
                        <span style="background: #e2e8f0; padding: 2px 8px; border-radius: 12px;">{{ $documents->count() }} Documents Queued</span>
                    </div>
                </div>
                <div class="accordion-body" id="date-{{ $date }}">
                    <div class="table-responsive">
                        <table class="ep-table">
                            <thead>
                                <tr>
                                    <th>Application ID / No</th>
                                    <th>Document Type</th>
                                    <th>Queued At</th>
                                    <th>Completed At</th>
                                    <th>Status</th>
                                    <th>Action By</th>
                                    <th>Error Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $doc)
                                    <tr>
                                        <td>
                                            App #{{ $doc->application_id }}<br>
                                            <span style="font-size: 11px; color: gray;">{{ $doc->application?->application_no }}</span>
                                        </td>
                                        <td>{{ ucwords(str_replace('_', ' ', $doc->document_type)) }}</td>
                                        <td>{{ $doc->queued_at ? \Carbon\Carbon::parse($doc->queued_at)->format('H:i:s') : '-' }}</td>
                                        <td>{{ $doc->completed_at ? \Carbon\Carbon::parse($doc->completed_at)->format('H:i:s') : '-' }}</td>
                                        <td>
                                            <span class="status-badge {{ $doc->status }}">{{ ucfirst($doc->status) }}</span>
                                        </td>
                                        <td>{{ $doc->actionBy?->name ?? 'System' }}</td>
                                        <td style="color: red; font-size: 12px; max-width: 250px; white-space: normal;">
                                            {{ $doc->error_message ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 40px; color: #64748b;">
                <i class="fa-solid fa-file-pdf fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
                <p>No document generation requests found in the queue.</p>
            </div>
        @endforelse
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
