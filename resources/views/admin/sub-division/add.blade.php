@extends('layouts.main')

@section('title', 'Add Sub Division | JSHB')

@section('content')
<div class="form-container">
    <div class="form-wrapper">
        <div class="form-main">
            <div class="form-header">
                <div>
                    <h4>Add Sub Division</h4>
                    <p>Create a new sub division using the admin form template.</p>
                </div>
                <a href="{{ route('admin.sub-divisions.index') }}" class="btn-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Back
                </a>
            </div>

            <form action="{{ route('admin.sub-divisions.store') }}" method="POST" id="subDivisionForm">
                @include('admin.sub-division._form', ['submitLabel' => 'Create Sub Division'])
            </form>
        </div>
    </div>
</div>
@endsection
