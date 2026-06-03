@php
    #return debug($applicant);
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
                    {{ $applicant->allottee_middle_name ?? '' }} {{ $applicant->allottee_surname ?? '' }}
                </td>
                <td class="label-cell">Full Name (Hindi)</td>
                <td class="value-cell">{{ $applicant->allottee_prefix_hindi ?? '' }}
                    {{ $applicant->allottee_name_hindi ?? '-' }} {{ $applicant->allottee_middle_hindi ?? '' }}
                    {{ $applicant->allottee_surname_hindi ?? '' }}
                </td>
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
                <td class="label-cell">PAN Card</td>
                <td class="value-cell mono">{{ $applicant->pan_card_number ?? '—' }}</td>
                <td class="label-cell">Aadhaar Card</td>
                <td class="value-cell mono">{{ $applicant->aadhar_card_number ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Nationality</td>
                <td class="value-cell">{{ $applicant->allottee_nationality ?? '' }}</td>
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
                        {{ getDistrictName($applicant->alloteeAdresses->permanent_district) ?? '' }}
                    </td>
                    <td class="label-cell">State</td>
                    <td class="value-cell">{{ getStateName($applicant->alloteeAdresses->permanent_state) ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Pin Code</td>
                    <td class="value-cell mono">{{ $applicant->alloteeAdresses->permanent_pincode ?? '' }}</td>
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
            <!-- <tr>
                <td class="label-cell">Property Number</td>
                <td class="value-cell mono">{{ $applicant->property_number ?? '' }}</td>
                <td class="label-cell">Allotment No.</td>
                <td class="value-cell mono">{{ $applicant->allotment_no ?? '' }}</td>
            </tr> -->
            <tr>
                <td class="label-cell">Application No.</td>
                <td class="value-cell mono">{{ $applicant->application_no ?? '' }}</td>
                <!-- <td class="label-cell">Allotment Date</td>
                <td class="value-cell">
                    {{ $applicant->allotment_day ?? '' }}-{{ $applicant->allotment_month ?? '' }}-{{ $applicant->allotment_year ?? '' }}
                </td> -->
            </tr>
            <tr>
                <td class="label-cell">Scheme</td>
                <td class="value-cell">{{ getSchemeName($applicant->scheme_id) ?? '' }}</td>
                {{-- <td class="label-cell">Register File</td>
                <td class="value-cell">{{ $applicant->register_file_id ?? '' }}</td> --}}
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
                        {{ number_format($applicant->allotProFinDetail->tentative_price ?? 0, 2) }}
                    </td>
                    <td class="label-cell">Deposited Amount</td>
                    <td class="value-cell">₹
                        {{ number_format($applicant->allotProFinDetail->deposited_amount ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <td class="label-cell">Remaining Amount</td>
                    <td class="value-cell">₹
                        {{ number_format($applicant->allotProFinDetail->remaining_amount ?? 0, 2) }}
                    </td>
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

</div>
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
        background: #212529;
        font-weight: 700;
        color: #ffffff;
        width: 15%;
        border-right: 1px solid #f0f0f0;
    }

    .value-cell {
        color: #131313;
        font-weight: 700;
        width: 35%;
        font-size: 14px;
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
        color: #131313;
        font-weight: 700;
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
