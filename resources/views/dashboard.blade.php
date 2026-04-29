@extends('layouts.main')

@section('title', 'User Dashboard | JESA')

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
                    <div class="card-title">Employee Details</div>
                    <div class="card-subtitle">Official employee record linked with your login</div>
                </div>
                <a class="btn-outline" href="{{ route('requisitions.create') }}"><i class="fa-solid fa-file-signature"></i> New Requisition</a>
            </div>
            <div style="padding:22px;display:grid;gap:14px">
                @if($employeeDetail)
                    <div class="activity-item">
                        <div class="activity-icon" style="background:var(--pink-light);color:var(--pink-primary)"><i class="fa-solid fa-id-badge"></i></div>
                        <div class="activity-line">
                            <div class="activity-text"><strong>{{ $employeeDetail->employee_name }}</strong> ({{ $employeeDetail->state_government_engineer_id }})</div>
                            <div class="activity-time">{{ $employeeDetail->department?->name ?: '-' }} / {{ $employeeDetail->postType?->name ?: '-' }}</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon" style="background:var(--sky-light);color:var(--sky-primary)"><i class="fa-solid fa-building"></i></div>
                        <div class="activity-line">
                            <div class="activity-text"><strong>Organization</strong></div>
                            <div class="activity-time">{{ $employeeDetail->currentOrganization?->name ?: '-' }}</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon" style="background:var(--green-light);color:var(--green-primary)"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="activity-line">
                            <div class="activity-text"><strong>District / Block</strong></div>
                            <div class="activity-time">{{ ($employeeDetail->district?->name_en ?: '-') . ' / ' . ($employeeDetail->block?->block_name_eng ?: '-') }}</div>
                        </div>
                    </div>
                @else
                    <div style="padding:10px 0;color:var(--text-light)">Employee details are not linked to this user yet.</div>
                @endif
            </div>
        </div>

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

    <div class="grid-2" style="margin-bottom:24px">
        <div class="card">
            <div class="card-head">
                <div>
                    <div class="card-title">Guest House Requisitions</div>
                    <div class="card-subtitle">Latest stay applications and approval status</div>
                </div>
                <a class="btn-pink" href="{{ route('requisitions.index') }}"><i class="fa-solid fa-list"></i> View All</a>
            </div>
            <div class="activity-feed">
                @forelse($recentRequisitions as $requisition)
                    <div class="activity-item">
                        <div class="activity-icon" style="background:var(--orange-light, #fff7ed);color:#ea580c">
                            <i class="fa-solid fa-hotel"></i>
                        </div>
                        <div class="activity-line">
                            <div class="activity-text"><strong>{{ $requisition->guest_house_name }}</strong> at {{ $requisition->block?->block_name_eng ?: '-' }}</div>
                            <div class="activity-time">{{ ucfirst($requisition->status) }} • {{ optional($requisition->stay_from)->format('d M Y') }} to {{ optional($requisition->stay_to)->format('d M Y') }}</div>
                        </div>
                    </div>
                @empty
                    <div style="padding:22px;color:var(--text-light)">No requisition applications found yet.</div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <div>
                    <div class="card-title">Profile Completion</div>
                    <div class="card-subtitle">Finish your account details for a better experience</div>
                </div>
                <a class="btn-pink" href="{{ route('profile') }}"><i class="fa-solid fa-pen-to-square"></i> Update Profile</a>
            </div>
            <div style="padding:22px">
                @php
                    $fields = [
                        filled($user->name),
                        filled($user->email),
                        filled(optional($user->detail)->phone),
                        filled(optional($user->detail)->city),
                        filled(optional($user->detail)->state),
                        filled($user->photo),
                    ];
                    $completion = (int) round((collect($fields)->filter()->count() / count($fields)) * 100);
                @endphp
                <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                    <span style="font-size:13px;color:var(--text-mid);font-weight:600">Completion status</span>
                    <span style="font-size:13px;color:var(--primary-color);font-weight:700">{{ $completion }}%</span>
                </div>
                <div class="progress-bar-custom">
                    <div class="progress-fill" style="width:{{ $completion }}%;background:linear-gradient(90deg,var(--primary-color),var(--sky-primary))"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
