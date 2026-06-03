@extends('layouts.main')

@section('title', 'Edit Department | JSHB')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">Edit Department</div>
            <div class="card-subtitle">Update department details from the admin panel</div>
        </div>
    </div>

    <form action="{{ route('admin.departments.update', $department) }}" method="POST">
        @method('PUT')
        @include('admin.department._form', ['submitLabel' => 'Update Department'])
    </form>
</div>
@endsection
