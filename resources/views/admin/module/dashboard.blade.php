@extends('layouts.main')

@section('title', 'Admin Dashboard | JESA')

@section('content')
<div id="page-dashboard" class="admin-dashboard-page">
    <div class="dashboard-hero-card">
        <div>
            <div class="dashboard-hero-kicker">Admin Quick View</div>
            <h2 class="dashboard-hero-title">Dashboard</h2>
            <!-- <p class="dashboard-hero-text">Track live engineer records, recent additions, district coverage, and important admin activity from one screen.</p> -->
        </div>
        <div class="dashboard-hero-meta">
            <div class="hero-time">{{ now()->format('g:i A') }}</div>
            <div class="hero-date">{{ now()->format('l, d M Y') }}</div>
            <!-- <div class="hero-actions">
                <a class="btn-pink" href="{{ route('admin.engineers.create') }}"><i class="fa-solid fa-user-plus"></i> Add Engineer</a>
                <a class="btn-outline" href="{{ route('admin.engineers.index') }}"><i class="fa-solid fa-list"></i> View List</a>
            </div> -->
        </div>
    </div>
</div>
@endsection