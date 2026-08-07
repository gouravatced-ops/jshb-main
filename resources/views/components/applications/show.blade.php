@props(['application', 'routePrefix', 'documentMasters' => collect(), 'allotteeDocuments' => collect(), 'documentRequests' => collect(), 'requiredDocumentIds' => [], 'excludedDocIds' => [], 'isSiteVerificationCompleted' => false])

@php
    $hasVerifyUploadRoute    = \Illuminate\Support\Facades\Route::has($routePrefix . '.applications.verify-upload');
    $hasSiteVerificationRoute = \Illuminate\Support\Facades\Route::has($routePrefix . '.applications.site-verification.form');
@endphp

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
        <a href="{{ route($routePrefix . '.applications.index') }}" class="btn-compact" style="background: #6c757d; box-shadow: none;"><i class="fa-solid fa-arrow-left"></i> Back to List</a>
    </div>
</div>

@php
    // CoAssistant acts on behalf of their superior (assistant_to_id), so check both role IDs
    $effectiveRoleId = Auth::user()->assistant_to_id
        ? (\App\Models\User::find(Auth::user()->assistant_to_id)?->role_id ?? Auth::user()->role_id)
        : Auth::user()->role_id;
@endphp

@if(Auth::check() && $application->current_role_id == $effectiveRoleId && $application->currentStep)
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
            <a href="{{ route($routePrefix . '.applications.action.form', ['application' => $application, 'action_type' => 'forward']) }}" class="btn-compact" style="background: #28a745; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-arrow-right"></i> Forward</a>
            @endif
            @if($application->currentStep->can_send_back)
            <a href="{{ route($routePrefix . '.applications.action.form', ['application' => $application, 'action_type' => 'send_back']) }}" class="btn-compact" style="background: #ffc107; color: #333; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-reply"></i> Send Back</a>
            @endif
            @if($application->currentStep->can_reject)
            <a href="{{ route($routePrefix . '.applications.action.form', ['application' => $application, 'action_type' => 'reject']) }}" class="btn-compact" style="background: #dc3545; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-times"></i> Reject</a>
            @endif
            @if($application->currentStep->action_type == 'approve' && strtolower($application->status) !== 'completed')
            <a href="{{ route($routePrefix . '.applications.action.form', ['application' => $application, 'action_type' => 'approve']) }}" class="btn-compact" style="background: #17a2b8; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-check"></i> Approve</a>
            @endif
            @if($hasSiteVerificationRoute && (strtolower($application->currentStep->action_type) == 'site_verification' || strtolower($application->currentStep->action_type) == 'site verification'))
                @if(isset($isSiteVerificationCompleted) && $isSiteVerificationCompleted)
                    <a href="{{ route($routePrefix . '.applications.site-verification.form', \Illuminate\Support\Facades\Crypt::encryptString($application->id)) }}" class="btn-compact" style="background: #28a745; color: #fff; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-circle-check"></i> Site Verified (View)</a>
                @else
                    <a href="{{ route($routePrefix . '.applications.site-verification.form', \Illuminate\Support\Facades\Crypt::encryptString($application->id)) }}" class="btn-compact" style="background: #e67e22; color: #fff; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-map-location-dot"></i> Site Verification</a>
                @endif
            @endif
            @if($application->currentStep->can_upload_document)
            <button class="btn-compact" data-bs-toggle="modal" data-bs-target="#uploadDocModal" style="background: #34495e;"><i class="fa-solid fa-upload"></i> Upload Doc</button>
            @php
            $hasVerifiedAndUploaded = $application->documents->where('document_type', 'engineer_verify_upload')->isNotEmpty();
            @endphp
            @if($hasVerifyUploadRoute && !$hasVerifiedAndUploaded)
            <button class="btn-compact" data-bs-toggle="modal" data-bs-target="#verifyUploadDocModal" style="background: #17a2b8;"><i class="fa-solid fa-file-signature"></i> Verify & Upload</button>
            @endif
            @endif
            <a href="{{ route($routePrefix . '.applications.action.form', ['application' => $application, 'action_type' => 'add_note']) }}" class="btn-compact" style="background: #6c757d; border: none; cursor: pointer; text-decoration: none;"><i class="fa-solid fa-comment-dots"></i> Add Note</a>
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
                                $previewSrc = route('media.document', ['path' => $docBaseUrl . '/' . ltrim($doc->file_path, '/')]);
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
            <a href="{{ route($routePrefix . '.applications.notes.pdf', $application) }}" target="_blank" class="btn-compact" style="background: rgb(200 14 14);color: #fff;border: 1px solid rgba(255,255,255,0.3);text-decoration: none;padding: 4px 10px;font-size: 11px;">
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
                                $previewSrc = route('media.document', ['path' => $docBaseUrl . '/' . ltrim($doc->uploaded_doc->file_path, '/')]);
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

<!-- Modals -->
@include('components.applications.modals.upload-doc')
@if($hasVerifyUploadRoute)
@include('components.applications.modals.verify-upload')
@endif
@include('components.applications.modals.request-doc')
@include('components.applications.modals.view-doc')
@include('components.applications.modals.workflow-steps')
@include('components.applications.modals.allottee-show-more')

@include('components.partials.summernote-editor')
@include('components.partials.qr-scanner-modal')
