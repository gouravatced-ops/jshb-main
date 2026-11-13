@extends('layouts.main')

@section('title', 'Add Division | JSHB')

@section('content')
<div class="form-container">
    <div class="form-wrapper">
        <div class="form-main">
            <div class="form-header">
                <div>
                    <h4>Add Division</h4>
                    <p>Create a new division using the admin form template.</p>
                </div>
                <a href="{{ route('admin.divisions.index') }}" class="btn-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Back
                </a>
            </div>

            <form action="{{ route('admin.divisions.store') }}" method="POST" id="divisionForm">
                @include('admin.division._form', ['submitLabel' => 'Create Division'])
            </form>
        </div>
    </div>
</div>
@endsection
