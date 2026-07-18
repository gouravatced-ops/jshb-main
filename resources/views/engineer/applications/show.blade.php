@extends('layouts.main')
@section('title', 'Review Application | JSHB')
@section('content')

@include('components.partials.compact-css')

<!-- Top Toolbar -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; background: #fff; padding: 12px 20px; border-radius: 8px; border: 1px solid #eaeaea; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
    <div>
        <h4 style="margin: 0; font-size: 16px; font-weight: 600; color: #2c3e50;">
            <i class="fa-solid fa-file-invoice" style="color: #3498db; margin-right: 8px;"></i> 
            Application No: <span style="color: #e74c3c;">{{ $application->application_no }}</span>
        </h4>
    </div>
    <div style="display: flex; gap: 10px;">
        <form action="{{ route('engineer.applications.reset', $application->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely RESET this application? All notes and movements will be deleted and it will go back to the first step. This cannot be undone.');">
            @csrf
            <button type="submit" class="btn-compact" style="background: #dc3545; border: none; box-shadow: none; color: white;"><i class="fa-solid fa-rotate-left"></i> Reset Workflow</button>
        </form>
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
            @if($application->currentStep->action_type == 'approve')
                <a href="{{ route('engineer.applications.action.form', ['application' => $application, 'action_type' => 'approve']) }}" class="btn-compact" style="background: #17a2b8; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-check"></i> Approve</a>
            @endif
            @if($application->currentStep->can_upload_document)
                <button class="btn-compact" data-bs-toggle="modal" data-bs-target="#uploadDocModal" style="background: #34495e;"><i class="fa-solid fa-upload"></i> Upload Doc</button>
            @endif
            <a href="{{ route('engineer.applications.action.form', ['application' => $application, 'action_type' => 'add_note']) }}" class="btn-compact" style="background: #6c757d; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-comment-dots"></i> Add Note</a>
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
                        @foreach($application->documents->sortByDesc('id') as $doc)
                        <tr>
                            <td style="font-weight: 500;">
                                {{ $doc->document_name }}
                                <div style="color: #888; font-size: 11px; margin-top: 2px; font-weight: normal;">
                                    {{ \Illuminate\Support\Str::limit($doc->file_name, 25) }}
                                    <br>
                                    <span style="color: #0056b3; font-weight: 500;">Uploaded by: {{ $doc->uploader_name }} ({{ ucfirst($doc->uploader_type ?? 'Staff') }})</span>
                                </div>
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
        <div class="compact-card-header header-purple" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span><i class="fa-solid fa-comments"></i> Application Notes</span>
                <span class="badge-compact" style="background: rgba(255,255,255,0.6); color: #4a148c; margin-left: 5px;">{{ $application->notes ? $application->notes->count() : 0 }} Notes</span>
            </div>
            @if($application->notes && $application->notes->count() > 0)
                <a href="{{ route('engineer.applications.notes.pdf', $application) }}" target="_blank" class="btn-compact" style="background: rgb(200 14 14);color: #fff;border: 1px solid rgba(255,255,255,0.3);text-decoration: none;padding: 4px 10px;font-size: 11px;">
                    <i class="fa-solid fa-file-pdf"></i> Preview PDF
                </a>
            @endif
        </div>
        <div class="compact-card-body">
            @if($application->notes && $application->notes->count() > 0)
                <ul class="notes-list">
                    @foreach($application->notes as $note)
                    <li class="note-item">
                        <div class="note-header">
                            <span class="note-author">
                                <i class="fa-solid fa-user-circle"></i> 
                                {{ $note->user ? $note->user->name : 'System' }}
                                @if($note->role)
                                    , {{ $note->role->name }}
                                @endif
                                @if($note->user && $note->user->division)
                                    ({{ $note->user->division->name }})
                                @endif
                            </span>
                            <span class="note-date"><i class="fa-regular fa-clock"></i> {{ $note->created_at ? $note->created_at->format('d-M-Y h:i A') : '' }}</span>
                        </div>
                        <div class="note-content">{!! $note->remarks !!}</div>
                    </li>
                    @endforeach
                </ul>
            @else
                <div style="text-align: center; color: #999; padding: 40px 20px;">
                    <i class="fa-regular fa-comment-dots" style="font-size: 32px; color: #ddd; margin-bottom: 10px;"></i>
                    <div>No application notes available</div>
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

