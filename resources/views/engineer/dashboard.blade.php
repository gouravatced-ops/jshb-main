@extends('layouts.main')

@section('title', 'Engineering Workstation | JSHB')

@section('content')
<div id="page-dashboard" class="admin-dashboard-page engineer-workstation-page">

    <!-- Scoped Engineering Workstation Styling -->
    <style>
        .engineer-workstation-page {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1e293b;
        }

        /* 1. Technical Desk Command Header */
        .eng-desk-header {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #0284c7;
            border-radius: 3px;
            padding: 12px 16px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            background:
                linear-gradient(rgba(12, 31, 68, 0.644),
                    rgba(68, 64, 12, 0.548)),
                url('../img/background-new234.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        .eng-header-left {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .eng-desk-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #ffffffff;
        }

        .eng-desk-title {
            font-size: 18px;
            font-weight: 800;
            color: #e4fdedff;
            margin: 0;
            line-height: 1.2;
            letter-spacing: -0.2px;
        }

        .eng-meta-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-top: 2px;
        }

        .eng-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11.5px;
            font-weight: 600;
            color: #475569;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 2px;
            padding: 2px 8px;
            line-height: 1.4;
        }

        .eng-chip.eng-chip-live {
            background: #ecfdf5;
            border-color: #a7f3d0;
            color: #047857;
        }

        .eng-header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .eng-hud-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 2px;
            padding: 6px 12px;
            text-align: right;
            min-width: 150px;
        }

        .eng-hud-time {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            font-variant-numeric: tabular-nums;
            line-height: 1.1;
        }

        .eng-hud-time span {
            color: #0284c7;
            font-size: 12px;
            font-weight: 700;
        }

        .eng-hud-date {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin-top: 2px;
        }

        .eng-btn-worklist {
            background: #0f172a;
            color: #ffffff !important;
            border: 1px solid #0f172a;
            border-radius: 2px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .eng-btn-worklist:hover {
            background: #1e293b;
            border-color: #1e293b;
            color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(15, 23, 42, 0.15);
        }

        /* 2. Compact Technical Stat Tiles */
        .eng-stat-tile {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 2px;
            padding: 9px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            transition: border-color 0.15s ease;
            height: 100%;
        }

        .eng-stat-tile:hover {
            border-color: #94a3b8;
        }

        .eng-stat-tile.tile-teal { border-top: 3px solid #0d9488; }
        .eng-stat-tile.tile-amber { border-top: 3px solid #d97706; }
        .eng-stat-tile.tile-green { border-top: 3px solid #16a34a; }
        .eng-stat-tile.tile-orange { border-top: 3px solid #ea580c; }
        .eng-stat-tile.tile-red { border-top: 3px solid #dc2626; }

        .eng-stat-icon-box {
            width: 36px;
            height: 36px;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
            margin-right: 10px;
        }

        .tile-teal .eng-stat-icon-box { background: rgba(13, 148, 136, 0.12); color: #0d9488; }
        .tile-amber .eng-stat-icon-box { background: rgba(217, 119, 6, 0.12); color: #d97706; }
        .tile-green .eng-stat-icon-box { background: rgba(22, 163, 74, 0.12); color: #16a34a; }
        .tile-orange .eng-stat-icon-box { background: rgba(234, 88, 12, 0.12); color: #ea580c; }
        .tile-red .eng-stat-icon-box { background: rgba(220, 38, 38, 0.12); color: #dc2626; }

        .eng-stat-body {
            flex-grow: 1;
            min-width: 0;
        }

        .eng-stat-label {
            font-size: 10.5px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .eng-stat-value {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            line-height: 1.1;
            font-variant-numeric: tabular-nums;
        }

        /* 3. Compact Worklist Table */
        .eng-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 2px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
            margin-bottom: 12px;
            overflow: hidden;
        }

        .eng-card-head {
            background: #f8fafc;
            border-bottom: 1px solid #cbd5e1;
            padding: 8px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .eng-card-title {
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
        }

        .eng-badge-pill {
            border-radius: 2px;
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 7px;
            letter-spacing: 0.3px;
        }

        .eng-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }

        .eng-table thead th {
            background: #0f172a !important;
            color: #ffffff !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.6px !important;
            padding: 7px 10px !important;
            border: none !important;
            white-space: nowrap;
        }

        .eng-table tbody td {
            padding: 7px 10px !important;
            font-size: 12px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #e2e8f0 !important;
            background: #ffffff;
        }

        .eng-table tbody tr:hover td {
            background: #f8fafc !important;
        }

        .eng-app-no {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 700;
            color: #0f172a;
            font-size: 12.5px;
        }

        .eng-allottee-name {
            font-weight: 700;
            color: #0f172a;
            line-height: 1.25;
        }

        .eng-prop-meta {
            font-size: 11px;
            color: #64748b;
            margin-top: 1px;
        }

        /* 4. Compact Calendar & Notices Overrides */
        .engineer-workstation-page .calendar-admin-container {
            background: #ffffff !important;
            border-radius: 2px !important;
            box-shadow: none !important;
            padding: 10px 14px !important;
            border: 1px solid #cbd5e1 !important;
            width: 100%;
        }

        .engineer-workstation-page .calendar-header {
            margin-bottom: 8px !important;
            padding-bottom: 8px !important;
            border-bottom: 1px solid #cbd5e1 !important;
        }

        .engineer-workstation-page .calendar-header h4 {
            font-size: 13px !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            text-transform: uppercase !important;
            letter-spacing: 0.4px !important;
        }

        .engineer-workstation-page .nav-buttons button {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            padding: 3px 8px !important;
            border-radius: 2px !important;
            font-size: 11px !important;
            color: #0f172a !important;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .engineer-workstation-page .nav-buttons button:hover {
            background: #0f172a !important;
            color: #ffffff !important;
            border-color: #0f172a !important;
        }

        .engineer-workstation-page .weekdays {
            font-size: 10.5px !important;
            font-weight: 700 !important;
            color: #64748b !important;
            margin-bottom: 6px !important;
            letter-spacing: 0.5px !important;
        }

        .engineer-workstation-page .days-grid {
            gap: 4px !important;
        }

        .engineer-workstation-page .day-cell {
            border-radius: 2px !important;
            border: 1px solid #e2e8f0 !important;
            background: #f8fafc !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            min-height: 38px !important;
            padding-top: 4px !important;
            transition: none !important;
        }

        .engineer-workstation-page .day-cell:hover:not(.empty) {
            background: #ffffff !important;
            border-color: #0284c7 !important;
            transform: none !important;
            box-shadow: none !important;
        }

        .engineer-workstation-page .day-cell.today {
            background: #0f172a !important;
            color: #ffffff !important;
            border-color: #0f172a !important;
            font-weight: 800 !important;
        }

        .engineer-workstation-page .day-cell.today:hover {
            background: #1e293b !important;
            transform: none !important;
            box-shadow: none !important;
        }

        .eng-calendar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 7px 12px;
            border-top: 1px solid #cbd5e1;
            background: #f8fafc;
            border-radius: 0 0 2px 2px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11.5px;
            font-weight: 600;
            color: #334155;
        }

        /* 5. Notice List Items */
        .eng-notice-item {
            padding: 9px 12px;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s ease;
        }

        .eng-notice-item:hover {
            background: #f8fafc;
        }

        .eng-notice-item:last-child {
            border-bottom: none;
        }

        .eng-notice-badge {
            border-radius: 2px;
            font-size: 9.5px;
            font-weight: 700;
            padding: 2px 5px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .eng-notice-title {
            font-size: 12.5px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.3;
        }

        .eng-notice-text {
            font-size: 11.5px;
            color: #475569;
            line-height: 1.4;
            margin-top: 3px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .eng-notice-action {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 700;
            color: #0284c7;
            text-decoration: none;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        .eng-notice-action:hover {
            color: #0369a1;
            text-decoration: underline;
        }

        /* 6. Sharp Modals */
        .engineer-workstation-page .modal-content {
            border-radius: 3px !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15) !important;
        }

        .engineer-workstation-page .modal-header {
            background: #f8fafc;
            border-bottom: 1px solid #cbd5e1;
            padding: 10px 14px;
        }

        .engineer-workstation-page .modal-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
        }

        .eng-btn-action {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            font-size: 11.5px;
            font-weight: 600;
            border-radius: 2px;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }

        .eng-btn-action.btn-review {
            background: #e0f2fe;
            color: #0369a1;
            border-color: #bae6fd;
        }

        .eng-btn-action.btn-review:hover {
            background: #0284c7;
            color: #ffffff;
            border-color: #0284c7;
        }

        .eng-btn-action.btn-extend {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }

        .eng-btn-action.btn-extend:hover {
            background: #b91c1c;
            color: #ffffff;
        }
    </style>

    <!-- SECTION 1: Technical Command Desk Header -->
    <div class="eng-desk-header">
        <div class="eng-header-left">
            <div class="eng-desk-tag">
                <i class="fa-solid fa-compass-drafting"></i> Officer Operations Console
            </div>
            <h2 class="eng-desk-title">
                {{ auth()->user()->role_display_name ?? 'Executive Engineer' }} Workstation
            </h2>
            <div class="eng-meta-bar">
                <span class="eng-chip">
                    <i class="fa-solid fa-building text-primary"></i>
                    {{ auth()->user()->division->name ?? 'Headquarters' }}
                    @if(auth()->user()->subDivision) &bull; {{ auth()->user()->subDivision->name }} @endif
                </span>

                <span class="eng-chip">
                    <i class="fa-solid fa-user-shield text-primary"></i>
                    {{ auth()->user()->name }}
                </span>

                @if($latestLogin)
                <span class="eng-chip eng-chip-live">
                    <i class="fa-solid fa-circle-check"></i>
                    Active Session &bull; IP: {{ $latestLogin->ip_address }} ({{ $latestLogin->created_at->diffForHumans() }})
                </span>
                @endif
            </div>
        </div>

        <div class="eng-header-right">
            <div class="eng-hud-box">
                <div class="eng-hud-time">
                    {{ now()->format('H:i') }} <span>{{ now()->format('A') }}</span>
                </div>
                <div class="eng-hud-date">
                    <i class="fa-regular fa-calendar me-1"></i> {{ now()->format('d M Y, l') }}
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: Compact 5-Metric Tiles Grid -->
    <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-5 g-2 mb-3">
        <!-- 1. Total Received -->
        <div class="col">
            <div class="eng-stat-tile tile-teal">
                <div class="d-flex align-items-center">
                    <div class="eng-stat-icon-box">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                    <div class="eng-stat-body">
                        <div class="eng-stat-label">Total Received</div>
                        <div class="eng-stat-value">{{ $totalReceived ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Pending Applications -->
        <div class="col">
            <div class="eng-stat-tile tile-amber">
                <div class="d-flex align-items-center">
                    <div class="eng-stat-icon-box">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div class="eng-stat-body">
                        <div class="eng-stat-label">Pending Review</div>
                        <div class="eng-stat-value">{{ $totalPending ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Processed Applications -->
        <div class="col">
            <div class="eng-stat-tile tile-green">
                <div class="d-flex align-items-center">
                    <div class="eng-stat-icon-box">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="eng-stat-body">
                        <div class="eng-stat-label">Processed & Moved</div>
                        <div class="eng-stat-value">{{ $totalProcessed ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Sent Back Applications -->
        <div class="col">
            <div class="eng-stat-tile tile-orange">
                <div class="d-flex align-items-center">
                    <div class="eng-stat-icon-box">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                    </div>
                    <div class="eng-stat-body">
                        <div class="eng-stat-label">Sent Back</div>
                        <div class="eng-stat-value">{{ $totalSentBack ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Rejected Applications -->
        <div class="col">
            <div class="eng-stat-tile tile-red">
                <div class="d-flex align-items-center">
                    <div class="eng-stat-icon-box">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                    <div class="eng-stat-body">
                        <div class="eng-stat-label">Rejected</div>
                        <div class="eng-stat-value">{{ $totalRejected ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 3: Actionable Pending Worklist -->
    @if(isset($pendingApplications) && $pendingApplications->count() > 0)
    <div class="eng-card">
        <div class="eng-card-head">
            <div class="eng-card-title">
                <i class="fa-solid fa-layer-group text-primary"></i> Actionable Applications Worklist
                <span class="badge bg-danger eng-badge-pill ms-2">{{ $pendingApplications->count() }} Priority Action</span>
            </div>
            <div>
                <a href="{{ route('engineer.applications.index') }}" class="btn btn-sm btn-outline-dark" style="border-radius: 2px; font-size: 11px; padding: 3px 8px; font-weight: 600;">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View All ({{ $totalPending }})
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table eng-table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 15%;">Application No</th>
                        <th style="width: 14%;">Type</th>
                        <th style="width: 26%;">Allottee & Property</th>
                        <th style="width: 16%;">Created / Age</th>
                        <th style="width: 9%;">Priority</th>
                        <th style="width: 10%;">Due Date</th>
                        <th style="width: 10%; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingApplications as $app)
                    <tr>
                        <td>
                            <span class="eng-app-no">{{ $app->application_no }}</span>
                        </td>
                        <td>
                            <span style="font-weight: 600; text-transform: capitalize; color: #334155;">
                                {{ str_replace('_', ' ', $app->application_type) }}
                            </span>
                        </td>
                        <td>
                            <div class="eng-allottee-name">
                                {{ trim(($app->prefix ?? '') . ' ' . ($app->allottee_name ?? '') . ' ' . ($app->allottee_middle_name ?? '') . ' ' . ($app->allottee_surname ?? '')) ?: '-' }}
                            </div>
                            <div class="eng-prop-meta">
                                <i class="fa-solid fa-house-chimney text-muted me-1"></i> {{ $app->property_number ?? 'N/A' }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #334155;">{{ $app->created_date_formatted }}</div>
                            <span class="badge bg-light text-dark border" style="border-radius: 2px; font-size: 10px; padding: 1px 5px; font-weight: 600;">
                                <i class="fa-regular fa-clock me-1"></i> {{ $app->days_pending }}d pending
                            </span>
                        </td>
                        <td>
                            @if($app->priority === 'Urgent' || $app->priority === 'Overdue')
                            <span class="badge-status inactive" style="border-radius: 2px; font-size: 10.5px; padding: 2px 6px;">
                                <i class="fa-solid fa-circle"></i> {{ $app->priority }}
                            </span>
                            @else
                            <span class="badge-status active" style="border-radius: 2px; font-size: 10.5px; padding: 2px 6px;">
                                <i class="fa-solid fa-circle"></i> {{ $app->priority }}
                            </span>
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
                                <div style="font-weight: 700; font-size: 12px; color: {{ $isOverdue ? '#dc2626' : '#16a34a' }};">
                                    <i class="fa-regular fa-calendar-check me-1"></i> {{ \Carbon\Carbon::parse($dueDate)->format('d M Y') }}
                                </div>
                            @else
                                <span class="text-muted" style="font-size: 11.5px;">N/A</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            @if($hasExtensionRequest)
                                <span class="badge bg-warning text-dark border" style="border-radius: 2px; font-size: 10px; padding: 3px 6px; font-weight: 700;">
                                    <i class="fa-solid fa-clock-rotate-left me-1"></i> Extension Pending
                                </span>
                            @elseif($isOverdue)
                                <div style="font-size: 10.5px; color: #dc2626; margin-bottom: 3px; font-weight: 700;">
                                    <i class="fa-solid fa-circle-exclamation"></i> Overdue
                                </div>
                                <a href="{{ route('engineer.extensions.create', \Illuminate\Support\Facades\Crypt::encryptString($app->id)) }}" class="eng-btn-action btn-extend">
                                    <i class="fa-solid fa-calendar-plus me-1"></i> Request Extension
                                </a>
                            @else
                                @if($daysRemaining !== null && $daysRemaining <= 2 && $daysRemaining >= 0)
                                    <div style="font-size: 10.5px; color: #ea580c; margin-bottom: 3px; font-weight: 700;">
                                        <i class="fa-solid fa-bell"></i> Expiring in {{ $daysRemaining }}d
                                    </div>
                                @endif
                                <a href="{{ route('engineer.applications.show', $app) }}" class="eng-btn-action btn-review">
                                    <i class="fa-regular fa-eye me-1"></i> Review
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="eng-card text-center p-4">
        <div style="font-size: 32px; color: #10b981; margin-bottom: 8px;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h5 style="color: #0f172a; font-weight: 700; margin-bottom: 4px; font-size: 14px;">All Caught Up!</h5>
        <p class="text-muted mb-0" style="font-size: 12px;">No pending applications requiring your review at the moment.</p>
    </div>
    @endif

    <!-- SECTION 4: Recent Notices & Workstation Calendar (Compact 50/50 Grid) -->
    <link rel="stylesheet" href="{{ asset('css/calendar-admin.css') }}">
    <div class="row g-2">
        <!-- 4A. Recent Notices Column -->
        <div class="col-12 col-lg-6">
            <div class="eng-card" style="height: 100%; display: flex; flex-direction: column; margin-bottom: 0;">
                <div class="eng-card-head">
                    <div class="eng-card-title">
                        <i class="fa-solid fa-bullhorn text-primary"></i> Recent Notices & Circulars
                    </div>
                    <span class="badge bg-primary eng-badge-pill">{{ count($dashboardNotices) }}</span>
                </div>

                <div class="card-body p-0" style="max-height: 420px; overflow-y: auto;">
                    @forelse(collect($dashboardNotices)->take(6) as $notice)
                    @php
                        $badgeClass = 'bg-primary';
                        if ($notice->notice_type === 'warning') $badgeClass = 'bg-danger';
                        if ($notice->notice_type === 'new') $badgeClass = 'bg-success';
                        if ($notice->notice_type === 'info') $badgeClass = 'bg-info text-dark';
                    @endphp
                    <div class="eng-notice-item">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                <span class="badge {{ $badgeClass }} eng-notice-badge">{{ strtoupper($notice->notice_type) }}</span>
                                <span class="eng-notice-title">{{ $notice->title }}</span>
                            </div>
                            <small class="text-muted ms-2" style="font-size: 11px; white-space: nowrap;">
                                <i class="fa-regular fa-calendar me-1"></i>{{ $notice->created_at->format('d M') }}
                            </small>
                        </div>
                        <div class="eng-notice-text">
                            {!! strip_tags(html_entity_decode($notice->message)) !!}
                        </div>
                        <div class="mt-2 text-end">
                            <button class="eng-notice-action" onclick="showSingleNoticeModal({{ $notice->id }})">
                                Read Notice <i class="fa-solid fa-arrow-right fs-xs"></i>
                            </button>
                        </div>

                        <!-- Hidden Content for Modal -->
                        <div id="notice-content-{{ $notice->id }}" style="display: none;">
                            <div class="full-message">{!! $notice->message !!}</div>
                            <div class="creator-name">{{ $notice->creator ? $notice->creator->name : 'System' }}</div>
                            <div class="created-date">{{ $notice->created_at->format('d M, Y h:i A') }}</div>
                            <div class="notice-title">{{ $notice->title }}</div>
                            <div class="badge-class">{{ $badgeClass }}</div>
                            <div class="type-label">{{ strtoupper($notice->notice_type) }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-muted">
                        <i class="fa-regular fa-folder-open fs-3 mb-2 text-muted"></i>
                        <p class="mb-0 small">No recent notices available.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 4B. Calendar Column -->
        <div class="col-12 col-lg-6">
            <div class="calendar-admin-container" id="calendarApp" style="height: 100%; position: relative; padding-bottom: {{ isset($currentMonthReceivedCount) ? '2.8rem' : '1rem' }} !important;">
                <div class="calendar-header">
                    <h4>
                        <i class="fa-regular fa-calendar-days text-primary me-1"></i>
                        <span id="monthYearDisplay">Month Year</span>
                    </h4>
                    <div class="nav-buttons">
                        <button id="prevMonthBtn" title="Previous Month"><i class="fas fa-chevron-left"></i></button>
                        <button id="nextMonthBtn" title="Next Month"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>

                <div class="weekdays">
                    <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span>
                    <span>Thu</span><span>Fri</span><span>Sat</span>
                </div>

                <div class="days-grid" id="daysGrid"></div>

                @if(isset($currentMonthReceivedCount))
                <div class="eng-calendar-footer">
                    <span><i class="fa-solid fa-inbox text-primary me-1"></i> Application Received This Month:</span>
                    <span class="badge bg-primary" style="border-radius: 2px; font-size: 11px; padding: 2px 7px;">{{ $currentMonthReceivedCount }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modals for Calendar and Notices -->
    <div class="modal fade" id="noticesModal" tabindex="-1" aria-labelledby="noticesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noticesModalLabel"><i class="fa-regular fa-calendar-days me-1 text-primary"></i> Events for <span id="noticesModalDate"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="noticesModalBody">
                    <!-- Events will be injected here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Single Notice Read More Modal -->
    <div class="modal fade" id="readMoreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header pb-2">
                    <h5 class="modal-title fw-bold" id="readMoreTitle">Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <div class="mb-2">
                        <span id="readMoreBadge" class="badge eng-notice-badge"></span>
                    </div>
                    <div id="readMoreMessage" style="color: #334155; line-height: 1.5; font-size: 13px;"></div>
                    <hr class="my-2 text-muted">
                    <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                        <span><i class="fa-solid fa-user me-1"></i> Published by: <span id="readMoreCreator"></span></span>
                        <span><i class="fa-regular fa-clock me-1"></i> <span id="readMoreDate"></span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar & Notice Scripts -->
    <script>
        window.adminNotices = @json($dashboardNotices ?? []);
        window.adminNotifications = @json($dashboardNotifications ?? []);
        window.adminForwardedApps = @json($dashboardForwardedApps ?? []);

        window.showNoticesModal = function(dateStr, dayData) {
            document.getElementById('noticesModalDate').textContent = dateStr;
            const body = document.getElementById('noticesModalBody');
            body.innerHTML = '';

            const notices = dayData.notices || [];
            const notifications = dayData.notifications || [];
            const forwardedApps = dayData.forwardedApps || [];

            if (notices.length === 0 && notifications.length === 0 && forwardedApps.length === 0) {
                body.innerHTML = '<p class="text-muted small">No items scheduled for this date.</p>';
            } else {
                notices.forEach(notice => {
                    let badgeClass = 'bg-primary';
                    if (notice.notice_type === 'warning') badgeClass = 'bg-danger';
                    if (notice.notice_type === 'new') badgeClass = 'bg-success';
                    if (notice.notice_type === 'info') badgeClass = 'bg-info text-dark';

                    let typeLabel = (notice.notice_type || 'notice').toUpperCase();
                    let creatorName = notice.creator ? notice.creator.name : 'System';
                    let createdDate = new Date(notice.created_at).toLocaleString();

                    let html = `
                        <div class="card mb-2 border-0 shadow-sm" style="border-left: 3px solid #0284c7 !important; border-radius: 2px;">
                            <div class="card-body p-2">
                                <h6 class="fw-bold mb-1" style="font-size: 13px;">
                                    <span class="badge ${badgeClass} me-1" style="border-radius: 2px; font-size: 9.5px;">${typeLabel}</span>
                                    ${notice.title}
                                </h6>
                                <div class="card-text small mt-1" style="color: #475569; font-size: 12px;">${notice.message}</div>
                                <hr class="my-1 text-muted">
                                <div class="d-flex justify-content-between text-muted" style="font-size: 0.72rem;">
                                    <span><i class="fa-solid fa-user me-1"></i> ${creatorName}</span>
                                    <span><i class="fa-regular fa-clock me-1"></i> ${createdDate}</span>
                                </div>
                            </div>
                        </div>
                    `;
                    body.innerHTML += html;
                });

                notifications.forEach(notif => {
                    let createdDate = new Date(notif.created_at).toLocaleString();
                    let html = `
                        <div class="card mb-2 border-0 shadow-sm" style="border-left: 3px solid #7c3aed !important; background-color: #faf5ff; border-radius: 2px;">
                            <div class="card-body p-2">
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 13px;">
                                    <span class="badge me-1" style="background-color: #7c3aed; border-radius: 2px; font-size: 9.5px;"><i class="fa-solid fa-bell me-1"></i> NOTIFICATION</span>
                                    ${notif.subject || 'System Notification'}
                                </h6>
                                <div class="card-text small mt-1" style="color: #475569; font-size: 12px;">${notif.message || ''}</div>
                                <hr class="my-1 text-muted">
                                <div class="d-flex justify-content-between text-muted" style="font-size: 0.72rem;">
                                    <span><i class="fa-solid fa-paper-plane me-1"></i> Auto-Generated</span>
                                    <span><i class="fa-regular fa-clock me-1"></i> ${createdDate}</span>
                                </div>
                            </div>
                        </div>
                    `;
                    body.innerHTML += html;
                });

                forwardedApps.forEach(app => {
                    let createdDate = new Date(app.created_date).toLocaleString();
                    let html = `
                        <div class="card mb-2 border-0 shadow-sm" style="border-left: 3px solid #dc2626 !important; background-color: #fef2f2; border-radius: 2px;">
                            <div class="card-body p-2">
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 13px;">
                                    <span class="badge me-1 bg-danger" style="border-radius: 2px; font-size: 9.5px;"><i class="fa-solid fa-file-signature me-1"></i> FORWARDED APP</span>
                                    App: ${app.application_no}
                                </h6>
                                <div class="card-text small mt-1" style="color: #475569; font-size: 12px;">
                                    <strong>Type:</strong> <span class="text-uppercase">${app.application_type}</span><br>
                                    <strong>Allottee:</strong> ${app.allottee_name || 'N/A'}
                                </div>
                                <hr class="my-1 text-muted">
                                <div class="d-flex justify-content-between text-muted" style="font-size: 0.72rem;">
                                    <span><i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Pending action</span>
                                    <span><i class="fa-regular fa-clock me-1"></i> ${createdDate}</span>
                                </div>
                            </div>
                        </div>
                    `;
                    body.innerHTML += html;
                });
            }

            var myModal = new bootstrap.Modal(document.getElementById('noticesModal'));
            myModal.show();
        };

        window.showSingleNoticeModal = function(id) {
            const container = document.getElementById(`notice-content-${id}`);
            if (!container) return;

            document.getElementById('readMoreTitle').textContent = container.querySelector('.notice-title').textContent;
            const badge = document.getElementById('readMoreBadge');
            badge.className = `badge eng-notice-badge ${container.querySelector('.badge-class').textContent}`;
            badge.textContent = container.querySelector('.type-label').textContent;

            document.getElementById('readMoreMessage').innerHTML = container.querySelector('.full-message').innerHTML;
            document.getElementById('readMoreCreator').textContent = container.querySelector('.creator-name').textContent;
            document.getElementById('readMoreDate').textContent = container.querySelector('.created-date').textContent;

            var myModal = new bootstrap.Modal(document.getElementById('readMoreModal'));
            myModal.show();
        };
    </script>
    <script src="{{ asset('js/calendar-admin.js') }}"></script>
</div>
@endsection
