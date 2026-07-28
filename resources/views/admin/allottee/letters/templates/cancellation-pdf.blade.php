<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Cancellation Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 12px 18px;
            font-size: 16px;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .sub-header {
            text-align: center;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .date-section {
            text-align: right;
            margin-bottom: 20px;
        }

        .content {
            margin-top: 20px;
            text-align: justify;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        JHARKHAND STATE HOUSING BOARD
    </div>
    <div class="sub-header">
        Harmu Housing Colony, Ranchi
        <br>
        <strong>ORDER OF CANCELLATION</strong>
    </div>

    <div class="date-section">
        Date: {{ now()->format('d/m/Y') }}
    </div>

    <div>
        <strong>To,</strong><br>
        {{ trim($allottee->prefix . ' ' . $allottee->allottee_name . ' ' . $allottee->allottee_surname) }}<br>
        Allotment No: {{ $allottee->allotment_no ?? $allottee->application_no }}<br>
        Scheme: {{ $allottee->scheme->scheme_name ?? 'N/A' }}<br>
        Property No: {{ $allottee->property_number ?? 'N/A' }}
    </div>

    <div class="content">
        <p>Dear Sir/Madam,</p>
        <p>
            This is to inform you that your allotment for the property mentioned above has been <strong>CANCELLED</strong>.
        </p>
        <p>
            Reason for Cancellation: <strong>{{ $allottee->cancellation_reason ?? 'Non-payment of 15% allotment amount within stipulated time.' }}</strong>
        </p>
        <p>
            As per the rules of the Jharkhand State Housing Board, failure to deposit the required allotment amount within the specified time period results in automatic cancellation of the allotment. 
        </p>
        <p>
            Any amount deposited (if applicable) will be forfeited or refunded as per the prevailing rules of the Board.
        </p>
        <p>
            This order is issued by the competent authority.
        </p>
    </div>

    <div class="footer">
        Authorized Signatory<br>
        Jharkhand State Housing Board
    </div>
</body>

</html>
