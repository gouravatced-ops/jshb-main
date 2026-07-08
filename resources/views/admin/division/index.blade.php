@extends('layouts.main')

@section('title', 'Division List | JSHB')

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
                <div class="card-title">Division List</div>
                <div class="card-subtitle">Manage all divisions from the admin panel</div>
            </div>
            <div class="card-actions">
                <form method="GET" action="{{ route('admin.divisions.index') }}" class="search-box"
                    onsubmit="return false;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="divisionSearchInput" name="search" value="{{ $search }}"
                        placeholder="Search divisions..." autocomplete="off">
                </form>
                <a class="btn-pink" href="{{ route('admin.divisions.create') }}">
                    <i class="fa-solid fa-plus"></i> Add Division
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Division Name</th>
                        <th>Division Code</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="divisionTableBody">
                    @forelse($divisions as $division)
                        <tr>
                            <td>{{ $divisions->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="table-user">
                                    <!-- <div class="table-avatar a1">{{ strtoupper(substr($division->name, 0, 2)) }}</div> -->
                                    <div>
                                        <div class="table-name">{{ $division->name }}</div>
                                        {{-- <div class="table-email">ID: {{ $division->id }}</div> --}}
                                    </div>
                                </div>
                            </td>
                            <td>{{ $division->division_code ?: '-' }}</td>
                            <td>
                                <span class="badge-status {{ $division->status ? 'active' : 'inactive' }}">
                                    <i class="fa-solid fa-circle"></i>
                                    {{ $division->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ optional($division->created_at)->format('M d, Y') ?: '-' }}</td>
                            <td>
                                <div class="action-btns">
                                    <a class="action-btn edit" href="{{ route('admin.divisions.edit', $division) }}"
                                        title="Edit">
                                        <i class="fa-solid fa-pen text-primary"></i>
                                    </a>
                                    <form action="{{ route('admin.divisions.toggle-status', $division) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('POST')
                                        <button class="action-btn toggle-status" type="submit" title="Toggle Status">
                                            @if($division->status)
                                                <i class="fa-solid fa-toggle-on text-success"></i>
                                            @else
                                                <i class="fa-solid fa-toggle-off text-danger"></i>
                                            @endif
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.divisions.destroy', $division) }}" method="POST"
                                        style="display:inline;">
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
                            <td colspan="6" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                                Division list not found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($divisions->total() > 0)
            <div class="table-pagination" id="divisionPagination">
                <span>
                    Showing <strong>{{ $divisions->firstItem() }}</strong> to
                    <strong>{{ $divisions->lastItem() }}</strong> of <strong>{{ $divisions->total() }}</strong> divisions
                </span>
                <div class="pagination-btns">
                    @if ($divisions->onFirstPage())
                        <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                                class="fa-solid fa-chevron-left"></i></span>
                    @else
                        <a class="pag-btn" href="{{ $divisions->previousPageUrl() }}"><i
                                class="fa-solid fa-chevron-left"></i></a>
                    @endif

                    @foreach ($divisions->getUrlRange(1, $divisions->lastPage()) as $page => $url)
                        <a class="pag-btn {{ $page === $divisions->currentPage() ? 'active' : '' }}"
                            href="{{ $url }}">{{ $page }}</a>
                    @endforeach

                    @if ($divisions->hasMorePages())
                        <a class="pag-btn" href="{{ $divisions->nextPageUrl() }}"><i
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
            const searchInput = document.getElementById('divisionSearchInput');
            const tableBody = document.getElementById('divisionTableBody');
            const pagination = document.getElementById('divisionPagination');
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
                        <td colspan="6" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                            Division not found for this search.
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
                                <div class="table-email">ID: ${escapeHtml(row.id)}</div>
                            </div>
                        </div>
                    </td>
                    <td>${escapeHtml(row.division_code)}</td>
                    <td>
                        <span class="badge-status ${row.status ? 'active' : 'inactive'}">
                            <i class="fa-solid fa-circle"></i>
                            ${escapeHtml(row.status_label)}
                        </span>
                    </td>
                    <td>${escapeHtml(row.created_at)}</td>
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
                const url = keyword === '' ?
                    @json(route('admin.divisions.index')) :
                    `${@json(route('admin.divisions.search'))}?search=${encodeURIComponent(keyword)}`;

                if (keyword === '') {
                    showSecondaryLoader('Loading divisions...');
                    window.location.href = @json(route('admin.divisions.index'));
                    return;
                }

                showSecondaryLoader('Searching divisions...');
                fetch(url, {
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
