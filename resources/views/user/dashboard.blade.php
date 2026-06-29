@extends('layouts.main')

@section('title', 'User Dashboard | JSHB')

@section('content')
<div id="page-dashboard">
    <div class="dashboard-intro-card">
        <div class="dashboard-intro-time">
            <div class="intro-time">{{ now()->format('g:i A') }}</div>
            <div class="intro-date">{{ now()->format('l, F j, Y') }}</div>
        </div>
        <div class="dashboard-intro-note">
            <div class="intro-note-label">Welcome</div>
            <div class="intro-note-text">Hello {{ $user->name }}, your member dashboard is ready.</div>
        </div>
    </div>

    <div class="stat-cards">
        <div class="stat-card pink">
            <div class="stat-top">
                <div class="stat-icon pink"><i class="fa-solid fa-user"></i></div>
                <div class="stat-value">{{ ucfirst($user->role) }}</div>
            </div>
            <div class="stat-label">Account Role</div>
        </div>
        <div class="stat-card sky">
            <div class="stat-top">
                <div class="stat-icon sky"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="stat-value">{{ $user->login_with_otp ? 'Enabled' : 'Disabled' }}</div>
            </div>
            <div class="stat-label">OTP Login</div>
        </div>
        <div class="stat-card green">
            <div class="stat-top">
                <div class="stat-icon green"><i class="fa-solid fa-key"></i></div>
                <div class="stat-value">{{ $otpLogCount }}</div>
            </div>
            <div class="stat-label">OTP Records</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-top">
                <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
                <div class="stat-value">{{ optional($user->password_created_at)->diffForHumans() ?? 'Not set' }}</div>
            </div>
            <div class="stat-label">Password Updated</div>
        </div>
    </div>

    <div class="grid-2" style="margin-bottom:24px">
        <div class="card">
            <div class="card-head">
                <div>
                    <div class="card-title">Account Overview</div>
                    <div class="card-subtitle">Quick information about your profile</div>
                </div>
            </div>
            <div style="padding:22px;display:grid;gap:16px">
                <div class="activity-item">
                    <div class="activity-icon" style="background:var(--pink-light);color:var(--pink-primary)">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="activity-line">
                        <div class="activity-text"><strong>Email</strong></div>
                        <div class="activity-time">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon" style="background:var(--sky-light);color:var(--sky-primary)">
                        <i class="fa-solid fa-user-tag"></i>
                    </div>
                    <div class="activity-line">
                        <div class="activity-text"><strong>Profile</strong></div>
                        <div class="activity-time">Keep your personal details updated from profile settings.</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon" style="background:var(--green-light);color:var(--green-primary)">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div class="activity-line">
                        <div class="activity-text"><strong>Security</strong></div>
                        <div class="activity-time">Use lock screen and password reset from the header menu any time.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <div>
                    <div class="card-title">Recent Login Activity</div>
                    <div class="card-subtitle">Your latest successful and failed attempts</div>
                </div>
            </div>
            <div class="activity-feed">
                @forelse($recentLogins as $log)
                    <div class="activity-item">
                        <div class="activity-icon" style="background:{{ $log->status === 'success' ? 'var(--green-light)' : '#fef2f2' }};color:{{ $log->status === 'success' ? 'var(--green-primary)' : '#ef4444' }}">
                            <i class="fa-solid {{ $log->status === 'success' ? 'fa-right-to-bracket' : 'fa-triangle-exclamation' }}"></i>
                        </div>
                        <div class="activity-line">
                            <div class="activity-text"><strong>{{ ucfirst(str_replace('_', ' ', $log->action)) }}</strong> from {{ $log->ip_address }}</div>
                            <div class="activity-time">{{ $log->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div style="padding:22px;color:var(--text-light)">No login activity found yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
