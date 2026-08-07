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
