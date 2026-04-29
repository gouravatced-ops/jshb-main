@extends('layouts.main')

@section('title', 'Admin Dashboard | JESA')

@section('content')
<div id="page-dashboard" class="admin-dashboard-page">
    <div class="dashboard-hero-card">
        <div>
            <div class="dashboard-hero-kicker">Admin Quick View</div>
            <h2 class="dashboard-hero-title">JESA Dashboard</h2>
            <!-- <p class="dashboard-hero-text">Track live engineer records, recent additions, district coverage, and important admin activity from one screen.</p> -->
        </div>
        <div class="dashboard-hero-meta">
            <div class="hero-time">{{ now()->format('g:i A') }}</div>
            <div class="hero-date">{{ now()->format('l, d M Y') }}</div>
            <!-- <div class="hero-actions">
                <a class="btn-pink" href="{{ route('admin.engineers.create') }}"><i class="fa-solid fa-user-plus"></i> Add Engineer</a>
                <a class="btn-outline" href="{{ route('admin.engineers.index') }}"><i class="fa-solid fa-list"></i> View List</a>
            </div> -->
        </div>
    </div>

    <div class="stat-cards">
        <div class="stat-card pink">
            <div class="stat-top">
                <div class="stat-icon pink"><i class="fa-solid fa-hard-hat"></i></div>
                <div class="stat-value">{{ $totalEngineers }}</div>
            </div>
            <div class="stat-label">Total Engineers</div>
        </div>
        <div class="stat-card sky">
            <div class="stat-top">
                <div class="stat-icon sky"><i class="fa-solid fa-location-dot"></i></div>
                <div class="stat-value">{{ $districtCoverage }}</div>
            </div>
            <div class="stat-label">District Coverage</div>
        </div>
        <div class="stat-card green">
            <div class="stat-top">
                <div class="stat-icon green"><i class="fa-solid fa-building"></i></div>
                <div class="stat-value">{{ $totalOrganizations }}</div>
            </div>
            <div class="stat-label">Organizations</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-top">
                <div class="stat-icon orange"><i class="fa-solid fa-hotel"></i></div>
                <div class="stat-value">{{ $pendingRequisitions }}</div>
            </div>
            <div class="stat-label">Pending Requisitions</div>
        </div>
    </div>

    <div class="grid-2 admin-dashboard-grid">
        <div class="card">
            <div class="card-head">
                <div>
                    <div class="card-title">District Wise Engineer Count</div>
                    <div class="card-subtitle">Top districts by total engineer records</div>
                </div>
            </div>

            <div class="district-chart-wrap">
                @php
                $maxDistrictCount = max(1, (int) ($districtEngineerCounts->max('engineer_count') ?? 1));
                @endphp

                @forelse($districtEngineerCounts as $row)
                <div class="district-chart-row">
                    <div class="district-chart-head">
                        <span>{{ $row->district_name }}</span>
                        <strong>{{ $row->engineer_count }}</strong>
                    </div>
                    <div class="district-chart-track">
                        <div class="district-chart-bar" style="width: {{ max(8, round(($row->engineer_count / $maxDistrictCount) * 100)) }}%"></div>
                    </div>
                </div>
                @empty
                <div class="empty-state-lite">No engineer district data available yet.</div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <div>
                    <div class="card-title">Quick View</div>
                    <div class="card-subtitle">Latest updates and fast admin insights</div>
                </div>
            </div>

            <div class="quick-view-grid">
                <div class="quick-view-tile soft-pink">
                    <div class="quick-view-icon"><i class="fa-solid fa-user-check"></i></div>
                    <div class="quick-view-label">Latest Engineer</div>
                    <div class="quick-view-value">{{ $latestEngineer?->employee_name ?: 'No data' }}</div>
                    <div class="quick-view-meta">{{ $latestEngineer?->created_at?->diffForHumans() ?: 'Engineer records not added yet' }}</div>
                </div>

                <div class="quick-view-tile soft-sky">
                    <div class="quick-view-icon"><i class="fa-solid fa-map-location-dot"></i></div>
                    <div class="quick-view-label">Top District</div>
                    <div class="quick-view-value">{{ $topDistrict?->district_name ?: 'No data' }}</div>
                    <div class="quick-view-meta">{{ $topDistrict?->engineer_count ? $topDistrict->engineer_count . ' engineers' : 'District data not available' }}</div>
                </div>

                <div class="quick-view-tile soft-green">
                    <div class="quick-view-icon"><i class="fa-solid fa-right-to-bracket"></i></div>
                    <div class="quick-view-label">Latest Login</div>
                    <div class="quick-view-value">{{ $latestLogin?->email ?: 'No login logs' }}</div>
                    <div class="quick-view-meta">{{ $latestLogin?->created_at?->diffForHumans() ?: 'No recent login activity' }}</div>
                </div>

                <div class="quick-view-tile soft-orange">
                    <div class="quick-view-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="quick-view-label">OTP Logs</div>
                    <div class="quick-view-value">{{ $otpLogs->count() }}</div>
                    <div class="quick-view-meta">Latest secure access records tracked</div>
                </div>
            </div>

            <div class="activity-feed" style="padding-top: 8px;">
                @forelse($recentEngineers->take(4) as $engineer)
                <div class="activity-item">
                    <div class="activity-icon" style="background:var(--pink-light);color:var(--pink-primary)">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div class="activity-line">
                        <div class="activity-text"><strong>{{ $engineer->employee_name }}</strong> added in {{ $engineer->district?->name_en ?: 'district not set' }}</div>
                        <div class="activity-time">{{ $engineer->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="empty-state-lite">No recent engineer additions available.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <div class="card-head">
            <div>
                <div class="card-title">Latest Guest House Requisitions</div>
                <div class="card-subtitle">Recent stay applications submitted by engineers</div>
            </div>
            <div class="card-actions">
                <a class="btn-outline" href="{{ route('admin.requisitions.index') }}"><i class="fa-solid fa-arrow-right"></i> Manage Requisitions</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Guest House</th>
                        <th>District / Block</th>
                        <th>Stay Dates</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRequisitions as $requisition)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $requisition->user?->name ?: '-' }}</td>
                        <td>{{ $requisition->guest_house_name }}</td>
                        <td>{{ ($requisition->district?->name_en ?: '-') . ' / ' . ($requisition->block?->block_name_eng ?: '-') }}</td>
                        <td>{{ optional($requisition->stay_from)->format('d M Y') }} - {{ optional($requisition->stay_to)->format('d M Y') }}</td>
                        <td><span class="badge-status {{ $requisition->status === 'approved' ? 'active' : ($requisition->status === 'rejected' ? 'inactive' : '') }}">{{ ucfirst($requisition->status) }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:32px 20px;color:var(--text-light);">No requisition applications found yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <div class="card-head">
            <div>
                <div class="card-title">Recent Added Engineers</div>
                <div class="card-subtitle">Latest engineer records created in the system</div>
            </div>
            <div class="card-actions">
                <a class="btn-outline" href="{{ route('admin.engineers.index') }}"><i class="fa-solid fa-arrow-right"></i> Full List</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Engineer</th>
                        <th>Govt ID</th>
                        <th>Current Organization</th>
                        <th>Post Type</th>
                        <th>District / Block</th>
                        <th>Added On</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEngineers as $engineer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="table-user">
                                <div class="table-avatar a{{ ($loop->iteration % 5) + 1 }}">{{ strtoupper(substr($engineer->employee_name, 0, 2)) }}</div>
                                <div>
                                    <div class="table-name">{{ $engineer->employee_name }}</div>
                                    <div class="table-email">{{ $engineer->user?->email ?: '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $engineer->masked_state_government_engineer_id }}</td>
                        <td>{{ $engineer->currentOrganization?->name ?: '-' }}</td>
                        <td>{{ $engineer->postType?->name ?: '-' }}</td>
                        <td>{{ ($engineer->district?->name_en ?: '-') . ' / ' . ($engineer->block?->block_name_eng ?: '-') }}</td>
                        <td>{{ $engineer->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                            No engineer records found yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid-2 admin-dashboard-grid">
        <div class="card">
            <div class="card-head">
                <div>
                    <div class="card-title">Recent Login Activity</div>
                    <div class="card-subtitle">Latest system login events</div>
                </div>
            </div>
            <div class="activity-feed">
                @forelse($loginLogs->take(6) as $log)
                <div class="activity-item">
                    <div class="activity-icon" style="background:{{ $log->status === 'success' ? 'var(--green-light)' : '#fef2f2' }};color:{{ $log->status === 'success' ? 'var(--green-primary)' : '#ef4444' }}">
                        <i class="fa-solid {{ $log->status === 'success' ? 'fa-right-to-bracket' : 'fa-triangle-exclamation' }}"></i>
                    </div>
                    <div class="activity-line">
                        <div class="activity-text"><strong>{{ $log->email }}</strong> {{ ucfirst(str_replace('_', ' ', $log->action)) }}</div>
                        <div class="activity-time">{{ $log->created_at->diffForHumans() }} from {{ $log->ip_address }}</div>
                    </div>
                </div>
                @empty
                <div class="empty-state-lite">No login activity found.</div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <div>
                    <div class="card-title">User Snapshot</div>
                    <div class="card-subtitle">Latest registered login users in the system</div>
                </div>
            </div>
            <div class="activity-feed">
                @forelse($users->take(6) as $member)
                <div class="activity-item">
                    <div class="activity-icon" style="background:var(--sky-light);color:var(--sky-primary)">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="activity-line">
                        <div class="activity-text"><strong>{{ $member->name }}</strong> as {{ ucfirst($member->role) }}</div>
                        <div class="activity-time">{{ $member->email }} • {{ $member->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="empty-state-lite">No user records available.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection