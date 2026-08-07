@extends('layouts.main')
@section('title', 'Site Verification | JSHB')
@section('content')

@php
    $verification = $allottee->siteVerification ?? null;
    $mapParams = $verification ? json_decode($verification->map_parameters ?? '{}', true) : [];
    $propertyCategory = getPropertyCategory();
@endphp

@include('components.partials.compact-css')

    <!-- Top Toolbar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; background: #fff; padding: 12px 20px; border-radius: 8px; border: 1px solid #eaeaea; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
        <div>
            <h4 style="margin: 0; font-size: 16px; font-weight: 600; color: #2c3e50;">
                <i class="fa-solid fa-map-location-dot" style="color: #3498db; margin-right: 8px;"></i>
                Site Verification (स्थल निरीक्षण) : <span style="color: #e74c3c;">{{ $allottee->application_no ?? '-' }}</span>
            </h4>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('engineer.applications.show', $encryptedId) }}" class="btn-compact" style="background: #6c757d; box-shadow: none;"><i class="fa-solid fa-arrow-left"></i> Back to Application</a>
        </div>
    </div>

    {{-- Form Content --}}
    <div class="compact-card mb-4">
        <div class="compact-card-header header-blue py-3" style="border-radius: 8px 8px 0 0;">
            <h5 class="mb-0 text-center fw-bold" style="letter-spacing: 0.5px; font-size: 16px;">
                जॉँच प्रपत्र (चेक लिस्ट)
            </h5>
        </div>
        <div class="compact-card-body p-4 bg-light">
            <form id="siteVerificationForm" action="{{ route('engineer.applications.site-verification.store', \Illuminate\Support\Facades\Crypt::encryptString($application->id)) }}" data-csrf="{{ csrf_token() }}">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">1. आवासीय कॉलोनी का नाम (Name of residential colony)</label>
                        <input type="text" class="form-control" name="colony_name" value="{{ $verification->colony_name ?? $allottee->scheme_name ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">2. आवंटी का नाम (Name of allottee)</label>
                        <input type="text" class="form-control" name="allottee_name" value="{{ $verification->allottee_name ?? trim(($allottee->prefix ?? '') . ' ' . ($allottee->allottee_name ?? '') . ' ' . ($allottee->allottee_middle_name ?? '') . ' ' . ($allottee->allottee_surname ?? '')) }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">3. आवंटित इकाई की संख्या (Number of allotted unit)</label>
                        <input type="text" class="form-control" name="unit_number" value="{{ $verification->unit_number ?? $allottee->property_number ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">4. आवंटित इकाई का उपयोग (Use of allotted unit)</label>
                        <select class="form-select" name="unit_use">
                            <option value="">चयन करें</option>
                                @foreach ($propertyCategory as $category)
                                    <option
                                        value="{{ $category->name }}"
                                        @selected(($verification->unit_use ?? '') === $category->name)
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                        </select>
                    </div>

                    <div class="col-12 mt-4 mb-2">
                        <h6 class="fw-bold text-primary border-bottom pb-2">5. सड़क का आकार (Size of road)</h6>
                    </div>
                    <div class="col-md-6 mt-0">
                        <label class="form-label text-dark">क) आवंटित इकाई के सामने (In front of allotted unit)</label>
                        <input type="text" class="form-control" name="road_front" value="{{ $verification->road_front ?? '' }}" placeholder="उदा. 6.00 M WIDE ROAD">
                    </div>
                    <div class="col-md-6 mt-0">
                        <label class="form-label text-dark">ख) आवंटित इकाई के बगल में (Beside the allotted unit)</label>
                        <input type="text" class="form-control" name="road_beside" value="{{ $verification->road_beside ?? '' }}" placeholder="उदा. BOARD'S LAND">
                    </div>

                    <div class="col-12 mt-4 mb-2">
                        <h6 class="fw-bold text-primary border-bottom pb-2">6. भूखण्ड का आकार (Size of plot)</h6>
                    </div>
                    <div class="col-md-6 mt-0">
                        <label class="form-label text-dark">क) आवंटनादेश के अनुसार (As per allotment order)</label>
                        <input type="text" class="form-control" name="plot_size_allotment" value="{{ $verification->plot_size_allotment ?? '' }}">
                    </div>
                    <div class="col-md-6 mt-0">
                        <label class="form-label text-dark">ख) निष्पादित एकरारनामा के अनुसार (As per executed agreement)</label>
                        <input type="text" class="form-control" name="plot_size_agreement" value="{{ $verification->plot_size_agreement ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-dark">ग) दिये गये दखल कब्जा के अनुसार (As per given possession)</label>
                        <input type="text" class="form-control" name="plot_size_possession" value="{{ $verification->plot_size_possession ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-dark">घ) अगर कोई अंतर हो तो इसका कारण (If any difference, reason thereof)</label>
                        <input type="text" class="form-control" name="plot_size_difference_reason" value="{{ $verification->plot_size_difference_reason ?? '' }}">
                    </div>

                    <div class="col-12 mt-4 mb-2">
                        <h6 class="fw-bold text-primary border-bottom pb-2">7. यदि कोई अतिक्रमण हो तो उसका ब्योरा (If any encroachment, details thereof)</h6>
                    </div>
                    <div class="col-md-12 mt-0">
                        <label class="form-label text-dark">क) रकबा (Area)</label>
                        <input type="text" class="form-control" name="encroachment_area" value="{{ $verification->encroachment_area ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-dark">ख) अतिक्रमित भाग रोड/पार्क/नाली/सिवरेज अन्य सार्वजनिक उपयोग हेतु चिन्हित था?</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="encroachment_public_use" id="pubUseYes" value="yes" {{ ($verification->encroachment_public_use ?? '') == 'yes' ? 'checked' : '' }}>
                                <label class="form-check-label" for="pubUseYes">हाँ (Yes)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="encroachment_public_use" id="pubUseNo" value="no" {{ ($verification->encroachment_public_use ?? '') == 'no' ? 'checked' : '' }}>
                                <label class="form-check-label" for="pubUseNo">नहीं (No)</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-dark">ग) इसका स्वतंत्र भूखण्ड / फ्लैट / मकान बनाने हेतु उपयोग में लिया जा सकता है?</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="encroachment_independent_use" id="indUseYes" value="yes" {{ ($verification->encroachment_independent_use ?? '') == 'yes' ? 'checked' : '' }}>
                                <label class="form-check-label" for="indUseYes">हाँ (Yes)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="encroachment_independent_use" id="indUseNo" value="no" {{ ($verification->encroachment_independent_use ?? '') == 'no' ? 'checked' : '' }}>
                                <label class="form-check-label" for="indUseNo">नहीं (No)</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-dark" style="font-size: 0.9rem;">घ) यह आवंटित इकाई से निकटम बिन्दु पर है, इसका कोई मुख्य उपयोग भविष्य में है या नही?</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="encroachment_future_use" id="futUseYes" value="yes" {{ ($verification->encroachment_future_use ?? '') == 'yes' ? 'checked' : '' }}>
                                <label class="form-check-label" for="futUseYes">हाँ (Yes)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="encroachment_future_use" id="futUseNo" value="no" {{ ($verification->encroachment_future_use ?? '') == 'no' ? 'checked' : '' }}>
                                <label class="form-check-label" for="futUseNo">नहीं (No)</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-dark">ङ) आवंटी के साथ बन्दोवस्ती की जा सकती है?</label>
                        <div class="d-flex gap-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="encroachment_settlement" id="setYes" value="yes" {{ ($verification->encroachment_settlement ?? '') == 'yes' ? 'checked' : '' }}>
                                <label class="form-check-label" for="setYes">हाँ (Yes)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="encroachment_settlement" id="setNo" value="no" {{ ($verification->encroachment_settlement ?? '') == 'no' ? 'checked' : '' }}>
                                <label class="form-check-label" for="setNo">नहीं (No)</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4 bg-white p-3 border rounded shadow-sm">
                        <label class="form-label fw-bold text-dark fs-6 mb-3">8. भूखण्ड पर मकान निर्मित है या नही? (Is house constructed on the plot or not)</label>
                        <div class="d-flex gap-4">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_house_constructed" id="houseYes" value="yes" {{ ($verification->is_house_constructed ?? '') == 'yes' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="houseYes">हाँ (Yes)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_house_constructed" id="houseNo" value="no" {{ ($verification->is_house_constructed ?? '') == 'no' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="houseNo">नहीं (No)</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4 mb-2">
                        <h6 class="fw-bold text-primary border-bottom pb-2">9. यदि मकान निर्मित हो तो सक्षम प्राधिकार द्वारा स्वीकृत नक्शा की अभिप्रमाणित प्रति संलग्न करें:</h6>
                    </div>
                    <div class="col-md-4 mt-0">
                        <label class="form-label text-dark">प्राधिकार का नाम (Name of authority)</label>
                        <input type="text" class="form-control" name="approved_map_authority" value="{{ $verification->approved_map_authority ?? '' }}">
                    </div>
                    <div class="col-md-4 mt-0">
                        <label class="form-label text-dark">केश संख्या (Case number)</label>
                        <input type="text" class="form-control" name="approved_map_case" value="{{ $verification->approved_map_case ?? '' }}">
                    </div>
                    <div class="col-md-4 mt-0">
                        <label class="form-label text-dark">तिथि (Date)</label>
                        <input type="date" class="form-control" name="approved_map_date" value="{{ $verification->approved_map_date ?? '' }}">
                    </div>

                    <div class="col-md-12 mt-4 bg-white p-3 border rounded shadow-sm">
                        <label class="form-label fw-bold text-dark fs-6 mb-3">10. आवंटित भूखण्ड पर मकान का निर्माण स्वीकृत नक्शा अनुसार है या नहीं?</label>
                        <div class="d-flex gap-4">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_construction_as_per_map" id="mapCnsYes" value="yes" {{ ($verification->is_construction_as_per_map ?? '') == 'yes' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="mapCnsYes">हाँ (Yes)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_construction_as_per_map" id="mapCnsNo" value="no" {{ ($verification->is_construction_as_per_map ?? '') == 'no' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="mapCnsNo">नहीं (No)</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4 mb-2">
                        <h6 class="fw-bold text-primary border-bottom pb-2">11. आवंटित मकान/फ्लैट में परिवर्तन/परिवर्द्धन हुआ है या नही, यदि हुआ है तो सक्षम प्राधिकार द्वारा स्वीकृत नक्शा की प्रति संलग्न करें:</h6>
                    </div>
                    <div class="col-md-4 mt-0">
                        <label class="form-label text-dark">प्राधिकार का नाम (Name of authority)</label>
                        <input type="text" class="form-control" name="alteration_map_authority" value="{{ $verification->alteration_map_authority ?? '' }}">
                    </div>
                    <div class="col-md-4 mt-0">
                        <label class="form-label text-dark">केश संख्या (Case number)</label>
                        <input type="text" class="form-control" name="alteration_map_case" value="{{ $verification->alteration_map_case ?? '' }}">
                    </div>
                    <div class="col-md-4 mt-0">
                        <label class="form-label text-dark">तिथि (Date)</label>
                        <input type="date" class="form-control" name="alteration_map_date" value="{{ $verification->alteration_map_date ?? '' }}">
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Map Generator Section --}}
    <div class="compact-card mb-4">
        <div class="compact-card-header header-purple py-3" style="border-radius: 8px 8px 0 0;">
            <h5 class="mb-0 text-center fw-bold" style="font-size: 16px;"><i class="fa-solid fa-map-location-dot me-2 text-warning"></i> स्थल मानचित्र जनरेटर (Site Map Generator)</h5>
        </div>
        <div class="compact-card-body p-4 bg-light">
            <div class="row">
                <!-- Parameters Input -->
                <div class="col-md-5 border-end pe-4">
                    <h6 class="fw-bold mb-3 text-secondary border-bottom pb-2"><i class="fa-solid fa-sliders me-2"></i> Map Parameters</h6>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Plot Number / Asset No</label>
                        <input type="text" class="form-control map-input" id="mapPlotNo" value="{{ $allottee->property_number }}">
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fa-solid fa-arrow-left text-primary"></i> North Dim (m)</label>
                            <input type="number" class="form-control map-input" id="mapNorth" value="{{ $mapParams['north'] ?? '' }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small">North Label</label>
                            <input type="text" class="form-control map-input" id="mapNorthLabel" value="{{ $mapParams['northLabel'] ?? '' }}">
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fa-solid fa-arrow-right text-danger"></i> South Dim (m)</label>
                            <input type="number" class="form-control map-input" id="mapSouth" value="{{ $mapParams['south'] ?? '' }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small">South Label</label>
                            <input type="text" class="form-control map-input" id="mapSouthLabel" value="{{ $mapParams['southLabel'] ?? '' }}">
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fa-solid fa-arrow-up text-success"></i> East Dim (m)</label>
                            <input type="number" class="form-control map-input" id="mapEast" value="{{ $mapParams['east'] ?? '' }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small">East Label</label>
                            <input type="text" class="form-control map-input" id="mapEastLabel" value="{{ $mapParams['eastLabel'] ?? '' }}">
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fa-solid fa-arrow-down text-warning"></i> West Dim (m)</label>
                            <input type="number" class="form-control map-input" id="mapWest" value="{{ $mapParams['west'] ?? '' }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small">West Label</label>
                            <input type="text" class="form-control map-input" id="mapWestLabel" value="{{ $mapParams['westLabel'] ?? '' }}">
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-outline-primary w-100 mt-2" onclick="generateSiteMap()">
                        <i class="fa-solid fa-arrows-rotate me-2"></i> Refresh Map Preview
                    </button>
                    <button type="button" class="btn btn-outline-secondary w-100 mt-2" onclick="downloadMapImage()">
                        <i class="fa-solid fa-download me-2"></i> Save Map as Image
                    </button>
                </div>
                <!-- Map Preview -->
                <div class="col-md-7 ps-4 d-flex justify-content-center align-items-center bg-light rounded-3" style="min-height: 450px; position: relative;">
                    <canvas id="siteMapCanvas" width="600" height="450" style="background: white; border: 2px dashed #cbd5e1; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-end mb-5">
        @if(isset($isSiteVerificationCompleted) && $isSiteVerificationCompleted)
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-success fs-5 py-2 px-4 rounded-pill shadow-sm" style="background-color: #28a745 !important; border: 1px solid #218838;"><i class="fa-solid fa-circle-check me-2"></i> Verified & Document Generated</span>
                <a href="{{ route('engineer.applications.action.form', ['application' => $encryptedId, 'action_type' => 'forward']) }}" class="btn btn-primary px-5 py-2 fs-5 rounded-pill shadow-sm"><i class="fa-solid fa-arrow-right-long me-2"></i> Proceed to Forward</a>
            </div>
        @else
            <button type="button" id="saveSiteVerificationBtn" class="btn btn-success px-5 py-2 fs-5 rounded-pill shadow-sm" onclick="saveSiteVerification()"><i class="fa-solid fa-save me-2"></i> Save Site Verification</button>
        @endif
    </div>

</div>

<!-- OTP Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-primary text-white" style="border-radius: 12px 12px 0 0;">
                <h5 class="modal-title" id="otpModalLabel"><i class="fa-solid fa-shield-halved me-2"></i> Verify Action</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="text-muted mb-4">An OTP has been sent to your registered email address. Please enter it below to authorize this Site Verification.</p>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control text-center fs-4 fw-bold tracking-widest" id="otpInput" placeholder="Enter 6-digit OTP" maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <label for="otpInput">Enter 6-digit OTP</label>
                </div>
                <div id="otpErrorMsg" class="text-danger small fw-bold d-none"></div>
                <div class="mt-3">
                    <div id="resendOtpContainer" class="p-2 rounded d-inline-block" style="background-color: #f8f9fa; border: 1px dashed #dee2e6;">
                        <span id="resendTimerText" class="text-muted small fw-semibold"><i class="fa-regular fa-clock me-1"></i> Resend OTP in <span id="resendTimerCount" class="text-primary fw-bold">30</span>s</span>
                        <a href="javascript:void(0)" id="resendOtpBtn" class="text-primary small fw-bold d-none text-decoration-none" onclick="resendOtp(event)"><i class="fa-solid fa-rotate-right me-1"></i> Resend OTP</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light" style="border-radius: 0 0 12px 12px;">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success px-4" id="verifyOtpBtn" onclick="submitSiteVerification()"><i class="fa-solid fa-check-circle me-2"></i> Verify & Save</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    window.generateSiteMap = function () {
        const canvas = document.getElementById('siteMapCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        // Get values
        const plotNo = document.getElementById('mapPlotNo') ? document.getElementById('mapPlotNo').value : '';
        const nDim = parseFloat(document.getElementById('mapNorth') ? document.getElementById('mapNorth').value : 0) || 0;
        const nLbl = document.getElementById('mapNorthLabel') ? document.getElementById('mapNorthLabel').value : '';
        const sDim = parseFloat(document.getElementById('mapSouth') ? document.getElementById('mapSouth').value : 0) || 0;
        const sLbl = document.getElementById('mapSouthLabel') ? document.getElementById('mapSouthLabel').value : '';
        const eDim = parseFloat(document.getElementById('mapEast') ? document.getElementById('mapEast').value : 0) || 0;
        const eLbl = document.getElementById('mapEastLabel') ? document.getElementById('mapEastLabel').value : '';
        const wDim = parseFloat(document.getElementById('mapWest') ? document.getElementById('mapWest').value : 0) || 0;
        const wLbl = document.getElementById('mapWestLabel') ? document.getElementById('mapWestLabel').value : '';

        // Clear Canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Draw Compass (Top Left)
        ctx.save();
        ctx.translate(60, 70);
        ctx.strokeStyle = '#1e293b';
        ctx.lineWidth = 1;
        ctx.beginPath();
        // Circle
        ctx.arc(0, 0, 10, 0, 2 * Math.PI);
        // Crosshair
        ctx.moveTo(0, -30); ctx.lineTo(0, 30);
        ctx.moveTo(-30, 0); ctx.lineTo(30, 0);
        ctx.stroke();

        // Arrow head pointing Left for N
        ctx.beginPath();
        ctx.moveTo(-30, 0);
        ctx.lineTo(-20, -5);
        ctx.lineTo(-20, 5);
        ctx.closePath();
        ctx.fillStyle = '#1e293b';
        ctx.fill();
        ctx.stroke();

        // Text
        ctx.font = '14px Arial';
        ctx.fillStyle = '#1e293b';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('N', -45, 0);
        ctx.fillText('S', 45, 0);
        ctx.fillText('E', 0, -45);
        ctx.fillText('W', 0, 45);
        ctx.restore();

        // Calculate dimensions to fit properly on canvas
        const cx = canvas.width / 2 + 20; // Shift right slightly to accommodate compass
        const cy = canvas.height / 2;

        const maxDim = Math.max(nDim, sDim, eDim, wDim, 1);
        const scaleFactor = 200 / maxDim; // Map fits within 200x200 max box

        // Calculate dynamic dimensions
        // With N on Left: Top is East, Bottom is West, Left is North, Right is South
        const nScaled = nDim * scaleFactor; // Left edge height
        const sScaled = sDim * scaleFactor; // Right edge height
        const eScaled = eDim * scaleFactor; // Top edge width
        const wScaled = wDim * scaleFactor; // Bottom edge width

        // Draw grid lines (optional for aesthetic)
        ctx.strokeStyle = '#f1f5f9';
        ctx.lineWidth = 1;
        for (let i = 0; i < canvas.width; i += 20) {
            ctx.beginPath(); ctx.moveTo(i, 0); ctx.lineTo(i, canvas.height); ctx.stroke();
        }
        for (let j = 0; j < canvas.height; j += 20) {
            ctx.beginPath(); ctx.moveTo(0, j); ctx.lineTo(canvas.width, j); ctx.stroke();
        }

        // Points for the dynamic shape (centered)
        const pTL = { x: cx - eScaled / 2, y: cy - nScaled / 2 }; // Top-Left
        const pTR = { x: cx + eScaled / 2, y: cy - sScaled / 2 }; // Top-Right
        const pBR = { x: cx + wScaled / 2, y: cy + sScaled / 2 }; // Bottom-Right
        const pBL = { x: cx - wScaled / 2, y: cy + nScaled / 2 }; // Bottom-Left

        // Shadow for plot
        ctx.shadowColor = 'rgba(0,0,0,0.15)';
        ctx.shadowBlur = 10;
        ctx.shadowOffsetX = 5;
        ctx.shadowOffsetY = 5;

        // Draw Plot Shape
        ctx.beginPath();
        ctx.moveTo(pTL.x, pTL.y);
        ctx.lineTo(pTR.x, pTR.y);
        ctx.lineTo(pBR.x, pBR.y);
        ctx.lineTo(pBL.x, pBL.y);
        ctx.closePath();
        ctx.fillStyle = '#fef3c7'; // warm yellow
        ctx.fill();

        // Reset shadow for borders
        ctx.shadowColor = 'transparent';
        ctx.shadowBlur = 0;
        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 0;

        // Border
        ctx.lineWidth = 3;
        ctx.strokeStyle = '#1e293b';
        ctx.stroke();

        // Plot Inner Label
        ctx.fillStyle = '#1e293b';
        ctx.font = 'bold 16px "Segoe UI", Arial, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(plotNo, cx, cy);

        ctx.font = 'bold 14px "Segoe UI", Arial, sans-serif';

        // East Data (Top)
        const topY = (pTL.y + pTR.y) / 2;
        ctx.textBaseline = 'bottom';
        ctx.fillStyle = '#0f172a';
        ctx.fillText(eDim + " m", cx, topY - 10);
        ctx.font = '14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#64748b';
        ctx.fillText(eLbl, cx, topY - 30);

        // West Data (Bottom)
        const bottomY = (pBL.y + pBR.y) / 2;
        ctx.font = 'bold 14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#0f172a';
        ctx.textBaseline = 'top';
        ctx.fillText(wDim + " m", cx, bottomY + 10);
        ctx.font = '14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#64748b';
        ctx.fillText(wLbl, cx, bottomY + 30);

        // North Data (Left side)
        const leftX = (pTL.x + pBL.x) / 2;
        ctx.save();
        ctx.translate(leftX - 30, cy);
        ctx.rotate(-Math.PI / 2); // Rotate text so it's readable sideways
        ctx.font = 'bold 14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#0f172a';
        ctx.textBaseline = 'bottom';
        ctx.textAlign = 'center';
        ctx.fillText(nDim + " m", 0, 0);
        ctx.font = '14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#64748b';
        ctx.textBaseline = 'top';
        ctx.fillText(nLbl, 0, 5);
        ctx.restore();

        // South Data (Right side)
        const rightX = (pTR.x + pBR.x) / 2;
        ctx.save();
        ctx.translate(rightX + 30, cy);
        ctx.rotate(Math.PI / 2); // Rotate text for right side
        ctx.font = 'bold 14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#0f172a';
        ctx.textBaseline = 'bottom';
        ctx.textAlign = 'center';
        ctx.fillText(sDim + " m", 0, 0);
        ctx.font = '14px "Segoe UI", Arial, sans-serif';
        ctx.fillStyle = '#64748b';
        ctx.textBaseline = 'top';
        ctx.fillText(sLbl, 0, 5);
        ctx.restore();
    };

    window.downloadMapImage = function() {
        const canvas = document.getElementById('siteMapCanvas');
        if (!canvas) return;
        const link = document.createElement('a');
        link.download = 'site-map.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    };

    window.saveSiteVerification = async function () {
        const btn = document.getElementById('saveSiteVerificationBtn');
        if (!btn) return;

        btn.disabled = true;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Sending OTP...';

        try {
            const sendOtpUrl = '{{ route('engineer.applications.site-verification.send-otp', request()->route('id')) }}';
            const token = document.querySelector('form[data-csrf]') ? document.querySelector('form[data-csrf]').getAttribute('data-csrf') : document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const form = document.getElementById('siteVerificationForm');
            const formData = new FormData(form);

            formData.append('mapPlotNo', document.getElementById('mapPlotNo')?.value || '');
            formData.append('mapNorth', document.getElementById('mapNorth')?.value || '');
            formData.append('mapNorthLabel', document.getElementById('mapNorthLabel')?.value || '');
            formData.append('mapSouth', document.getElementById('mapSouth')?.value || '');
            formData.append('mapSouthLabel', document.getElementById('mapSouthLabel')?.value || '');
            formData.append('mapEast', document.getElementById('mapEast')?.value || '');
            formData.append('mapEastLabel', document.getElementById('mapEastLabel')?.value || '');
            formData.append('mapWest', document.getElementById('mapWest')?.value || '');
            formData.append('mapWestLabel', document.getElementById('mapWestLabel')?.value || '');

            const canvas = document.getElementById('siteMapCanvas');
            if (canvas) {
                formData.append('map_image_data', canvas.toDataURL('image/png'));
            }

            const response = await fetch(sendOtpUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Show modal
                document.getElementById('otpInput').value = '';
                document.getElementById('otpErrorMsg').classList.add('d-none');
                document.getElementById('resendTimerText').innerHTML = '<i class="fa-regular fa-clock me-1"></i> Resend OTP in <span id="resendTimerCount" class="text-primary fw-bold">30</span>s';
                startResendTimer();
                var otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
                otpModal.show();
            } else {
                alert('Error: ' + (result.message || 'Failed to send OTP'));
            }
        } catch (error) {
            console.error(error);
            alert('Server error occurred while sending OTP.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    };

    let resendInterval;
    window.startResendTimer = function() {
        clearInterval(resendInterval);
        const timerText = document.getElementById('resendTimerText');
        const countSpan = document.getElementById('resendTimerCount');
        const resendBtn = document.getElementById('resendOtpBtn');
        
        timerText.classList.remove('d-none');
        resendBtn.classList.add('d-none');
        
        let timeLeft = 30;
        countSpan.innerText = timeLeft;
        
        resendInterval = setInterval(() => {
            timeLeft--;
            countSpan.innerText = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(resendInterval);
                timerText.classList.add('d-none');
                resendBtn.classList.remove('d-none');
            }
        }, 1000);
    };

    window.resendOtp = async function(e) {
        if(e) e.preventDefault();
        const resendBtn = document.getElementById('resendOtpBtn');
        const timerText = document.getElementById('resendTimerText');
        const errorMsg = document.getElementById('otpErrorMsg');
        errorMsg.classList.add('d-none');

        resendBtn.classList.add('d-none');
        timerText.classList.remove('d-none');
        timerText.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Sending...';

        try {
            const sendOtpUrl = '{{ route('engineer.applications.site-verification.send-otp', request()->route('id')) }}';
            const token = document.querySelector('form[data-csrf]') ? document.querySelector('form[data-csrf]').getAttribute('data-csrf') : document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const form = document.getElementById('siteVerificationForm');
            const formData = new FormData(form);

            formData.append('mapPlotNo', document.getElementById('mapPlotNo')?.value || '');
            formData.append('mapNorth', document.getElementById('mapNorth')?.value || '');
            formData.append('mapNorthLabel', document.getElementById('mapNorthLabel')?.value || '');
            formData.append('mapSouth', document.getElementById('mapSouth')?.value || '');
            formData.append('mapSouthLabel', document.getElementById('mapSouthLabel')?.value || '');
            formData.append('mapEast', document.getElementById('mapEast')?.value || '');
            formData.append('mapEastLabel', document.getElementById('mapEastLabel')?.value || '');
            formData.append('mapWest', document.getElementById('mapWest')?.value || '');
            formData.append('mapWestLabel', document.getElementById('mapWestLabel')?.value || '');

            const canvas = document.getElementById('siteMapCanvas');
            if (canvas) {
                formData.append('map_image_data', canvas.toDataURL('image/png'));
            }

            const response = await fetch(sendOtpUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Reset timer HTML and start timer again
                timerText.innerHTML = '<i class="fa-regular fa-clock me-1"></i> Resend OTP in <span id="resendTimerCount" class="text-primary fw-bold">30</span>s';
                startResendTimer();
            } else {
                errorMsg.innerText = result.message || 'Failed to resend OTP';
                errorMsg.classList.remove('d-none');
                resendBtn.classList.remove('d-none');
                timerText.classList.add('d-none');
            }
        } catch (error) {
            console.error(error);
            errorMsg.innerText = 'Server error occurred while resending OTP.';
            errorMsg.classList.remove('d-none');
            resendBtn.classList.remove('d-none');
            timerText.classList.add('d-none');
        }
    };

    window.submitSiteVerification = async function() {
        const otpValue = document.getElementById('otpInput').value;
        const errorMsg = document.getElementById('otpErrorMsg');
        
        if (!otpValue || otpValue.length !== 6) {
            errorMsg.innerText = 'Please enter a valid 6-digit OTP.';
            errorMsg.classList.remove('d-none');
            return;
        }
        errorMsg.classList.add('d-none');

        const btn = document.getElementById('verifyOtpBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Verifying & Saving...';

        const form = document.getElementById('siteVerificationForm');
        const formData = new FormData(form);

        formData.append('otp', otpValue);
        formData.append('mapPlotNo', document.getElementById('mapPlotNo')?.value || '');
        formData.append('mapNorth', document.getElementById('mapNorth')?.value || '');
        formData.append('mapNorthLabel', document.getElementById('mapNorthLabel')?.value || '');
        formData.append('mapSouth', document.getElementById('mapSouth')?.value || '');
        formData.append('mapSouthLabel', document.getElementById('mapSouthLabel')?.value || '');
        formData.append('mapEast', document.getElementById('mapEast')?.value || '');
        formData.append('mapEastLabel', document.getElementById('mapEastLabel')?.value || '');
        formData.append('mapWest', document.getElementById('mapWest')?.value || '');
        formData.append('mapWestLabel', document.getElementById('mapWestLabel')?.value || '');

        const canvas = document.getElementById('siteMapCanvas');
        if (canvas) {
            formData.append('map_image_data', canvas.toDataURL('image/png'));
        }

        try {
            const url = form.getAttribute('action');
            const token = form.getAttribute('data-csrf');

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Remove modal backdrop
                const modalEl = document.getElementById('otpModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
                
                // Automatically redirect to the forward page, no alert needed
                window.location.href = result.redirect_url;
            } else {
                errorMsg.innerText = result.message || 'Something went wrong!';
                errorMsg.classList.remove('d-none');
            }
        } catch (error) {
            console.error(error);
            errorMsg.innerText = 'Server error occurred while saving.';
            errorMsg.classList.remove('d-none');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    };
    
    // Initial map generation
    setTimeout(() => {
        if(typeof generateSiteMap === 'function') generateSiteMap();
    }, 500);

    // Real-time canvas updates
    document.querySelectorAll('.map-input').forEach(input => {
        input.addEventListener('input', function() {
            if(typeof generateSiteMap === 'function') generateSiteMap();
        });
    });
</script>
@endsection

