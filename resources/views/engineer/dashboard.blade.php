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
    @if(Auth::user()->roleRelation?->slug === 'dealing-assistant' && isset($pendingApplications) && $pendingApplications->count() > 0)
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
    @endif
</div>
@endsection
