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
        <!-- <form action="{{ route('engineer.applications.reset', $application->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely RESET this application? All notes and movements will be deleted and it will go back to the first step. This cannot be undone.');">
            @csrf
            <button type="submit" class="btn-compact" style="background: #dc3545; border: none; box-shadow: none; color: white;"><i class="fa-solid fa-rotate-left"></i> Reset Workflow</button>
        </form> -->
        <a href="{{ route('engineer.applications.index') }}" class="btn-compact" style="background: #6c757d; box-shadow: none;"><i class="fa-solid fa-arrow-left"></i> Back to List</a>
    </div>
</div>

@if(Auth::check() && $application->current_role_id == Auth::user()->role_id && $application->currentStep)
<div class="compact-card" style="margin-bottom: 15px; border-left: 4px solid #3498db;">
    <div class="compact-card-body" style="padding: 12px 20px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <strong style="color: #2c3e50; font-size: 14px; margin-right: 15px;">
                <i class="fa-solid fa-bolt" style="color: #f39c12;"></i> Action Required ({{ $application->currentStep->step_name }})
            </strong><br>
            <span style="color: #666; font-size: 13px;">Please review the details below and take appropriate action.</span>
        </div>
        <div style="display: flex; gap: 8px;">
            @if($application->currentStep->can_forward && $application->currentStep->action_type != 'approve')
            <a href="{{ route('engineer.applications.action.form', ['application' => $application, 'action_type' => 'forward']) }}" class="btn-compact" style="background: #28a745; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-arrow-right"></i> Forward</a>
            @endif
            @if($application->currentStep->can_send_back)
            <a href="{{ route('engineer.applications.action.form', ['application' => $application, 'action_type' => 'send_back']) }}" class="btn-compact" style="background: #ffc107; color: #333; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-reply"></i> Send Back</a>
            @endif
            @if($application->currentStep->can_reject)
            <a href="{{ route('engineer.applications.action.form', ['application' => $application, 'action_type' => 'reject']) }}" class="btn-compact" style="background: #dc3545; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-times"></i> Reject</a>
            @endif
            @if($application->currentStep->action_type == 'approve' && strtolower($application->status) !== 'completed')
            <a href="{{ route('engineer.applications.action.form', ['application' => $application, 'action_type' => 'approve']) }}" class="btn-compact" style="background: #17a2b8; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-check"></i> Approve</a>
            @endif
            @if(strtolower($application->currentStep->action_type) == 'site_verification' || strtolower($application->currentStep->action_type) == 'site verification')
                @if(isset($isSiteVerificationCompleted) && $isSiteVerificationCompleted)
                    <a href="{{ route('engineer.applications.site-verification.form', \Illuminate\Support\Facades\Crypt::encryptString($application->id)) }}" class="btn-compact" style="background: #28a745; color: #fff; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-circle-check"></i> Site Verified (View)</a>
                @else
                    <a href="{{ route('engineer.applications.site-verification.form', \Illuminate\Support\Facades\Crypt::encryptString($application->id)) }}" class="btn-compact" style="background: #e67e22; color: #fff; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-map-location-dot"></i> Site Verification</a>
                @endif
            @endif
            @if($application->currentStep->can_upload_document)
            <button class="btn-compact" data-bs-toggle="modal" data-bs-target="#uploadDocModal" style="background: #34495e;"><i class="fa-solid fa-upload"></i> Upload Doc</button>
            @php
            $hasVerifiedAndUploaded = $application->documents->where('document_type', 'engineer_verify_upload')->isNotEmpty();
            @endphp
            @if(!$hasVerifiedAndUploaded)
            <button class="btn-compact" data-bs-toggle="modal" data-bs-target="#verifyUploadDocModal" style="background: #17a2b8;"><i class="fa-solid fa-file-signature"></i> Verify & Upload</button>
            @endif
            @endif
            <a href="{{ route('engineer.applications.action.form', ['application' => $application, 'action_type' => 'add_note']) }}" class="btn-compact" style="background: #6c757d; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-comment-dots"></i> Add Note</a>
            <button class="btn-compact" data-bs-toggle="modal" data-bs-target="#workflowModal" style="background: #6f42c1; color: white; border: none; cursor: pointer;"><i class="fa-solid fa-code-branch"></i> View Workflow</button>
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
            <button type="button" class="btn btn-sm" style="background: #1b5e20; color: #ffffff; border: none; padding: 3px 10px; font-size: 11px; float: right; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#allotteeShowMoreModal">
                Show More
            </button>
        </div>
        <div class="compact-card-body">
            @if($application->allottee)
            <div class="data-pair">
                <div class="data-label">Applicant Name</div>
                <div class="data-value">{{ trim(($application->allottee->prefix ?? '') . ' ' . ($application->allottee->allottee_name ?? '') . ' ' . ($application->allottee->allottee_middle_name ?? '') . ' ' . ($application->allottee->allottee_surname ?? '')) ?: '-' }}</div>
            </div>
            <div class="data-pair">
                <div class="data-label">Hindi Name</div>
                <div class="data-value" style="font-family: 'KrutiDev', sans-serif, Arial; font-size: 20px;">{{ trim(($application->allottee->allottee_prefix_hindi ?? '') . ' ' . ($application->allottee->allottee_name_hindi ?? '') . ' ' . ($application->allottee->allottee_middle_hindi ?? '') . ' ' . ($application->allottee->allottee_surname_hindi ?? '')) ?: '-' }}</div>
            </div>
            <div class="data-pair">
                <div class="data-label">Property Number</div>
                <div class="data-value" style="color: #1b5e20;">{{ $application->allottee->property_number ?? 'N/A' }}</div>
            </div>
            <div class="data-pair">
                <div class="data-label">Username</div>
                <div class="data-value">{{ $application->allottee->username ?? 'N/A' }}</div>
            </div>
            <div class="data-pair">
                <div class="data-label">Email</div>
                <div class="data-value">{{ $application->allottee->alloteeAdresses->email ?? 'N/A' }}</div>
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
                                $docBaseUrl = rtrim(env('DOC_API_URL', ''), '/');
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
                    @php
                    $noteFontFamily = "";
                    if (isset($note->font_family) && $note->font_family === 'krutidev') {
                    $noteFontFamily = "font-family: 'KrutiDev011', sans-serif;";
                    } else if (isset($note->font_family) && $note->font_family === 'normalhindi') {
                    $noteFontFamily = "font-family: 'notosansdevanagari', sans-serif;";
                    }
                    @endphp
                    <div class="note-content" style="{{ $noteFontFamily }}">{!! $note->remarks !!}</div>
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

    <!-- Allottee Document Status (col-12) -->
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-blue" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span><i class="fa-solid fa-list-check"></i> Document Upload Status</span>
            </div>
            <button class="btn-compact" data-bs-toggle="modal" data-bs-target="#requestDocModal" style="background: #17a2b8; color: white; border: none;"><i class="fa-solid fa-plus"></i> Request Additional Document</button>
        </div>
        <div class="compact-card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Document Name</th>
                            <th style="text-align: center;">Upload Status</th>
                            <th>Remarks / Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $statusDocs = collect();

                        foreach($documentMasters as $dm) {
                        $isRequired = in_array($dm->id, $requiredDocumentIds);

                        $uploaded = $allotteeDocuments->first(function($item) use ($dm) {
                        return $item->document_id == $dm->id;
                        });

                        $requestPending = $documentRequests->first(function($item) use ($dm) {
                        return $item->document_master_id == $dm->id;
                        });

                        // Check if the request is fulfilled but wasn't matched above for some reason
                        if (!$uploaded && $requestPending && $requestPending->uploadedDocument) {
                        $uploaded = $requestPending->uploadedDocument;
                        }

                        if ($isRequired || $uploaded || $requestPending) {
                        $statusDocs->push((object)[
                        'master' => $dm,
                        'is_required' => $isRequired,
                        'uploaded_doc' => $uploaded,
                        'request' => $requestPending,
                        ]);
                        }
                        }

                        // Catch any uploaded documents that didn't map to a DocumentMaster but have document_type
                        foreach($allotteeDocuments as $adoc) {
                        if (!$adoc->document_id && !empty($adoc->document_type) && !is_numeric($adoc->document_type)) {
                        $statusDocs->push((object)[
                        'master' => (object)['document_name' => ucwords(str_replace('_', ' ', $adoc->document_type))],
                        'is_required' => false,
                        'uploaded_doc' => $adoc,
                        'request' => null,
                        ]);
                        }
                        }
                        @endphp

                        @forelse($statusDocs as $doc)
                        <tr>
                            <td style="font-weight: 500;">
                                {{ $doc->master->document_name }}
                                @if($doc->is_required)
                                <span style="color: #dc3545; font-size: 11px; margin-left: 5px;">(Required)</span>
                                @endif
                                @if($doc->uploaded_doc)
                                <div style="color: #888; font-size: 11px;">{{ \Illuminate\Support\Str::limit($doc->uploaded_doc->file_name, 30) }}</div>
                                @endif
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                @if($doc->uploaded_doc && $doc->uploaded_doc->file_path)
                                @php
                                $docBaseUrl = rtrim(env('DOC_API_URL', ''), '/');
                                $previewSrc = $docBaseUrl . '/' . ltrim($doc->uploaded_doc->file_path, '/');
                                $docName = addslashes($doc->master->document_name);
                                @endphp
                                <a href="javascript:void(0)" onclick="viewDocument('{{ $previewSrc }}', '{{ $docName }}')" style="display: inline-block; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="Click to view document">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </a>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" title="Not Uploaded">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                </svg>
                                @endif
                            </td>
                            <td>
                                @if($doc->request && $doc->request->status == 'pending')
                                <span class="badge-compact" style="background:#fff3cd; color:#856404; display: block; margin-bottom: 5px; width: fit-content;">Pending Request (Expires: {{ $doc->request->expires_at->format('d-M-Y') }})</span>
                                <div style="color: #666; font-size: 11px;">Requested by: {{ $doc->request->requestedBy ? $doc->request->requestedBy->name : 'Engineer' }}</div>
                                @elseif($doc->uploaded_doc)
                                <span class="badge-compact" style="background:#d4edda; color:#155724;">Uploaded by Allottee</span>
                                @if($doc->uploaded_doc->remarks)
                                <div style="color: #666; font-size: 11px; margin-top: 4px;">{{ $doc->uploaded_doc->remarks }}</div>
                                @endif
                                @elseif($doc->is_required)
                                <span style="color: #888; font-size: 12px; font-style: italic;">Awaiting Upload</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #999; padding: 20px;">
                                No document requirements found for this application.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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

