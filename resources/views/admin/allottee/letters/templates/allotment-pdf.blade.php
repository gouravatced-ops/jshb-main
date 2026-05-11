<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #111827; line-height: 1.6; }
        .title { text-align: center; font-size: 20px; font-weight: 700; margin-bottom: 20px; }
        .meta { margin-top: 20px; }
        .meta p { margin: 2px 0; }
    </style>
</head>
<body>
    <div class="title">Allotment Letter</div>
    <p>To,</p>
    <p><strong>{{ trim(($allottee->allottee_name ?? '') . ' ' . ($allottee->allottee_middle_name ?? '') . ' ' . ($allottee->allottee_surname ?? '')) ?: 'Allottee' }}</strong></p>

    <p>
        This is to inform you that property number <strong>{{ $allottee->property_number ?: '-' }}</strong>
        is allotted under division <strong>{{ $allottee->division->name ?? '-' }}</strong>,
        sub division <strong>{{ $allottee->subDivision->name ?? '-' }}</strong>.
    </p>

    <div class="meta">
        <p><strong>Application No:</strong> {{ $allottee->application_no ?: '-' }}</p>
        <p><strong>Allotment No:</strong> {{ $allottee->allotment_no ?: '-' }}</p>
        <p><strong>Date:</strong> {{ now()->format('d-m-Y') }}</p>
    </div>
</body>
</html>
