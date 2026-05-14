{{-- overview.blade.php --}}
<div>
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div>
            <h5 class="fw-semibold mb-0" style="color: #1C3B4F;">Dashboard overview</h5>
            <p class="text-muted small">Key information at a glance</p>
        </div>
        <!-- <span class="badge-status" style="background: #EFF6FF; color: #2563EB;">Active allottee</span> -->
    </div>

    <div class="row g-3">
        @if (isset($allottee->property_number))
        <!-- property number -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-chip">
                <div class="stat-label">Property No.</div>
                <div class="stat-value">{{ $allottee->property_number ?: '—' }}</div>
            </div>
        </div>
        @endif
        <!-- Scheme -->
        <div class="col-6">
            <div class="stat-chip">
                <div class="stat-label">Scheme</div>
                <div class="stat-value text-truncate">{{ $allottee->scheme->scheme_name ?? '—' }}</div>
            </div>
        </div>
        <!-- division -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-chip">
                <div class="stat-label">Division</div>
                <div class="stat-value text-truncate">{{ $allottee->division->name ?? '—' }}</div>
            </div>
        </div>
        <!-- sub division -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-chip">
                <div class="stat-label">Sub division</div>
                <div class="stat-value text-truncate">{{ $allottee->subDivision->name ?? '—' }}</div>
            </div>
        </div>
        <!-- category -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-chip">
                <div class="stat-label">Category</div>
                <div class="stat-value">{{ $allottee->propertyCategory->name ?? '—' }}</div>
            </div>
        </div>
        <!-- property type -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-chip">
                <div class="stat-label">Property type</div>
                <div class="stat-value">{{ $allottee->propertyType->name ?? '—' }}</div>
            </div>
        </div>
        <!-- quarter type -->
        <div class="col-6">
            <div class="stat-chip">
                <div class="stat-label">Quarter type</div>
                <div class="stat-value">{{ ($allottee->quarterType->quarter_code ?? '') ?: '—' }}-{{ $allottee->quarterType->quarter_name ?? '' }}</div>
            </div>
        </div>

        @if (isset($allottee->payment_option) || isset($allottee->remaining_amount) || isset($allottee->emi_monthly_amount))
        <!-- payment option -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-chip">
                <div class="stat-label">Payment option</div>
                <div class="stat-value text-uppercase">{{ $allottee->payment_option ?? '—' }}</div>
            </div>
        </div>
        <!-- remaining amount -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-chip">
                <div class="stat-label">Remaining amount</div>
                <div class="stat-value">₹ {{ $allottee->remaining_amount ? number_format((float)$allottee->remaining_amount,2) : '—' }}</div>
            </div>
        </div>
        <!-- emi monthly -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-chip">
                <div class="stat-label">EMI monthly</div>
                <div class="stat-value">₹ {{ $allottee->emi_monthly_amount ? number_format((float)$allottee->emi_monthly_amount,2) : '—' }}</div>
            </div>
        </div>
        @endif

        <!-- application_no type -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-chip">
                <div class="stat-label">Application No.</div>
                <div class="stat-value">{{ $allottee->application_no ?? '' }}</div>
            </div>
        </div>

        <!-- application date -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-chip">
                <div class="stat-label">Application Date</div>
                <div class="stat-value">{{ $allottee->application_day ?? '' }}/{{ $allottee->application_month ?? '' }}/{{$allottee->application_year ?? ''}}</div>
            </div>
        </div>

        <!-- quarter type -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-chip">
                <div class="stat-label">Applicant Name</div>
                <div class="stat-value">{{ ($allottee->prefix ?? '') }} {{ $allottee->allottee_name ?? '' }} {{ $allottee->allottee_middle_name ?? '' }} {{ $allottee->allottee_surname ?? '' }}</div>
            </div>
        </div>
    </div>
</div>
<br>
<div>
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div>
            <h6 class="fw-semibold mb-0" style="color: #1C3B4F;">Letter overview</h6>
        </div>
        <!-- <span class="badge-status" style="background: #EFF6FF; color: #2563EB;">Active allottee</span> -->
    </div>

    <div class="row g-3">
        <!-- Allotment Letter -->