<!-- Verify & Upload Document Modal -->
<div class="modal fade" id="verifyUploadDocModal" tabindex="-1" aria-labelledby="verifyUploadDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content upload-modal-content">
            <div class="modal-header upload-modal-header" style="background: #17a2b8;">
                <h5 class="modal-title upload-modal-title" id="verifyUploadDocModalLabel" style="color: white;">
                    <i class="fa-solid fa-file-signature"></i> Verify & Upload Document
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('engineer.applications.verify-upload', $application) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body upload-modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="upload-input-group">
                                <label>Select File <span class="text-danger">*</span></label>
                                <div class="upload-file-wrapper">
                                    <input type="file" name="document_file" class="upload-file-input" required accept=".pdf,.jpg,.jpeg,.png">
                                    <div class="upload-file-icon">
                                        <i class="fa-regular fa-file-pdf"></i>
                                    </div>
                                    <p class="upload-file-text">Click to browse or drag file</p>
                                    <p class="upload-file-subtext">Supported formats: PDF, JPG, PNG (Max 5MB)</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="upload-input-group">
                                <label>Verification Notes <span class="text-danger">*</span></label>
                                <textarea id="summernote" name="remarks" required></textarea>
                            </div>
                            <div class="upload-input-group mt-3" style="display: none;">
                                <label>Font Family</label>
                                <select name="font_family" class="form-select">
                                    <option value="english" selected>English</option>
                                    <option value="hindi">Hindi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer upload-modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border: 1px solid #dce1e6; font-weight: 500;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: #17a2b8; border: none; font-weight: 500; padding: 8px 20px;">
                        <i class="fa-solid fa-check"></i> Verify & Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Request Document Modal -->
