{{-- resources/views/admin/allottee/sections/allottee-details.blade.php --}}
<div>
    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                Allottee Information
            </h1>
            <p class="page-subtitle">
                Key information at a glance · Application
                {{ $allottee->application_no ?? 'JSHBA-24928374' }}
            </p>
        </div>
        <button class="btn-ghost" onclick="window.close();">
            <i class="fa-solid fa-arrow-left"></i>
            Back to List
        </button>
    </div>
    <!-- BASIC DETAILS -->
    <div class="row g-3 mb-4">
        <!-- APPLICATION NO -->
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-hashtag me-1"></i>
                    Application No.
                </p>
                <p class="info-card-value" style="font-family:'DM Mono',monospace;">
                    {{ $allottee->application_no ?? '—' }}
                </p>
            </div>
        </div>
        <!-- APPLICATION DATE -->
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-regular fa-calendar me-1"></i>
                    Application Date
                </p>
                <p class="info-card-value">
                    {{ sprintf('%02d', $allottee->application_day ?? 0) }}/{{ sprintf('%02d', $allottee->application_month ?? 0) }}/{{ $allottee->application_year ?? '—' }}
                </p>
            </div>
        </div>
        <!-- APPLICANT NAME -->
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-user me-1"></i>
                    Applicant Name
                </p>
                <p class="info-card-value">
                    {{ trim(($allottee->prefix ?? '') . ' ' . ($allottee->allottee_name ?? '') . ' ' . ($allottee->allottee_middle_name ?? '') . ' ' . ($allottee->allottee_surname ?? '')) }}
                </p>
            </div>
        </div>
    </div>
    <!-- PERSONAL DETAILS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-id-card me-1"></i>
                    PAN Number
                </p>
                <p class="info-card-value">
                    {{ $allottee->pan_card_number ?? '—' }}
                    {{-- &nbsp;<span id="copytheValue"
                        data-value="{{ $allottee->pan_card_number }}" style="cursor:pointer;">
                        <i class="fa-regular fa-copy me-1"></i>
                    </span> --}}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-fingerprint me-1"></i>
                    Aadhaar Number
                </p>
                <p class="info-card-value">
                    {{ $allottee->aadhar_card_number ?? '—' }}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-venus-mars me-1"></i>
                    Gender
                </p>
                <p class="info-card-value">
                    {{ $allottee->allottee_gender ?? '—' }}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-ring me-1"></i>
                    Marital Status
                </p>
                <p class="info-card-value">
                    {{ $allottee->marital_status ?? '—' }}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-user-group me-1"></i>
                    Relation Type {{ $allottee->allottee_relation_type ?? '—' }}
                </p>
                <p class="info-card-value">
                    {{ $allottee->relation_name ?? '—' }}
                </p>
            </div>
        </div>
    </div>
    {{-- Contact Informations Section --}}
    <div class="section-title"><i class="fa-solid fa-phone me-2"></i>Contact Information</div>
    <!-- ADDRESS DETAILS -->
    <div class="row g-3 mb-4">
        <!-- MOBILE -->
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-phone me-1"></i>
                    Mobile Number
                </p>
                <p class="info-card-value">
                    {{ $allottee->alloteeAdresses->mobile_number ?? '—' }}
                </p>
            </div>
        </div>
        <!-- ALT MOBILE -->
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-mobile-screen-button me-1"></i>
                    Alternate Mobile
                </p>
                <p class="info-card-value">
                    {{ $allottee->alloteeAdresses->alternate_mobile ?? '—' }}
                </p>
            </div>
        </div>
        <!-- WHATSAPP -->
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-brands fa-whatsapp me-1"></i>
                    WhatsApp Number
                </p>
                <p class="info-card-value">
                    {{ $allottee->alloteeAdresses->whatsapp_number ?? '—' }}
                </p>
            </div>
        </div>
        @if (isset($allottee->alloteeAdresses->stdCode) && isset($allottee->alloteeAdresses->landline))
            <!-- LANDLINE -->
            <div class="col-md-4">
                <div class="info-card">
                    <p class="info-card-label">
                        <i class="fa-solid fa-phone me-1"></i>
                        Landline Number
                    </p>
                    <p class="info-card-value">
                        {{ $allottee->alloteeAdresses->stdCode ?? '—' }}-{{ $allottee->alloteeAdresses->landline ?? '—' }}
                    </p>
                </div>
            </div>
        @endif
        <!-- EMAIL -->
        <div class="col-md-6">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-envelope me-1"></i>
                    Email Address
                </p>
                <p class="info-card-value">
                    {{ $allottee->alloteeAdresses->email ?? '—' }}
                </p>
            </div>
        </div>
    </div>
    {{-- Present Address Section --}}
    <div class="section-title"><i class="fa-solid fa-map-location-dot me-2"></i>Present Address</div>
    <!-- ADDRESS DETAILS -->
    <div class="row g-3 mb-4">
        <!-- Present Address -->
        @if ($allottee->alloteeAdresses->present_address)
            <div class="col-md-6">
                <div class="info-card">
                    <p class="info-card-label">
                        <i class="fa-solid fa-location-dot me-1"></i>
                        Present Address
                    </p>
                    <p class="info-card-value">
                        {{ $allottee->alloteeAdresses->present_address }}
                    </p>
                </div>
            </div>
        @endif
        <!-- Present Post Office -->
        @if ($allottee->alloteeAdresses->present_post_office)
            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        <i class="fa-solid fa-building me-1"></i>
                        Post Office
                    </p>
                    <p class="info-card-value">
                        {{ $allottee->alloteeAdresses->present_post_office }}
                    </p>
                </div>
            </div>
        @endif
        <!-- Present Police Station -->
        @if ($allottee->alloteeAdresses->present_police_station)
            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        <i class="fa-solid fa-shield-halved me-1"></i>
                        Police Station
                    </p>
                    <p class="info-card-value">
                        {{ $allottee->alloteeAdresses->present_police_station }}
                    </p>
                </div>
            </div>
        @endif
        <!-- Present Pincode -->
        @if ($allottee->alloteeAdresses->present_pincode)
            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        <i class="fa-solid fa-map-pin me-1"></i>
                        Pincode
                    </p>
                    <p class="info-card-value">
                        {{ $allottee->alloteeAdresses->present_pincode }}
                    </p>
                </div>
            </div>
        @endif
        <!-- Present District -->
        @if ($allottee->alloteeAdresses->present_district)
            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        <i class="fa-solid fa-location-dot me-1"></i>
                        District
                    </p>
                    <p class="info-card-value">
                        {{ getdistrictNameById($allottee->alloteeAdresses->present_district) }}
                    </p>
                </div>
            </div>
        @endif
        <!-- Present State -->
        @if ($allottee->alloteeAdresses->present_state)
            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        <i class="fa-solid fa-location-dot me-1"></i>
                        State
                    </p>
                    <p class="info-card-value">
                        {{ getStateName($allottee->alloteeAdresses->present_state) }}
                    </p>
                </div>
            </div>
        @endif
    </div>
    {{-- Permanent Address Section --}}
    <div class="section-title"><i class="fa-solid fa-house me-2"></i>Permanent Address</div>
    <!-- ADDRESS DETAILS -->
    <div class="row g-3 mb-4">
        <!-- Present Address -->
        @if ($allottee->alloteeAdresses->permanent_address)
            <div class="col-md-6">
                <div class="info-card">
                    <p class="info-card-label">
                        <i class="fa-solid fa-location-dot me-1"></i>
                        Permanent Address
                    </p>
                    <p class="info-card-value">
                        {{ $allottee->alloteeAdresses->permanent_address }}
                    </p>
                </div>
            </div>
        @endif
        <!-- Present Post Office -->
        @if ($allottee->alloteeAdresses->permanent_post_office)
            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        <i class="fa-solid fa-building me-1"></i>
                        Post Office
                    </p>
                    <p class="info-card-value">
                        {{ $allottee->alloteeAdresses->permanent_post_office }}
                    </p>
                </div>
            </div>
        @endif
        <!-- Present Police Station -->
        @if ($allottee->alloteeAdresses->permanent_police_station)
            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        <i class="fa-solid fa-shield-halved me-1"></i>
                        Police Station
                    </p>
                    <p class="info-card-value">
                        {{ $allottee->alloteeAdresses->permanent_police_station }}
                    </p>
                </div>
            </div>
        @endif
        <!-- Present Pincode -->
        @if ($allottee->alloteeAdresses->permanent_pincode)
            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        <i class="fa-solid fa-map-pin me-1"></i>
                        Pincode
                    </p>
                    <p class="info-card-value">
                        {{ $allottee->alloteeAdresses->permanent_pincode }}
                    </p>
                </div>
            </div>
        @endif
        <!-- Present District -->
        @if ($allottee->alloteeAdresses->permanent_district)
            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        <i class="fa-solid fa-location-dot me-1"></i>
                        District
                    </p>
                    <p class="info-card-value">
                        {{ getdistrictNameById($allottee->alloteeAdresses->permanent_district) }}
                    </p>
                </div>
            </div>
        @endif
        <!-- Present State -->
        @if ($allottee->alloteeAdresses->permanent_state)
            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        <i class="fa-solid fa-location-dot me-1"></i>
                        State
                    </p>
                    <p class="info-card-value">
                        {{ getStateName($allottee->alloteeAdresses->permanent_state) }}
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
