@extends('layouts.main')

@section('title', 'Sub Division List | JSHB')

@section('content')
<div class="card">
    @if(session('success'))
        <div class="alert alert-success" style="margin: 20px 20px 0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="card-head">
        <div>
            <div class="card-title">Sub Division List</div>
            <div class="card-subtitle">Manage all sub divisions from the admin panel</div>
        </div>
        <div class="card-actions">
            <form method="GET" action="{{ route('admin.sub-divisions.index') }}" class="search-box" onsubmit="return false;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="subDivisionSearchInput" name="search" value="{{ $search }}" placeholder="Search sub divisions..." autocomplete="off">
            </form>
            <a class="btn-pink" href="{{ route('admin.sub-divisions.create') }}">
                <i class="fa-solid fa-plus"></i> Add Sub Division
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sub Division</th>
                    <th>Division</th>
                    <th>Code</th>
                    <th>Colony</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="subDivisionTableBody">
                @forelse($subDivisions as $subDivision)
                    <tr>
                        <td>{{ $subDivisions->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="table-user">
                                <!-- <div class="table-avatar a2">{{ strtoupper(substr($subDivision->name, 0, 2)) }}</div> -->
                                <div>
                                    <div class="table-name">{{ $subDivision->name }}</div>
                                    <div class="table-email">{{ $subDivision->locality_address ?: 'No address added' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $subDivision->division?->name ?: '-' }}</td>
                        <td>{{ $subDivision->subdivision_code ?: '-' }}</td>
                        <td>{{ $subDivision->colony_name ?: '-' }}</td>
                        <td>
                            <span class="badge-status {{ $subDivision->status ? 'active' : 'inactive' }}">
                                <i class="fa-solid fa-circle"></i>
                                {{ $subDivision->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a class="action-btn edit" href="{{ route('admin.sub-divisions.edit', $subDivision) }}" title="Edit">
                                    <i class="fa-solid fa-pen text-primary"></i>
                                </a>
                                <form action="{{ route('admin.sub-divisions.toggle-status', $subDivision) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('POST')
                                    <button class="action-btn toggle-status" type="submit" title="Toggle Status">
                                        @if($subDivision->status)
                                            <i class="fa-solid fa-toggle-on text-success"></i>
                                        @else
                                            <i class="fa-solid fa-toggle-off text-danger"></i>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('admin.sub-divisions.destroy', $subDivision) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn del" type="submit" title="Delete">
                                        <i class="fa-solid fa-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                            Sub Division list not found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($subDivisions->total() > 0)
        <div class="table-pagination" id="subDivisionPagination">
            <span>
                Showing <strong>{{ $subDivisions->firstItem() }}</strong> to <strong>{{ $subDivisions->lastItem() }}</strong> of <strong>{{ $subDivisions->total() }}</strong> sub divisions
            </span>
            <div class="pagination-btns">
                @if($subDivisions->onFirstPage())
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a class="pag-btn" href="{{ $subDivisions->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @endif

                @foreach($subDivisions->getUrlRange(1, $subDivisions->lastPage()) as $page => $url)
                    <a class="pag-btn {{ $page === $subDivisions->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                @endforeach

                @if($subDivisions->hasMorePages())
                    <a class="pag-btn" href="{{ $subDivisions->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('subDivisionSearchInput');
        const tableBody = document.getElementById('subDivisionTableBody');
        const pagination = document.getElementById('subDivisionPagination');
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
                        <td colspan="7" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                            Sub Division not found for this search.
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
                                <div class="table-name">${escapeHtml(row.name)}</div>
                                <div class="table-email">${escapeHtml(row.locality_address)}</div>
                            </div>
                        </div>
                    </td>
                    <td>${escapeHtml(row.division_name)}</td>
                    <td>${escapeHtml(row.subdivision_code)}</td>
                    <td>${escapeHtml(row.colony_name)}</td>
                    <td>
                        <span class="badge-status ${row.status ? 'active' : 'inactive'}">
                            <i class="fa-solid fa-circle"></i>
                            ${escapeHtml(row.status_label)}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a class="action-btn edit" href="${escapeHtml(row.edit_url)}" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="${escapeHtml(row.delete_url)}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="action-btn del" type="submit" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function fetchResults() {
            const keyword = searchInput.value.trim();

            if (keyword === '') {
                showSecondaryLoader('Loading sub divisions...');
                window.location.href = @json(route('admin.sub-divisions.index'));
                return;
            }

            showSecondaryLoader('Searching sub divisions...');
            fetch(`${@json(route('admin.sub-divisions.search'))}?search=${encodeURIComponent(keyword)}`, {
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
            searchInput.addEventListener('keyup', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(fetchResults, 250);
            });
        }
    });
</script>
@endsection
