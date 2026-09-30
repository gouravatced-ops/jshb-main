@extends('layouts.main')

@section('title', 'Official Dashboard | JSHB')

@section('content')
<div id="page-dashboard" class="admin-dashboard-page">
    <div class="dashboard-hero-card">
        <div>
            <div class="dashboard-hero-kicker">
                Official Quick View
            </div>

            <h2 class="dashboard-hero-title" style="text-transform: capitalize;">Official Dashboard</h2>

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
    <style>
        .stat-card-modern {
            background: #ffffff;
            border: 1px solid #eef2f7;
            border-radius: 12px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
        }
        .stat-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border-color: #d1d5db;
        }
        .stat-icon-modern {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        .stat-label-modern {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 2px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-value-modern {
            font-size: 1.5rem;
            font-weight: 800;
            color: #323a46;
            margin: 0;
            line-height: 1.2;
        }
    </style>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-5 g-3 mb-4 mt-3">
        <!-- Total Received -->
        <div class="col">
            <div class="stat-card-modern">
                <div class="stat-icon-modern" style="background: rgba(20, 184, 166, 0.15); color: #0d9488;">
                    <i class="fa-solid fa-inbox"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label-modern">Total Received</p>
                    <p class="stat-value-modern">{{ $totalReceived ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Applications -->
        <div class="col">
            <div class="stat-card-modern">
                <div class="stat-icon-modern" style="background: rgba(234, 179, 8, 0.15); color: #ca8a04;">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label-modern">Pending Apps</p>
                    <p class="stat-value-modern">{{ $totalPending ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Processed Applications -->
        <div class="col">
            <div class="stat-card-modern">
                <div class="stat-icon-modern" style="background: rgba(34, 197, 94, 0.15); color: #16a34a;">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label-modern">Processed Apps</p>
                    <p class="stat-value-modern">{{ $totalProcessed ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Sent Back -->
        <div class="col">
            <div class="stat-card-modern">
                <div class="stat-icon-modern" style="background: rgba(249, 115, 22, 0.15); color: #ea580c;">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label-modern">Sent Back</p>
                    <p class="stat-value-modern">{{ $totalSentBack ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="col">
            <div class="stat-card-modern">
                <div class="stat-icon-modern" style="background: rgba(239, 68, 68, 0.15); color: #dc2626;">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label-modern">Rejected</p>
                    <p class="stat-value-modern">{{ $totalRejected ?? 0 }}</p>
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
                        <th>Due Date</th>
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
                            <!-- <div class="table-subtitle">{{ trim(($app->allottee_prefix_hindi ?? '') . ' ' . ($app->allottee_name_hindi ?? '') . ' ' . ($app->allottee_middle_hindi ?? '') . ' ' . ($app->allottee_surname_hindi ?? '')) ?: '-' }}</div> -->
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
                            @php
                                $currentMovement = $app->movements ? $app->movements->whereNotNull('due_date')->last() : null;
                                $dueDate = $currentMovement ? $currentMovement->due_date : null;

                                $isOverdue = false;
                                $daysRemaining = null;
                                if ($dueDate) {
                                    $parsedDueDate = \Carbon\Carbon::parse($dueDate)->endOfDay();
                                    $isOverdue = $parsedDueDate->isPast();
                                    $daysRemaining = (int) floor(now()->startOfDay()->diffInDays($parsedDueDate->startOfDay(), false));
                                }

                                $hasExtensionRequest = $app->extensionRequests ? $app->extensionRequests->where('requested_by', auth()->id())->where('status', 'pending')->isNotEmpty() : false;
                            @endphp

                            @if($dueDate)
                                <div style="font-weight: 600; font-size: 13px; color: {{ $isOverdue ? '#dc2626' : '#16a34a' }};">
                                    {{ \Carbon\Carbon::parse($dueDate)->format('d M Y') }}
                                </div>
                            @else
                                <span class="text-muted" style="font-size: 13px;">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($hasExtensionRequest)
                                <div style="margin-bottom: 5px;">
                                    <span style="background-color: #f59e0b; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;"><i class="fa-solid fa-clock-rotate-left"></i> Extension Pending</span>
                                </div>
                            @elseif($isOverdue)
                                <div style="font-size: 11px; color: #dc2626; margin-bottom: 6px; font-weight: 600;"><i class="fa-solid fa-circle-exclamation"></i> Due date passed, you can't act.</div>
                                <a href="{{ route('engineer.extensions.create', \Illuminate\Support\Facades\Crypt::encryptString($app->id)) }}" class="btn-danger" style="padding: 4px 10px; font-size: 12px; display: inline-block; text-decoration: none; background-color: #dc3545; color: white; border-radius: 4px;"><i class="fa-solid fa-calendar-plus"></i> Request Extension</a>
                            @else
                                @if($daysRemaining !== null && $daysRemaining <= 2 && $daysRemaining >= 0)
                                    <div style="font-size: 11px; color: #ea580c; margin-bottom: 6px; font-weight: 600;"><i class="fa-solid fa-bell"></i> Expiring in {{ $daysRemaining }} day(s)</div>
                                @endif
                                <a href="{{ route('engineer.applications.show', $app) }}" class="btn-primary" style="padding: 4px 10px; font-size: 12px; display: inline-block; text-decoration: none;">Review</a>
                            @endif
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
    @include('components.partials.notice-calendar')
</div>
@endsection