<!-- Allotment Letter Card -->
<div class="col-12 col-md-6 col-lg-4">

    @php
        $allotmentLetter = \App\Models\AllotteeGeneratedDocument::where([
            'allottee_id'   => $allottee->id,
            'document_type' => 'allotment-letter',
        ])->latest()->first();
    @endphp

    <div style="
        border:1px solid #e5e7eb;
        border-radius:16px;
        padding:18px;
        background:#ffffff;
        box-shadow:0 2px 10px rgba(0,0,0,0.04);
        height:100%;
        transition:0.3s;
    ">

        <!-- TOP -->

        <div style="
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:14px;
        ">

            <div style="
                display:flex;
                align-items:center;
                gap:12px;
            ">

                <!-- ICON -->

                <div style="
                    width:52px;
                    height:52px;
                    border-radius:14px;
                    background:linear-gradient(135deg,#dbeafe,#bfdbfe);
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    color:#2563eb;
                    font-size:22px;
                ">

                    <i class="fa-solid fa-file-pdf"></i>

                </div>

                <!-- TITLE -->

                <div>

                    <div style="
                        font-size:16px;
                        font-weight:700;
                        color:#111827;
                        line-height:1.2;
                    ">
                        Allotment Letter
                    </div>

                    <div style="
                        font-size:13px;
                        color:#6b7280;
                        margin-top:3px;
                    ">
                        Generated PDF Document
                    </div>

                </div>

            </div>


            <!-- STATUS -->

            @if($allotmentLetter)

                <div style="
                    background:#dcfce7;
                    color:#166534;
                    padding:4px 10px;
                    border-radius:999px;
                    font-size:11px;
                    font-weight:600;
                ">
                    Generated
                </div>

            @else

                <div style="
                    background:#fee2e2;
                    color:#991b1b;
                    padding:4px 10px;
                    border-radius:999px;
                    font-size:11px;
                    font-weight:600;
                ">
                    Pending
                </div>

            @endif

        </div>


        <!-- FILE INFO -->

        @if($allotmentLetter)

            <!-- <div style="
                font-size:13px;
                color:#6b7280;
                margin-bottom:16px;
                word-break:break-word;
            ">
                {{ $allotmentLetter->file_name }}
            </div> -->

        @else

            <div style="
                font-size:13px;
                color:#9ca3af;
                margin-bottom:16px;
            ">
                Letter not generated yet.
            </div>

        @endif


        <!-- ACTIONS -->

        <div style="
            display:flex;
            gap:10px;
        ">

            @if($allotmentLetter)

                <!-- VIEW -->

                <a
                    href="{{ asset($allotmentLetter->file_path) }}"
                    target="_blank"
                    style="
                        flex:1;
                        text-align:center;
                        padding:10px 14px;
                        border-radius:10px;
                        background:#eff6ff;
                        color:#2563eb;
                        font-size:13px;
                        font-weight:600;
                        text-decoration:none;
                        border:1px solid #bfdbfe;
                    "
                >
                    <i class="fa-solid fa-eye"></i>
                    View
                </a>


                <!-- DOWNLOAD -->

                <a
                    href="{{ route('admin.allottees.letters.allotment.pdf', ['allottee' => $allottee, 'download' => 1]) }}"
                    style="
                        flex:1;
                        text-align:center;
                        padding:10px 14px;
                        border-radius:10px;
                        background:linear-gradient(135deg,#2563eb,#1d4ed8);
                        color:#fff;
                        font-size:13px;
                        font-weight:600;
                        text-decoration:none;
                    "
                >
                    <i class="fa-solid fa-download"></i>
                    Download
                </a>

            @else

                <button
                    type="button"
                    disabled
                    style="
                        width:100%;
                        padding:10px;
                        border:none;
                        border-radius:10px;
                        background:#f3f4f6;
                        color:#9ca3af;
                        font-size:13px;
                        font-weight:600;
                        cursor:not-allowed;
                    "
                >
                    <i class="fa-solid fa-clock"></i>
                    Waiting For Generation
                </button>

            @endif

        </div>

    </div>

</div>
    </div>
</div>