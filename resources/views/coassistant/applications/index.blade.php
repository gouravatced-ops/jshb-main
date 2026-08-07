@extends('layouts.main')

@section('title', 'Pending Applications | JSHB')

@section('content')
<x-applications.index :applications="$applications" routePrefix="coassistant" :subDivisions="$subDivisions" />
@endsection
