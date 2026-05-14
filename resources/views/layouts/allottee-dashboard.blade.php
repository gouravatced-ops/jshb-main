{{-- allottee-dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Allottee Dashboard')</title>
    <meta name="description" content="Jharkhand State Housing Board | Allottee Portal" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(config('panel.faviconIcon')) }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/all.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #F0F4F8;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1A2C3E;
        }

        /* Compact utility */
        .compact-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.2s ease;
        }

        .compact-card:hover {
            border-color: #B0D4E8;
            box-shadow: 0 2px 6px rgba(0, 50, 60, 0.04);
        }

        /* Sidebar modern clean */
        .allottee-sidebar-modern {
            background: #FFFFFF;
            border-right: 1px solid #E2EDF2;
        }

        .allottee-tab-modern {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            margin: 6px 12px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.9rem;
            color: #2C4C6C;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .allottee-tab-modern i {
            width: 20px;
            font-size: 1rem;
            color: #3B82F6;
        }

        .allottee-tab-modern:hover {
            background: #EEF5F9;
            border-color: #CAE3F0;
            color: #0F5C7A;
        }

        .allottee-tab-modern.active {
            background: #EFF7FC;
            border-left: 3px solid #0F766E;
            border-radius: 10px 8px 8px 10px;
            color: #0F766E;
            font-weight: 600;
        }

        .allottee-tab-modern.active i {
            color: #0F766E;
        }

        .allottee-tab-modern.locked {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            background: #EFF3F6;
            color: #2F5B7A;
        }

        .step-badge.completed {
            background: #0F766E;
            color: white;
        }

        .step-badge.pending {
            background: #EFF6FF;
            color: #3B82F6;
            border: 1px solid #BFDBFE;
        }

        .step-badge.locked {
            background: #F1F5F9;
            color: #7E9CB0;
        }

        .progress-compact {
            height: 6px;
            background: #E2EDF2;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-compact-bar {
            background: #0F766E;
            width: 0%;
            height: 100%;
            border-radius: 10px;
        }

        /* modern stat cards */
        .stat-chip {
            background: #F8FCFE;
            border: 1px solid #E2F0F5;
            border-radius: 14px;
            padding: 1rem 0.9rem;
        }

        .stat-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #668aa5;
        }

        .stat-value {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1C3B4F;
            line-height: 1.2;
            word-break: break-word;
        }

        hr {
            background: #E2EDF2;
            opacity: 0.6;
        }

        .btn-outline-green {
            border: 1px solid #0F766E;
            color: #0F766E;
            background: transparent;
            border-radius: 30px;
            padding: 0.3rem 1rem;
            font-size: 0.8rem;
            transition: 0.15s;
        }

        .btn-outline-green:hover {
            background: #0F766E;
            color: white;
        }

        .badge-status {
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
            border-radius: 50px;
            font-weight: 500;
        }

        /* modern-sidebar.css - Green/Blue/White Theme, No Gradients */

        /* ==================================================
   SIDEBAR CONTAINER - MODERN COMPACT DESIGN
   ================================================== */
        .allottee-sidebar-modern {
            background: #FFFFFF;
            border-right: 1px solid #E2F0F5;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Progress section */
        .progress-section {
            padding: 1.25rem 1rem 0.75rem 1rem;
            border-bottom: 1px solid #EEF5F9;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 0.5rem;
        }

        .progress-label span:first-child {
            font-size: 0.7rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #5A8CAA;
        }

        .progress-label span:last-child {
            font-size: 0.8rem;
            font-weight: 700;
            color: #0F766E;
        }

        .progress-bar-compact {
            height: 4px;
            background: #E2EDF2;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #0F766E;
            border-radius: 4px;
            width: 0%;
            transition: width 0.2s ease;
        }

        /* Menu header */
        .menu-header {
            padding: 1rem 1rem 0.5rem 1rem;
        }

        .menu-header .menu-title {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #7CA5C2;
            margin-bottom: 0.25rem;
        }

        .menu-header .menu-subtitle {
            font-size: 0.7rem;
            color: #A8C4D8;
        }

        /* ==================================================
   SIDEBAR TABS / NAVIGATION ITEMS
   ================================================== */
        .allottee-tab-modern {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            margin: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #2C5A7A;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.2, 0, 0, 1);
            border: 1px solid transparent;
            background: transparent;
            cursor: pointer;
        }

        /* Icon styling */
        .allottee-tab-modern i {
            width: 20px;
            font-size: 1rem;
            color: #5FA3CF;
            transition: color 0.2s;
            text-align: center;
        }

        /* Hover state */
        .allottee-tab-modern:hover {
            background: #F0F9FC;
            border-color: #C8E5F0;
            color: #0F6B6B;
        }

        .allottee-tab-modern:hover i {
            color: #0F766E;
        }

        /* Active state - clean left accent, no gradient */
        .allottee-tab-modern.active {
            background: #EBF6FA;
            border-left: 3px solid #0F766E;
            border-radius: 12px 10px 10px 12px;
            color: #0F766E;
            font-weight: 600;
            box-shadow: none;
        }

        .allottee-tab-modern.active i {
            color: #0F766E;
        }

        /* Locked/disabled state */
        .allottee-tab-modern.locked {
            opacity: 0.45;
            cursor: not-allowed;
            pointer-events: none;
            filter: grayscale(0.05);
        }

        /* ==================================================
   STEP BADGES - Modern number indicators
   ================================================== */
        .step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 700;
            flex-shrink: 0;
            transition: all 0.15s;
        }

        /* Badge status variants */
        .step-badge.completed {
            background: #0F766E;
            color: #FFFFFF;
            box-shadow: 0 1px 2px rgba(15, 118, 110, 0.2);
        }

        .step-badge.pending {
            background: #EFF6FF;
            color: #3B82F6;
            border: 1px solid #BFDBFE;
        }

        .step-badge.locked {
            background: #F1F5F9;
            color: #94AEC8;
            border: 1px solid #E2E8F0;
        }

        /* Hover effect on badge inside non-locked items */
        .allottee-tab-modern:not(.locked):hover .step-badge.pending {
            background: #E0F2FE;
            border-color: #7AB8E0;
        }

        /* ==================================================
   DIVIDER
   ================================================== */
        .sidebar-divider {
            margin: 12px 16px;
            border: none;
            height: 1px;
            background: #EAF3F8;
        }

        /* ==================================================
   FOOTER / EXTRA INFO (optional)
   ================================================== */
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem;
            border-top: 1px solid #EEF5F9;
            font-size: 0.7rem;
            color: #8FB4CC;
            text-align: center;
        }

        /* ==================================================
   RESPONSIVE BEHAVIOR
   ================================================== */
        @media (max-width: 768px) {
            .allottee-sidebar-modern {
                border-right: none;
                border-bottom: 1px solid #E2F0F5;
            }

            .allottee-tab-modern {
                padding: 8px 12px;
                margin: 2px 8px;
                font-size: 0.8rem;
            }

            .step-badge {
                width: 24px;
                height: 24px;
                font-size: 0.65rem;
            }
        }

        /* ==================================================
   SCROLLBAR (modern thin)
   ================================================== */
        .allottee-sidebar-modern::-webkit-scrollbar {
            width: 4px;
        }

        .allottee-sidebar-modern::-webkit-scrollbar-track {
            background: #F0F7FA;
        }

        .allottee-sidebar-modern::-webkit-scrollbar-thumb {
            background: #BED9E8;
            border-radius: 4px;
        }

        /* ==================================================
   ADDITIONAL UTILITIES FOR SIDEBAR
   ================================================== */
        /* Tooltip style for locked items (optional) */
        .allottee-tab-modern.locked {
            position: relative;
        }

        /* Compact mode for dense view */
        .sidebar-compact .allottee-tab-modern {
            padding: 8px 12px;
            margin: 2px 12px;
            gap: 10px;
        }

        .sidebar-compact .step-badge {
            width: 22px;
            height: 22px;
            font-size: 0.65rem;
        }

        /* Focus state for accessibility */
        .allottee-tab-modern:focus-visible {
            outline: 2px solid #0F766E;
            outline-offset: -2px;
            border-radius: 12px;
        }
    </style>
</head>

<body>
    <main class="container-fluid px-3 px-md-4 py-3">
        @yield('content')
    </main>
</body>

</html>