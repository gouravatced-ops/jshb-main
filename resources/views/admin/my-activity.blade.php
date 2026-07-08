@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-head">
        <div>
            <div class="card-title">My Activity</div>
            <div class="card-subtitle">View your recent account activity and security logs</div>
        </div>
        <div class="card-actions">
            <form method="GET" action="{{ route('my-activity') }}" class="search-box"
                onsubmit="return false;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="logSearchInput" name="search" value=""
                    placeholder="Search logs..." autocomplete="off">
            </form>
        </div>
    </div>
    <div class="ep-card">
        <div class="tabs-container">
            
                <div class="tabs-nav" role="tablist" style="margin-top: 10px; margin-left: 10px; margin-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                    <button type="button" class="tab-btn active" data-tab="login-logs" role="tab" onclick="switchActivityTab('login-logs')">
                        <i class="fa-solid fa-right-to-bracket"></i> Login Logs
                    </button>
                    <button type="button" class="tab-btn" data-tab="otp-logs" role="tab" onclick="switchActivityTab('otp-logs')">
                        <i class="fa-solid fa-shield-halved"></i> OTP Logs
                    </button>
                </div>

                <div class="tabs-content">
                    <!-- Login Logs Tab -->
                    <div class="tab-pane active" id="login-logs-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="ep-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Date & Time</th>
                                        <th>IP Address</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                        <th>Device / Browser</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($loginLogs as $key => $log)
                                        @php
                                            $badgeClass = 'active';
                                            if (str_contains(strtolower($log->status), 'fail') || str_contains(strtolower($log->status), 'block')) {
                                                $badgeClass = 'inactive';
                                            } elseif (str_contains(strtolower($log->action), 'logout')) {
                                                $badgeClass = 'inactive';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                            <td>{{ $log->ip_address }}</td>
                                            <td>
                                                <div class="table-name">{{ $log->action }}</div>
                                            </td>
                                            <td>
                                                <span class="badge-status {{ $badgeClass }}">
                                                    <i class="fa-solid fa-circle"></i>
                                                    {{ $log->status }}
                                                </span>
                                            </td>
                                            <td style="font-size: 12px;">{{ \Illuminate\Support\Str::limit($log->user_agent, 60) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" style="text-align:center;padding:32px 20px;color:var(--text-light);">No login logs found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($loginLogs->total() > 0)
                            <div class="table-pagination">
                                <span>
                                    Showing <strong>{{ $loginLogs->firstItem() }}</strong> to
                                    <strong>{{ $loginLogs->lastItem() }}</strong> of <strong>{{ $loginLogs->total() }}</strong> logs
                                </span>
                                <div class="pagination-btns">
                                    @if ($loginLogs->onFirstPage())
                                        <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-left"></i></span>
                                    @else
                                        <a class="pag-btn" href="{{ $loginLogs->appends(['otp_page' => request('otp_page')])->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                                    @endif

                                    @foreach ($loginLogs->getUrlRange(max(1, $loginLogs->currentPage() - 2), min($loginLogs->lastPage(), $loginLogs->currentPage() + 2)) as $page => $url)
                                        <a class="pag-btn {{ $page === $loginLogs->currentPage() ? 'active' : '' }}"
                                            href="{{ $loginLogs->appends(['otp_page' => request('otp_page')])->url($page) }}">{{ $page }}</a>
                                    @endforeach

                                    @if ($loginLogs->hasMorePages())
                                        <a class="pag-btn" href="{{ $loginLogs->appends(['otp_page' => request('otp_page')])->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                                    @else
                                        <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-right"></i></span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- OTP Logs Tab -->
                    <div class="tab-pane" id="otp-logs-pane" role="tabpanel" style="display: none;">
                        <div class="table-responsive">
                            <table class="ep-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Date & Time</th>
                                        <th>IP Address</th>
                                        <th>Purpose</th>
                                        <th>Sent To</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($otpLogs as $key => $otpLog)
                                        @php
                                            $badgeClass = $otpLog->verified ? 'active' : 'inactive';
                                            $statusText = $otpLog->verified ? 'Verified' : 'Pending / Expired';
                                        @endphp
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{ $otpLog->created_at->format('d M Y, h:i A') }}</td>
                                            <td>{{ $otpLog->ip_address ?? 'N/A' }}</td>
                                            <td>
                                                <div class="table-name" style="text-transform: capitalize;">{{ str_replace('_', ' ', $otpLog->purpose) }}</div>
                                            </td>
                                            <td>{{ $otpLog->email }}</td>
                                            <td>
                                                <span class="badge-status {{ $badgeClass }}">
                                                    <i class="fa-solid fa-circle"></i>
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" style="text-align:center;padding:32px 20px;color:var(--text-light);">No OTP logs found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($otpLogs->total() > 0)
                            <div class="table-pagination">
                                <span>
                                    Showing <strong>{{ $otpLogs->firstItem() }}</strong> to
                                    <strong>{{ $otpLogs->lastItem() }}</strong> of <strong>{{ $otpLogs->total() }}</strong> logs
                                </span>
                                <div class="pagination-btns">
                                    @if ($otpLogs->onFirstPage())
                                        <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-left"></i></span>
                                    @else
                                        <a class="pag-btn" href="{{ $otpLogs->appends(['login_page' => request('login_page')])->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                                    @endif

                                    @foreach ($otpLogs->getUrlRange(max(1, $otpLogs->currentPage() - 2), min($otpLogs->lastPage(), $otpLogs->currentPage() + 2)) as $page => $url)
                                        <a class="pag-btn {{ $page === $otpLogs->currentPage() ? 'active' : '' }}"
                                            href="{{ $otpLogs->appends(['login_page' => request('login_page')])->url($page) }}">{{ $page }}</a>
                                    @endforeach

                                    @if ($otpLogs->hasMorePages())
                                        <a class="pag-btn" href="{{ $otpLogs->appends(['login_page' => request('login_page')])->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                                    @else
                                        <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-right"></i></span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchActivityTab(tabName) {
        // Update Buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            if (btn.getAttribute('data-tab') === tabName) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Update Panes
        document.getElementById('login-logs-pane').style.display = (tabName === 'login-logs') ? 'block' : 'none';
        document.getElementById('otp-logs-pane').style.display = (tabName === 'otp-logs') ? 'block' : 'none';
    }

    // Maintain tab state after pagination
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('otp_page')) {
            switchActivityTab('otp-logs');
        } else {
            switchActivityTab('login-logs');
        }
    });
</script>
@endsection
