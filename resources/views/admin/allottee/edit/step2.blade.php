@php
    #return debug($applicant);
    $states = getStates();
    $relationDistricts = getDistrict(15);
    $presentDistricts = getDistrict(15);
    $permanentDistricts = getDistrict(15);
    $correspondenceDistricts = getDistrict(15);
@endphp
<form id="step2Form" method="POST">
    @csrf
    <input type="hidden" name="applicant_id" value="">

    <div class="form-section">
        <div class="bilingual-grid member-card" style="background: #faf9f6 !important;">
            <div class="section-header gradient-header" style="background: linear-gradient(90deg, #f59e0b, #f97316);">
                <div class="section-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                </div>
                <div>
                    <h3 class="form-section-title">Present Address</h3>
                </div>
            </div>
            <div class="section-header gradient-header" style="background: linear-gradient(90deg, #f59e0b, #f97316);">
                <div class="section-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                </div>
                <div>
                    <h3 class="form-section-title">वर्तमान पता</h3>
                </div>
            </div>

            <!-- Address -->
            <div class="field">
                <label class="field-label">Address</label>
                <textarea name="present_address" class="custom-input only-address" rows="2" placeholder="Enter address">{{ $applicant->present_address ?? '' }}</textarea>
            </div>

            <div class="field">
                <label class="field-label">पता</label>
                <textarea name="present_address_hindi" class="custom-input only-hindi-address" rows="2"
                    placeholder="Enter address in Hindi">{{ $applicant->present_address_hindi ?? '' }}</textarea>
            </div>

            <!-- State (English) -->
            <div class="field">
                <label class="field-label">State</label>
                <select name="present_state" class="custom-input state-select" data-target="present-district-eng">
                    <option value="">-- Select State --</option>
                    @foreach ($states as $item)
                        <option value="{{ $item->id }}"
                            {{ isset($applicant) && $applicant->present_state == $item->id ? 'selected' : '' }}>
                            {{ $item->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>


            <!-- State (Hindi) -->
            <div class="field">
                <label class="field-label">राज्य</label>
                <select name="present_state_hindi" class="custom-input state-select-hindi"
                    data-target="present-district-hi">
                    <option value="">-- राज्य चुनें --</option>
                    @foreach ($states as $item)
                        <option value="{{ $item->id }}"
                            {{ isset($applicant) && $applicant->present_state == $item->id ? 'selected' : '' }}>
                            {{ $item->name_hi }}
                        </option>
                    @endforeach

                </select>
            </div>


            <!-- District (English) -->
            <div class="field">
                <label class="field-label">District</label>
                <select name="present_district" class="custom-input fetch-district" id="present-district-eng">
                    <option value="">-- Select District --</option>
                    @if (!empty($presentDistricts))
                        @foreach ($presentDistricts as $dist)
                            <option value="{{ $dist->id }}"
                                {{ isset($applicant) && $applicant->present_district == $dist->id ? 'selected' : '' }}>
                                {{ $dist->name_en }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>


            <!-- District (Hindi) -->
            <div class="field">
                <label class="field-label">जिला</label>
                <select name="present_district_hindi" class="custom-input fetch-district-hindi"
                    id="present-district-hi">
                    <option value="">-- जिला चुनें --</option>
                    @if (!empty($presentDistricts))
                        @foreach ($presentDistricts as $dist)
                            <option value="{{ $dist->id }}"
                                {{ isset($applicant) && $applicant->present_district_hindi == $dist->id ? 'selected' : '' }}>
                                {{ $dist->name_hi }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="field">
                <label class="field-label">Pincode</label>
                <input type="text" name="present_pincode" class="custom-input only-number" maxlength="6"
                    value="{{ $applicant->present_pincode ?? '' }}" placeholder="Enter 6-digit pincode">
            </div>

            <div class="field">
                <label class="field-label">पिनकोड</label>
                <input type="text" name="present_pincode_hindi" class="custom-input only-number" maxlength="6"
                    value="{{ $applicant->present_pincode ?? '' }}" placeholder="Enter 6-digit pincode">
            </div>

            <div class="field">
                <label class="field-label">Post Office</label>
                <input type="text" name="present_post_office" class="custom-input only-alphabet"
                    value="{{ $applicant->present_post_office ?? '' }}" placeholder="Enter post office name">
            </div>
            <div class="field">
                <label class="field-label">पोस्ट ऑफ़िस</label>
                <input type="text" name="present_post_office_hindi" class="custom-input only-hindi"
                    value="{{ $applicant->present_post_office_hindi ?? '' }}"
                    placeholder="Enter post office name in Hindi">
            </div>

            <div class="field">
                <label class="field-label">Police Station</label>
                <input type="text" name="present_police_station" class="custom-input only-alphabet"
                    value="{{ $applicant->present_police_station ?? '' }}" placeholder="Enter police station name">
            </div>

            <div class="field">
                <label class="field-label">पुलिस स्टेशन</label>
                <input type="text" name="present_police_station_hindi" class="custom-input only-hindi"
                    value="{{ $applicant->present_police_station_hindi ?? '' }}"
                    placeholder="Enter police station name in Hindi">
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="bilingual-grid member-card" style="background: #faf9f6 !important;">
            <div class="section-header gradient-header" style="background:linear-gradient(90deg,#1e3c72,#2a5298)">
                <div class="section-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                </div>
                <div>
                    <h3 class="form-section-title">
                        Full permanent address of Applicant
                    </h3>
                </div>
            </div>
            <div class="section-header gradient-header" style="background:linear-gradient(90deg,#1e3c72,#2a5298)">
                <div class="section-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                </div>
                <div>
                    <h3 class="form-section-title">
                        आवेदक का पूरा स्थायी पता
                    </h3>
                </div>
            </div>

            <div class="field" style="display:flex; gap:8px; width:300px;">
                <label class="field-label" for="same_as_present_place_residance"
                    style="display:flex; align-items:center; gap:8px; margin:0; cursor:pointer; font-weight:600;">

                    Same as Present Address

                    <input type="checkbox" id="same_as_present_place_residance"
                        name="same_as_present_place_residance"
                        style="width:18px; height:18px; margin:0; cursor:pointer;"
                        {{ isset($applicant) && $applicant->same_as_present_place_residance == 'on' ? 'checked' : '' }}>

                </label>
            </div>
            <br>

            <!-- Address -->
            <div class="field">
                <label class="field-label">Address</label>
                <textarea name="permanent_address" class="custom-input only-address" rows="2" placeholder="Enter address">{{ $applicant->permanent_address ?? '' }}</textarea>
            </div>

            <div class="field">
                <label class="field-label">पता </label>
                <textarea name="permanent_address_hindi" class="custom-input only-hindi-address" rows="2"
                    placeholder="Enter address in Hindi">{{ $applicant->permanent_address_hindi ?? '' }}</textarea>
            </div>

            <!-- State (English) -->
            <div class="field">
                <label class="field-label">State</label>
                <select name="permanent_state" class="custom-input state-select"
                    data-target="permanent-district-eng">
                    <option value="">-- Select State --</option>
                    @foreach ($states as $item)
                        <option value="{{ $item->id }}"
                            {{ isset($applicant) && $applicant->permanent_state == $item->id ? 'selected' : '' }}>
                            {{ $item->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>


            <!-- State (Hindi) -->
            <div class="field">
                <label class="field-label">राज्य</label>
                <select name="permanent_state_hindi" class="custom-input state-select-hindi"
                    data-target="permanent-district-hi">
                    <option value="">-- राज्य चुनें --</option>
                    @foreach ($states as $item)
                        <option value="{{ $item->id }}"
                            {{ isset($applicant) && $applicant->permanent_state == $item->id ? 'selected' : '' }}>
                            {{ $item->name_hi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- District (English) -->
            <div class="field">
                <label class="field-label">District</label>
                <select name="permanent_district" class="custom-input fetch-district" id="permanent-district-eng">
                    <option value="">-- Select District --</option>
                    @if (!empty($permanentDistricts))
                        @foreach ($permanentDistricts as $dist)
                            <option value="{{ $dist->id }}"
                                {{ isset($applicant) && $applicant->permanent_district == $dist->id ? 'selected' : '' }}>
                                {{ $dist->name_en }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- District (Hindi) -->
            <div class="field">
                <label class="field-label">जिला</label>
                <select name="permanent_district_hindi" class="custom-input fetch-district-hindi"
                    id="permanent-district-hi">
                    <option value="">-- जिला चुनें --</option>
                    @if (!empty($permanentDistricts))
                        @foreach ($permanentDistricts as $dist)
                            <option value="{{ $dist->id }}"
                                {{ isset($applicant) && $applicant->permanent_district_hindi == $dist->id ? 'selected' : '' }}>
                                {{ $dist->name_hi }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="field">
                <label class="field-label">Pincode</label>
                <input type="text" name="permanent_pincode" class="custom-input only-number" maxlength="6"
                    value="{{ $applicant->permanent_pincode ?? '' }}" placeholder="Enter 6-digit pincode">
            </div>

            <div class="field">
                <label class="field-label">पिनकोड </label>
                <input type="text" name="permanent_pincode_hindi" class="custom-input only-number" maxlength="6"
                    value="{{ $applicant->permanent_pincode ?? '' }}" placeholder="Enter 6-digit pincode">
            </div>

            <div class="field">
                <label class="field-label">Post Office</label>
                <input type="text" name="permanent_post_office" class="custom-input only-alphabet"
                    value="{{ $applicant->permanent_post_office ?? '' }}" placeholder="Enter post office name">
            </div>

            <div class="field">
                <label class="field-label">पोस्ट ऑफ़िस</label>
                <input type="text" name="permanent_post_office_hindi" class="custom-input only-hindi"
                    value="{{ $applicant->permanent_post_office_hindi ?? '' }}"
                    placeholder="Enter post office name in Hindi">
            </div>

            <div class="field">
                <label class="field-label">Police Station</label>
                <input type="text" name="permanent_police_station" class="custom-input only-alphabet"
                    value="{{ $applicant->permanent_police_station ?? '' }}" placeholder="Enter police station name">
            </div>

            <div class="field">
                <label class="field-label">पुलिस स्टेशन</label>
                <input type="text" name="permanent_police_station_hindi" class="custom-input only-hindi"
                    value="{{ $applicant->permanent_police_station_hindi ?? '' }}"
                    placeholder="Enter police station name in Hindi">
            </div>

        </div>
    </div>

    <div class="form-section">

        <div class="section-header gradient-header" style="background:linear-gradient(90deg,#ff512f,#dd2476)">
            <div class="section-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2
                            19.86 19.86 0 0 1-8.63-3.07
                            19.5 19.5 0 0 1-6-6
                            19.86 19.86 0 0 1-3.07-8.67
                            A2 2 0 0 1 4.11 2h3
                            a2 2 0 0 1 2 1.72
                            c.12.81.37 1.6.72 2.34
                            a2 2 0 0 1-.45 2.11L8.09 9.91
                            a16 16 0 0 0 6 6l1.74-1.29
                            a2 2 0 0 1 2.11-.45
                            c.74.35 1.53.6 2.34.72
                            A2 2 0 0 1 22 16.92z" />
                </svg>
            </div>
            <div>
                <h3 class="form-section-title">Contact Details of Applicant</h3>
            </div>
        </div>
        <div class="form-grid member-card" style="background: #faf9f6 !important;">
            <div class="field">
                <label class="field-label">
                    Primary Mobile No. of Applicant
                </label>
                <input type="text" name="mobile_number" class="custom-input only-number" maxlength="10"
                    value="{{ old('mobile_number', $applicant->mobile_number ?? '') }}"
                    placeholder="Enter 10-digit mobile number">
            </div>

            <div class="field">
                <label class="field-label">
                    Alternate Mobile No.
                </label>
                <input type="text" name="alternate_mobile" class="custom-input only-number" maxlength="10"
                    value="{{ old('alternate_mobile', $applicant->alternate_mobile ?? '') }}"
                    placeholder="Enter 10-digit alternate mobile number">
            </div>

            <div class="field">
                <label class="field-label">
                    Landline (STD Code + Phone Number) (STD Code Start With 0)
                </label>
                <div class="input-group" style="gap :10px">
                    <input type="text" name="stdCode" class="prefix-select only-number" maxlength="5"
                        minlength="5" value="{{ old('stdCode', $applicant->stdCode ?? '') }}"
                        placeholder="Enter stdCode number">
                    <input type="text" name="landline" class="custom-input only-number" maxlength="7"
                        minlength="5" value="{{ old('landline', $applicant->landline ?? '') }}"
                        placeholder="Enter landline number">
                </div>
            </div>

            <div class="field">
                <label class="field-label">
                    WhatsApp No.
                </label>
                <input type="text" name="whatsapp_number" class="custom-input only-number" maxlength="10"
                    value="{{ old('whatsapp_number', $applicant->whatsapp_number ?? '') }}"
                    placeholder="Enter 10-digit WhatsApp number">
            </div>

            <div class="field">
                <label class="field-label">
                    E-mail ID of Applicant
                </label>
                <input type="email" name="email" class="custom-input only-email"
                    value="{{ old('email', $applicant->email ?? '') }}" placeholder="Enter email address">
            </div>
        </div>
    </div>
</form>
