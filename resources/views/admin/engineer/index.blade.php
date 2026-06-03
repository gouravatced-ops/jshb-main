@extends('layouts.main')

@section('title', 'Engineer List | JSHB')

@section('content')
    <div class="card">
        @if (session('success'))
            <div class="alert alert-success" style="margin: 20px 20px 0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="card-head">
            <div>
                <div class="card-title">Engineer List</div>
                <div class="card-subtitle">Manage engineer logins and employee details from the admin panel</div>
            </div>
            <div class="card-actions">
                <form method="GET" action="{{ route('admin.engineers.index') }}" class="search-box"
                    onsubmit="return false;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="engineerSearchInput" name="search" value="{{ $search }}"
                        placeholder="Search engineers..." autocomplete="off">
                </form>
                <a class="btn-pink" href="{{ route('admin.engineers.create') }}">
                    <i class="fa-solid fa-plus"></i> Add Engineer
                </a>
            </div>
        </div>
        @php
            #return debug($engineers);
        @endphp
        <div class="table-responsive">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Engineer</th>
                        <th>Govt ID</th>
                        <th>Current Organization</th>
                        <th>Department</th>
                        <th>Post Type</th>
                        <th>District / Block</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="engineerTableBody">
                    @forelse($engineers as $engineer)
                        <tr>
                            <td>{{ $engineers->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="table-user">
                                    <div>
                                        <div class="table-name">{{ $engineer->employee_name }}</div>
                                        <div class="table-email">{{ $engineer->user?->email ?: '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $engineer->masked_state_government_engineer_id }}</td>
                            <td>{{ $engineer->currentOrganization?->name ?: '-' }}</td>
                            <td>{{ $engineer->department?->name ?: '-' }}</td>
                            <td>{{ $engineer->postType?->name ?: '-' }}</td>
                            <td>{{ ($engineer->district?->name_en ?: '-') . ' / ' . ($engineer->block?->block_name_eng ?: '-') }}
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a class="action-btn edit"
                                        href="{{ route('admin.engineers.edit', $engineer->encrypted_route_key) }}"
                                        title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                                Engineer list not found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($engineers->total() > 0)
            <div class="table-pagination" id="engineerPagination">
                <span>
                    Showing <strong>{{ $engineers->firstItem() }}</strong> to
                    <strong>{{ $engineers->lastItem() }}</strong> of <strong>{{ $engineers->total() }}</strong> engineers
                </span>
                <div class="pagination-btns">
                    @if ($engineers->onFirstPage())
                        <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                                class="fa-solid fa-chevron-left"></i></span>
                    @else
                        <a class="pag-btn" href="{{ $engineers->previousPageUrl() }}"><i
                                class="fa-solid fa-chevron-left"></i></a>
                    @endif

                    @foreach ($engineers->getUrlRange(1, $engineers->lastPage()) as $page => $url)
                        <a class="pag-btn {{ $page === $engineers->currentPage() ? 'active' : '' }}"
                            href="{{ $url }}">{{ $page }}</a>
                    @endforeach

                    @if ($engineers->hasMorePages())
                        <a class="pag-btn" href="{{ $engineers->nextPageUrl() }}"><i
                                class="fa-solid fa-chevron-right"></i></a>
                    @else
                        <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                                class="fa-solid fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('engineerSearchInput');
            const tableBody = document.getElementById('engineerTableBody');
            const pagination = document.getElementById('engineerPagination');
            let debounceTimer;

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function renderRows(rows) {
                if (!rows.length) {
                    tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                            Engineer not found for this search.
                        </td>
                    </tr>
                `;
                    return;
                }

                tableBody.innerHTML = rows.map((row, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <div class="table-user">
                            <div>
                                <div class="table-name">${escapeHtml(row.employee_name)}</div>
                                <div class="table-email">${escapeHtml(row.email)}</div>
                            </div>
                        </div>
                    </td>
                    <td>${escapeHtml(row.state_government_engineer_id)}</td>
                    <td>${escapeHtml(row.current_organization)}</td>
                    <td>${escapeHtml(row.department)}</td>
                    <td>${escapeHtml(row.post_type)}</td>
                    <td>${escapeHtml(row.district)} / ${escapeHtml(row.block)}</td>
                    <td>
                        <div class="action-btns">
                            <a class="action-btn edit" href="${escapeHtml(row.edit_url)}" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            `).join('');
            }

            function fetchResults() {
                const keyword = searchInput.value.trim();

                if (keyword === '') {
                    showSecondaryLoader('Loading engineers...');
                    window.location.href = @json(route('admin.engineers.index'));
                    return;
                }

                showSecondaryLoader('Searching engineers...');
                fetch(`${@json(route('admin.engineers.search'))}?search=${encodeURIComponent(keyword)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (pagination) {
                            pagination.style.display = 'none';
                        }
                        renderRows(result.data || []);
                    })
                    .catch(() => {
                        renderRows([]);
                    })
                    .finally(() => {
                        hideSecondaryLoader();
                    });
            }

            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(fetchResults, 250);
                });
            }
        });
    </script>
@endsection
