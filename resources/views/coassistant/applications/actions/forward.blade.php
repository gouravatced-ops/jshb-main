@extends('layouts.main')

@section('title', 'Forward Application | JSHB')

@section('content')
<x-applications.actions.forward :application="$application" routePrefix="coassistant" :forwardOptions="$forwardOptions" />
@endsection
