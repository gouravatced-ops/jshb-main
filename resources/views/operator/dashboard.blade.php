@extends('layouts.main')

@section('title', 'Operator Dashboard | JSHB')

@section('content')
<div id="page-dashboard" class="admin-dashboard-page">
    <div class="dashboard-hero-card">
        <div>
            <div class="dashboard-hero-kicker">
                Operator Quick View
            </div>

            <h2 class="dashboard-hero-title">Operator Dashboard</h2>

            @if($latestLogin)
                <div class="login-meta" style="margin-top:15px;">
                    <span class="login-ip">
                        Current Login IP: {{ $latestLogin->ip_address }}
                    </span>

                    <span class="login-time">
                        Login Since {{ $latestLogin->created_at->diffForHumans() }}
                    </span>
                </div>
            @endif
        </div>

        <div class="dashboard-hero-meta">
            <div class="hero-time">{{ now()->format('g:i A') }}</div>
            <div class="hero-date">{{ now()->format('l, d M Y') }}</div>
        </div>
    </div>
</div>
@endsection
