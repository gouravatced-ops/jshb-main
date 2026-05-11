<div class="mb-3">
    <h5 class="mb-1">Quick View</h5>
    <div class="text-muted small">Default allottee dashboard summary</div>
</div>

<div class="row g-3">
    <div class="col-md-4"><div class="border rounded p-3 h-100"><div class="text-muted small">Property Number</div><div class="fw-bold">{{ $allottee->property_number ?: '-' }}</div></div></div>
    <div class="col-md-4"><div class="border rounded p-3 h-100"><div class="text-muted small">Division</div><div class="fw-bold">{{ $allottee->division->name ?? '-' }}</div></div></div>
    <div class="col-md-4"><div class="border rounded p-3 h-100"><div class="text-muted small">Sub Division</div><div class="fw-bold">{{ $allottee->subDivision->name ?? '-' }}</div></div></div>
    <div class="col-md-4"><div class="border rounded p-3 h-100"><div class="text-muted small">Category</div><div class="fw-bold">{{ $allottee->propertyCategory->name ?? '-' }}</div></div></div>
    <div class="col-md-4"><div class="border rounded p-3 h-100"><div class="text-muted small">Property Type</div><div class="fw-bold">{{ $allottee->propertyType->name ?? '-' }}</div></div></div>
    <div class="col-md-4"><div class="border rounded p-3 h-100"><div class="text-muted small">Quarter Type</div><div class="fw-bold">{{ ($allottee->quarterType->quarter_code ?? '-') . ' ' . ($allottee->quarterType->quarter_name ?? '') }}</div></div></div>
    <div class="col-md-4"><div class="border rounded p-3 h-100"><div class="text-muted small">Payment Option</div><div class="fw-bold">{{ $allottee->payment_option ? strtoupper($allottee->payment_option) : '-' }}</div></div></div>
    <div class="col-md-4"><div class="border rounded p-3 h-100"><div class="text-muted small">Remaining Amount</div><div class="fw-bold">{{ $allottee->remaining_amount ? number_format((float)$allottee->remaining_amount,2) : '-' }}</div></div></div>
    <div class="col-md-4"><div class="border rounded p-3 h-100"><div class="text-muted small">EMI Monthly Amount</div><div class="fw-bold">{{ $allottee->emi_monthly_amount ? number_format((float)$allottee->emi_monthly_amount,2) : '-' }}</div></div></div>
</div>
