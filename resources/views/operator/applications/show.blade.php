@extends('layouts.main')

@section('title', 'Review Application | JSHB')

@section('content')
<x-applications.show :application="$application" routePrefix="operator" :documentMasters="$documentMasters" :allotteeDocuments="$allotteeDocuments" :documentRequests="$documentRequests" :requiredDocumentIds="$requiredDocumentIds" :excludedDocIds="$excludedDocIds" />
@endsection
