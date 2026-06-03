<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>अधिकार पत्र - झारखण्ड राज्य आवास बोर्ड</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #111827;
            line-height: 1.6;
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .meta {
            margin-top: 20px;
        }

        .meta p {
            margin: 2px 0;
        }
    </style>
</head>

<body>
    <div class="title">Possession Letter</div>
    <p>To,</p>
    <p><strong>{{ trim(($allottee->allottee_name ?? '') . ' ' . ($allottee->allottee_middle_name ?? '') . ' ' . ($allottee->allottee_surname ?? '')) ?: 'Allottee' }}</strong></p>

    <p>
        Possession approval is issued for property number <strong>{{ $allottee->property_number ?: '-' }}</strong>
        against allotment number <strong>{{ $allottee->allotment_no ?: '-' }}</strong>.
        Please complete remaining dues and formalities.
    </p>

    <div class="meta">
        <p><strong>Division:</strong> {{ $allottee->division->name ?? '-' }}</p>
        <p><strong>Sub Division:</strong> {{ $allottee->subDivision->name ?? '-' }}</p>
        <p><strong>Date:</strong> {{ now()->format('d-m-Y') }}</p>
    </div>
</body>

</html>