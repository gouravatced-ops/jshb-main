@extends('layouts.allottee-dashboard')
@section('title', 'Possession Letter | JSHB')
@section('content')
    <div class="card border-0 shadow-sm">
        {{-- Compact Classic Header --}}
        <div class="card-header border-bottom py-2 px-3"
            style="
            background-image:url('{{ asset('img/header4-background.png') }}');
            background-size:cover;
            background-position:right;
            background-repeat:no-repeat;
         ">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                {{-- Left --}}
                <div>
                    <h5 class="mb-0 fw-semibold text-dark d-inline-block border-bottom border-2 pb-1">
                        <i class="fa-solid fa-file-signature text-success me-1"></i>
                        Possession Letter
                    </h5>
                    <div>
                        <small class="text-muted">
                            Application No :
                            <strong>{{ $allottee->application_no ?? '-' }}</strong>
                        </small>
                    </div>
                </div>
                {{-- Right Buttons --}}
                <div class="d-flex flex-wrap gap-1">
                    {{-- Back --}}
                    <a href="#" class="btn btn-dark btn-sm px-3" onclick="event.preventDefault(); loadStep(4);">
                        <i class="fa-solid fa-arrow-left me-1"></i>
                        Back
                    </a>

                    {{-- Regenerate --}}
                    <a href="{{ route('admin.allottees.letters.possession', $allottee) }}"
                        class="btn btn-sm btn-warning text-dark">
                        <i class="fa-solid fa-rotate-right"></i>
                        Regenerate
                    </a>

                    {{-- Preview --}}
                    <a href="{{ route('admin.allottees.letters.possession.pdf', ['allottee' => $allottee]) }}"
                        target="_blank" class="btn btn-sm btn-info text-white">
                        <i class="fa-solid fa-eye"></i>
                        Preview
                    </a>

                    {{-- Download --}}
                    <a href="{{ route('admin.allottees.letters.possession.pdf', [
                        'allottee' => $allottee,
                        'download' => 1,
                    ]) }}"
                        class="btn btn-sm btn-success">
                        <i class="fa-solid fa-download"></i>
                        Save For Allottee
                    </a>

                    {{-- Print --}}
                    <button type="button" onclick="document.getElementById('pdfFrame').contentWindow.print();"
                        class="btn btn-sm btn-light">
                        <i class="fa-solid fa-print"></i>
                        Print
                    </button>
                </div>
            </div>
        </div>
        {{-- PDF Viewer --}}
        <div class="card-body bg-light p-2">
            <div class="border rounded bg-white overflow-hidden">
                <iframe id="pdfFrame"
                    src="{{ route('admin.allottees.letters.possession.pdf', ['allottee' => $allottee]) }}"
                    style="width:100%; height:82vh; border:0;" title="Possession Letter PDF">
                </iframe>
            </div>
        </div>
    </div>
@endsection
