<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMI Payment Receipt</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }

        .receipt-container {
            border: 2px solid #000;
            padding: 20px;
            position: relative;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
            font-size: 12px;
        }

        .receipt-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 15px 0;
            text-decoration: underline;
        }

        .receipt-meta {
            width: 100%;
            margin-bottom: 20px;
        }

        .receipt-meta td {
            padding: 4px 0;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .content-table td {
            padding: 6px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 160px;
        }

        .payment-breakdown {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .payment-breakdown th,
        .payment-breakdown td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .payment-breakdown th {
            background-color: #f5f5f5;
        }

        .amount-col {
            text-align: right;
            width: 120px;
        }

        .words-section {
            margin-top: 15px;
            padding: 10px;
            border: 1px dashed #666;
            background-color: #fafafa;
        }

        .footer {
            margin-top: 60px;
        }

        .footer-table {
            width: 100%;
        }

        .signature-box {
            border-top: 1px solid #000;
            width: 180px;
            text-align: center;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <div class="header">
            <h1>Jharkhand State Housing Board</h1>
            <p>Government of Jharkhand</p>
        </div>

        <div class="receipt-title">EMI PAYMENT RECEIPT</div>

        <table class="receipt-meta">
            <tr>
                <td><strong>Receipt No:</strong> {{ $transaction->transaction_no }}</td>
                <td style="text-align: right;"><strong>Date:</strong> {{ $transaction->paid_at->format('d-m-Y h:i A') }}
                </td>
            </tr>
        </table>

        <table class="content-table">
            <tr>
                <td class="label">Allottee Name:</td>
                <td>{{ trim($demand->allottee->prefix . ' ' . $demand->allottee->allottee_name . ' ' . $demand->allottee->allottee_middle_name . ' ' . $demand->allottee->allottee_surname) }}
                </td>
            </tr>
            <tr>
                <td class="label">Allotment No:</td>
                <td>{{ $demand->allottee->allotment_no }}</td>
            </tr>
            <tr>
                <td class="label">Property No:</td>
                <td>{{ $demand->allottee->property_number }}</td>
            </tr>
            <tr>
                <td class="label">EMI Month:</td>
                <td>{{ $emiMonth }} (Installment No: {{ $demand->emi_no }})</td>
            </tr>
            <tr>
                <td class="label">Payment Mode:</td>
                <td>{{ strtoupper($transaction->payment_mode) }}
                    {{ $transaction->utr_no ? '[' . $transaction->utr_no . ']' : '' }}</td>
            </tr>
        </table>

        <table class="payment-breakdown">
            <thead>
                <tr>
                    <th>Description of Charges</th>
                    <th class="amount-col">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Monthly Installment (Principal + Interest)</td>
                    <td class="amount-col">{{ number_format($demand->emi_amount, 2) }}</td>
                </tr>
                @if ($demand->late_fine_penalty > 0)
                    <tr>
                        <td>Late Fine Penalty (1% of EMI)</td>
                        <td class="amount-col">{{ number_format($demand->late_fine_penalty, 2) }}</td>
                    </tr>
                @endif
                @if ($demand->penalty_interest_amount > 0)
                    <tr>
                        <td>Penalty Interest (16% Annual Rate)</td>
                        <td class="amount-col">{{ number_format($demand->penalty_interest_amount, 2) }}</td>
                    </tr>
                @endif
                @if ($demand->penalty_admin_charges > 0)
                    <tr>
                        <td>Administrative Charges</td>
                        <td class="amount-col">{{ number_format($demand->penalty_admin_charges, 2) }}</td>
                    </tr>
                @endif
                <tr style="font-weight: bold;">
                    <td>TOTAL RECEIVED AMOUNT</td>
                    <td class="amount-col">{{ number_format($transaction->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="words-section">
            <p style="margin: 0 0 5px 0;"><strong>In English:</strong> {{ $amountInEnglish }} Only</p>
            {{-- <p style="margin: 0;"><strong>In Hindi:</strong> {{ $amountInHindi }}</p> --}}
        </div>

        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td>
                        <p style="font-size: 10px; color: #666;">* This is a computer generated receipt and does not
                            require a physical signature.</p>
                    </td>
                    <td style="text-align: right;">
                        <div class="signature-box" style="float: right;">
                            Authorized Signatory
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
