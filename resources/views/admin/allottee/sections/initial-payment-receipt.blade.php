{{-- resources/views/admin/allottee/receipts/initial-payment-receipt.blade.php --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>

        body{
            font-family: DejaVu Sans;
            font-size:14px;
            padding:30px;
            color:#111;
        }

        .title{
            text-align:center;
            font-size:24px;
            font-weight:bold;
            margin-bottom:20px;
        }

        .box{
            border:1px solid #d1d5db;
            padding:15px;
            border-radius:8px;
            margin-bottom:15px;
        }

        .label{
            font-size:12px;
            color:#666;
        }

        .value{
            font-size:15px;
            font-weight:bold;
            margin-top:4px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table td{
            border:1px solid #d1d5db;
            padding:10px;
        }

    </style>

</head>

<body>

    <div class="title">
        Initial Payment Receipt
    </div>

    <div class="box">

        <div class="label">
            Transaction No
        </div>

        <div class="value">
            {{ $payment->transaction_no }}
        </div>

    </div>

    <table>

        <tr>
            <td>Application No</td>
            <td>{{ $payment->allottee->application_no }}</td>
        </tr>

        <tr>
            <td>Allotment No</td>
            <td>{{ $payment->allottee->allotment_no }}</td>
        </tr>

        <tr>
            <td>Applicant Name</td>
            <td>
                {{ trim(
                    ($payment->allottee->prefix ?? '') . ' ' .
                    ($payment->allottee->allottee_name ?? '') . ' ' .
                    ($payment->allottee->allottee_middle_name ?? '') . ' ' .
                    ($payment->allottee->allottee_surname ?? '')
                ) }}
            </td>
        </tr>

        <tr>
            <td>Property Amount</td>
            <td>
                ₹ {{ number_format($payment->property_amount, 2) }}
            </td>
        </tr>

        <tr>
            <td>Initial Payment</td>
            <td>
                ₹ {{ number_format($payment->initial_amount, 2) }}
            </td>
        </tr>

        <tr>
            <td>Penalty Amount</td>
            <td>
                ₹ {{ number_format($payment->penalty_amount, 2) }}
            </td>
        </tr>

        <tr>
            <td>Total Paid Amount</td>
            <td>
                ₹ {{ number_format($payment->paid_amount, 2) }}
            </td>
        </tr>

        <tr>
            <td>Payment Date</td>
            <td>
                {{ $payment->paid_date?->format('d-m-Y h:i A') }}
            </td>
        </tr>

        <tr>
            <td>Payment Gateway</td>
            <td>
                {{ $payment->payment_gateway }}
            </td>
        </tr>

    </table>

</body>

</html>