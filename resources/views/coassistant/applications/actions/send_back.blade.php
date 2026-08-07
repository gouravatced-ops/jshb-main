@extends('layouts.main')

@section('title', 'Send Back Application | JSHB')

@section('content')
<x-applications.actions.send_back :application="$application" routePrefix="coassistant" :sendBackOptions="$sendBackOptions" />
@endsection
