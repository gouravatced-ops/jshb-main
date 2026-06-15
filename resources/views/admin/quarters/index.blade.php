@extends('layouts.main')

@section('title', 'Quarter Type List | JSHB')

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
            <div class="card-title">Quarter Type List</div>
            <div class="card-subtitle">Manage all quarter types from the admin panel</div>
        </div>
        <div class="card-actions">
            <form method="GET" action="{{ route('admin.quarter-types.index') }}" class="search-box" onsubmit="return false;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="quarterTypeSearchInput" name="search" value="{{ $search }}" placeholder="Search quarter types..." autocomplete="off">
            </form>
            <a class="btn-pink" href="{{ route('admin.quarter-types.create') }}">
                <i class="fa-solid fa-plus"></i> Add Quarter Type
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Quarter Name</th>
                    <th>Full Name</th>
                    <th>Income Range</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="quarterTypeTableBody">
                @forelse($quarterTypes as $quarterType)
                    <tr>
                        <td>{{ $quarterTypes->firstItem() + $loop->index }}</td>
                        <td>{{ $quarterType->quarter_code }}</td>
                        <td>
                            <div class="table-user">
                                <div>
                                    <div class="table-name">{{ $quarterType->quarter_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $quarterType->quarter_full_name ?: '-' }}</td>
                        <td>{{ $quarterType->income_range }}</td>
                        <td>{{ $quarterType->display_order }}</td>
                        <td>
                            <span class="badge-status {{ $quarterType->status == 1 ? 'active' : 'inactive' }}">
                                <i class="fa-solid fa-circle"></i>
                                {{ $quarterType->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a class="action-btn edit" href="{{ route('admin.quarter-types.edit', $quarterType) }}" title="Edit">
                                    <i class="fa-solid fa-pen text-primary"></i>
                                </a>
                                <form action="{{ route('admin.quarter-types.toggle-status', $quarterType) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('POST')
                                    <button class="action-btn toggle-status" type="submit" title="Toggle Status">
                                        @if($quarterType->status)
                                            <i class="fa-solid fa-toggle-on text-success"></i>
                                        @else
                                            <i class="fa-solid fa-toggle-off text-danger"></i>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('admin.quarter-types.destroy', $quarterType) }}" method="POST" style="display:inline;">
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
                        <td colspan="8" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                            Quarter type list not found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($quarterTypes->total() > 0)
        <div class="table-pagination" id="quarterTypePagination">
            <span>
                Showing <strong>{{ $quarterTypes->firstItem() }}</strong> to <strong>{{ $quarterTypes->lastItem() }}</strong> of <strong>{{ $quarterTypes->total() }}</strong> quarter types
            </span>
            <div class="pagination-btns">
                @if($quarterTypes->onFirstPage())
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a class="pag-btn" href="{{ $quarterTypes->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @endif

                @foreach($quarterTypes->getUrlRange(1, $quarterTypes->lastPage()) as $page => $url)
                    <a class="pag-btn {{ $page === $quarterTypes->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                @endforeach

                @if($quarterTypes->hasMorePages())
                    <a class="pag-btn" href="{{ $quarterTypes->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('quarterTypeSearchInput');
        const tableBody = document.getElementById('quarterTypeTableBody');
        const pagination = document.getElementById('quarterTypePagination');
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
                            Quarter type not found for this search.
                        </td>
                    </tr>
                `;
                return;
            }

            tableBody.innerHTML = rows.map((row, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${escapeHtml(row.quarter_code)}</td>
                    <td>
                        <div class="table-user">
                            <div>
                                <div class="table-name">${escapeHtml(row.quarter_name)}</div>
                            </div>
                        </div>
                    </td>
                    <td>${escapeHtml(row.quarter_full_name)}</td>
                    <td>${escapeHtml(row.income_range)}</td>
                    <td>${escapeHtml(row.display_order)}</td>
                    <td>
                        <span class="badge-status ${row.status == 1 ? 'active' : 'inactive'}">
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
                showSecondaryLoader('Loading quarter types...');
                window.location.href = @json(route('admin.quarter-types.index'));
                return;
            }

            showSecondaryLoader('Searching quarter types...');
            fetch(`${@json(route('admin.quarter-types.search'))}?search=${encodeURIComponent(keyword)}`, {
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
