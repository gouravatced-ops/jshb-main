@extends('layouts.main')

@section('title', 'Post Type List | JSHB')

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
            <div class="card-title">Post Type List</div>
            <div class="card-subtitle">Manage engineer post types and levels from the admin panel</div>
        </div>
        <div class="card-actions">
            <form method="GET" action="{{ route('admin.post-types.index') }}" class="search-box" onsubmit="return false;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="postTypeSearchInput" name="search" value="{{ $search }}" placeholder="Search post types..." autocomplete="off">
            </form>
            <a class="btn-pink" href="{{ route('admin.post-types.create') }}">
                <i class="fa-solid fa-plus"></i> Add Post Type
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Post Type</th>
                    <th>Engineer Post Level</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="postTypeTableBody">
                @forelse($postTypes as $postType)
                    <tr>
                        <td>{{ $postTypes->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="table-user">
                                <div>
                                    <div class="table-name">{{ $postType->name }}</div>
                                    <div class="table-email">ID: {{ $postType->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $postType->level }}</td>
                        <td>
                            <span class="badge-status {{ $postType->status ? 'active' : 'inactive' }}">
                                <i class="fa-solid fa-circle"></i>
                                {{ $postType->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a class="action-btn edit" href="{{ route('admin.post-types.edit', $postType) }}" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.post-types.destroy', $postType) }}" method="POST" style="display:inline;">
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
                        <td colspan="5" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                            Post Type list not found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($postTypes->total() > 0)
        <div class="table-pagination" id="postTypePagination">
            <span>
                Showing <strong>{{ $postTypes->firstItem() }}</strong> to <strong>{{ $postTypes->lastItem() }}</strong> of <strong>{{ $postTypes->total() }}</strong> post types
            </span>
            <div class="pagination-btns">
                @if($postTypes->onFirstPage())
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a class="pag-btn" href="{{ $postTypes->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @endif

                @foreach($postTypes->getUrlRange(1, $postTypes->lastPage()) as $page => $url)
                    <a class="pag-btn {{ $page === $postTypes->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                @endforeach

                @if($postTypes->hasMorePages())
                    <a class="pag-btn" href="{{ $postTypes->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('postTypeSearchInput');
        const tableBody = document.getElementById('postTypeTableBody');
        const pagination = document.getElementById('postTypePagination');
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
                        <td colspan="5" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                            Post Type not found for this search.
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
                    <td>${escapeHtml(row.level)}</td>
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
                showSecondaryLoader('Loading post types...');
                window.location.href = @json(route('admin.post-types.index'));
                return;
            }

            showSecondaryLoader('Searching post types...');
            fetch(`${@json(route('admin.post-types.search'))}?search=${encodeURIComponent(keyword)}`, {
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
