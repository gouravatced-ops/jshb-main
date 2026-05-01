@extends('layouts.main')

@section('title', 'Block List | JSHB')

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
            <div class="card-title">Block List</div>
            <div class="card-subtitle">Manage Jharkhand district blocks from the admin panel</div>
        </div>
        <div class="card-actions">
            <form method="GET" action="{{ route('admin.blocks.index') }}" onsubmit="return false;">
                <select id="blockDistrictFilter" name="district_id" class="form-control" style="min-width: 220px;">
                    <option value="">All Jharkhand Districts</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}" {{ $districtId === $district->id ? 'selected' : '' }}>
                            {{ $district->name_en }}
                        </option>
                    @endforeach
                </select>
            </form>
            <form method="GET" action="{{ route('admin.blocks.index') }}" class="search-box" onsubmit="return false;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="blockSearchInput" name="search" value="{{ $search }}" placeholder="Search blocks..." autocomplete="off">
            </form>
            <a class="btn-pink" href="{{ route('admin.blocks.create') }}" data-secondary-loader data-loader-message="Opening block form...">
                <i class="fa-solid fa-plus"></i> Add Block
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="ep-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>District</th>
                    <th>Block Name English</th>
                    <th>Block Name Hindi</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="blockTableBody">
                @forelse($blocks as $block)
                    <tr>
                        <td>{{ $blocks->firstItem() + $loop->index }}</td>
                        <td>{{ $block->district?->name_en ?: '-' }}</td>
                        <td>
                            <div class="table-user">
                                <div>
                                    <div class="table-name">{{ $block->block_name_eng }}</div>
                                    <div class="table-email">ID: {{ $block->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $block->block_name_hn ?: '-' }}</td>
                        <td>
                            <span class="badge-status {{ $block->status ? 'active' : 'inactive' }}">
                                <i class="fa-solid fa-circle"></i>
                                {{ $block->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a class="action-btn edit" href="{{ route('admin.blocks.edit', $block) }}" title="Edit" data-secondary-loader data-loader-message="Opening block editor...">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.blocks.destroy', $block) }}" method="POST" style="display:inline;" data-loader-message="Deleting block...">
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
                            Block list not found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($blocks->total() > 0)
        <div class="table-pagination" id="blockPagination">
            <span>
                Showing <strong>{{ $blocks->firstItem() }}</strong> to <strong>{{ $blocks->lastItem() }}</strong> of <strong>{{ $blocks->total() }}</strong> blocks
            </span>
            <div class="pagination-btns">
                @if($blocks->onFirstPage())
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a class="pag-btn" href="{{ $blocks->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @endif

                @foreach($blocks->getUrlRange(1, $blocks->lastPage()) as $page => $url)
                    <a class="pag-btn {{ $page === $blocks->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                @endforeach

                @if($blocks->hasMorePages())
                    <a class="pag-btn" href="{{ $blocks->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('blockSearchInput');
        const districtFilter = document.getElementById('blockDistrictFilter');
        const tableBody = document.getElementById('blockTableBody');
        const pagination = document.getElementById('blockPagination');
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
                            Block not found for this search.
                        </td>
                    </tr>
                `;
                return;
            }

            tableBody.innerHTML = rows.map((row, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${escapeHtml(row.district_name)}</td>
                    <td>
                        <div class="table-user">
                            <div>
                                <div class="table-name">${escapeHtml(row.block_name_eng)}</div>
                                <div class="table-email">ID: ${escapeHtml(row.id)}</div>
                            </div>
                        </div>
                    </td>
                    <td>${escapeHtml(row.block_name_hn)}</td>
                    <td>
                        <span class="badge-status ${row.status ? 'active' : 'inactive'}">
                            <i class="fa-solid fa-circle"></i>
                            ${escapeHtml(row.status_label)}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a class="action-btn edit" href="${escapeHtml(row.edit_url)}" title="Edit" data-secondary-loader data-loader-message="Opening block editor...">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="${escapeHtml(row.delete_url)}" method="POST" style="display:inline;" data-loader-message="Deleting block...">
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

            if (typeof initializeSecondaryLoaderBindings === 'function') {
                initializeSecondaryLoaderBindings();
            }
        }

        function fetchResults() {
            const keyword = searchInput.value.trim();
            const districtId = districtFilter.value;

            if (keyword === '' && districtId === '') {
                showSecondaryLoader('Loading blocks...');
                window.location.href = @json(route('admin.blocks.index'));
                return;
            }

            const params = new URLSearchParams();
            if (keyword !== '') {
                params.set('search', keyword);
            }
            if (districtId !== '') {
                params.set('district_id', districtId);
            }

            showSecondaryLoader('Searching blocks...');
            fetch(`${@json(route('admin.blocks.search'))}?${params.toString()}`, {
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

        if (districtFilter) {
            districtFilter.addEventListener('change', function () {
                clearTimeout(debounceTimer);
                fetchResults();
            });
        }
    });
</script>
@endsection
