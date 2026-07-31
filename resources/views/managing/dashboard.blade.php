@extends('layouts.main')

@section('title', 'Administration Dashboard | JSHB')

@section('content')
<div id="page-dashboard" class="admin-dashboard-page">
    <div class="dashboard-hero-card">
        <div>
            <div class="dashboard-hero-kicker">
                Quick View
            </div>

            <h2 class="dashboard-hero-title">
                Administration Dashboard
                @if(Auth::user()->assistant_to_id)
                    <br><span style="font-size: 16px; font-weight: normal; color: #f5c518; display: inline-block; margin-top: 5px;">(Co-Assistant to {{ Auth::user()->assistantTo->name ?? 'Managing Director' }})</span>
                @endif
            </h2>

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
            <div class="hero-time">{{ now()->format('g:i') }} <span
                    style="color:#f5c518;">{{ now()->format('A') }}</span></div>
            <div class="hero-date">{{ now()->format('l, d M Y') }}</div>
        </div>
    </div>
    @include('components.partials.notice-calendar')
</div>
@endsection

