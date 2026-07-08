@extends('layouts.main')

@section('title', 'Sub Organization List | JSHB')

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
                <div class="card-title">Sub Organization List</div>
                <div class="card-subtitle">Manage sub organizations and their parent organization links.</div>
            </div>
            <div class="card-actions">
                <form method="GET" action="{{ $indexRoute }}" class="search-box" onsubmit="return false;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="organizationSearchInput" name="search" value="{{ $search }}"
                        placeholder="Search organizations..." autocomplete="off">
                </form>
                <a class="btn-pink" href="{{ route('admin.organizations.create') }}">
                    <i class="fa-solid fa-plus"></i> Add Sub Organization
                </a>
            </div>
        </div>
        @php
            #return debug($organizations);
        @endphp
        <div class="table-responsive">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sub Organization</th>
                        <th>Parent Organization</th>
                        <th>District Posting</th>
                        <th>State</th>
                        <th>District</th>
                        <th>PIN Code</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="organizationTableBody">
                    @forelse($organizations as $organization)
                        <tr>
                            <td>{{ $organizations->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="table-user">
                                    <div class="table-avatar a3">{{ strtoupper(substr($organization->name, 0, 2)) }}</div>
                                    <div>
                                        <div class="table-name">{{ $organization->name }}</div>
                                        <div class="table-email">
                                            {{ $organization->locality ?: ($organization->post_office ?: 'No locality added') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $organization->parentOrganization->display_code ?? $organization->parentOrganization->name ?: '-' }}
                            </td>
                            <td>
                                <span
                                    class="badge-status {{ $organization->district_wise_posting ? 'active' : 'inactive' }}">
                                    <i class="fa-solid fa-circle"></i>
                                    {{ $organization->district_wise_posting ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>{{ $organization->state ?: 'Jharkhand' }}</td>
                            <td>{{ getdistrictNameById($organization->district) ?: 'N/A' }}</td>
                            <td>{{ $organization->pin_code ?: '-' }}</td>
                            <td>
                                <span class="badge-status {{ $organization->status ? 'active' : 'inactive' }}">
                                    <i class="fa-solid fa-circle"></i>
                                    {{ $organization->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a class="action-btn edit"
                                        href="{{ route('admin.organizations.edit', $organization) }}" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.organizations.destroy', $organization) }}" method="POST"
                                        style="display:inline;">
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
                            <td colspan="8" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                                Organization list not found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($organizations->total() > 0)
            <div class="table-pagination" id="organizationPagination">
                <span>
                    Showing <strong>{{ $organizations->firstItem() }}</strong> to
                    <strong>{{ $organizations->lastItem() }}</strong> of <strong>{{ $organizations->total() }}</strong>
                    organizations
                </span>
                <div class="pagination-btns">
                    @if ($organizations->onFirstPage())
                        <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                                class="fa-solid fa-chevron-left"></i></span>
                    @else
                        <a class="pag-btn" href="{{ $organizations->previousPageUrl() }}"><i
                                class="fa-solid fa-chevron-left"></i></a>
                    @endif

                    @foreach ($organizations->getUrlRange(1, $organizations->lastPage()) as $page => $url)
                        <a class="pag-btn {{ $page === $organizations->currentPage() ? 'active' : '' }}"
                            href="{{ $url }}">{{ $page }}</a>
                    @endforeach

                    @if ($organizations->hasMorePages())
                        <a class="pag-btn" href="{{ $organizations->nextPageUrl() }}"><i
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
            const searchInput = document.getElementById('organizationSearchInput');
            const tableBody = document.getElementById('organizationTableBody');
            const pagination = document.getElementById('organizationPagination');
            const listRoute = "{{ $indexRoute }}";
            const searchRoute = "{{ $searchRoute }}";
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
                        <td colspan="9" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                            Organization not found for this search.
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
                            <div class="table-avatar a3">${escapeHtml((row.name || '').substring(0, 2).toUpperCase())}</div>
                            <div>
                                <div class="table-name">${escapeHtml(row.name)}</div>
                                <div class="table-email">${escapeHtml(row.locality)}</div>
                            </div>
                        </div>
                    </td>
                    <td>${escapeHtml(row.parent_name || '-')}</td>
                    <td>
                        <span class="badge-status ${row.district_wise_posting === 'Yes' ? 'active' : 'inactive'}">
                            <i class="fa-solid fa-circle"></i>
                            ${escapeHtml(row.district_wise_posting)}
                        </span>
                    </td>
                    <td>${escapeHtml(row.state)}</td>
                    <td>${escapeHtml(row.district)}</td>
                    <td>${escapeHtml(row.pin_code)}</td>
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
                    showSecondaryLoader('Loading organizations...');
                    window.location.href = listRoute;
                    return;
                }

                showSecondaryLoader('Searching organizations...');
                fetch(`${searchRoute}?search=${encodeURIComponent(keyword)}`, {
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
