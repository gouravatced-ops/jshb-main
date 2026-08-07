@extends('layouts.main')

@section('title', 'Reject Application | JSHB')

@section('content')
<x-applications.actions.reject :application="$application" routePrefix="engineer" />
@endsection
