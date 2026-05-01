@extends('layouts.main')

@section('title', 'Add Department | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">Add Department</div>
            <div class="card-subtitle">Create a new department for engineer mapping</div>
        </div>
    </div>

    <form action="{{ route('admin.departments.store') }}" method="POST">
        @include('admin.department._form', ['submitLabel' => 'Save Department'])
    </form>
</div>
@endsection
