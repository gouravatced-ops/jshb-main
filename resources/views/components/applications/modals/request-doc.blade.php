<div class="modal fade" id="requestDocModal" tabindex="-1" aria-labelledby="requestDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content upload-modal-content">
            <div class="modal-header upload-modal-header" style="background: #17a2b8; border-bottom: none;">
                <h5 class="modal-title upload-modal-title" id="requestDocModalLabel" style="color: white;">
                    <i class="fa-solid fa-file-circle-plus"></i> Request Additional Document
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route($routePrefix . '.document-requests.store') }}" method="POST">
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
                                <label class="form-check-label" for="doc_{{ $dm->id }}" style="font-size: 13px; color: #334155; margin:0px;">
                                    {{ $dm->document_name }} <span class="text-danger" style="font-size: 11px;">(Required)</span>
                                </label>
                            </div>
                            @endforeach

                            <div style="font-size: 12px; font-weight: 700; color: #1e293b; text-transform: uppercase; margin-top: 16px; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 1px solid #e2e8f0;">Other Optional Documents</div>
                            @foreach($documentMasters->whereNotIn('id', $requiredDocumentIds)->whereNotIn('id', $excludedDocIds) as $dm)
                            <div class="form-check" style="margin-bottom: 8px;">
                                <input class="form-check-input" type="checkbox" name="document_master_ids[]" value="{{ $dm->id }}" id="doc_{{ $dm->id }}">
                                <label class="form-check-label" for="doc_{{ $dm->id }}" style="font-size: 13px; color: #334155; margin:0px;">
                                    {{ $dm->document_name }}
                                </label>
                            </div>
                            @endforeach
                            @else
                            @foreach($documentMasters->whereNotIn('id', $excludedDocIds) as $dm)
                            <div class="form-check" style="margin-bottom: 8px;">
                                <input class="form-check-input" type="checkbox" name="document_master_ids[]" value="{{ $dm->id }}" id="doc_{{ $dm->id }}">
                                <label class="form-check-label" for="doc_{{ $dm->id }}" style="font-size: 13px; color: #334155; margin:0px;">
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
