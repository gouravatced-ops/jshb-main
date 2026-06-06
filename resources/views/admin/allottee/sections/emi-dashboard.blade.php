{{-- resources/views/admin/allottee/sections/emi-dashboard.blade.php --}}

@php

    $emiAccount = \App\Models\AllotteeEmiAccount::where('allottee_id', $allottee->id)->first();

    $schedules = $emiAccount
        ? \App\Models\AllotteeEmiSchedule::where('emi_account_id', $emiAccount->id)->orderBy('emi_no')->get()
        : collect();

    $paidEmis = $schedules->where('payment_status', 'paid')->count();
    $pendingEmis = $schedules->where('payment_status', '!=', 'paid')->count();

    $nextEmi = $schedules->where('payment_status', '!=', 'paid')->sortBy('emi_no')->first();

@endphp

<div>

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">
                EMI Dashboard
            </h1>

            <p class="page-subtitle">
                EMI Dashboard ·
                Application :
                {{ $allottee->application_no ?? '-' }}
            </p>
        </div>

        <button class="btn-ghost" onclick="window.close();">

            <i class="fa-solid fa-arrow-left"></i>
            Back to List

        </button>
    </div>

    @if ($emiAccount)

        {{-- SUMMARY --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Principal Amount
                    </p>

                    <p class="info-card-value">
                        ₹ {{ number_format($emiAccount->principal_amount, 2) }}
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        EMI Amount
                    </p>

                    <p class="info-card-value text-primary">
                        ₹ {{ number_format($emiAccount->emi_amount, 2) }}
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Interest Rate
                    </p>

                    <p class="info-card-value">
                        {{ $emiAccount->annual_interest_rate }}%
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Remaining Balance
                    </p>

                    <p class="info-card-value text-danger">
                        ₹ {{ number_format($emiAccount->remaining_amount, 2) }}
                    </p>
                </div>
            </div>

        </div>

        {{-- SECOND ROW --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Total EMI
                    </p>

                    <p class="info-card-value">
                        {{ $emiAccount->tenure_months }}
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Paid EMI
                    </p>

                    <p class="info-card-value text-success">
                        {{ $paidEmis }}
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Pending EMI
                    </p>

                    <p class="info-card-value text-warning">
                        {{ $pendingEmis }}
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-card">
                    <p class="info-card-label">
                        Account Status
                    </p>

                    <p class="info-card-value">

                        @if ($emiAccount->account_status === 'closed')
                            <span class="badge bg-success">
                                Closed
                            </span>
                        @else
                            <span class="badge bg-primary">
                                Active
                            </span>
                        @endif

                    </p>
                </div>
            </div>

        </div>

        {{-- NEXT EMI --}}
        @if ($nextEmi)
            <div class="alert alert-warning mb-4">

                <strong>
                    Next EMI :
                </strong>

                EMI #{{ $nextEmi->emi_no }}

                |

                Due Date :
                {{ \Carbon\Carbon::parse($nextEmi->due_date)->format('d-m-Y') }}

                |

                Amount :
                ₹ {{ number_format($nextEmi->total_payable, 2) }}

            </div>
        @endif

        {{-- ACTIONS --}}
        <div
            style="
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            margin-bottom:20px;
        ">

            @if ($nextEmi)
                <button class="btn-brand" onclick="payEmi('{{ encrypt($nextEmi->id) }}')">

                    <i class="fa-solid fa-credit-card"></i>
                    Pay EMI

                </button>
            @endif

            <button class="btn-brand" onclick="prePayment('{{ encrypt($emiAccount->id) }}')">

                <i class="fa-solid fa-money-bill-transfer"></i>
                Pre Payment

            </button>

            <button class="btn-brand" onclick="closeLoan('{{ encrypt($emiAccount->id) }}')">

                <i class="fa-solid fa-circle-check"></i>
                Close Loan

            </button>

            <a href="#" target="_blank" class="btn-brand">

                <i class="fa-solid fa-file-pdf"></i>
                Download Statement

            </a>

        </div>

        {{-- EMI SCHEDULE --}}
        <div class="card">

            <div class="card-header">
                EMI Schedule
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="bg-brand">
                            <tr>
                                <th>#</th>
                                <th>Due Date</th>
                                <th>Opening Principal</th>
                                <th>Principal</th>
                                <th>Interest</th>
                                <th>Penalty</th>
                                <th>Total Payable</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($schedules as $schedule)
                                <tr>

                                    <td>
                                        {{ $schedule->emi_no }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($schedule->due_date)->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        ₹ {{ number_format($schedule->opening_principal, 2) }}
                                    </td>

                                    <td>
                                        ₹ {{ number_format($schedule->principal_component, 2) }}
                                    </td>

                                    <td>
                                        ₹ {{ number_format($schedule->interest_component, 2) }}
                                    </td>

                                    <td class="text-danger">
                                        ₹ {{ number_format($schedule->penalty_amount, 2) }}
                                    </td>

                                    <td>
                                        ₹ {{ number_format($schedule->total_payable, 2) }}
                                    </td>

                                    <td>

                                        @if ($schedule->payment_status == 'paid')
                                            <span class="badge bg-success">
                                                Paid
                                            </span>
                                        @elseif($schedule->payment_status == 'overdue')
                                            <span class="badge bg-danger">
                                                Overdue
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8" class="text-center">
                                        No EMI Schedule Found
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    @else
        <div class="alert alert-warning">
            EMI Account not generated yet.
        </div>

    @endif

</div>

<script>
    function payEmi(id) {
        console.log('Pay EMI', id);
    }

    function prePayment(id) {
        console.log('Pre Payment', id);
    }

    function closeLoan(id) {
        if (confirm('Close this loan account ?')) {
            console.log('Close Loan', id);
        }
    }
</script>
