@extends('layouts.main')

@section('title', 'Admin Dashboard | JSHB')

@section('content')
    <div id="page-dashboard" class="admin-dashboard-page">
        <div class="dashboard-hero-card">
            {{-- <svg class="bg-svg" viewBox="0 0 600 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">

                <!-- Dark Green Layer -->
                <polygon points="320,0 600,0 600,100 260,100" fill="#166534" />

                <!-- Dark Yellow/Gold Layer -->
                <polygon points="400,0 600,0 600,100 340,100" fill="#a16207" />

                <!-- Extra Depth Layer -->
                <polygon points="500,0 600,0 600,100 450,100" fill="#0f172a" />

                <!-- Bright Accent Lines -->
                <line x1="310" y1="0" x2="250" y2="100" stroke="#22c55e" stroke-width="2" />

                <line x1="380" y1="0" x2="320" y2="100" stroke="#4ade80" stroke-width="1.5" />

                <line x1="450" y1="0" x2="390" y2="100" stroke="#facc15" stroke-width="1.5" />

                <line x1="520" y1="0" x2="460" y2="100" stroke="#fde047" stroke-width="1" />

            </svg> --}}
            <div>
                <div class="dashboard-hero-kicker">
                    Admin Quick View
                </div>

                <h2 class="dashboard-hero-title">Dashboard</h2>

                @if ($latestLogin)
                    <div class="login-meta">
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
