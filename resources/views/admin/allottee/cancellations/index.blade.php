@extends('layouts.main')
@section('title', 'Overdue Cancellations | JSHB')

@section('content')
<div class="container-fluid">
    <!-- <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-danger"><i class="fa-solid fa-ban me-2"></i> Overdue Allotment Cancellations</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.allottees.index') }}">Allottees</a></li>
                <li class="breadcrumb-item active">Cancellations</li>
            </ol>
        </nav>
    </div> -->

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <form action="{{ route('admin.allottees.cancellations.bulk') }}" method="POST" id="cancellationForm">
                @csrf

                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                    <div>
                        <strong>Select Allottees to Cancel</strong>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmCancellation()">
                        <i class="fa-solid fa-ban me-1"></i> Cancel Selected Allotments
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;" class="text-center">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </th>
                                <th>Allotment No</th>
                                <th>Allottee Name</th>
                                <th>Scheme / Property</th>
                                <th>Overdue Since</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allottees as $allottee)
                            @php
                            $paymentOrder = $allottee->allotteeOrders->where('order_type', 'allotment')->first();
                            $overdueSince = $paymentOrder ? \Carbon\Carbon::parse($paymentOrder->due_date)->diffForHumans() : 'Unknown';
                            @endphp
                            <tr>
                                <td class="text-center">
                                    <input class="form-check-input allottee-checkbox" type="checkbox" name="allottee_ids[]" value="{{ $allottee->id }}">
                                </td>
                                <td>
                                    <strong>{{ $allottee->allotment_no ?? $allottee->application_no }}</strong>
                                </td>
                                <td>
                                    {{ trim($allottee->allottee_prefix_hindi . ' ' . $allottee->allottee_name_hindi . ' ' . $allottee->allottee_middle_hindi . ' ' . $allottee->allottee_surname_hindi) }}
                                    <br>
                                    <small class="text-muted">{{ trim($allottee->prefix . ' ' . $allottee->allottee_name . ' ' . $allottee->allottee_surname) }}</small>
                                </td>
                                <td>
                                    {{ $allottee->scheme->scheme_name ?? 'N/A' }}
                                    <br>
                                    <span class="badge bg-secondary">{{ $allottee->property_number ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="text-danger fw-bold"><i class="fa-solid fa-clock"></i> {{ $overdueSince }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fa-regular fa-folder-open mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">No overdue allotments found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.allottee-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    function confirmCancellation() {
        const selected = document.querySelectorAll('.allottee-checkbox:checked').length;
        if (selected === 0) {
            alert('Please select at least one allottee to cancel.');
            return;
        }

        if (confirm(`Are you sure you want to cancel ${selected} allotment(s)? This will generate cancellation orders and mark them as cancelled.`)) {
            document.getElementById('cancellationForm').submit();
        }
    }
</script>
@endsection
