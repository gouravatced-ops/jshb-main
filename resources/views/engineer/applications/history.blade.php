@extends('layouts.main')

@section('title', 'Application History | JSHB')

@section('content')
<x-applications.history :applications="$applications" routePrefix="engineer" :subDivisions="$subDivisions" />
@endsection
