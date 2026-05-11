<h4 style="margin-bottom:12px;">Allottee Personal Detail</h4>
<div class="table-responsive">
    <table class="ep-table">
        <tbody>
            <tr><th>Allottee Name</th><td>{{ trim(($allottee->allottee_name ?? '') . ' ' . ($allottee->allottee_middle_name ?? '') . ' ' . ($allottee->allottee_surname ?? '')) ?: '-' }}</td></tr>
            <tr><th>Gender</th><td>{{ $allottee->allottee_gender ?: '-' }}</td></tr>
            <tr><th>Category</th><td>{{ $allottee->allottee_category ?: '-' }}</td></tr>
            <tr><th>Marital Status</th><td>{{ $allottee->marital_status ?: '-' }}</td></tr>
            <tr><th>Date of Birth</th><td>{{ ($allottee->date_of_birth_day && $allottee->date_of_birth_month && $allottee->date_of_birth_year) ? $allottee->date_of_birth_day . '-' . $allottee->date_of_birth_month . '-' . $allottee->date_of_birth_year : '-' }}</td></tr>
            <tr><th>Division</th><td>{{ $allottee->division->name ?? '-' }}</td></tr>
            <tr><th>Sub Division</th><td>{{ $allottee->subDivision->name ?? '-' }}</td></tr>
            <tr><th>Property Category</th><td>{{ $allottee->propertyCategory->name ?? '-' }}</td></tr>
            <tr><th>Property Number</th><td>{{ $allottee->property_number ?: '-' }}</td></tr>
            <tr><th>Allotment Number</th><td>{{ $allottee->allotment_no ?: '-' }}</td></tr>
        </tbody>
    </table>
</div>
