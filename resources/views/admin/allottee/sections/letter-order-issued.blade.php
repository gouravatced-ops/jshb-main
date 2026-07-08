{{-- resources/views/admin/allottee/sections/letter-order-issued.blade.php --}}
<div>

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">
                Letter / Orders Issued
            </h1>

            <p class="page-subtitle">
                Letter / Orders Issued ·
                Application :
                {{ $allottee->application_no ?? '-' }}
            </p>
        </div>

        <button class="btn-ghost" onclick="window.close();">

            <i class="fa-solid fa-arrow-left"></i>
            Back to List

        </button>
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
</div>
