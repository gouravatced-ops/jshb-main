<div>
@php
    $verification = $allottee->siteVerification ?? null;
    $mapParams = $verification ? json_decode($verification->map_parameters ?? '{}', true) : [];
    $propertyCategory = getPropertyCategory();
@endphp

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">
                Site Verification (स्थल निरीक्षण)
            </h1>

            <p class="page-subtitle">
                Site Verification ·
                Application :
                {{ $allottee->application_no ?? '-' }}
            </p>
        </div>
        <button class="btn-ghost" onclick="window.close();">
            <i class="fa-solid fa-arrow-left"></i>
            Back to List
        </button>
    </div>

    {{-- Form Content --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 text-center fw-bold" style="letter-spacing: 0.5px;">
                {{-- झारखण्ड राज्य आवास बोर्ड, राँची प्रमंडल<br> --}}
                जॉँच प्रपत्र (चेक लिस्ट)<br>
                {{-- <small class="text-light fw-normal mt-2 d-block" style="font-size: 0.9rem;">अन्तिम हस्तान्तरण से संबंधित कार्यपालक अभियंता का स्थल निरीक्षण प्रतिवेदन</small> --}}
            </h5>
        </div>
        <div class="card-body p-4 bg-light">
            <form id="siteVerificationForm" action="{{ route('admin.allottees.site-verification.store', $allottee->id) }}" data-csrf="{{ csrf_token() }}">
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
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0 text-center fw-bold"><i class="fa-solid fa-map-location-dot me-2 text-warning"></i> स्थल मानचित्र जनरेटर (Site Map Generator)</h5>
        </div>
        <div class="card-body p-4">
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
                            <input type="number" class="form-control map-input" id="mapNorth" value="{{ $mapParams['north']}}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small">North Label</label>
                            <input type="text" class="form-control map-input" id="mapNorthLabel" value="{{ $mapParams['northLabel']}}">
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fa-solid fa-arrow-right text-danger"></i> South Dim (m)</label>
                            <input type="number" class="form-control map-input" id="mapSouth" value="{{ $mapParams['south']}}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small">South Label</label>
                            <input type="text" class="form-control map-input" id="mapSouthLabel" value="{{ $mapParams['southLabel']}}">
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fa-solid fa-arrow-up text-success"></i> East Dim (m)</label>
                            <input type="number" class="form-control map-input" id="mapEast" value="{{ $mapParams['east']}}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small">East Label</label>
                            <input type="text" class="form-control map-input" id="mapEastLabel" value="{{ $mapParams['eastLabel']}}">
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small"><i class="fa-solid fa-arrow-down text-warning"></i> West Dim (m)</label>
                            <input type="number" class="form-control map-input" id="mapWest" value="{{ $mapParams['west']}}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-muted small">West Label</label>
                            <input type="text" class="form-control map-input" id="mapWestLabel" value="{{ $mapParams['westLabel']}}">
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
        <button type="button" id="saveSiteVerificationBtn" class="btn btn-success px-5 py-2 fs-5 rounded-pill shadow-sm" onclick="saveSiteVerification()"><i class="fa-solid fa-save me-2"></i> Save Site</button>
    </div>

</div>

