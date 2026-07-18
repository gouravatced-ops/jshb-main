@extends('layouts.main')

@section('title', 'Engineer Dashboard | JSHB')

@section('content')
<div id="page-dashboard" class="admin-dashboard-page">
    <div class="dashboard-hero-card">
        <div>
            <div class="dashboard-hero-kicker">
                Engineer Quick View
            </div>

            <h2 class="dashboard-hero-title" style="text-transform: capitalize;">{{ Auth::user()->user_type }} Dashboard</h2>

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

    <!-- Stat Cards -->
    <div class="row g-2 mb-3 mt-4">
        <div class="col-6 col-xl-4">
            <div class="stat-card landed" style="background: var(--card-bg); border: 1px solid var(--border); border-radius: 4px; padding: 16px; display: flex; align-items: center; box-shadow: 0 4px 18px rgba(0, 0, 0, 0.09);">
                <div class="stat-icon teal" style="width: 48px; height: 48px; border-radius: 8px; background: rgba(20, 184, 166, 0.1); color: #14b8a6; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 16px;">
                    <i class="fas fa-inbox"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label" style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px; font-weight: 600;">Total Received</p>
                    <p class="stat-value" style="font-size: 22px; font-weight: 700; color: var(--text-main); margin: 0;">{{ $totalReceived ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="stat-card landed" style="background: var(--card-bg); border: 1px solid var(--border); border-radius: 4px; padding: 16px; display: flex; align-items: center; box-shadow: 0 4px 18px rgba(0, 0, 0, 0.09);">
                <div class="stat-icon yellow" style="width: 48px; height: 48px; border-radius: 8px; background: rgba(234, 179, 8, 0.1); color: #eab308; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 16px;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label" style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px; font-weight: 600;">Pending Applications</p>
                    <p class="stat-value" style="font-size: 22px; font-weight: 700; color: var(--text-main); margin: 0;">{{ $totalPending ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-4">
            <div class="stat-card landed" style="background: var(--card-bg); border: 1px solid var(--border); border-radius: 4px; padding: 16px; display: flex; align-items: center; box-shadow: 0 4px 18px rgba(0, 0, 0, 0.09);">
                <div class="stat-icon green" style="width: 48px; height: 48px; border-radius: 8px; background: rgba(34, 197, 94, 0.1); color: #22c55e; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 16px;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label" style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px; font-weight: 600;">Processed Applications</p>
                    <p class="stat-value" style="font-size: 22px; font-weight: 700; color: var(--text-main); margin: 0;">{{ $totalProcessed ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(isset($pendingApplications) && $pendingApplications->count() > 0)
    <div class="card mt-4" style="margin-top: 20px;">
        <div class="card-head">
            <div>
                <div class="card-title">Pending Applications</div>
                <div class="card-subtitle">Recent applications awaiting your review</div>
            </div>
            <div class="card-actions">
                <a href="{{ route('engineer.applications.index') }}" class="btn-primary" style="padding: 6px 12px; font-size: 14px;">View All</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>Application No</th>
                        <th>Type</th>
                        <th>Allottee</th>
                        <th>Created Date</th>
                        <th>Priority</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingApplications as $app)
                    <tr>
                        <td><strong>{{ $app->application_no }}</strong></td>
                        <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $app->application_type) }}</td>
                        <td>
                            <div class="table-name">{{ trim(($app->prefix ?? '') . ' ' . ($app->allottee_name ?? '') . ' ' . ($app->allottee_middle_name ?? '') . ' ' . ($app->allottee_surname ?? '')) ?: '-' }}</div>
                            <div class="table-subtitle">{{ trim(($app->allottee_prefix_hindi ?? '') . ' ' . ($app->allottee_name_hindi ?? '') . ' ' . ($app->allottee_middle_hindi ?? '') . ' ' . ($app->allottee_surname_hindi ?? '')) ?: '-' }}</div>
                            <small class="text-muted">{{ $app->property_number }}</small>
                        </td>
                        <td>
                            <div>{{ $app->created_date_formatted }}</div>
                            <small class="text-muted">{{ $app->days_pending }} days pending</small>
                        </td>
                        <td>
                            @if($app->priority === 'Urgent' || $app->priority === 'Overdue')
                                <span class="badge-status inactive"><i class="fa-solid fa-circle"></i> {{ $app->priority }}</span>
                            @else
                                <span class="badge-status active"><i class="fa-solid fa-circle"></i> {{ $app->priority }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('engineer.applications.show', $app) }}" class="btn-primary" style="padding: 4px 10px; font-size: 12px; display: inline-block; text-decoration: none;">Review</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="card mt-4" style="margin-top: 20px; text-align: center; padding: 40px;">
        <div style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;">
            <i class="fas fa-check-circle"></i>
        </div>
        <h4 style="color: var(--text-main); margin-bottom: 8px;">All Caught Up!</h4>
        <p style="color: var(--text-muted); margin-bottom: 0;">You have no pending applications to review at the moment.</p>
    </div>
    @endif
</div>
@endsection
