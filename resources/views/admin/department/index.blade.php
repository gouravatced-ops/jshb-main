@extends('layouts.main')

@section('title', 'Department List | JSHB')

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
            <div class="card-title">Department List</div>
            <div class="card-subtitle">Manage all departments from the admin panel</div>
        </div>
        <div class="card-actions">
            <form method="GET" action="{{ route('admin.departments.index') }}" class="search-box" onsubmit="return false;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="departmentSearchInput" name="search" value="{{ $search }}" placeholder="Search departments..." autocomplete="off">
            </form>
            <a class="btn-pink" href="{{ route('admin.departments.create') }}">
                <i class="fa-solid fa-plus"></i> Add Department
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Department Name</th>
                    <th>Department Code</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="departmentTableBody">
                @forelse($departments as $department)
                    <tr>
                        <td>{{ $departments->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="table-user">
                                <div>
                                    <div class="table-name">{{ $department->name }}</div>
                                    <div class="table-email">ID: {{ $department->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $department->department_code ?: '-' }}</td>
                        <td>
                            <span class="badge-status {{ $department->status ? 'active' : 'inactive' }}">
                                <i class="fa-solid fa-circle"></i>
                                {{ $department->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ optional($department->created_at)->format('M d, Y') ?: '-' }}</td>
                        <td>
                            <div class="action-btns">
                                <a class="action-btn edit" href="{{ route('admin.departments.edit', $department) }}" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn del" type="submit" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                            Department list not found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($departments->total() > 0)
        <div class="table-pagination" id="departmentPagination">
            <span>
                Showing <strong>{{ $departments->firstItem() }}</strong> to <strong>{{ $departments->lastItem() }}</strong> of <strong>{{ $departments->total() }}</strong> departments
            </span>
            <div class="pagination-btns">
                @if($departments->onFirstPage())
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a class="pag-btn" href="{{ $departments->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @endif

                @foreach($departments->getUrlRange(1, $departments->lastPage()) as $page => $url)
                    <a class="pag-btn {{ $page === $departments->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                @endforeach

                @if($departments->hasMorePages())
                    <a class="pag-btn" href="{{ $departments->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('departmentSearchInput');
        const tableBody = document.getElementById('departmentTableBody');
        const pagination = document.getElementById('departmentPagination');
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
                            Department not found for this search.
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
                    <td>${escapeHtml(row.department_code)}</td>
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

            if (keyword === '') {
                showSecondaryLoader('Loading departments...');
                window.location.href = @json(route('admin.departments.index'));
                return;
            }

            showSecondaryLoader('Searching departments...');
            fetch(`${@json(route('admin.departments.search'))}?search=${encodeURIComponent(keyword)}`, {
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
