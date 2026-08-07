@extends('layouts.main')

@section('title', 'Approve Application | JSHB')

@section('content')
<x-applications.actions.approve :application="$application" routePrefix="operator" />
@endsection
