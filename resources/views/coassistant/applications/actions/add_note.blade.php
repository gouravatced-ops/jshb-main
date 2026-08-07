@extends('layouts.main')

@section('title', 'Add Note to Application | JSHB')

@section('content')
<x-applications.actions.add_note :application="$application" routePrefix="coassistant" />
@endsection
