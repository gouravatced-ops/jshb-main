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

                                    <button type="button" class="action-btn delete" style="border:none;" 
                                        onclick="openApplicationModal({{ $allottee->id }})" 
                                        title="Manage Applications">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>

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
    <!-- Application Management Modal -->
    <div id="applicationModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1050; align-items: flex-start; padding-top: 50px; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
        <div class="modal-content" style="background: #fff; width: 700px; max-width: 95%; border-radius: 6px; padding: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); transform: translateY(-50px); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eaeaea; padding-bottom: 12px; margin-bottom: 15px;">
                <h3 style="margin: 0; font-size: 17px; color: #333; font-weight: 600;">Manage Applications Flow</h3>
                <div>
                    <button type="button" onclick="closeApplicationModal()" style="background: none; border: none; font-size: 22px; color: #999; cursor: pointer; line-height: 1;">&times;</button>
                </div>
            </div>
            <div id="applicationModalAlert" style="display: none; padding: 12px; margin-bottom: 15px; border-radius: 4px; font-size: 13px; font-weight: 500;"></div>
            <div id="applicationModalBody">
                <p>Loading applications...</p>
            </div>
        </div>
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

        let currentModalAllotteeId = null;

        function showModalAlert(message, type = 'error') {
            const alertBox = document.getElementById('applicationModalAlert');
            alertBox.style.display = 'block';
            alertBox.innerHTML = message;
            if (type === 'success') {
                alertBox.style.backgroundColor = '#d1e7dd';
                alertBox.style.color = '#0f5132';
                alertBox.style.border = '1px solid #badbcc';
            } else {
                alertBox.style.backgroundColor = '#f8d7da';
                alertBox.style.color = '#842029';
                alertBox.style.border = '1px solid #f5c2c7';
            }
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 5000);
        }

        function openApplicationModal(allotteeId) {
            currentModalAllotteeId = allotteeId;
            const modal = document.getElementById('applicationModal');
            const modalContent = modal.querySelector('.modal-content');
            const modalBody = document.getElementById('applicationModalBody');
            
            modal.style.display = 'flex';
            // Trigger reflow
            void modal.offsetWidth;
            
            modal.style.opacity = '1';
            modalContent.style.transform = 'translateY(0)';

            modalBody.innerHTML = '<p>Loading applications...</p>';

            fetch(`/admin/allottees/${allotteeId}/applications`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const appTypes = [
                            'allotment', 'agreement', 'possession', 'registry', 'mutation',
                            'transfer', 'noc', 'lease_renewal', 'duplicate_certificate',
                            'cancellation', 'name_correction'
                        ];
                        
                        let html = '<table style="width: 100%; border-collapse: collapse; font-size: 13px;">';
                        html += '<thead><tr style="border-bottom: 2px solid #eaeaea; background: #fbfbfc;">';
                        html += '<th style="padding: 10px; text-align: left; text-transform: uppercase; font-size: 11px; color: #6c757d; letter-spacing: 0.5px;">Application Type</th>';
                        html += '<th style="padding: 10px; text-align: left; text-transform: uppercase; font-size: 11px; color: #6c757d; letter-spacing: 0.5px;">Application No</th>';
                        html += '<th style="padding: 10px; text-align: left; text-transform: uppercase; font-size: 11px; color: #6c757d; letter-spacing: 0.5px;">Status</th>';
                        html += '<th style="padding: 10px; text-align: right; text-transform: uppercase; font-size: 11px; color: #6c757d; letter-spacing: 0.5px;">Action</th>';
                        html += '</tr></thead><tbody>';

                        appTypes.forEach(type => {
                            // Find if application exists
                            const existingApp = data.applications.find(a => a.application_type === type);
                            
                            html += `<tr style="border-bottom: 1px solid #f4f4f4; transition: background 0.2s;" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 10px; font-weight: 500; color: #333; text-transform: capitalize;">${type.replace('_', ' ')}</td>`;
                                
                            if (existingApp) {
                                html += `<td style="padding: 10px; color: #555;">${existingApp.application_no}</td>
                                         <td style="padding: 10px;"><span style="background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 500;">${existingApp.status}</span></td>
                                         <td style="padding: 10px; text-align: right;">
                                             <button onclick="deleteApplication(${existingApp.id})" style="background: #fff; color: #dc3545; border: 1px solid #dc3545; padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 12px; transition: all 0.2s;" onmouseover="this.style.background='#dc3545'; this.style.color='#fff'" onmouseout="this.style.background='#fff'; this.style.color='#dc3545'">Delete</button>
                                         </td>`;
                            } else {
                                html += `<td style="padding: 10px; color: #aaa; font-style: italic;">Not Created</td>
                                         <td style="padding: 10px; color: #aaa;">-</td>
                                         <td style="padding: 10px; text-align: right;">
                                             <button onclick="createApplicationOfType('${type}')" style="background: #fff; color: #198754; border: 1px solid #198754; padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 12px; transition: all 0.2s;" onmouseover="this.style.background='#198754'; this.style.color='#fff'" onmouseout="this.style.background='#fff'; this.style.color='#198754'">Create</button>
                                         </td>`;
                            }
                            html += `</tr>`;
                        });
                        
                        html += '</tbody></table>';
                        modalBody.innerHTML = html;
                    } else {
                        modalBody.innerHTML = '<p style="color: red; font-size: 16px;">Failed to load applications.</p>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    modalBody.innerHTML = '<p style="color: red;">An error occurred.</p>';
                });
        }

        function closeApplicationModal() {
            const modal = document.getElementById('applicationModal');
            const modalContent = modal.querySelector('.modal-content');
            
            modal.style.opacity = '0';
            modalContent.style.transform = 'translateY(-50px)';
            
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        function deleteApplication(applicationId) {
            if (!confirm('Are you sure you want to delete this application?')) {
                return;
            }

            fetch(`/admin/applications/${applicationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showModalAlert(data.message, 'success');
                    openApplicationModal(currentModalAllotteeId); // Refresh list
                } else {
                    showModalAlert(data.message || 'Error deleting application.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showModalAlert('An error occurred while deleting the application.', 'error');
            });
        }

        function createApplicationOfType(type) {
            if (!currentModalAllotteeId) return;

            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Creating...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('application_type', type);

            fetch(`/admin/allottees/${currentModalAllotteeId}/applications`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                
                if (data.success) {
                    showModalAlert(data.message, 'success');
                    openApplicationModal(currentModalAllotteeId); // Refresh list
                } else {
                    showModalAlert(data.message || 'Error creating application.', 'error');
                }
            })
            .catch(err => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                console.error(err);
                showModalAlert('An error occurred while creating the application.', 'error');
            });
        }
    </script>
@endsection
