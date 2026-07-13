@extends('layouts.main')

@section('title', 'Workflow List | JSHB')

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
                <div class="card-title">Workflow List</div>
                <div class="card-subtitle">Manage all workflows from the admin panel</div>
            </div>
            <div class="card-actions">
                <form method="GET" action="{{ route('admin.workflows.index') }}" class="search-box"
                    onsubmit="return false;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="workflowSearchInput" name="search" value="{{ $search }}"
                        placeholder="Search workflows..." autocomplete="off">
                </form>
                <a class="btn-pink" href="{{ route('admin.workflows.create') }}">
                    <i class="fa-solid fa-plus"></i> Add Workflow
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Workflow Name</th>
                        <th>App Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="workflowTableBody">
                    @forelse($workflows as $workflow)
                        <tr>
                            <td>{{ $workflows->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="table-user">
                                    <div>
                                        <div class="table-name">{{ $workflow->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $workflow->application_type }}</td>
                            <td>
                                <span class="badge-status {{ $workflow->is_active ? 'active' : 'inactive' }}">
                                    <i class="fa-solid fa-circle"></i>
                                    {{ $workflow->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a class="action-btn edit"
                                        href="{{ route('admin.workflows.edit', $workflow) }}" title="Edit">
                                        <i class="fa-solid fa-pen text-primary"></i>
                                    </a>
                                    <form action="{{ route('admin.workflows.toggle-status', $workflow) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('POST')
                                    <button class="action-btn toggle-status" type="submit" title="Toggle Status">
                                        @if($workflow->is_active)
                                            <i class="fa-solid fa-toggle-on text-success"></i>
                                        @else
                                            <i class="fa-solid fa-toggle-off text-danger"></i>
                                        @endif
                                    </button>
                                </form>
                                    <form action="{{ route('admin.workflows.destroy', $workflow) }}"
                                        method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this workflow?');">
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
                            <td colspan="5" style="text-align:center;padding:32px 20px;color:var(--text-light);">
                                Workflow list not found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($workflows->total() > 0)
            <div class="table-pagination" id="workflowPagination">
                <span>
                    Showing <strong>{{ $workflows->firstItem() }}</strong> to
                    <strong>{{ $workflows->lastItem() }}</strong> of <strong>{{ $workflows->total() }}</strong>
                    workflows
                </span>
                <div class="pagination-btns">
                    @if ($workflows->onFirstPage())
                        <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                                class="fa-solid fa-chevron-left"></i></span>
                    @else
                        <a class="pag-btn" href="{{ $workflows->previousPageUrl() }}"><i
                                class="fa-solid fa-chevron-left"></i></a>
                    @endif

                    @foreach ($workflows->getUrlRange(1, $workflows->lastPage()) as $page => $url)
                        <a class="pag-btn {{ $page === $workflows->currentPage() ? 'active' : '' }}"
                            href="{{ $url }}">{{ $page }}</a>
                    @endforeach

                    @if ($workflows->hasMorePages())
                        <a class="pag-btn" href="{{ $workflows->nextPageUrl() }}"><i
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
            const searchInput = document.getElementById('workflowSearchInput');
            const tableBody = document.getElementById('workflowTableBody');
            const pagination = document.getElementById('workflowPagination');
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
                            Workflow not found for this search.
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
                    <td>${escapeHtml(row.application_type)}</td>
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
                            <form action="${escapeHtml(row.toggle_status_url)}" method="POST" style="display:inline;">
                                @csrf
                                @method('POST')
                                <button class="action-btn toggle-status" type="submit" title="Toggle Status">
                                    ${row.status ? '<i class="fa-solid fa-toggle-on text-success"></i>' : '<i class="fa-solid fa-toggle-off text-danger"></i>'}
                                </button>
                            </form>
                            <form action="${escapeHtml(row.delete_url)}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this workflow?');">
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
                    showSecondaryLoader('Loading workflows...');
                    window.location.href = @json(route('admin.workflows.index'));
                    return;
                }

                showSecondaryLoader('Searching workflows...');
                fetch(`${@json(route('admin.workflows.search'))}?search=${encodeURIComponent(keyword)}`, {
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
