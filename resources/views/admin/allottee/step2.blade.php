@php
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
            <div class="section-header gradient-header" style="background:linear-gradient(90deg,#8e2de2,#4a00e0)">
                <div class="section-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                </div>
                <div>
                    <h3 class="form-section-title">
                        Name and Full Address of Father/Husband
                    </h3>
                </div>
            </div>
            <div class="section-header gradient-header" style="background:linear-gradient(90deg,#8e2de2,#4a00e0)">
                <div class="section-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                </div>
                <div>
                    <h3 class="form-section-title">
                        पिता/पति का नाम एवं पूरा स्थायी पता
                    </h3>
                </div>
            </div>
            <!-- Relation Type -->
            <div class="field">
                <div class="field-inline">
                    <label>
                        <input type="radio" name="relation_type" value="father">
                        Father
                    </label>

                    <label>
                        <input type="radio" name="relation_type" value="husband">
                        Husband
                    </label>

                    <label>
                        <input type="radio" name="relation_type" value="wife">
                        Wife
                    </label>
                </div>
                <label class="field-label">Name</label>
                <div class="input-group">
                    @php $prefixes = ['Shri', 'Late', 'Smt.', 'Miss', 'M/S']; @endphp
                    <select name="prefix_relation_eng" class="prefix-select">
                        @foreach ($prefixes as $prefix)
                            <option value="{{ $prefix }}">
                                {{ $prefix }}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" name="relation_name" class="custom-input only-alphabet"
                        value="" placeholder="e.g. Rajesh">
                </div>
            </div>

            <div class="field">
                <div class="field-inline">
                    <label>
                        <input type="radio" name="relation_type_hindi" value="पिता">
                        पिता
                    </label>

                    <label>
                        <input type="radio" name="relation_type_hindi" value="पति">
                        पति
                    </label>
                    <label>
                        <input type="radio" name="relation_type_hindi" value="पत्नी">
                        पत्नी
                    </label>
                </div>
                <label class="field-label">नाम </label>
                <div class="input-group">
                    @php $prefixes = ['श्री', 'स्व०', 'श्रीमती', 'सुश्री', 'मेसर्स']; @endphp
                    <select name="prefix_relation_hindi" class="prefix-select">
                        @foreach ($prefixes as $prefix)
                            <option value="{{ $prefix }}">
                                {{ $prefix }}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" name="relation_name_hindi" class="custom-input only-hindi"
                        value="" placeholder="e.g. राजेश">
                </div>
            </div>

            <!-- Address -->
            <div class="field">
                <label class="field-label">Address</label>
                <textarea name="relation_address" class="custom-input only-address" rows="2" placeholder="Enter address">{{ $applicant->relation_address ?? '' }}</textarea>
            </div>

            <div class="field">
                <label class="field-label">पता </label>
                <textarea name="relation_address_hindi" class="custom-input only-hindi-address" rows="2"
                    placeholder="Enter address in Hindi"></textarea>
            </div>

            <!-- State (English) -->
            <div class="field">
                <label class="field-label">State</label>
                <select name="relation_state" class="custom-input state-select" data-target="relation-district-eng">
                    <option value="">-- Select State --</option>
                    @foreach ($states as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- State (Hindi) -->
            <div class="field">
                <label class="field-label">राज्य</label>
                <select name="relation_state_hindi" class="custom-input state-select-hindi"
                    data-target="relation-district-hi">
                    <option value="">-- राज्य चुनें --</option>
                    @foreach ($states as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name_hi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- District (English) -->
            <div class="field">
                <label class="field-label">District</label>
                <select name="relation_district" class="custom-input fetch-district" id="relation-district-eng">
                    <option value="">-- Select District --</option>
                    @if (!empty($relationDistricts))
                        @foreach ($relationDistricts as $dist)
                            <option value="{{ $dist->id }}">
                                {{ $dist->name_en }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- District (Hindi) -->
            <div class="field">
                <label class="field-label">जिला</label>
                <select name="relation_district_hindi" class="custom-input fetch-district-hindi"
                    id="relation-district-hi">
                    <option value="">-- जिला चुनें --</option>
                    @if (!empty($relationDistricts))
                        @foreach ($relationDistricts as $dist)
                            <option value="{{ $dist->id }}">
                                {{ $dist->name_hi }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="field">
                <label class="field-label">Pincode</label>
                <input type="text" name="relation_pincode" class="custom-input only-number" maxlength="6"
                    value="" placeholder="Enter 6-digit pincode">
            </div>

            <div class="field">
                <label class="field-label">पिनकोड </label>
                <input type="text" name="relation_pincode_hindi" class="custom-input only-number" maxlength="6"
                    value="" placeholder="Enter 6-digit pincode">
            </div>


            <div class="field">
                <label class="field-label">Post Office</label>
                <input type="text" name="relation_post_office" class="custom-input only-alphabet"
                    value="" placeholder="Enter post office name">
            </div>

            <div class="field">
                <label class="field-label">पोस्ट ऑफ़िस</label>
                <input type="text" name="relation_post_office_hindi" class="custom-input only-hindi"
                    value=""
                    placeholder="Enter post office name in Hindi">
            </div>

            <div class="field">
                <label class="field-label">Police Station</label>
                <input type="text" name="relation_police_station" class="custom-input only-alphabet"
                    value="" placeholder="Enter police station name">
            </div>

            <div class="field">
                <label class="field-label">पुलिस स्टेशन</label>
                <input type="text" name="relation_police_station_hindi" class="custom-input only-hindi"
                    value=""
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
                    value="" placeholder="Enter 10-digit mobile number">
            </div>

            <div class="field">
                <label class="field-label">
                    Alternate Mobile No.
                </label>
                <input type="text" name="alternate_mobile" class="custom-input only-number" maxlength="10"
                    value=""
                    placeholder="Enter 10-digit alternate mobile number">
            </div>

            <div class="field">
                <label class="field-label">
                    Landline (STD Code + Phone Number) (STD Code Start With 0)
                </label>
                <div class="input-group" style="gap :10px">
                    <input type="text" name="stdCode" class="prefix-select only-number" maxlength="5"
                        minlength="5" value="" placeholder="Enter stdCode number">
                    <input type="text" name="landline" class="custom-input only-number" maxlength="7"
                        minlength="5" value="" placeholder="Enter landline number">
                </div>
            </div>

            <div class="field">
                <label class="field-label">
                    WhatsApp No.
                </label>
                <input type="text" name="whatsapp_number" class="custom-input only-number" maxlength="10"
                    value="" placeholder="Enter 10-digit WhatsApp number">
            </div>

            <div class="field">
                <label class="field-label">
                    E-mail ID of Applicant
                </label>
                <input type="email" name="email" class="custom-input only-email"
                    value="" placeholder="Enter email address">
            </div>
        </div>
    </div>
</form>
