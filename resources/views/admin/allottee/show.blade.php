{{-- resources/views/admin/allottee/show.blade.php --}}
@extends('layouts.allottee-dashboard')

@section('title', 'View Allottee | JSHB')

@section('content')
    @include('admin.allottee.sections.overview', ['allottee' => $allottee])
@endsection