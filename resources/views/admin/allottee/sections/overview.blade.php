{{-- resources/views/admin/allottee/sections/overview.blade.php --}}
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard Overview</h1>
            <p class="page-subtitle">Key information at a glance · Application
                {{ $allottee->application_no ?? '-' }}</p>
        </div>
        <button class="btn-ghost" onclick="window.close();">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </button>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label"><i class="fa-solid fa-layer-group me-1"></i>Scheme</p>
                <p class="info-card-value">{{ $allottee->scheme->scheme_name ?? '—' }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label"><i class="fa-solid fa-map-pin me-1"></i>Division / Sub Division</p>
                <p class="info-card-value">{{ $allottee->division->name ?? '—' }} /
                    {{ $allottee->subDivision->name ?? '—' }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label"><i class="fa-solid fa-tag me-1"></i>Category</p>
                <p class="info-card-value">{{ $allottee->propertyCategory->name ?? '—' }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label"><i class="fa-solid fa-house me-1"></i>Property Type</p>
                <p class="info-card-value">{{ $allottee->propertyType->name ?? '—' }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label"><i class="fa-solid fa-people-group me-1"></i>Quarter Type</p>
                <p class="info-card-value">
                    {{ $allottee->quarterType->quarter_code ?? '' ?: '—' }}-{{ $allottee->quarterType->quarter_name ?? '' }}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label">
                    <i class="fa-solid fa-house me-1"></i> Property Number
                </p>
                <p class="info-card-value badge bg-success ms-2 text-white">
                    {{ $allottee->property_number ?: '—' }}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label"><i class="fa-solid fa-hashtag me-1"></i>Application No.</p>
                <p class="info-card-value" style="font-family:'DM Mono',monospace;">
                    {{ $allottee->application_no ?? '—' }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label"><i class="fa-regular fa-calendar me-1"></i>Application Date</p>
                <p class="info-card-value">
                    {{ $allottee->application_day ?? '' }}/{{ $allottee->application_month ?? '' }}/{{ $allottee->application_year ?? '' }}
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <p class="info-card-label"><i class="fa-solid fa-user me-1"></i>Applicant Name</p>
                <p class="info-card-value">
                    {{ trim(($allottee->prefix ?? '') . ' ' . ($allottee->allottee_name ?? '') . ' ' . ($allottee->allottee_middle_name ?? '') . ' ' . ($allottee->allottee_surname ?? '')) }}
                </p>
            </div>
        </div>
    </div>
    <div class="section-title">
        <i class="fa-solid fa-file-circle-check me-2"></i>
        Document Records
    </div>
    <div class="letter-grid">
        @foreach ($allottee->generatedDocument as $key => $document)
            @php
                $isSigned = !empty($document->signed_file_path);
                $isGenerated = !$isSigned && !empty($document->file_path);
                $documentType = $document->document_type;

                $filePath = $isSigned ? $document->signed_file_path : $document->file_path;
                $statusLabel = '';
                $subTitle = '';
                if ($documentType == 'agreement') {
                    $statusLabel = 'Uploaded';
                    $subTitle = 'Document Uploaded By Admin';
                } else {
                    $statusLabel = $isSigned ? 'Signed' : 'Generated';
                    $subTitle = $isSigned ? 'Your signed document is available' : 'Signed Document Uploded';
                }
            @endphp

            <div class="letter-hero letter-hero{{ $key + 1 }}">

                <p class="letter-hero-title">
                    <i class="fa-solid fa-envelope-open-text me-2"></i>

                    {{ ucwords($document->document_name) }}

                    <span class="badge bg-dark ms-2">
                        {{ $statusLabel }}
                    </span>
                </p>

                <p class="letter-hero-sub">
                    {{ $subTitle }}
                </p>

                <div class="app-no">
                    {{ $allottee->application_no ?? 'N/A' }}
                </div>

                <div
                    style="
                    display:flex;
                    gap:10px;
                    margin-top:18px;
                    flex-wrap:nowrap;
                ">

                    <!-- VIEW -->
                    <a href="{{ asset($filePath) }}" target="_blank" class="btn-brand"
                        style="
                            background:rgba(255,255,255,.2);
                            border:1.5px solid rgba(255,255,255,.4)
                        ">
                        <i class="fa-solid fa-eye"></i>
                        View
                    </a>

                    <!-- DOWNLOAD -->
                    <a href="{{ asset($filePath) }}" download class="btn-brand"
                        style="
                    background:rgba(255,255,255,.95);
                    color:var(--brand)
                ">
                        <i class="fa-solid fa-download"></i>
                        Download
                    </a>

                </div>
            </div>
        @endforeach
    </div>
    <!-- @php
        $allotmentLetter = \App\Models\AllotteeGeneratedDocument::where([
            'allottee_id' => $allottee->id,
            'document_type' => 'allotment-letter',
        ])
            ->latest()
            ->first();
    @endphp
    <div class="section-title"><i class="fa-solid fa-folder-open text-success me-2"></i>Required Documents</div>
    <div class="doc-row">
        <div class="doc-icon gen"><i class="fa-solid fa-file-pdf"></i></div>
        <div class="doc-info">
            <p class="doc-name">Allotment Letter</p>
            <p class="doc-meta">Generated PDF Document · {{ $allotmentLetter ? $allotmentLetter->created_at->format('d M Y') : 'Not generated' }}</p>
        </div>
        <span class="badge-status badge-generated">{{ $allotmentLetter ? 'Generated' : 'Pending' }}</span>
        <div class="d-flex gap-2">
            @if ($allotmentLetter)
<a href="{{ asset($allotmentLetter->file_path) }}" target="_blank" class="btn-ghost"><i class="fa-solid fa-eye"></i> View</a>
            <a href="{{ route('admin.allottees.letters.allotment.pdf', ['allottee' => $allottee, 'download' => 1]) }}" class="btn-brand"><i class="fa-solid fa-download"></i></a>
@else
<button class="btn-ghost" disabled>Not Available</button>
@endif
        </div>
    </div> -->
    <!-- @php
        $documents = [
            [
                'name' => 'Aadhaar Card (ID Proof)',
                'icon' => 'fa-solid fa-id-card',
                'icon_class' => 'img',
                'status' => 'uploaded',
                'meta' => 'Identity Document · JPG / PNG / PDF · Max 2MB',
            ],
            [
                'name' => 'Income Certificate',
                'icon' => 'fa-solid fa-file-lines',
                'icon_class' => 'doc',
                'status' => 'pending',
                'meta' => 'Issued by competent authority · PDF only · Max 2MB',
            ],
            [
                'name' => 'Address Proof',
                'icon' => 'fa-solid fa-location-dot',
                'icon_class' => 'img',
                'status' => 'required',
                'meta' => 'Electricity bill / Rent agreement / Bank passbook',
            ],
            [
                'name' => 'Caste Certificate (if applicable)',
                'icon' => 'fa-solid fa-certificate',
                'icon_class' => 'doc',
                'status' => 'pending',
                'meta' => 'SC / ST / OBC certificate from competent authority',
            ],
            [
                'name' => 'Passport Size Photograph',
                'icon' => 'fa-solid fa-user-circle',
                'icon_class' => 'img',
                'status' => 'uploaded',
                'meta' => 'Recent photo · JPG / PNG · Max 500KB',
            ],
        ];
        $statusStyles = ['uploaded' => 'badge-uploaded', 'pending' => 'badge-pending', 'required' => 'badge-required'];
        $statusText = ['uploaded' => 'Uploaded', 'pending' => 'Pending', 'required' => 'Required'];
    @endphp
    @foreach ($documents as $doc)
<div class="doc-row">
        <div class="doc-icon {{ $doc['icon_class'] }}"><i class="{{ $doc['icon'] }}"></i></div>
        <div class="doc-info">
            <p class="doc-name">{{ $doc['name'] }}</p>
            <p class="doc-meta">{{ $doc['meta'] }}</p>
        </div>
        <span class="badge-status {{ $statusStyles[$doc['status']] }}">{{ $statusText[$doc['status']] }}</span>
        <div class="d-flex gap-2">
            @if ($doc['status'] == 'uploaded')
<button class="btn-ghost" onclick="viewDocument('{{ $doc['name'] }}')"><i class="fa-solid fa-eye"></i> View</button>
            <button class="btn-outline-brand" onclick="openReupload('{{ $doc['name'] }}')"><i class="fa-solid fa-arrow-upload"></i> Re-upload</button>
@else
<button class="btn-brand" onclick="openReupload('{{ $doc['name'] }}')"><i class="fa-solid fa-cloud-arrow-up"></i> Upload</button>
@endif
        </div>
    </div>
@endforeach
    <div class="d-flex gap-2 mt-4 flex-wrap align-items-center">
        <span class="stat-chip"><span class="dot" style="background:var(--success)"></span> 2 Uploaded</span>
        <span class="stat-chip"><span class="dot" style="background:var(--accent)"></span> 2 Pending</span>
        <span class="stat-chip"><span class="dot" style="background:var(--danger)"></span> 1 Required</span>
        <span class="stat-chip"><span class="dot" style="background:var(--brand)"></span> {{ $allotmentLetter ? '1' : '0' }} Generated</span>
        <span class="ms-auto"></span>
        <button class="btn-brand" style="background:var(--accent);gap:8px" onclick="submitAllDocuments()">
            <i class="fa-solid fa-paper-plane"></i> Submit Documents
        </button>
    </div> -->
</div>
