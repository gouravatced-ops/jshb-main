@extends('layouts.main')

@section('title', 'Forward Application | JSHB')

@section('content')
<x-applications.actions.forward :application="$application" routePrefix="engineer" :forwardOptions="$forwardOptions" :isSiteVerificationStep="$isSiteVerificationStep ?? false" :isSiteVerificationCompleted="$isSiteVerificationCompleted ?? false" />
@endsection
