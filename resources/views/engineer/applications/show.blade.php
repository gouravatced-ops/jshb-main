@extends('layouts.main')
@section('title', 'Review Application | JSHB')
@section('content')

<style>
    /* Refined Layout */
    .compact-wrapper {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 20px;
        margin-top: 15px;
    }

    .compact-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #eef0f2;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .compact-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .compact-card-header {
        padding: 12px 16px;
        font-size: 15px;
        font-weight: 600;
        border-bottom: 1px solid #eaeaea;
        display: flex;
        justify-content: space-between;
        align-items: center;
        letter-spacing: 0.3px;
    }
    
    /* Distinct Header Colors with Subtle Gradients */
    .header-blue { background: linear-gradient(135deg, #e3f2fd, #bbdefb); color: #0d47a1; border-bottom-color: #90caf9; }
    .header-green { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #1b5e20; border-bottom-color: #a5d6a7; }
    .header-orange { background: linear-gradient(135deg, #fff3e0, #ffe0b2); color: #e65100; border-bottom-color: #ffcc80; }
    .header-purple { background: linear-gradient(135deg, #f3e5f5, #e1bee7); color: #4a148c; border-bottom-color: #ce93d8; }
    .header-gray { background: linear-gradient(135deg, #f5f5f5, #e0e0e0); color: #424242; border-bottom-color: #eeeeee; }

    .compact-card-body {
        padding: 15px 16px;
        flex-grow: 1;
        overflow-y: auto;
        font-size: 14px;
        color: #444;
    }

    .compact-table {
        width: 100%;
        border-collapse: collapse;
    }

    .compact-table th, .compact-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #f0f0f0;
        text-align: left;
    }

    .compact-table th {
        color: #777;
        font-weight: 600;
        background: #fcfcfc;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .compact-table tr:last-child td {
        border-bottom: none;
    }

    .data-pair {
        display: flex;
        margin-bottom: 10px;
        border-bottom: 1px dashed #f0f0f0;
        padding-bottom: 8px;
        align-items: center;
    }

    .data-pair:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .data-label {
        color: #666;
        width: 35%;
        font-weight: 500;
        font-size: 13px;
    }

    .data-value {
        color: #222;
        width: 65%;
        font-weight: 600;
        word-break: break-word;
    }

    .badge-compact {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-normal { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .badge-urgent { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    .badge-overdue { background: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2; }

    .btn-compact {
        background: #007bff;
        color: white;
        padding: 5px 12px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 500;
        transition: opacity 0.2s, transform 0.1s;
        box-shadow: 0 2px 4px rgba(0,123,255,0.2);
    }
    .btn-compact:hover { opacity: 0.9; color: white; transform: translateY(-1px); }

    .notes-list { list-style: none; padding: 0; margin: 0; }
    .note-item { border-bottom: 1px solid #f0f0f0; padding-bottom: 10px; margin-bottom: 10px; }
    .note-item:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
    .note-header { display: flex; justify-content: space-between; margin-bottom: 6px; align-items: center; }
    .note-author { font-weight: 600; color: #4a148c; font-size: 13px; }
    .note-date { color: #888; font-size: 12px; display: flex; align-items: center; gap: 4px; }
    .note-content { color: #333; line-height: 1.5; font-size: 13px; background: #fdfdfd; padding: 10px; border-radius: 6px; border: 1px solid #f5f5f5; }

    /* Layout Grids */
    .col-span-4 { grid-column: span 4; }
    .col-span-8 { grid-column: span 8; }
    .col-span-6 { grid-column: span 6; }
    .col-span-12 { grid-column: span 12; }
    
    @media (max-width: 992px) {
        .col-span-4, .col-span-8, .col-span-6 { grid-column: span 12; }
    }
</style>

<!-- Top Toolbar -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; background: #fff; padding: 12px 20px; border-radius: 8px; border: 1px solid #eaeaea; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
    <div>
        <h4 style="margin: 0; font-size: 16px; font-weight: 600; color: #2c3e50;">
            <i class="fa-solid fa-file-invoice" style="color: #3498db; margin-right: 8px;"></i> 
            Application No: <span style="color: #e74c3c;">{{ $application->application_no }}</span>
        </h4>
    </div>
    <div>
        <a href="{{ route('engineer.applications.index') }}" class="btn-compact" style="background: #6c757d; box-shadow: none;"><i class="fa-solid fa-arrow-left"></i> Back to List</a>
    </div>
</div>

@if(Auth::check() && $application->current_role_id == Auth::user()->role_id && $application->currentStep)
<div class="compact-card" style="margin-bottom: 15px; border-left: 4px solid #3498db;">
    <div class="compact-card-body" style="padding: 12px 20px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <strong style="color: #2c3e50; font-size: 14px; margin-right: 15px;">
                <i class="fa-solid fa-bolt" style="color: #f39c12;"></i> Action Required ({{ $application->currentStep->step_name }})
            </strong>
            <span style="color: #666; font-size: 13px;">Please review the details below and take appropriate action.</span>
        </div>
        <div style="display: flex; gap: 8px;">
            @if($application->currentStep->can_forward)
                <a href="{{ route('engineer.applications.action.form', ['application' => $application, 'action_type' => 'forward']) }}" class="btn-compact" style="background: #28a745; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-arrow-right"></i> Forward</a>
            @endif
            @if($application->currentStep->can_send_back)
                <a href="{{ route('engineer.applications.action.form', ['application' => $application, 'action_type' => 'send_back']) }}" class="btn-compact" style="background: #ffc107; color: #333; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-reply"></i> Send Back</a>
            @endif
            @if($application->currentStep->can_reject)
                <a href="{{ route('engineer.applications.action.form', ['application' => $application, 'action_type' => 'reject']) }}" class="btn-compact" style="background: #dc3545; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-times"></i> Reject</a>
            @endif
            @if($application->currentStep->action_type == 'approve' || $application->currentStep->action_type == 'verify')
                <a href="{{ route('engineer.applications.action.form', ['application' => $application, 'action_type' => $application->currentStep->action_type]) }}" class="btn-compact" style="background: #17a2b8; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-check"></i> {{ ucfirst($application->currentStep->action_type) }}</a>
            @endif
            @if($application->currentStep->can_add_note)
                <a href="{{ route('engineer.applications.action.form', ['application' => $application, 'action_type' => 'add_note']) }}" class="btn-compact" style="background: #6f42c1; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-comment-medical"></i> Add Note</a>
            @endif
            @if($application->currentStep->can_upload_document)
                <button class="btn-compact" style="background: #34495e;"><i class="fa-solid fa-upload"></i> Upload Doc</button>
            @endif
        </div>
    </div>
</div>
@endif

<div class="compact-wrapper">
    
    <!-- Application Details (col-6) -->
    <div class="compact-card col-span-6">
        <div class="compact-card-header header-blue">
            <span><i class="fa-solid fa-circle-info"></i> Application Details</span>
        </div>
        <div class="compact-card-body">
            <div class="data-pair">
                <div class="data-label">Application Type</div>
                <div class="data-value" style="text-transform: capitalize;">{{ str_replace('_', ' ', $application->application_type) }}</div>
            </div>
            <div class="data-pair">
                <div class="data-label">Current Status</div>
                <div class="data-value" style="text-transform: capitalize;">
                    <span style="background: #e3f2fd; color: #0d47a1; padding: 3px 8px; border-radius: 4px; font-size: 12px;">{{ str_replace('_', ' ', $application->status) }}</span>
                </div>
            </div>
            <div class="data-pair">
                <div class="data-label">Priority Level</div>
                <div class="data-value">
                    <span class="badge-compact {{ $application->priority == 'normal' ? 'badge-normal' : ($application->priority == 'urgent' ? 'badge-urgent' : 'badge-overdue') }}">
                        {{ ucfirst($application->priority) }}
                    </span>
                </div>
            </div>
            <div class="data-pair">
                <div class="data-label">Date Created</div>
                <div class="data-value">{{ $application->created_date ? $application->created_date->format('d-M-Y h:i A') : 'N/A' }}</div>
            </div>
            <div class="data-pair">
                <div class="data-label">Expected Completion</div>
                <div class="data-value">{{ $application->expected_completion_date ? $application->expected_completion_date->format('d-M-Y') : 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Allottee Details (col-6) -->
    <div class="compact-card col-span-6">
        <div class="compact-card-header header-green">
            <span><i class="fa-solid fa-user-shield"></i> Allottee Information</span>
        </div>
        <div class="compact-card-body">
            @if($application->allottee)
                <div class="data-pair">
                    <div class="data-label">Applicant Name</div>
                    <div class="data-value">{{ trim(($application->allottee->prefix ?? '') . ' ' . ($application->allottee->allottee_name ?? '') . ' ' . ($application->allottee->allottee_middle_name ?? '') . ' ' . ($application->allottee->allottee_surname ?? '')) ?: '-' }}</div>
                </div>
                <div class="data-pair">
                    <div class="data-label">Hindi Name</div>
                    <div class="data-value" style="font-family: 'KrutiDev', sans-serif, Arial;">{{ trim(($application->allottee->allottee_prefix_hindi ?? '') . ' ' . ($application->allottee->allottee_name_hindi ?? '') . ' ' . ($application->allottee->allottee_middle_hindi ?? '') . ' ' . ($application->allottee->allottee_surname_hindi ?? '')) ?: '-' }}</div>
                </div>
                <div class="data-pair">
                    <div class="data-label">Property Number</div>
                    <div class="data-value" style="color: #1b5e20;">{{ $application->allottee->property_number ?? 'N/A' }}</div>
                </div>
                <div class="data-pair">
                    <div class="data-label">Allotment No</div>
                    <div class="data-value">{{ $application->allottee->allotment_no ?? 'N/A' }}</div>
                </div>
            @else
                <div style="text-align: center; color: #999; padding: 20px;">No Allottee Information Available</div>
            @endif
        </div>
    </div>

    <!-- Documents (col-6) -->
    <div class="compact-card col-span-6" style="max-height: 380px;">
        <div class="compact-card-header header-orange">
            <span><i class="fa-solid fa-folder-open"></i> Attached Documents</span>
            <span class="badge-compact" style="background: rgba(255,255,255,0.6); color: #e65100;">{{ $application->documents ? $application->documents->count() : 0 }} Files</span>
        </div>
        <div class="compact-card-body" style="padding: 0;">
            @if($application->documents && $application->documents->count() > 0)
            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Document Name</th>
                            <th>Type</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($application->documents as $doc)
                        <tr>
                            <td style="font-weight: 500;">
                                {{ $doc->document_name }}
                                <div style="color: #888; font-size: 11px; margin-top: 2px; font-weight: normal;">{{ \Illuminate\Support\Str::limit($doc->file_name, 25) }}</div>
                            </td>
                            <td style="text-transform: capitalize; color: #555;">{{ str_replace('_', ' ', $doc->document_type) }}</td>
                            <td style="text-align: right;">
                                @if($doc->file_path)
                                    @php
                                        $docBaseUrl = rtrim(str_replace(['api/upload.php', '/api/upload.php'], '', env('DOC_API_URL', '')), '/');
                                        $previewSrc = $docBaseUrl . '/' . ltrim($doc->file_path, '/');
                                    @endphp
                                    <button type="button" onclick="viewDocument('{{ $previewSrc }}', '{{ addslashes($doc->document_name) }}')" class="btn-compact" style="border: none; cursor: pointer;">
                                        <i class="fa-solid fa-eye" style="font-size: 10px;"></i> View
                                    </button>
                                @else
                                    <span style="color: #999; font-size: 12px; font-style: italic;">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div style="text-align: center; color: #999; padding: 40px 20px;">
                <i class="fa-solid fa-file-excel" style="font-size: 32px; color: #ddd; margin-bottom: 10px;"></i>
                <div>No documents attached</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Application Notes (col-6) -->
    <div class="compact-card col-span-6" style="max-height: 380px;">
        <div class="compact-card-header header-purple">
            <span><i class="fa-solid fa-comments"></i> Application Notes & Remarks</span>
            <span class="badge-compact" style="background: rgba(255,255,255,0.6); color: #4a148c;">{{ $application->notes ? $application->notes->count() : 0 }} Notes</span>
        </div>
        <div class="compact-card-body">
            @if($application->notes && $application->notes->count() > 0)
                <ul class="notes-list">
                    @foreach($application->notes as $note)
                    <li class="note-item">
                        <div class="note-header">
                            <span class="note-author"><i class="fa-solid fa-user-circle"></i> {{ $note->createdBy ? $note->createdBy->name : 'System' }}</span>
                            <span class="note-date"><i class="fa-regular fa-clock"></i> {{ $note->created_at ? $note->created_at->format('d-M-Y h:i A') : '' }}</span>
                        </div>
                        <div class="note-content">{{ $note->remarks }}</div>
                    </li>
                    @endforeach
                </ul>
            @else
                <div style="text-align: center; color: #999; padding: 40px 20px;">
                    <i class="fa-regular fa-comment-dots" style="font-size: 32px; color: #ddd; margin-bottom: 10px;"></i>
                    <div>No remarks available</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Movements Timeline (col-12) -->
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-gray">
            <span><i class="fa-solid fa-timeline"></i> Application Movement History</span>
            <span class="badge-compact" style="background: rgba(0,0,0,0.1); color: #333;">{{ $application->movements ? $application->movements->count() : 0 }} Steps</span>
        </div>
        <div class="compact-card-body" style="padding: 0;">
            @if($application->movements && $application->movements->count() > 0)
            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th style="width: 195px;">Date & Time</th>
                            <th style="width: 120px;">Action</th>
                            <th style="width: 200px;">Transferred From</th>
                            <th style="width: 200px;">Transferred To</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($application->movements as $movement)
                        <tr>
                            <td style="color: #555;"><i class="fa-regular fa-calendar" style="color: #aaa; margin-right: 4px;"></i> {{ $movement->movement_date ? $movement->movement_date->format('d-M-Y h:i A') : 'N/A' }}</td>
                            <td>
                                <span class="badge-compact" style="background: #e0e0e0; font-weight: 500; font-size: 11px;">{{ strtoupper(str_replace('_', ' ', $movement->action_type)) }}</span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #333;">{{ $movement->fromUser ? $movement->fromUser->name : 'System Generated' }}</div>
                                @if($movement->fromRole)
                                    <div style="color: #888; font-size: 11px;">{{ $movement->fromRole->name }}</div>
                                @endif
                            </td>
                            <td>
                                @if($movement->toUser || $movement->toRole)
                                    <div style="font-weight: 600; color: #333;">{{ $movement->toUser ? $movement->toUser->name : 'Unassigned' }}</div>
                                    @if($movement->toRole)
                                        <div style="color: #888; font-size: 11px;">{{ $movement->toRole->name }}</div>
                                    @endif
                                @else
                                    <span style="color: #aaa; font-style: italic;">-</span>
                                @endif
                            </td>
                            <td style="color: #444;">
                                @if($movement->remarks)
                                    {{ $movement->remarks }}
                                @else
                                    <span style="color: #bbb; font-style: italic;">No remarks</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div style="text-align: center; color: #999; padding: 40px 20px;">
                <i class="fa-solid fa-arrow-right-arrow-left" style="font-size: 32px; color: #ddd; margin-bottom: 10px;"></i>
                <div>No application movements recorded yet.</div>
            </div>
            @endif
        </div>
    </div>

</div>

<!-- Document Viewer Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
        <h5 class="modal-title" id="documentModalLabel" style="font-weight: 600; color: #333;">View Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="height: 70vh; padding: 0; display: flex; justify-content: center; align-items: center; background: #e9ecef;">
        <iframe id="documentIframe" src="" style="width: 100%; height: 100%; border: none; display: none;"></iframe>
        <img id="documentImage" src="" style="max-width: 100%; max-height: 100%; object-fit: contain; display: none;" alt="Document">
      </div>
      <div class="modal-footer" style="padding: 10px;">
          <a id="documentDownloadBtn" href="#" target="_blank" class="btn btn-primary btn-sm"><i class="fa-solid fa-download"></i> Download</a>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
    function viewDocument(url, name) {
        document.getElementById('documentModalLabel').innerText = name;
        document.getElementById('documentDownloadBtn').href = url;
        
        var iframe = document.getElementById('documentIframe');
        var image = document.getElementById('documentImage');
        
        // Detect file type based on extension
        var lowerUrl = url.toLowerCase();
        if(lowerUrl.match(/\.(jpeg|jpg|gif|png|webp)(\?.*)?$/) != null) {
            // It's an image
            iframe.style.display = 'none';
            iframe.src = '';
            
            image.style.display = 'block';
            image.src = url;
        } else {
            // Assume PDF or other embeddable document
            image.style.display = 'none';
            image.src = '';
            
            iframe.style.display = 'block';
            iframe.src = url;
        }
        
        // Use Bootstrap modal instance
        var docModal = new bootstrap.Modal(document.getElementById('documentModal'));
        docModal.show();
    }
    
    // Clear iframe and image src when modal is closed to stop rendering overhead
    document.getElementById('documentModal').addEventListener('hidden.bs.modal', function (event) {
        document.getElementById('documentIframe').src = '';
        document.getElementById('documentImage').src = '';
    });
</script>

@endsection