<div class="modal fade" id="requestDocModal" tabindex="-1" aria-labelledby="requestDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content upload-modal-content">
            <div class="modal-header upload-modal-header" style="background: #17a2b8; border-bottom: none;">
                <h5 class="modal-title upload-modal-title" id="requestDocModalLabel" style="color: white;">
                    <i class="fa-solid fa-file-circle-plus"></i> Request Additional Document
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('engineer.document-requests.store') }}" method="POST">
                @csrf
                <input type="hidden" name="application_id" value="{{ $application->id }}">
                <input type="hidden" name="allottee_id" value="{{ $application->allottee_id }}">
                <div class="modal-body upload-modal-body" style="padding: 24px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 20px;">
                        <i class="fa-solid fa-circle-info" style="color: #17a2b8;"></i>
                        The allottee will be notified via SMS, WhatsApp, and Email. They will have <strong>2 days</strong> to upload this document before the request expires.
                    </p>

                    <div class="form-group mb-3">
                        <label style="font-weight: 500; font-size: 13px; color: #444; margin-bottom: 8px; display: block;">Select Document Types <span class="text-danger">*</span></label>
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #dce1e6; border-radius: 6px; padding: 12px; background: #fafbfc;">
                            @if(count($requiredDocumentIds) > 0)
                            <div style="font-size: 12px; font-weight: 700; color: #1e293b; text-transform: uppercase; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 1px solid #e2e8f0;">Required for this Workflow</div>
                            @foreach($documentMasters->whereIn('id', $requiredDocumentIds)->whereNotIn('id', $excludedDocIds) as $dm)
                            <div class="form-check" style="margin-bottom: 8px;">
                                <input class="form-check-input" type="checkbox" name="document_master_ids[]" value="{{ $dm->id }}" id="doc_{{ $dm->id }}">
                                <label class="form-check-label" for="doc_{{ $dm->id }}" style="font-size: 13px; color: #334155;">
                                    {{ $dm->document_name }} <span class="text-danger" style="font-size: 11px;">(Required)</span>
                                </label>
                            </div>
                            @endforeach

                            <div style="font-size: 12px; font-weight: 700; color: #1e293b; text-transform: uppercase; margin-top: 16px; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 1px solid #e2e8f0;">Other Optional Documents</div>
                            @foreach($documentMasters->whereNotIn('id', $requiredDocumentIds)->whereNotIn('id', $excludedDocIds) as $dm)
                            <div class="form-check" style="margin-bottom: 8px;">
                                <input class="form-check-input" type="checkbox" name="document_master_ids[]" value="{{ $dm->id }}" id="doc_{{ $dm->id }}">
                                <label class="form-check-label" for="doc_{{ $dm->id }}" style="font-size: 13px; color: #334155;">
                                    {{ $dm->document_name }}
                                </label>
                            </div>
                            @endforeach
                            @else
                            @foreach($documentMasters->whereNotIn('id', $excludedDocIds) as $dm)
                            <div class="form-check" style="margin-bottom: 8px;">
                                <input class="form-check-input" type="checkbox" name="document_master_ids[]" value="{{ $dm->id }}" id="doc_{{ $dm->id }}">
                                <label class="form-check-label" for="doc_{{ $dm->id }}" style="font-size: 13px; color: #334155;">
                                    {{ $dm->document_name }}
                                </label>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label style="font-weight: 500; font-size: 13px; color: #444; margin-bottom: 6px; display: block;">Remarks / Instructions</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Provide specific instructions for the allottee..." style="border-radius: 6px; border: 1px solid #dce1e6; resize: none;"></textarea>
                    </div>
                </div>
                <div class="modal-footer upload-modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border: 1px solid #dce1e6; font-weight: 500;">Cancel</button>
                    <button type="submit" class="btn btn-info" style="background: #17a2b8; color: white; border: none; font-weight: 500; padding: 8px 20px;">
                        <i class="fa-solid fa-paper-plane"></i> Send Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = document.querySelectorAll('.upload-file-input');

        fileInputs.forEach(fileInput => {
            fileInput.addEventListener('change', function(e) {
                const wrapper = this.closest('.upload-file-wrapper');
                const fileText = wrapper.querySelector('.upload-file-text');
                const fileIcon = wrapper.querySelector('.upload-file-icon');

                if (this.files && this.files.length > 0) {
                    const fileName = this.files[0].name;
                    fileText.innerHTML = `<span style="color: #28a745; font-weight: 600;">Selected: ${fileName}</span>`;
                    fileIcon.innerHTML = `<i class="fa-solid fa-file-circle-check" style="color: #28a745;"></i>`;
                } else {
                    fileText.innerHTML = `Click to browse or drag file here`;
                    fileIcon.innerHTML = `<i class="fa-regular fa-file-pdf"></i>`;
                }
            });
        });
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
        if (lowerUrl.match(/\.(jpeg|jpg|gif|png|webp)(\?.*)?$/) != null) {
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
    document.getElementById('documentModal').addEventListener('hidden.bs.modal', function(event) {
        document.getElementById('documentIframe').src = '';
        document.getElementById('documentImage').src = '';
    });
</script>

<!-- Workflow Steps Modal -->
<div class="modal fade" id="workflowModal" tabindex="-1" aria-labelledby="workflowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #6f42c1, #563d7c); border-radius: 8px 8px 0 0; color: white;">
                <h5 class="modal-title" id="workflowModalLabel">
                    <i class="fa-solid fa-code-branch me-2"></i> Application Workflow Steps
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background: #fdfdfd; max-height: 70vh; overflow-y: auto;">
                @if($application->workflow && $application->workflow->steps)
                <div class="workflow-timeline" style="position: relative; margin-left: 20px;">
                    <div style="position: absolute; left: 14px; top: 0; bottom: 0; width: 2px; background: #e9ecef; z-index: 1;"></div>
                    @foreach($application->workflow->steps->sortBy('step_order') as $step)
                    @php
                    $isCurrent = $application->current_step_id == $step->id;
                    $isCompleted = $application->currentStep && $step->step_order < $application->currentStep->step_order;
                        @endphp
                        <div style="position: relative; padding-left: 45px; margin-bottom: 20px; z-index: 2;">
                            <div style="position: absolute; left: 0; top: 0; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;
                                    @if($isCurrent) background: #6f42c1; color: white; box-shadow: 0 0 0 4px rgba(111, 66, 193, 0.2);
                                    @elseif($isCompleted) background: #28a745; color: white;
                                    @else background: #e9ecef; color: #6c757d; border: 2px solid #ced4da;
                                    @endif">
                                @if($isCompleted)
                                <i class="fa-solid fa-check"></i>
                                @else
                                {{ $step->step_order }}
                                @endif
                            </div>
                            <div style="background: {{ $isCurrent ? '#f8f9fa' : 'white' }}; padding: 12px 16px; border-radius: 6px; border: 1px solid {{ $isCurrent ? '#6f42c1' : '#e9ecef' }};">
                                <h6 style="margin: 0; color: {{ $isCurrent ? '#6f42c1' : '#333' }}; font-weight: 600; font-size: 15px;">
                                    {{ $step->name }}
                                    @if($isCurrent) <span class="badge bg-primary ms-2" style="font-size: 11px;">Current Stage</span> @endif
                                </h6>
                                <div style="color: #6c757d; font-size: 13px; margin-top: 4px;">
                                    <i class="fa-solid fa-user-tag me-1"></i> Role: <strong>{{ $step->role ? $step->role->name : 'N/A' }}</strong>
                                </div>
                                <div style="color: #6c757d; font-size: 13px; margin-top: 2px;">
                                    <i class="fa-solid fa-bolt me-1"></i> Action: <strong style="text-transform: capitalize;">{{ str_replace('_', ' ', $step->action_type) }}</strong>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </div>
                @else
                <div class="text-center text-muted">No workflow steps found.</div>
                @endif
            </div>
            <div class="modal-footer" style="background: #f5f5f5; border-radius: 0 0 8px 8px;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Allottee Show More Modal -->
<div class="modal fade" id="allotteeShowMoreModal" tabindex="-1" aria-labelledby="allotteeShowMoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <div class="modal-header header-green" style="border-radius: 8px 8px 0 0; color: rgb(3, 73, 24);">
                <h5 class="modal-title" id="allotteeShowMoreModalLabel">
                    <i class="fa-solid fa-list-ul me-2"></i> Additional Allottee Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background: #fdfdfd;">
                @if($application->allottee)
                <table class="table table-bordered table-striped" style="font-size: 13px;">
                    <tbody>
                        <tr>
                            <td style="font-weight: 600; width: 40%; color: #555;">Division</td>
                            <td>{{ $application->allottee->division ? $application->allottee->division->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Sub Division</td>
                            <td>{{ $application->allottee->subDivision ? $application->allottee->subDivision->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Property No</td>
                            <td><strong style="color: #1b5e20;">{{ $application->allottee->property_number ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Property Category</td>
                            <td>{{ $application->allottee->propertyCategory ? $application->allottee->propertyCategory->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Property Type</td>
                            <td>{{ $application->allottee->propertyType ? $application->allottee->propertyType->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Property Sub Type</td>
                            <td>{{ $application->allottee->propertySubType ? $application->allottee->propertySubType->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Quarter Type</td>
                            <td>{{ $application->allottee->quarterType ? $application->allottee->quarterType->quarter_name : 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
                @else
                <div class="text-center text-muted">No Additional Details Found.</div>
                @endif
            </div>
            <div class="modal-footer" style="background: #f5f5f5; border-radius: 0 0 8px 8px;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('components.partials.summernote-editor')
@include('components.partials.qr-scanner-modal')
@endsection