<!-- Upload Document Modal -->
<style>
  /* Custom animation for modal to slide from top to center with bounce */
  #uploadDocModal.fade .modal-dialog {
    transform: translateY(-50px) scale(0.9);
    opacity: 0;
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.4s ease;
  }
  #uploadDocModal.show .modal-dialog {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
  
  .upload-modal-content {
      border: none;
      border-radius: 12px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      overflow: hidden;
  }
  .upload-modal-header {
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      color: white;
      padding: 18px 24px;
      border-bottom: none;
  }
  .upload-modal-header .btn-close {
      filter: invert(1) grayscale(100%) brightness(200%);
      opacity: 0.8;
  }
  .upload-modal-header .btn-close:hover {
      opacity: 1;
  }
  .upload-modal-title {
      font-weight: 600;
      font-size: 1.2rem;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 10px;
  }
  .upload-modal-body {
      padding: 24px;
      background: #fdfdfd;
  }
  .upload-input-group {
      margin-bottom: 20px;
  }
  .upload-input-group label {
      font-weight: 600;
      font-size: 0.9rem;
      color: #34495e;
      margin-bottom: 8px;
      display: block;
  }
  .upload-control {
      border: 1px solid #dce1e6;
      border-radius: 8px;
      padding: 12px 14px;
      font-size: 0.95rem;
      transition: all 0.2s ease;
      background: #fff;
      width: 100%;
  }
  .upload-control:focus {
      border-color: #2a5298;
      box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.15);
      outline: none;
  }
  .upload-file-wrapper {
      position: relative;
      border: 2px dashed #cbd3da;
      border-radius: 10px;
      padding: 30px 20px;
      text-align: center;
      background: #f8f9fa;
      transition: all 0.3s ease;
  }
  .upload-file-wrapper:hover {
      border-color: #2a5298;
      background: #f1f4f8;
  }
  .upload-file-input {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      cursor: pointer;
  }
  .upload-file-icon {
      font-size: 2.5rem;
      color: #2a5298;
      margin-bottom: 12px;
  }
  .upload-file-text {
      color: #495057;
      font-size: 1rem;
      font-weight: 500;
      margin: 0;
  }
  .upload-file-subtext {
      color: #868e96;
      font-size: 0.85rem;
      margin-top: 6px;
  }
  .upload-modal-footer {
      padding: 16px 24px;
      background: #f8f9fa;
      border-top: 1px solid #eaeaea;
      display: flex;
      justify-content: flex-end;
      gap: 12px;
  }
</style>

<div class="modal fade" id="uploadDocModal" tabindex="-1" aria-labelledby="uploadDocModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content upload-modal-content">
      <div class="modal-header upload-modal-header">
        <h5 class="modal-title upload-modal-title" id="uploadDocModalLabel">
            <i class="fa-solid fa-cloud-arrow-up"></i> Upload New Document
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('engineer.applications.upload-document', $application) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body upload-modal-body">
            <div class="upload-input-group">
                <label>Select File <span class="text-danger">*</span></label>
                <div class="upload-file-wrapper">
                    <input type="file" name="document_file" class="upload-file-input" required accept=".pdf,.jpg,.jpeg,.png">
                    <div class="upload-file-icon">
                        <i class="fa-regular fa-file-pdf"></i>
                    </div>
                    <p class="upload-file-text">Click to browse or drag file here</p>
                    <p class="upload-file-subtext">Supported formats: PDF, JPG, PNG (Max 5MB)</p>
                </div>
            </div>
        </div>
        <div class="modal-footer upload-modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border: 1px solid #dce1e6; font-weight: 500;">Cancel</button>
            <button type="submit" class="btn btn-primary" style="background: #2a5298; border: none; font-weight: 500; padding: 8px 20px;">
                <i class="fa-solid fa-upload"></i> Upload Document
            </button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('.upload-file-input');
    const fileText = document.querySelector('.upload-file-text');
    const fileIcon = document.querySelector('.upload-file-icon');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files.length > 0) {
                const fileName = this.files[0].name;
                fileText.innerHTML = `<span style="color: #28a745; font-weight: 600;">Selected: ${fileName}</span>`;
                fileIcon.innerHTML = `<i class="fa-solid fa-file-circle-check" style="color: #28a745;"></i>`;
            } else {
                fileText.innerHTML = `Click to browse or drag file here`;
                fileIcon.innerHTML = `<i class="fa-regular fa-file-pdf"></i>`;
            }
        });
    }
});
</script>

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
