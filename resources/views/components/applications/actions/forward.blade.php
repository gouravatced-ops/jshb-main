@props(['application', 'routePrefix', 'forwardOptions' => [], 'isSiteVerificationStep' => false, 'isSiteVerificationCompleted' => false])

<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-blue" style="display: flex; justify-content: space-between; align-items: center;">
            <span><i class="fa-solid fa-arrow-right-long" style="margin-right: 8px;"></i> Forward Application <span style="opacity: 0.7; font-size: 14px; font-weight: 500; margin-left: 5px;">| No: {{ $application->application_no }}</span></span>
            <div>
                <button type="button" onclick="openQrModal()" style="font-weight: 600; font-size: 13px; padding: 6px 12px; border-radius: 6px; border: none; color: #0d47a1; background: #e3f2fd; cursor: pointer; margin-right: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><i class="fa-solid fa-mobile-screen-button" style="margin-right: 5px;"></i> Live Image Upload</button>
                <a href="{{ route($routePrefix . '.applications.show', $application) }}" class="btn btn-outline-primary btn-sm" style="background: rgba(255,255,255,0.7); font-weight: 600; color: #0d47a1; border-color: #0d47a1;"><i class="fa-solid fa-arrow-left"></i> Back to Review</a>
            </div>
        </div>
        <div class="compact-card-body">

            <style>
                .forward-card-radio {
                    display: none;
                }

                .forward-card-label {
                    display: flex;
                    align-items: center;
                    padding: 12px 15px;
                    border: 2px solid #e2e8f0;
                    border-radius: 8px;
                    cursor: pointer;
                    transition: all 0.2s;
                    background: #fff;
                    margin-bottom: 10px;
                    position: relative;
                    overflow: hidden;
                }

                .forward-card-label:hover {
                    border-color: #cbd5e1;
                    background: #f8fafc;
                }

                .forward-card-radio:checked+.forward-card-label {
                    border-color: #0d47a1;
                    background: #e3f2fd;
                    box-shadow: 0 4px 6px -1px rgba(13, 71, 161, 0.1);
                }

                .forward-card-radio:checked+.forward-card-label::before {
                    content: '\f058';
                    font-family: 'Font Awesome 6 Free';
                    font-weight: 900;
                    color: #0d47a1;
                    position: absolute;
                    right: 15px;
                    top: 50%;
                    transform: translateY(-50%);
                    font-size: 20px;
                }

                .forward-avatar {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    background: #e2e8f0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 15px;
                    color: #64748b;
                    font-weight: bold;
                    font-size: 16px;
                    transition: all 0.2s;
                }

                .forward-card-radio:checked+.forward-card-label .forward-avatar {
                    background: #0d47a1;
                    color: #fff;
                }

                .forward-details h6 {
                    margin: 0 0 3px 0;
                    font-size: 15px;
                    color: #1e293b;
                    font-weight: 600;
                }

                .forward-details p {
                    margin: 0;
                    font-size: 12px;
                    color: #64748b;
                }

                .forward-group-title {
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    color: #64748b;
                    font-weight: 700;
                    margin: 15px 0 10px;
                    border-bottom: 1px solid #e2e8f0;
                    padding-bottom: 5px;
                }

                .forward-cards-container::-webkit-scrollbar {
                    width: 6px;
                }

                .forward-cards-container::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 4px;
                }

                .forward-cards-container::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 4px;
                }

                .forward-cards-container::-webkit-scrollbar-thumb:hover {
                    background: #94a3b8;
                }
            </style>

            <div style="background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 5px solid #ffeeba; margin-bottom: 20px; color: #856404;">
                <i class="fa-solid fa-info-circle"></i> <strong>Note:</strong> The official noting you provide below will be permanently recorded in the file history. This is equivalent to signing and stamping a physical green noting sheet.
            </div>

            @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route($routePrefix . '.applications.action', $application) }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" value="forward">

                @if(isset($isSiteVerificationStep) && $isSiteVerificationStep)
                @if($isSiteVerificationCompleted)
                <div class="alert1 alert-success" style="border-left: 5px solid #28a745; background-color: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                    <i class="fa-solid fa-circle-check me-2"></i> <strong>Site Verification Completed:</strong> The site verification report has been generated successfully. You can proceed to forward this application.
                </div>
                @else
                <div class="alert alert-danger" style="border-left: 5px solid #dc3545; background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <strong>Site Verification Pending:</strong> Please complete the site verification process from the "Site Verification" tab before you can forward this application.
                </div>
                @endif
                @endif


                <div class="row">
                    <div class="col-md-12 mb-4">
                        @if(isset($approvedBypass) && $approvedBypass)
                            <div class="alert alert-success" style="border-left: 5px solid #28a745; background-color: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                                <i class="fa-solid fa-circle-check me-2"></i> <strong>Bypass Approved:</strong> Your request to bypass to <strong>{{ $approvedBypass->targetStep->step_name ?? 'Higher Authority' }}</strong> has been approved. You can now select them below and forward.
                            </div>
                        @endif

                        <label style="font-weight: 600; font-size: 15px; margin-bottom: 8px; display: block; color: #333;">Forward To <span class="text-danger">*</span></label>
                        @if(!empty($forwardOptions))
                        <div class="forward-cards-container" style="max-height: 280px; overflow-y: auto; padding-right: 5px;">
                            @foreach($forwardOptions as $index => $option)
                            @if($index == 0)
                                @if(isset($approvedBypass) && $approvedBypass)
                                <div class="forward-group-title" style="margin-top: 0;">
                                    <i class="fa-solid fa-check-circle" style="margin-right: 5px; color: #28a745;"></i> Approved Target: {{ $option['step']->step_name }} ({{ $option['step']->role->name ?? 'Role' }})
                                </div>
                                @else
                                <div class="forward-group-title" style="margin-top: 0;">
                                    <i class="fa-solid fa-sitemap" style="margin-right: 5px;"></i> Immediate Next Step: {{ $option['step']->step_name }} ({{ $option['step']->role->name ?? 'Role' }})
                                </div>
                                @endif
                            @elseif($index == 1)
                            
                            @if(!isset($approvedBypass) || !$approvedBypass)
                            <div style="margin: 15px 0;">
                                <div class="form-check" style="background: #fff3cd; padding: 10px 15px 10px 35px; border-radius: 6px; border: 1px solid #ffeeba;">
                                    <input class="form-check-input" type="checkbox" id="requestBypassToggle" name="is_bypass_request" value="1">
                                    <label class="form-check-label" for="requestBypassToggle" style="font-weight: 600; color: #856404;">
                                        <i class="fa-solid fa-forward-step me-1"></i> Request Workflow Bypass (Skip Level)
                                    </label>
                                    <p style="margin: 5px 0 0; font-size: 12px; color: #856404; font-weight: normal;">Check this if the immediate officer is unavailable and you need to forward this directly to a higher authority. Requires Admin approval.</p>
                                </div>
                            </div>
                            
                            <div id="bypassOptionsContainer" style="display: none; padding-left: 10px; border-left: 2px solid #ffc107; margin-bottom: 20px;">
                            @else
                            <div id="bypassOptionsContainer" style="display: block; padding-left: 10px; border-left: 2px solid #28a745; margin-bottom: 20px;">
                            @endif

                            <div class="forward-group-title">
                                <i class="fa-solid fa-angles-right" style="margin-right: 5px;"></i> Bypass Options
                            </div>
                            @else
                            <div class="forward-group-title">
                                <i class="fa-solid fa-angles-right" style="margin-right: 5px;"></i> Bypass To: {{ $option['step']->step_name }} ({{ $option['step']->role->name ?? 'Role' }})
                            </div>
                            @endif
                            <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
                                @foreach($option['engineers'] as $engineer)
                                <div class="forward-card-wrapper" style="flex: 0 1 320px; max-width: 100%;">
                                    <input type="radio" name="forward_to_user" id="fwd_{{ $engineer->id }}_{{ $option['step']->id }}" value="{{ $engineer->id }}|{{ $option['step']->id }}" class="forward-card-radio" required>
                                    <label for="fwd_{{ $engineer->id }}_{{ $option['step']->id }}" class="forward-card-label" style="height: 100%; margin-bottom: 0;">
                                        <div class="forward-avatar">
                                            {{ substr($engineer->name, 0, 1) }}
                                        </div>
                                        <div class="forward-details">
                                            <h6>{{ $engineer->name }}</h6>
                                            <p><i class="fa-regular fa-id-badge" style="margin-right: 4px;"></i>{{ $option['step']->role->name ?? '' }}</p>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                            
                            @if(count($forwardOptions) > 1)
                            </div> <!-- End bypassOptionsContainer -->
                            
                            <!-- Bypass Reason (Hidden initially) -->
                            <div id="bypassReasonContainer" style="display: none; margin-bottom: 20px;">
                                <label style="font-weight: 600; font-size: 15px; margin-bottom: 8px; display: block; color: #333;">Reason for Bypass <span class="text-danger">*</span></label>
                                <textarea name="bypass_reason" id="bypass_reason" class="form-control" rows="3" placeholder="Please explain why you are skipping the immediate next step (e.g., Officer on leave)..."></textarea>
                            </div>
                            
                            <script>
                                const bypassToggle = document.getElementById('requestBypassToggle');
                                if (bypassToggle) {
                                    bypassToggle.addEventListener('change', function() {
                                        const container = document.getElementById('bypassOptionsContainer');
                                        const reasonContainer = document.getElementById('bypassReasonContainer');
                                        const reasonInput = document.getElementById('bypass_reason');
                                        const submitBtn = document.getElementById('forwardSubmitBtn');
                                        
                                        const summernote = document.getElementById('summernote');
                                        const asterisk = document.getElementById('remarksRequiredAsterisk');
                                        const optText = document.getElementById('remarksOptionalText');
                                        
                                        if(this.checked) {
                                            container.style.display = 'block';
                                            reasonContainer.style.display = 'block';
                                            reasonInput.setAttribute('required', 'required');
                                            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Submit Bypass Request';
                                            submitBtn.className = 'btn btn-warning';
                                            
                                            summernote.removeAttribute('required');
                                            if(asterisk) asterisk.style.display = 'none';
                                            if(optText) optText.style.display = 'block';
                                        } else {
                                            container.style.display = 'none';
                                            reasonContainer.style.display = 'none';
                                            reasonInput.removeAttribute('required');
                                            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Submit Noting & Forward';
                                            submitBtn.className = 'btn btn-success';
                                            
                                            summernote.setAttribute('required', 'required');
                                            if(asterisk) asterisk.style.display = 'inline';
                                            if(optText) optText.style.display = 'none';
                                            
                                            // Uncheck any selected bypass radios
                                            const bypassRadios = container.querySelectorAll('input[type="radio"]');
                                            bypassRadios.forEach(r => r.checked = false);
                                        }
                                    });
                                }
                            </script>
                            @endif
                            
                        </div>
                        @else
                        <div class="alert alert-warning" style="margin-bottom: 0; padding: 10px 15px;">
                            <i class="fa-solid fa-triangle-exclamation"></i> No eligible engineers found in your division to forward this application to.
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-group mb-4 summernote-wrapper">
                    <label style="font-weight: 600; font-size: 16px; margin-bottom: 10px; display: block; color: #333;"><i class="fa-solid fa-pen-fancy" style="color: #0d47a1;"></i> Official Noting / Remarks <span id="remarksRequiredAsterisk" class="text-danger">*</span></label>

                    <!-- Font Family Selection -->
                    <div class="mb-3 p-2" style="background: #f8f9fa; border-radius: 6px; border: 1px solid #e9ecef;">
                        <label class="me-3" style="font-weight: 600; font-size: 14px; color: #495057;">Typing Language:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input font-family-selector" type="radio" name="font_family" id="font_english_note" value="english">
                            <label class="form-check-label" for="font_english_note" style="margin:0px;">English (Arial)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input font-family-selector" type="radio" name="font_family" id="font_hindi_note" value="krutidev">
                            <label class="form-check-label" for="font_hindi_note" style="margin:0px;">Hindi (Kruti Dev)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input font-family-selector" type="radio" name="font_family" id="font_normal_hindi_note" value="normalhindi" checked>
                            <label class="form-check-label" for="font_normal_hindi_note" style="margin:0px;">Normal Hindi</label>
                        </div>
                    </div>

                    <!-- Rich Text Editor via Summernote -->
                    <textarea id="summernote" name="remarks" required></textarea>
                    <span id="remarksOptionalText" style="display: none; font-size: 12px; color: #666; margin-top: 5px;">* Remarks are optional when requesting a bypass.</span>
                </div>

                <hr style="margin: 20px 0; border-top: 1px solid #eaeaea;">

                <div style="text-align: right;">
                    <button type="submit" id="forwardSubmitBtn" class="btn btn-success" style="font-size: 15px; padding: 8px 20px;" {{ (isset($isSiteVerificationStep) && $isSiteVerificationStep && !$isSiteVerificationCompleted) ? 'disabled' : '' }}>
                        <i class="fa-solid fa-paper-plane me-1"></i> Submit Noting & Forward
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('components.partials.summernote-editor')
    @include('components.partials.qr-scanner-modal')
</div>
