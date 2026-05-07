@php
    #return getDebugIndex($applicant);
@endphp
<div class="review-section">
    <!-- Header with Application Number -->
    <div class="review-header">
        <h3 class="review-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 12l2 2 4-4"></path>
                <circle cx="12" cy="12" r="10"></circle>
            </svg>
            Review Your Application
        </h3>
        @if ($applicant)
            <div class="application-badge">
                <span class="badge-label">Application No:</span>
                <span class="badge-value">{{ $applicant->application_no }}</span>
            </div>
        @endif
    </div>

    <!-- Personal Details Table -->
    <div class="review-table-container">
        <div class="table-header" style="background: linear-gradient(90deg, #aa7700, #ffb703);">
            <div class="header-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="header-content">
                <h4>Personal Details</h4>
                <p>Allottee information verification</p>
            </div>
        </div>
        <table class="review-table">
            <tr>
                <td class="label-cell">Full Name (English)</td>
                <td class="value-cell">{{ $applicant->prefix ?? '' }} {{ $applicant->allottee_name ?? '' }}
                    {{ $applicant->allottee_middle_name ?? '' }} {{ $applicant->allottee_surname ?? '' }}</td>
                <td class="label-cell">Full Name (Hindi)</td>
                <td class="value-cell">{{ $applicant->allottee_prefix_hindi ?? '' }}
                    {{ $applicant->allottee_name_hindi ?? '' }} {{ $applicant->allottee_middle_hindi ?? '' }}
                    {{ $applicant->allottee_surname_hindi ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">{{ $applicant->allottee_relation_type ?? 'Father' }}'s Name</td>
                <td class="value-cell">{{ $applicant->relation_name ?? '' }}</td>
                <td class="label-cell">Date of Birth</td>
                <td class="value-cell">
                    {{ $applicant->date_of_birth_day ?? '' }}-{{ $applicant->date_of_birth_month ?? '' }}-{{ $applicant->date_of_birth_year ?? '' }}
                </td>
            </tr>
            <tr>
                <td class="label-cell">Gender</td>
                <td class="value-cell">{{ $applicant->allottee_gender ?? '' }}</td>
                <td class="label-cell">Marital Status</td>
                <td class="value-cell">{{ $applicant->marital_status ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Category</td>
                <td class="value-cell">{{ $applicant->allottee_category ?? '' }}</td>
                <td class="label-cell">Religion</td>
                <td class="value-cell">{{ $applicant->allottee_religion ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Nationality</td>
                <td class="value-cell">{{ $applicant->allottee_nationality ?? '' }}</td>
                <td class="label-cell">Age at Application</td>
                <td class="value-cell">{{ $applicant->age_number_of_birth_application ?? '' }} Years
                    ({{ $applicant->age_word_of_birth_application ?? '' }})</td>
            </tr>
            <tr>
                <td class="label-cell">PAN Card</td>
                <td class="value-cell mono">{{ $applicant->pan_card_number ?? '—' }}</td>
                <td class="label-cell">Aadhaar Card</td>
                <td class="value-cell mono">{{ $applicant->aadhar_card_number ?? '—' }}</td>
            </tr>
        </table>
    </div>

    <!-- Contact Details Table -->
    @if ($applicant->alloteeAdresses)
        <div class="review-table-container">
            <div class="table-header" style="background: linear-gradient(90deg, #00c6ff, #0072ff);">
                <div class="header-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8 10a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z">
                        </path>
                    </svg>
                </div>
                <div class="header-content">
                    <h4>Contact Details</h4>
                    <p>Contact information verification</p>
                </div>
            </div>
            <table class="review-table">
                <tr>
                    <td class="label-cell">Mobile Number</td>
                    <td class="value-cell mono">{{ $applicant->alloteeAdresses->mobile_number ?? '—' }}</td>
                    <td class="label-cell">Alternate Mobile</td>
                    <td class="value-cell mono">{{ $applicant->alloteeAdresses->alternate_mobile ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">WhatsApp Number</td>
                    <td class="value-cell mono">{{ $applicant->alloteeAdresses->whatsapp_number ?? '—' }}</td>
                    <td class="label-cell">Email</td>
                    <td class="value-cell">{{ $applicant->alloteeAdresses->email ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Landline</td>
                    <td class="value-cell">
                        {{ $applicant->alloteeAdresses->stdCode ?? '' }}-{{ $applicant->alloteeAdresses->landline ?? '' }}
                    </td>
                    <td class="label-cell"></td>
                    <td class="value-cell"></td>
                </tr>
            </table>
        </div>
    @endif

    <!-- Present Address Table -->
    @if ($applicant->alloteeAdresses && $applicant->alloteeAdresses->present_address)
        <div class="review-table-container">
            <div class="table-header" style="background: linear-gradient(90deg, #fc466b, #3f5efb);">
                <div class="header-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="header-content">
                    <h4>Present Address</h4>
                    <p>Current residential address</p>
                </div>
            </div>
            <table class="review-table">
                <tr>
                    <td class="label-cell" style="width: 15%;">Address</td>
                    <td class="value-cell" colspan="3">{{ $applicant->alloteeAdresses->present_address ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Post Office</td>
                    <td class="value-cell">{{ $applicant->alloteeAdresses->present_post_office ?? '' }}</td>
                    <td class="label-cell">Police Station</td>
                    <td class="value-cell">{{ $applicant->alloteeAdresses->present_police_station ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">District</td>
                    <td class="value-cell">{{ getDistrictName($applicant->alloteeAdresses->present_district) ?? '' }}
                    </td>
                    <td class="label-cell">State</td>
                    <td class="value-cell">{{ getStateName($applicant->alloteeAdresses->present_state) ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Pin Code</td>
                    <td class="value-cell mono">{{ $applicant->alloteeAdresses->present_pincode ?? '' }}</td>
                    <td class="label-cell"></td>
                    <td class="value-cell"></td>
                </tr>
            </table>
        </div>
    @endif

    <!-- Permanent Address Table -->
    @if ($applicant->alloteeAdresses && $applicant->alloteeAdresses->permanent_address)
        <div class="review-table-container">
            <div class="table-header" style="background: linear-gradient(90deg, #11998e, #38ef7d);">
                <div class="header-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 12l2-2 2 2 2-2 2 2 2-2 2 2 2-2 2 2"></path>
                        <path d="M5 21v-7M19 21v-7"></path>
                        <rect x="2" y="3" width="20" height="18" rx="2"></rect>
                    </svg>
                </div>
                <div class="header-content">
                    <h4>Permanent Address</h4>
                    <p>Permanent residential address</p>
                </div>
            </div>
            <table class="review-table">
                <tr>
                    <td class="label-cell" style="width: 15%;">Address</td>
                    <td class="value-cell" colspan="3">{{ $applicant->alloteeAdresses->permanent_address ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td class="label-cell">Post Office</td>
                    <td class="value-cell">{{ $applicant->alloteeAdresses->permanent_post_office ?? '' }}</td>
                    <td class="label-cell">Police Station</td>
                    <td class="value-cell">{{ $applicant->alloteeAdresses->permanent_police_station ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">District</td>
                    <td class="value-cell">
                        {{ getDistrictName($applicant->alloteeAdresses->permanent_district) ?? '' }}</td>
                    <td class="label-cell">State</td>
                    <td class="value-cell">{{ getStateName($applicant->alloteeAdresses->permanent_state) ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Pin Code</td>
                    <td class="value-cell mono">{{ $applicant->alloteeAdresses->permanent_pincode ?? '' }}</td>
                    <td class="label-cell"></td>
                    <td class="value-cell"></td>
                </tr>
            </table>
        </div>
    @endif

    <!-- Correspondence Address Table -->
    @if ($applicant->alloteeAdresses && $applicant->alloteeAdresses->correspondence_address)
        <div class="review-table-container">
            <div class="table-header" style="background: linear-gradient(90deg, #f7971e, #ffd200);">
                <div class="header-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
                <div class="header-content">
                    <h4>Correspondence Address</h4>
                    <p>Mailing address</p>
                </div>
            </div>
            <table class="review-table">
                <tr>
                    <td class="label-cell" style="width: 15%;">Address</td>
                    <td class="value-cell" colspan="3">
                        {{ $applicant->alloteeAdresses->correspondence_address ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Post Office</td>
                    <td class="value-cell">{{ $applicant->alloteeAdresses->correspondence_post_office ?? '' }}</td>
                    <td class="label-cell">Police Station</td>
                    <td class="value-cell">{{ $applicant->alloteeAdresses->correspondence_police_station ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">District</td>
                    <td class="value-cell">
                        {{ getDistrictName($applicant->alloteeAdresses->correspondence_district) ?? '' }}</td>
                    <td class="label-cell">State</td>
                    <td class="value-cell">{{ getStateName($applicant->alloteeAdresses->correspondence_state) ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td class="label-cell">Pin Code</td>
                    <td class="value-cell mono">{{ $applicant->alloteeAdresses->correspondence_pincode ?? '' }}</td>
                    <td class="label-cell"></td>
                    <td class="value-cell"></td>
                </tr>
            </table>
        </div>
    @endif

    <!-- Property Details Table -->
    <div class="review-table-container">
        <div class="table-header" style="background: linear-gradient(90deg, #834d9b, #d04ed6);">
            <div class="header-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <rect x="2" y="4" width="20" height="18" rx="2" ry="2"></rect>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
            </div>
            <div class="header-content">
                <h4>Property Details</h4>
                <p>Allotted property information</p>
            </div>
        </div>
        <table class="review-table">
            <tr>
                <td class="label-cell">Division</td>
                <td class="value-cell">{{ $applicant->division->name ?? '' }}
                    ({{ $applicant->division->division_code ?? '' }})</td>
                <td class="label-cell">Sub Division</td>
                <td class="value-cell">{{ $applicant->subDivision->name ?? '' }}
                    ({{ $applicant->subDivision->subdivision_code ?? '' }})</td>
            </tr>
            <tr>
                <td class="label-cell">Property Category</td>
                <td class="value-cell">{{ $applicant->propertyCategory->name ?? '' }}</td>
                <td class="label-cell">Property Type</td>
                <td class="value-cell">{{ $applicant->propertyType->name ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Property Number</td>
                <td class="value-cell mono">{{ $applicant->property_number ?? '' }}</td>
                <td class="label-cell">Allotment No.</td>
                <td class="value-cell mono">{{ $applicant->allotment_no ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Application No.</td>
                <td class="value-cell mono">{{ $applicant->application_no ?? '' }}</td>
                <td class="label-cell">Allotment Date</td>
                <td class="value-cell">
                    {{ $applicant->allotment_day ?? '' }}-{{ $applicant->allotment_month ?? '' }}-{{ $applicant->allotment_year ?? '' }}
                </td>
            </tr>
            <tr>
                <td class="label-cell">Scheme</td>
                <td class="value-cell">{{ getSchemeName($applicant->scheme_id) ?? '' }}</td>
                {{-- <td class="label-cell">Register File</td>
                <td class="value-cell">{{ $applicant->register_file_id ?? '' }}</td> --}}
            </tr>
            <tr>
                <td class="label-cell">No. of Files</td>
                <td class="value-cell">{{ $applicant->no_of_files ?? '' }}</td>
                <td class="label-cell">No. of Supplement</td>
                <td class="value-cell">{{ $applicant->no_of_supplement ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Total Pages</td>
                <td class="value-cell">{{ $applicant->total_pages ?? '' }}</td>
                {{-- <td class="label-cell">Current Step</td>
                <td class="value-cell">{{ $applicant->current_step ?? '' }}</td> --}}
            </tr>
        </table>
    </div>

    <!-- Property Financial Details -->
    @if ($applicant->allotProFinDetail)
        <div class="review-table-container">
            <div class="table-header" style="background: linear-gradient(90deg, #1f4037, #99f2c8);">
                <div class="header-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div class="header-content">
                    <h4>Property Financial Details</h4>
                    <p>Financial information</p>
                </div>
            </div>
            <table class="review-table">
                <tr>
                    <td class="label-cell">Tentative Price</td>
                    <td class="value-cell">₹
                        {{ number_format($applicant->allotProFinDetail->tentative_price ?? 0, 2) }}</td>
                    <td class="label-cell">Deposited Amount</td>
                    <td class="value-cell">₹
                        {{ number_format($applicant->allotProFinDetail->deposited_amount ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Remaining Amount</td>
                    <td class="value-cell">₹
                        {{ number_format($applicant->allotProFinDetail->remaining_amount ?? 0, 2) }}</td>
                    <td class="label-cell">Payment Months</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->payment_months ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Payment Start</td>
                    <td class="value-cell">
                        {{ $applicant->allotProFinDetail->payment_start_month ?? '' }}/{{ $applicant->allotProFinDetail->payment_start_year ?? '' }}
                    </td>
                    <td class="label-cell">Last Payment Due</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->last_payment_due_date ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Interest Type</td>
                    <td class="value-cell">{{ ucfirst($applicant->allotProFinDetail->interest_type ?? '') }}</td>
                    <td class="label-cell">Interest Amount</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->pre_interest_amount ?? '' }}
                        ({{ $applicant->allotProFinDetail->pre_interest ?? '' }})%</td>
                </tr>
                <tr>
                    <td class="label-cell">Late Interest AMount</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->late_interest_amount ?? '' }}
                        ({{ $applicant->allotProFinDetail->late_interest ?? '' }})%</td>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    <!-- Allotted Property Location -->
    @if ($applicant->allotProFinDetail)
        <div class="review-table-container">
            <div class="table-header" style="background: linear-gradient(90deg, #373b44, #4286f4);">
                <div class="header-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="header-content">
                    <h4>Allotted Property Location</h4>
                    <p>Property address details</p>
                </div>
            </div>
            <table class="review-table">
                <tr>
                    <td class="label-cell">Colony Name</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->colony_name ?? 'N/A' }}</td>
                    <td class="label-cell">Plot Number</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->plot_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Area (sq.ft.)</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->area_sqft ?? 'N/A' }}</td>
                    <td class="label-cell">Mohalla</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->mohalla ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Post Office</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->post_office ?? 'N/A' }}</td>
                    <td class="label-cell">Police Station</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->police_station ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">City</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->city ?? 'N/A' }}</td>
                    <td class="label-cell">District</td>
                    <td class="value-cell">{{ getDistrictName($applicant->allotProFinDetail->district) ?? 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td class="label-cell">State</td>
                    <td class="value-cell">{{ getStateName($applicant->allotProFinDetail->state) ?? 'N/A' }}</td>
                    <td class="label-cell">Lottery Details</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->lottery_details ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <!-- Property Boundaries -->
        <div class="review-table-container">
            <div class="table-header" style="background: linear-gradient(90deg, #42275a, #734b6d);">
                <div class="header-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="3" y1="9" x2="21" y2="9"></line>
                        <line x1="3" y1="15" x2="21" y2="15"></line>
                        <line x1="9" y1="3" x2="9" y2="21"></line>
                        <line x1="15" y1="3" x2="15" y2="21"></line>
                    </svg>
                </div>
                <div class="header-content">
                    <h4>Property Boundaries</h4>
                    <p>Land boundaries measurement</p>
                </div>
            </div>
            <table class="review-table">
                <tr>
                    <td class="label-cell">North Boundary</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->north_boundary ?? '0' }} ft</td>
                    <td class="label-cell">South Boundary</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->south_boundary ?? '0' }} ft</td>
                </tr>
                <tr>
                    <td class="label-cell">East Boundary</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->east_boundary ?? '0' }} ft</td>
                    <td class="label-cell">West Boundary</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->west_boundary ?? '0' }} ft</td>
                </tr>
                <tr>
                    <td class="label-cell">EW North</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->ew_north ?? '0' }} ft</td>
                    <td class="label-cell">EW South</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->ew_south ?? '0' }} ft</td>
                </tr>
                <tr>
                    <td class="label-cell">NS East</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->ns_east ?? '0' }} ft</td>
                    <td class="label-cell">NS West</td>
                    <td class="value-cell">{{ $applicant->allotProFinDetail->ns_west ?? '0' }} ft</td>
                </tr>
            </table>
        </div>
    @endif

    <!-- Nominee Details -->
    @if ($applicant->nomineesBank)
        <div class="review-table-container">
            <div class="table-header" style="background: linear-gradient(90deg, #c04848, #480048);">
                <div class="header-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div class="header-content">
                    <h4>Nominee Details</h4>
                    <p>Nominee information</p>
                </div>
            </div>
            <table class="review-table">
                <tr>
                    <td class="label-cell">Nominee Name</td>
                    <td class="value-cell">{{ $applicant->nomineesBank->nominee_prefix ?? '' }}
                        {{ $applicant->nomineesBank->nominee_name ?? 'N/A' }}</td>
                    <td class="label-cell">Relationship</td>
                    <td class="value-cell">{{ $applicant->nomineesBank->nominee_relationship ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Nominee PAN</td>
                    <td class="value-cell mono">{{ $applicant->nomineesBank->nominee_pan_card ?? '—' }}</td>
                    <td class="label-cell">Nominee Aadhaar</td>
                    <td class="value-cell mono">{{ $applicant->nomineesBank->nominee_aadhaar ?? '—' }}</td>
                </tr>
            </table>
        </div>
    @endif

    <!-- Family Details Table -->
    @if ($applicant->nomineesBank && $applicant->nomineesBank->family_name)
        <div class="review-table-container">
            <div class="table-header" style="background: linear-gradient(90deg, #11998e, #38ef7d);">
                <div class="header-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="header-content">
                    <h4>Family Details</h4>
                    <p>Family member information</p>
                </div>
            </div>
            <table class="review-table">
                <thead>
                    <tr>
                        <th class="table-subhead">Full Name</th>
                        <th class="table-subhead">Gender</th>
                        <th class="table-subhead">Date of Birth</th>
                        <th class="table-subhead">Relationship</th>
                        <th class="table-subhead">Aadhaar Number</th>
                        <th class="table-subhead">PAN Card</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $applicant->nomineesBank->family_name_prefix ?? '' }}
                            {{ $applicant->nomineesBank->family_name ?? '' }}</td>
                        <td>{{ $applicant->nomineesBank->family_gender ?? '' }}</td>
                        <td>{{ $applicant->nomineesBank->family_dob ?? '' }}</td>
                        <td>{{ $applicant->nomineesBank->family_relationship ?? '' }}</td>
                        <td class="mono">{{ $applicant->nomineesBank->family_aadhaar ?? '—' }}</td>
                        <td class="mono">{{ $applicant->nomineesBank->family_pan ?? '—' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    <!-- Bank Details Table -->
    @if ($applicant->nomineesBank && $applicant->nomineesBank->bank_name)
        <div class="review-table-container">
            <div class="table-header" style="background: linear-gradient(90deg, #f7971e, #ffd200);">
                <div class="header-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="2" y="6" width="20" height="16" rx="2"></rect>
                        <path d="M2 12h20"></path>
                        <path d="M7 12v5"></path>
                        <path d="M17 12v5"></path>
                        <path d="M7 6V4"></path>
                        <path d="M17 6V4"></path>
                    </svg>
                </div>
                <div class="header-content">
                    <h4>Bank Details</h4>
                    <p>Bank account information</p>
                </div>
            </div>
            <table class="review-table">
                <tr>
                    <td class="label-cell" style="width: 15%;">Bank Name</td>
                    <td class="value-cell" style="width: 35%;">{{ $applicant->nomineesBank->bank_name ?? '' }}</td>
                    <td class="label-cell" style="width: 15%;">Account Number</td>
                    <td class="value-cell mono" style="width: 35%;">
                        {{-- {{ substr($applicant->nomineesBank->bank_account_no ?? '', -4) ? '****' . substr($applicant->nomineesBank->bank_account_no, -4) : '—' }} --}}
                        {{ $applicant->nomineesBank->bank_account_no }}
                    </td>
                </tr>
                <tr>
                    <td class="label-cell">Branch</td>
                    <td class="value-cell">{{ $applicant->nomineesBank->bank_branch ?? '' }}</td>
                    <td class="label-cell">IFSC Code</td>
                    <td class="value-cell mono">{{ $applicant->nomineesBank->bank_ifsc ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Account Holder</td>
                    <td class="value-cell">{{ $applicant->nomineesBank->bank_account_holder ?? '' }}</td>
                    <td class="label-cell"></td>
                    <td class="value-cell"></td>
                </tr>
            </table>
        </div>
    @endif

    <!-- EMI Status -->
    @if ($applicant->accountLedger)
        <div class="review-table-container">
            <div class="table-header" style="background: linear-gradient(90deg, #232526, #414345);">
                <div class="header-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 12v3a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4v-3"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M3 3v5h5"></path>
                        <path d="M21 3v5h-5"></path>
                    </svg>
                </div>
                <div class="header-content">
                    <h4>EMI Status
                        @if ($applicant->is_emi_active)
                            <span
                                style="background: #4CAF50; color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.7rem; margin-left: 10px;">Active</span>
                        @else
                            <span
                                style="background: #9E9E9E; color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.7rem; margin-left: 10px;">Completed</span>
                        @endif
                    </h4>
                    <p>EMI payment status</p>
                </div>
            </div>
            @if ($applicant->accountLedger->emi_calculated)
                @php
                    $emiCalc = is_array($applicant->accountLedger->emi_calculated)
                        ? $applicant->accountLedger->emi_calculated
                        : json_decode($applicant->accountLedger->emi_calculated, true);
                @endphp
                <table class="review-table">
                    <tr>
                        <td class="label-cell">Total Amount</td>
                        <td class="value-cell">₹ {{ number_format($applicant->accountLedger->total_amount ?? 0, 2) }}
                        </td>
                        <td class="label-cell">Total EMI Count</td>
                        <td class="value-cell">{{ $applicant->accountLedger->total_emi_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">Completed EMI</td>
                        <td class="value-cell">{{ $applicant->accountLedger->completed_emi ?? 0 }}</td>
                        <td class="label-cell">Remaining EMI</td>
                        <td class="value-cell">{{ $applicant->accountLedger->remaining_emi ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">Total Paid</td>
                        <td class="value-cell">₹ {{ number_format($applicant->accountLedger->total_paid ?? 0, 2) }}
                        </td>
                        <td class="label-cell">Total Remaining</td>
                        <td class="value-cell">₹
                            {{ number_format($applicant->accountLedger->total_remaining ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">Current Balance</td>
                        <td class="value-cell">₹
                            {{ number_format($applicant->accountLedger->current_balance ?? 0, 2) }}</td>
                        <td class="label-cell">EMI Status</td>
                        <td class="value-cell">{{ $applicant->accountLedger->emi_status ?? '—' }}</td>
                    </tr>
                </table>
            @endif
        </div>
    @endif

    @php
        $isTransferred = $applicant->name_transfer_status === 'yes';
    @endphp
    <!-- Property Transfer Status -->
    <div class="review-table-container">
        <div class="table-header" style="background: linear-gradient(90deg,#aa4b6b,#6b6b83,#3b8d99);">
            <div class="header-content">
                <h4>Property Transfer Status</h4>
            </div>
        </div>

        <table class="review-table">
            <tr>
                <td class="label-cell" style="width:20%;">Status</td>
                <td class="value-cell" colspan="3">

                    <span
                        style="
                    display:inline-flex;
                    align-items:center;
                    gap:8px;
                    padding:4px 12px;
                    border-radius:20px;
                    color:#fff;
                    background: {{ $isTransferred ? '#4CAF50' : '#f44336' }};
                ">
                        {{ $isTransferred ? 'Property Transferred to Another Person' : 'Property Not Transferred' }}
                    </span>

                </td>
            </tr>
        </table>
    </div>

    @php
    $isFreeHold = ($applicant->free_hold_status ?? 'no') === 'yes';
    @endphp

    <!-- Lease / Free Hold Status -->

    <div class="review-table-container">
        <div class="table-header" style="background: linear-gradient(90deg,#11998e,#38ef7d);">
            <div class="header-content">
                <h4>Property Ownership Type</h4>
            </div>
        </div>
        <table class="review-table">

            <tr>

                <td class="label-cell" style="width:20%;">
                    Ownership Type
                </td>

                <td class="value-cell" colspan="3">

                    <span style="
                        display:inline-flex;
                        align-items:center;
                        gap:8px;
                        padding:4px 12px;
                        border-radius:20px;
                        color:#fff;
                        background: {{ $isFreeHold ? '#4CAF50' : '#ff9800' }};
                    ">

                        {{ $isFreeHold ? 'Free Hold Property' : 'Lease Hold Property' }}

                    </span>

                </td>

            </tr>

        </table>
    </div>

    <!-- Allottee Document Path -->
    <div class="review-table-container">
        <div class="table-header" style="background: linear-gradient(90deg,#aa4b6b,#6b6b83,#3b8d99);">
            <div class="header-content">
                <h4>Allottee Document Path</h4>
            </div>
        </div>

        <table class="review-table">
            <tr>
                <td class="label-cell" style="width:20%;">Path</td>
                <td class="value-cell" colspan="3">
                    <span>
                        {{ $applicant->allottee_document_path }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>
<form id="step6Form" method="POST">
    @csrf
    <input type="hidden" name="allottee_id" id="allottee_id" value="{{ $applicant->id ?? '' }}">

    @php
    $defaultRemarks = "
        Step 1 : -
        Step 2 : -
        Step 3 : -
        Step 4 : -
        Step 5 : -
    ";
    @endphp

{{-- ── Allottee Step Form Remarks ── --}}
<div class="form-section" style="margin-top:10px;">
    <div class="form-grid" style="grid-template-columns: repeat(2, 1fr) !important;">
        <div class="field">
            <label class="field-label">
                Is this property FIRST TIME registered or not ?
            </label>
            <select name="is_first_time_register" id="is_first_time_register" class="custom-input">
                <option value="1" {{ (old('is_first_time_register', $applicant->is_first_time_register) == '1') ? 'selected' : '' }}>
                    Yes</option>
                <option value="0" {{ (old('is_first_time_register', $applicant->is_first_time_register) == '0') ? 'selected' : '' }}>
                    No</option>
            </select>
        </div>
        <!-- <div class="field">
            <label class="field-label">
                Is this property earlier cancelled or not ?
            </label>
            <select name="is_earlier_cancelled" id="is_earlier_cancelled" class="custom-input">
                <option value="1" {{ (old('is_earlier_cancelled', $applicant->is_earlier_cancelled) == '1') ? 'selected' : '' }}>
                    Yes</option>
                <option value="0" {{ (old('is_earlier_cancelled', $applicant->is_earlier_cancelled) == '0') ? 'selected' : '' }}>
                    No</option>
            </select>
        </div> -->
    </div>
    <div class="form-grid" style="grid-template-columns: repeat(1, 1fr) !important;">
        <div class="field">
            <label class="field-label">
                Remarks of Step
            </label>

            <textarea name="step_remarks" id="step_remarks" rows="8" class="custom-input">{{ old('step_remarks', $applicant->step_remarks ?? $defaultRemarks) }}</textarea>

        </div>
    </div>
</div>
</form>
<style>
    .review-section {
        margin: 0 auto;
        /* font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; */
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .review-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
    }

    .review-title svg {
        color: #aa7700;
    }

    .application-badge {
        background: #f8f9fa;
        padding: 8px 16px;
        border-radius: 30px;
        border: 1px solid #e0e0e0;
        font-size: 0.9rem;
    }

    .badge-label {
        color: #666;
        margin-right: 8px;
    }

    .badge-value {
        color: #aa7700;
        font-weight: 600;
        /* font-family: monospace; */
        font-size: 1rem;
    }

    .review-table-container {
        margin-bottom: 25px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        background: white;
    }

    .table-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 20px;
        color: white;
    }

    .header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
    }

    .header-content h4 {
        margin: 0 0 4px;
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .header-content p {
        margin: 0;
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .review-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .review-table tr {
        border-bottom: 1px solid #f0f0f0;
    }

    .review-table tr:last-child {
        border-bottom: none;
    }

    .review-table td {
        padding: 12px 15px;
        font-size: 0.9rem;
    }

    .label-cell {
        background: #f8f9fa;
        font-weight: 500;
        color: #666;
        width: 15%;
        border-right: 1px solid #f0f0f0;
    }

    .value-cell {
        color: #333;
        font-weight: 400;
        width: 35%;
    }

    .review-table th {
        background: #f8f9fa;
        padding: 10px 15px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #555;
        text-align: left;
        border-bottom: 2px solid #e0e0e0;
    }

    .table-subhead {
        background: #f8f9fa;
        font-size: 0.85rem;
        font-weight: 600;
        color: #555;
    }

    .mono {
        /* font-family: 'Courier New', monospace; */
        font-weight: 500;
    }

    .review-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .btn-edit,
    .btn-confirm {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        border: none;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-edit {
        background: white;
        color: #666;
        border: 1px solid #ddd;
    }

    .btn-edit:hover {
        background: #f8f9fa;
        border-color: #999;
    }

    .btn-confirm {
        background: #aa7700;
        color: white;
    }

    .btn-confirm:hover {
        background: #8b6200;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(170, 119, 0, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .review-section {
            padding: 15px;
        }

        .review-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .review-table,
        .review-table tbody,
        .review-table tr,
        .review-table td {
            display: block;
        }

        .review-table tr {
            margin-bottom: 10px;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
        }

        .review-table td {
            display: flex;
            padding: 10px;
            border: none;
        }

        .label-cell {
            width: 40%;
            background: none;
            border: none;
        }

        .value-cell {
            width: 60%;
        }

        .review-table th {
            display: none;
        }
    }

    /* Compact Mode */
    @media (min-width: 1200px) {
        .review-table td {
            padding: 10px 15px;
            font-size: 0.85rem;
        }

        .review-table-container {
            margin-bottom: 20px;
        }

        .table-header {
            padding: 12px 20px;
        }
    }
</style>
