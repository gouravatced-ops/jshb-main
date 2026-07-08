@extends('layouts.main')

@section('title', 'Allottee List | JSHB')

@section('content')
    <div class="card">
        <div class="card-head">
            <div>
                <div class="card-title">Allottee List</div>
                <div class="card-subtitle">Search, filter, and manage all allottees</div>
            </div>
            <div class="card-actions">
                <button class="btn-primary" type="button" id="toggleFilterBtn">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                <a class="btn-pink" href="{{ route('admin.apply.index') }}">
                    <i class="fa-solid fa-plus"></i> Add Allottee
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" style="margin: 20px 20px 0;">
                {{ session('success') }}
            </div>
        @endif

        <style>
            #allotteeFilterForm {
                display: none;
                padding: 10px 14px;
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
            }

            #allotteeFilterForm .form-control,
            #allotteeFilterForm select {
                height: 36px;
                border: 1px solid #dcdfe4;
                border-radius: 6px;
                font-size: 13px;
                padding: 4px 10px;
                box-shadow: none;
                transition: 0.2s;
            }

            #allotteeFilterForm .form-control:focus,
            #allotteeFilterForm select:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 2px rgba(13, 110, 253, .08);
            }

            #allotteeFilterForm .btn {
                height: 36px;
                padding: 0 14px;
                font-size: 13px;
                border-radius: 6px;
                font-weight: 500;
            }

            #allotteeFilterForm .row {
                row-gap: 8px;
            }
        </style>

        <form method="GET" action="{{ route('admin.allottees.index') }}" id="allotteeFilterForm">

            <div class="row g-2">

                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                        placeholder="Search by app no / name">
                </div>

                <div class="col-md-3">
                    <select class="form-control" name="division_id">
                        <option value="">All Divisions</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}"
                                {{ (string) request('division_id') === (string) $division->id ? 'selected' : '' }}>
                                {{ $division->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-control" name="subdivision_id">
                        <option value="">All Sub Divisions</option>
                        @foreach ($subDivisions as $subDivision)
                            <option value="{{ $subDivision->id }}"
                                {{ (string) request('subdivision_id') === (string) $subDivision->id ? 'selected' : '' }}>
                                {{ $subDivision->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-control" name="pcategory_id">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ (string) request('pcategory_id') === (string) $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="text" class="form-control" name="property_number"
                        value="{{ request('property_number') }}" placeholder="Property number">
                </div>

                <div class="col-md-3">
                    <input type="text" class="form-control" name="flat" value="{{ request('flat') }}"
                        placeholder="Flat / allotment no">
                </div>

                <div class="col-md-6 d-flex gap-2">
                    <button class="btn btn-success" type="submit">
                        Search
                    </button>

                    <a class="btn btn-secondary" href="{{ route('admin.allottees.index') }}">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        <div class="table-responsive">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Allottee Name</th>
                        <th>Division / Sub Division</th>
                        <th>Category</th>
                        <th>Property No</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allottees as $allottee)
                        <tr>
                            <td>{{ $allottees->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="table-name">
                                    {{ trim(($allottee->allottee_name ?? '') . ' ' . ($allottee->allottee_middle_name ?? '') . ' ' . ($allottee->allottee_surname ?? '')) ?: '-' }}
                                </div>
                                <div class="table-email">App No: {{ $allottee->application_no ?: '-' }}</div>
                            </td>
                            <td>
                                <div class="table-name">{{ $allottee->division->name ?? '-' }}</div>
                                <div class="table-email">{{ $allottee->subDivision->name ?? '-' }}</div>
                            </td>
                            <td>{{ $allottee->propertyCategory->name ?? '-' }}</td>
                            <td>{{ $allottee->property_number ?: '-' }}</td>
                            <td>
                                <span class="badge-status {{ $allottee->payment_amount ? 'active' : 'inactive' }}">
                                    {{ $allottee->payment_amount ? 'Paid: ' . number_format((float) $allottee->payment_amount, 2) : 'Pending' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">

                                    <a class="action-btn view" href="{{ route('admin.allottees.show', $allottee) }}"
                                        target="_blank" title="View Allottee">
                                        <i class="fa-solid fa-file-lines"></i>
                                    </a>

                                    <a class="action-btn edit" href="{{ route('admin.edit.apply.index', $allottee) }}"
                                        title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <a class="action-btn delete"
                                        href="{{ route('admin.allottee.delete.components', $allottee) }}"
                                        title="Reset Allottee Components">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </a>

                                    @if ($allottee->payment_option == 'emi')
                                        <a class="action-btn delete"
                                            href="{{ route('admin.allottee.delete.emi.setup', $allottee) }}"
                                            title="Reset EMI">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                    @endif

                                    {{-- <a class="action-btn edit"
                                        href="{{ route('admin.allottees.letters.allotment', $allottee) }}"
                                        target="_blank"
                                        title="Generate Allotment Letter">
                                        <i class="fa-solid fa-file-signature"></i>
                                    </a> --}}

                                    <!-- <a class="action-btn success"
                                                                                    href="{{ route('admin.allottees.letters.possession', $allottee) }}"
                                                                                    target="_blank"
                                                                                    title="Generate Possession Letter">
                                                                                    <i class="fa-solid fa-file-circle-check"></i>
                                                                                </a> -->

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                style="text-align:center;padding:32px 20px;color:var(--text-dark);font-weight:600;">
                                No allottees found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($allottees->total() > 0)
            <div class="table-pagination">
                <span>
                    Showing <strong>{{ $allottees->firstItem() }}</strong> to
                    <strong>{{ $allottees->lastItem() }}</strong> of <strong>{{ $allottees->total() }}</strong> allottees
                </span>
                <div class="pagination-btns">
                    @if ($allottees->onFirstPage())
                        <span class="pag-btn" style="pointer-events:none;opacity:.5;"><i
                                class="fa-solid fa-chevron-left"></i></span>
                    @else
                        <a class="pag-btn" href="{{ $allottees->previousPageUrl() }}"><i
                                class="fa-solid fa-chevron-left"></i></a>
                    @endif

                    @foreach ($allottees->getUrlRange(1, $allottees->lastPage()) as $page => $url)
                        <a class="pag-btn {{ $page === $allottees->currentPage() ? 'active' : '' }}"
                            href="{{ $url }}">{{ $page }}</a>
                    @endforeach

                    @if ($allottees->hasMorePages())
                        <a class="pag-btn" href="{{ $allottees->nextPageUrl() }}"><i
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
            const btn = document.getElementById('toggleFilterBtn');
            const form = document.getElementById('allotteeFilterForm');
            if (!btn || !form) return;
            const hasFilter = new URLSearchParams(window.location.search).toString().length > 0;
            if (hasFilter) form.style.display = 'none';
            btn.addEventListener('click', function() {
                form.style.display = form.style.display === 'block' ? 'none' : 'block';
            });
        });
    </script>
@endsection
