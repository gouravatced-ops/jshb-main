@extends('layouts.main')

@section('title', 'Property Type List | JSHB')

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
                <div class="card-title">Property Type List</div>
                <div class="card-subtitle">Manage all property types from the admin panel</div>
            </div>
            <div class="card-actions">
                <form method="GET" action="{{ route('admin.property-types.index') }}" class="search-box"
                    onsubmit="return false;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="propertyTypeSearchInput" name="search" value="{{ $search }}"
                        placeholder="Search property types..." autocomplete="off">
                </form>
                <a class="btn-pink" href="{{ route('admin.property-types.create') }}">
                    <i class="fa-solid fa-plus"></i> Add Property Type
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type Name</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="propertyTypeTableBody">
                    @forelse($propertyTypes as $propertyType)
                        <tr>
                            <td>{{ $propertyTypes->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="table-user">
                                    <div>
                                        <div class="table-name">{{ $propertyType->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $propertyType->propertyCategory?->name ?: '-' }}</td>
                            <td>
                                <span class="badge-status {{ $propertyType->status ? 'active' : 'inactive' }}">
                                    <i class="fa-solid fa-circle"></i>
                                    {{ $propertyType->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a class="action-btn edit"
                                        href="{{ route('admin.property-types.edit', $propertyType) }}" title="Edit">
                                        <i class="fa-solid fa-pen text-primary"></i>
                                    </a>
                                    <form action="{{ route('admin.property-types.toggle-status', $propertyType) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('POST')
                                    <button class="action-btn toggle-status" type="submit" title="Toggle Status">
                                        @if($propertyType->status)
                                            <i class="fa-solid fa-toggle-on text-success"></i>
                                        @else
                                            <i class="fa-solid fa-toggle-off text-danger"></i>
                                        @endif
                                    </button>
                                </form>
                                    <form action="{{ route('admin.property-types.destroy', $propertyType) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-btn del" type="submit" title="Toggle Status">
                                            <i class="fa-solid fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                                Property type list not found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($propertyTypes->total() > 0)
            <div class="table-pagination" id="propertyTypePagination">
                <span>
                    Showing <strong>{{ $propertyTypes->firstItem() }}</strong> to
                    <strong>{{ $propertyTypes->lastItem() }}</strong> of <strong>{{ $propertyTypes->total() }}</strong>
                    property types
                </span>
                <div class="pagination-btns">
                    @if ($propertyTypes->onFirstPage())
                        <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                                class="fa-solid fa-chevron-left"></i></span>
                    @else
                        <a class="pag-btn" href="{{ $propertyTypes->previousPageUrl() }}"><i
                                class="fa-solid fa-chevron-left"></i></a>
                    @endif

                    @foreach ($propertyTypes->getUrlRange(1, $propertyTypes->lastPage()) as $page => $url)
                        <a class="pag-btn {{ $page === $propertyTypes->currentPage() ? 'active' : '' }}"
                            href="{{ $url }}">{{ $page }}</a>
                    @endforeach

                    @if ($propertyTypes->hasMorePages())
                        <a class="pag-btn" href="{{ $propertyTypes->nextPageUrl() }}"><i
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
            const searchInput = document.getElementById('propertyTypeSearchInput');
            const tableBody = document.getElementById('propertyTypeTableBody');
            const pagination = document.getElementById('propertyTypePagination');
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
                            Property type not found for this search.
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
                            </div>
                        </div>
                    </td>
                    <td>${escapeHtml(row.category_name)}</td>
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
                                <button class="action-btn del" type="submit" title="Toggle Status">
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
                    showSecondaryLoader('Loading property types...');
                    window.location.href = @json(route('admin.property-types.index'));
                    return;
                }

                showSecondaryLoader('Searching property types...');
                fetch(`${@json(route('admin.property-types.search'))}?search=${encodeURIComponent(keyword)}`, {
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
