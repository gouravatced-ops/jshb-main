@php($address = $allottee->alloteeAdresses)

<h4 style="margin-bottom:12px;">Communication Address</h4>
<div class="table-responsive">
    <table class="ep-table">
        <tbody>
            <tr><th>Present Address</th><td>{{ $address->present_address ?? '-' }}</td></tr>
            <tr><th>Permanent Address</th><td>{{ $address->permanent_address ?? '-' }}</td></tr>
            <tr><th>Correspondence Address</th><td>{{ $address->correspondence_address ?? '-' }}</td></tr>
            <tr><th>District</th><td>{{ $address->correspondence_district ?? $address->present_district ?? '-' }}</td></tr>
            <tr><th>Pincode</th><td>{{ $address->correspondence_pincode ?? $address->present_pincode ?? '-' }}</td></tr>
            <tr><th>Mobile</th><td>{{ $address->mobile_number ?? '-' }}</td></tr>
            <tr><th>Alternate Mobile</th><td>{{ $address->alternate_mobile ?? '-' }}</td></tr>
            <tr><th>Email</th><td>{{ $address->email ?? '-' }}</td></tr>
        </tbody>
    </table>
</div>
