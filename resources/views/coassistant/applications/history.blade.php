@extends('layouts.main')

@section('title', 'Application History | JSHB')

@section('content')
<div class="card">
    <div class="card-head" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <div class="card-title">Application History</div>
            <div class="card-subtitle">History of applications you have processed or interacted with</div>
        </div>
        <div>
            <button type="button" class="btn-primary" onclick="openFilterModal()" style="padding: 8px 16px; font-size: 14px; background: #0f172a; border-radius: 4px; border: none; cursor: pointer; color: white;">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if(request()->anyFilled(['application_no', 'status', 'created_date_from', 'created_date_to', 'property_number', 'sub_division_id']))
                <a href="{{ route('coassistant.applications.history') }}" class="btn-secondary" style="padding: 8px 16px; font-size: 14px; background: #e2e8f0; color: #334155; border-radius: 4px; text-decoration: none; margin-left: 10px;">Clear</a>
            @endif
        </div>
    </div>

    <!-- Filter Modal -->
    <div id="filterModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1050; align-items: flex-start; padding-top: 50px; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
        <div class="modal-content" style="background: #fff; width: 600px; max-width: 95%; border-radius: 6px; padding: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); transform: translateY(-50px); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eaeaea; padding-bottom: 12px; margin-bottom: 15px;">
                <h3 style="margin: 0; font-size: 17px; color: #333; font-weight: 600;">Filter History</h3>
                <div>
                    <button type="button" onclick="closeFilterModal()" style="background: none; border: none; font-size: 22px; color: #999; cursor: pointer; line-height: 1;">&times;</button>
                </div>
            </div>
            
            <form action="{{ route('coassistant.applications.history') }}" method="GET">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; color: #555; margin-bottom: 5px;">Sub Division</label>
                        <select name="sub_division_id" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                            <option value="">All Sub Divisions</option>
                            @foreach($subDivisions as $sd)
                                <option value="{{ $sd->id }}" {{ request('sub_division_id') == $sd->id ? 'selected' : '' }}>{{ $sd->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #555; margin-bottom: 5px;">Property Number</label>
                        <input type="text" name="property_number" value="{{ request('property_number') }}" placeholder="e.g. A-123" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #555; margin-bottom: 5px;">Application No</label>
                        <input type="text" name="application_no" value="{{ request('application_no') }}" placeholder="e.g. APP-2023..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #555; margin-bottom: 5px;">Status</label>
                        <select name="status" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="forwarded" {{ request('status') == 'forwarded' ? 'selected' : '' }}>Forwarded</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #555; margin-bottom: 5px;">Created Date (From)</label>
                        <input type="date" name="created_date_from" value="{{ request('created_date_from') }}" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #555; margin-bottom: 5px;">Created Date (To)</label>
                        <input type="date" name="created_date_to" value="{{ request('created_date_to') }}" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                    </div>
                </div>
                <div style="text-align: right; border-top: 1px solid #eaeaea; padding-top: 15px;">
                    <button type="button" onclick="closeFilterModal()" style="background: #f8f9fa; border: 1px solid #ddd; color: #333; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 14px; margin-right: 10px;">Cancel</button>
                    <button type="submit" style="background: #0f172a; border: none; color: white; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 14px;">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openFilterModal() {
            const modal = document.getElementById('filterModal');
            const modalContent = modal.querySelector('.modal-content');
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.style.opacity = '1';
                modalContent.style.transform = 'translateY(0)';
            }, 10);
        }

        function closeFilterModal() {
            const modal = document.getElementById('filterModal');
            const modalContent = modal.querySelector('.modal-content');
            modal.style.opacity = '0';
            modalContent.style.transform = 'translateY(-50px)';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    </script>

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>Application No</th>
                    <th>Type</th>
                    <th>Allottee Details</th>
                    <th>Property / Allotment</th>
                    <th>Created Date</th>
                    <th>Current Status</th>
                    <th>Priority</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td><strong>{{ $app->application_no }}</strong></td>
                    <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $app->application_type) }}</td>
                    <td>
                        <div class="table-user">
                            <div>
                                <div class="table-name">{{ trim(($app->allottee->prefix ?? '') . ' ' . ($app->allottee->allottee_name ?? '') . ' ' . ($app->allottee->allottee_middle_name ?? '') . ' ' . ($app->allottee->allottee_surname ?? '')) ?: '-' }}</div>
                                <div class="table-subtitle">{{ trim(($app->allottee->allottee_prefix_hindi ?? '') . ' ' . ($app->allottee->allottee_name_hindi ?? '') . ' ' . ($app->allottee->allottee_middle_hindi ?? '') . ' ' . ($app->allottee->allottee_surname_hindi ?? '')) ?: '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>Property: {{ $app->allottee->property_number ?? 'N/A' }}</div>
                        <small class="text-muted">Allotment: {{ $app->allottee->allotment_no ?? 'N/A' }}</small>
                    </td>
                    <td>
                        <div>{{ $app->created_date_formatted }}</div>
                    </td>
                    <td>
                        @if($app->status == 'completed' || $app->status == 'approved')
                            <span class="badge-status active"><i class="fa-solid fa-check"></i> {{ ucfirst($app->status) }}</span>
                        @elseif($app->status == 'rejected')
                            <span class="badge-status inactive"><i class="fa-solid fa-times"></i> {{ ucfirst($app->status) }}</span>
                        @else
                            <span class="badge-status" style="background:#e2e8f0; color:#333;"><i class="fa-solid fa-spinner"></i> {{ ucfirst(str_replace('_', ' ', $app->status)) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($app->priority === 'Urgent' || $app->priority === 'Overdue')
                            <span class="badge-status inactive"><i class="fa-solid fa-circle"></i> {{ $app->priority }}</span>
                        @else
                            <span class="badge-status active"><i class="fa-solid fa-circle"></i> {{ $app->priority }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('coassistant.applications.show', $app) }}" class="btn-primary" style="padding: 6px 12px; font-size: 13px; text-decoration: none; background: #475569;">View Details</a>
                    </td>
                </tr>
                @empty
                <x-no-data colspan="7" message="No application history found." description="Applications you process will appear here." icon="fa-clock-rotate-left" />
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if ($applications->total() > 0)
        <div class="table-pagination">
            <span>
                Showing <strong>{{ $applications->firstItem() }}</strong> to
                <strong>{{ $applications->lastItem() }}</strong> of <strong>{{ $applications->total() }}</strong>
                applications
            </span>
            <div class="pagination-btns">
                @if ($applications->onFirstPage())
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                            class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a class="pag-btn" href="{{ $applications->previousPageUrl() }}"><i
                            class="fa-solid fa-chevron-left"></i></a>
                @endif

                @foreach ($applications->getUrlRange(1, $applications->lastPage()) as $page => $url)
                    <a class="pag-btn {{ $page === $applications->currentPage() ? 'active' : '' }}"
                        href="{{ $url }}">{{ $page }}</a>
                @endforeach

                @if ($applications->hasMorePages())
                    <a class="pag-btn" href="{{ $applications->nextPageUrl() }}"><i
                            class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                            class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
