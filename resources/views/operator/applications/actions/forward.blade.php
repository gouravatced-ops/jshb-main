@extends('layouts.main')

@section('title', 'Forward Application | JSHB')

@section('content')
<x-applications.actions.forward :application="$application" routePrefix="operator" :forwardOptions="$forwardOptions" />
@endsection
