<div class="modal fade" id="allotteeShowMoreModal" tabindex="-1" aria-labelledby="allotteeShowMoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <div class="modal-header header-green" style="border-radius: 8px 8px 0 0; color: rgb(3, 73, 24);">
                <h5 class="modal-title" id="allotteeShowMoreModalLabel">
                    <i class="fa-solid fa-list-ul me-2"></i> Additional Allottee Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background: #fdfdfd;">
                @if($application->allottee)
                <table class="table table-bordered table-striped" style="font-size: 13px;">
                    <tbody>
                        <tr>
                            <td style="font-weight: 600; width: 40%; color: #555;">Division</td>
                            <td>{{ $application->allottee->division ? $application->allottee->division->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Sub Division</td>
                            <td>{{ $application->allottee->subDivision ? $application->allottee->subDivision->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Property No</td>
                            <td><strong style="color: #1b5e20;">{{ $application->allottee->property_number ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Property Category</td>
                            <td>{{ $application->allottee->propertyCategory ? $application->allottee->propertyCategory->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Property Type</td>
                            <td>{{ $application->allottee->propertyType ? $application->allottee->propertyType->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Property Sub Type</td>
                            <td>{{ $application->allottee->propertySubType ? $application->allottee->propertySubType->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; color: #555;">Quarter Type</td>
                            <td>{{ $application->allottee->quarterType ? $application->allottee->quarterType->quarter_name : 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
                @else
                <div class="text-center text-muted">No Additional Details Found.</div>
                @endif
            </div>
            <div class="modal-footer" style="background: #f5f5f5; border-radius: 0 0 8px 8px;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
