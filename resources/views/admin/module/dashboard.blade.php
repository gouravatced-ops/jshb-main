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
                <div class="hero-time">{{ now()->format('g:i') }} <span
                        style="color:#f5c518;">{{ now()->format('A') }}</span></div>
                <div class="hero-date">{{ now()->format('l, d M Y') }}</div>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="row g-2 mb-3">
            <div class="col-6 col-xl-3">
                <div class="stat-card landed">
                    <div class="stat-card-top">
                        <div class="stat-icon teal"><i class="fas fa-users"></i></div>
                        <div class="stat-info">
                            <p class="stat-label">Total Allottees</p>
                            <p class="stat-value">2,354</p>
                        </div>
                    </div>
                    {{-- <div class="stat-delta up"><i class="fas fa-arrow-up"></i> 12.5% <span style="color:var(--text-dark)">vs
                            last month</span></div> --}}
                    <div class="stat-chart"><canvas id="sparkline1"></canvas></div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card landed">
                    <div class="stat-card-top">
                        <div class="stat-icon green"><i class="fas fa-file-lines"></i></div>
                        <div class="stat-info">
                            <p class="stat-label">Total Projects</p>
                            <p class="stat-value">128</p>
                        </div>
                    </div>
                    {{-- <div class="stat-delta up"><i class="fas fa-arrow-up"></i> 8.3% <span style="color:var(--text-dark)">vs
                            last month</span></div> --}}
                    <div class="stat-chart"><canvas id="sparkline2"></canvas></div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card landed">
                    <div class="stat-card-top">
                        <div class="stat-icon yellow"><i class="fas fa-indian-rupee-sign"></i></div>
                        <div class="stat-info">
                            <p class="stat-label">Total Transactions</p>
                            <p class="stat-value" style="font-size:16px;">₹ 45.68 Cr</p>
                        </div>
                    </div>
                    {{-- <div class="stat-delta up"><i class="fas fa-arrow-up"></i> 15.7% <span style="color:var(--text-dark)">vs
                            last month</span></div> --}}
                    <div class="stat-chart"><canvas id="sparkline3"></canvas></div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card landed">
                    <div class="stat-card-top">
                        <div class="stat-icon navy"><i class="fas fa-chart-column"></i></div>
                        <div class="stat-info">
                            <p class="stat-label">Total Amount Allotted</p>
                            <p class="stat-value" style="font-size:16px;">₹ 320.45 Cr</p>
                        </div>
                    </div>
                    {{-- <div class="stat-delta up"><i class="fas fa-arrow-up"></i> 10.4% <span style="color:var(--text-dark)">vs
                            last month</span></div> --}}
                    <div class="stat-chart"><canvas id="sparkline4"></canvas></div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-2 mb-3">
            <div class="col-12 col-xl-6">
                <div class="chart-card landed">
                    <div class="chart-card-header">
                        <h6>Transactions Overview</h6>
                        <select class="chart-select">
                            <option>This Year</option>
                            <option>Last Year</option>
                        </select>
                    </div>
                    <canvas id="txnChart" height="110"></canvas>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="chart-card landed">
                    <div class="chart-card-header">
                        <h6>Allottees Overview</h6>
                        <select class="chart-select">
                            <option>This Year</option>
                            <option>Last Year</option>
                        </select>
                    </div>
                    <canvas id="allotChart" height="110"></canvas>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row g-2">
            <div class="col-12 col-xl-6">
                <div class="table-card landed">
                    <div class="table-card-header">
                        <h6>Recent Transactions</h6>
                        <button class="btn-view-all">View All</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-border table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Transaction ID</th>
                                    <th>Allottee Name</th>
                                    <th>Amount (₹)</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="row-num">1</td>
                                    <td>TXN10001</td>
                                    <td>Ravi Kumar</td>
                                    <td>₹ 12,45,000</td>
                                    <td>24 May 2024</td>
                                    <td><span class="badge-status badge-completed">Completed</span></td>
                                </tr>
                                <tr>
                                    <td class="row-num">2</td>
                                    <td>TXN10002</td>
                                    <td>Priya Sharma</td>
                                    <td>₹ 8,75,000</td>
                                    <td>23 May 2024</td>
                                    <td><span class="badge-status badge-completed">Completed</span></td>
                                </tr>
                                <tr>
                                    <td class="row-num">3</td>
                                    <td>TXN10003</td>
                                    <td>Amit Verma</td>
                                    <td>₹ 15,60,000</td>
                                    <td>22 May 2024</td>
                                    <td><span class="badge-status badge-pending">Pending</span></td>
                                </tr>
                                <tr>
                                    <td class="row-num">4</td>
                                    <td>TXN10004</td>
                                    <td>Neha Singh</td>
                                    <td>₹ 7,25,000</td>
                                    <td>21 May 2024</td>
                                    <td><span class="badge-status badge-completed">Completed</span></td>
                                </tr>
                                <tr>
                                    <td class="row-num">5</td>
                                    <td>TXN10005</td>
                                    <td>Sandeep Patel</td>
                                    <td>₹ 9,80,000</td>
                                    <td>20 May 2024</td>
                                    <td><span class="badge-status badge-failed">Failed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="table-card">
                    <div class="table-card-header">
                        <h6>Recent Allottees</h6>
                        <button class="btn-view-all">View All</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-border table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Allottee ID</th>
                                    <th>Allottee Name</th>
                                    <th>Contact</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="row-num">1</td>
                                    <td>ALT10001</td>
                                    <td>Ravi Kumar</td>
                                    <td>9876543210</td>
                                    <td>24 May 2024</td>
                                    <td><span class="badge-status badge-active">Active</span></td>
                                </tr>
                                <tr>
                                    <td class="row-num">2</td>
                                    <td>ALT10002</td>
                                    <td>Priya Sharma</td>
                                    <td>9123456780</td>
                                    <td>23 May 2024</td>
                                    <td><span class="badge-status badge-active">Active</span></td>
                                </tr>
                                <tr>
                                    <td class="row-num">3</td>
                                    <td>ALT10003</td>
                                    <td>Amit Verma</td>
                                    <td>9988776655</td>
                                    <td>22 May 2024</td>
                                    <td><span class="badge-status badge-active">Active</span></td>
                                </tr>
                                <tr>
                                    <td class="row-num">4</td>
                                    <td>ALT10004</td>
                                    <td>Neha Singh</td>
                                    <td>9090909090</td>
                                    <td>21 May 2024</td>
                                    <td><span class="badge-status badge-inactive">Inactive</span></td>
                                </tr>
                                <tr>
                                    <td class="row-num">5</td>
                                    <td>ALT10005</td>
                                    <td>Sandeep Patel</td>
                                    <td>8881234567</td>
                                    <td>20 May 2024</td>
                                    <td><span class="badge-status badge-active">Active</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* CHART CARDS */
        .chart-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 12px 14px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.09);
        }

        .chart-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .chart-card-header h6 {
            font-size: 13px;
            font-weight: 700;
            margin: 0;
        }

        .chart-select {
            font-size: 12px;
            border: 1px solid var(--border);
            border-radius: 3px;
            padding: 3px 6px;
            color: var(--text-main);
            font-weight: 600;
            letter-spacing: 0.02rem;
            background: var(--body-bg);
            cursor: pointer;
            outline: none;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.09);
        }

        /* TABLES */
        .table-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 4px;
            overflow: hidden;
        }

        .table-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
        }

        .table-card-header h6 {
            font-size: 13px;
            font-weight: 700;
            margin: 0;
        }

        .btn-view-all {
            background: var(--accent);
            color: var(--sidebar-bg);
            border: none;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            cursor: pointer;
            color: var(--text-main);
            font-weight: 600;
            letter-spacing: 0.02rem;
        }

        .table {
            margin: 0;
            font-size: 12px;
        }

        .table th {
            background: #0a1e3d;
            color: var(--text-light);
            font-weight: 600;
            font-size: 12px;
            padding: 7px 10px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .table td {
            padding: 7px 10px;
            vertical-align: middle;
            border-color: #f0f2f5;
        }

        .table tbody tr:hover {
            background: #cfe6fd;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 2px;
            font-size: 10px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-completed {
            background: var(--green-light);
            color: var(--green);
        }

        .badge-pending {
            background: var(--yellow-light);
            color: #a07a00;
        }

        .badge-failed {
            background: var(--red-light);
            color: var(--red);
        }

        .badge-active {
            background: var(--green-light);
            color: var(--green);
        }

        .badge-inactive {
            background: #ffe8e8;
            color: var(--red);
        }

        .row-num {
            color: var(--text-dark);
            font-size: 11px;
        }
    </style>
@endsection
