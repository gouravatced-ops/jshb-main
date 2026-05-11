@extends('layouts.allottee-dashboard')

@section('title', 'Allotment Letter | JSHB')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Allotment Letter PDF</h5>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('admin.allottees.show', $allottee) }}" target="_blank">Back to Allottee</a>
            <a class="btn btn-primary" href="{{ route('admin.allottees.letters.allotment.pdf', ['allottee' => $allottee, 'download' => 1]) }}">Download PDF</a>
        </div>
    </div>
    <div class="card-body p-0">
        <iframe src="{{ route('admin.allottees.letters.allotment.pdf', ['allottee' => $allottee]) }}" style="width:100%;height:82vh;border:0;" title="Allotment Letter PDF"></iframe>
    </div>
</div>
@endsection
