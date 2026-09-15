<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>अधिकार पत्र - झारखण्ड राज्य आवास बोर्ड</title>
    <style>
        @font-face {
            font-family: 'KrutiDev';
            src: url("{{ public_path('font/KrutiDev011.ttf') }}") format('truetype');
        }

        body {
            font-family: 'KrutiDev';
            margin: 12px 18px;
            font-size: 16px;
            line-height: 1.2;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        p {
            margin: 0;
            padding: 0;
        }

        .list-table {
            width: 100%;
            margin-top: 30px;
        }

        .list-table td {
            vertical-align: top;
            padding: 8px 0;
            font-size: 17px;
        }

        .td-num { width: 6%; }
        .td-label { width: 34%; }
        .td-value { width: 60%; }
    </style>

</head>

<body>

    <!-- HEADER -->
    <table style="margin-bottom:5px;">
        <tr>
            <td style="width:15%;">
                <img src="{{ public_path('img/jshb_logo.png') }}" style="width:70px;">
            </td>
            <td style="width:70%; text-align:center;">
                <div style="font-size:20px;">
                    >kj[k.M ljdkj
                </div>
                <div style="font-size:28px; font-weight:bold; line-height:1;">
                    >kj[k.M jkT; vkokl cksMZ] jk¡ph
                </div>
                <div style="font-size:18px; line-height:1; margin-top:5px; text-decoration: underline;">
                    vfèkdkj & i=
                </div>
            </td>
            <td style="width:15%; text-align:right;">
                <img src="{{ public_path('img/logo.png') }}" style="width:72px;">
            </td>
        </tr>
    </table>

    <table class="list-table">
        <tr>
            <td class="td-num">1-</td>
            <td class="td-label">vkoaVh dk uke ,oa iwjk irk %&</td>
            <td class="td-value">
                {{ $allottee->allottee_prefix_hindi ?? '' }} {{ trim(($allottee->allottee_name_hindi ?? '') . ' ' . ($allottee->allottee_middle_hindi ?? '') . ' ' . ($allottee->allottee_surname_hindi ?? '')) }}<br>
                firk& {{ $allottee->relation_prefix_hindi ?? '' }} {{ $allottee->relation_name_hindi ?? '' }}, {{ optional($allottee->alloteeAdresses)->present_address_hindi ?? optional($allottee->alloteeAdresses)->present_address ?? '---------------------------------' }}
            </td>
        </tr>
        <tr>
            <td class="td-num">2-</td>
            <td class="td-label">cksMZ dk vkoaVukns'k ,oa frfFk %&</td>
            <td class="td-value">
                Hkw&lEink inkfèkdkjh] >kj[k.M jkT; vkokl cksMZ ds <br> i=kad ----------- fnukad -----------<br>
                dk;Zikyd vfHk;ark dk i=kad ------------------------- fnukad ------------------------------------
            </td>
        </tr>
        <tr>
            <td class="td-num">3-</td>
            <td class="td-label">vkoafVr Hkw[k.M@edku@¶ySV la0%&</td>
            <td class="td-value">
                {{ $allottee->property_number ? \App\Models\Allottee::convertPropertyNumberToKrutiDev($allottee->property_number) : '------' }}
            </td>
        </tr>
        <tr>
            <td class="td-num">4-</td>
            <td class="td-label">LFkku %&</td>
            <td class="td-value">
                gjew vkoklh; d‚yksuh] jk¡phA
            </td>
        </tr>
        <tr>
            <td class="td-num">5-</td>
            <td class="td-label">pkSgíh %&</td>
            <td class="td-value">
                @php
                    $mapHindi = json_decode(optional($allottee->siteVerification)->map_parameters_hindi, true) ?? [];
                @endphp
                <table style="width: 100%;">
                    <tr><td style="width: 15%;">mÙkj</td><td style="width: 5%;">%&</td><td>{{ $mapHindi['northLabel'] ?? '---------------------------' }}</td></tr>
                    <tr><td>nf{k.k</td><td>%&</td><td>{{ $mapHindi['southLabel'] ?? '---------------------------' }}</td></tr>
                    <tr><td>iwoZ</td><td>%&</td><td>{{ $mapHindi['eastLabel'] ?? '---------------------------' }}</td></tr>
                    <tr><td>if'pe</td><td>%&</td><td>{{ $mapHindi['westLabel'] ?? '---------------------------' }}</td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="td-num">6-</td>
            <td class="td-label">{ks=Qy ¼jdck½ %&</td>
            <td class="td-value">
                <span style="font-family: Arial, sans-serif; font-size: 14px;">{{ optional($allottee->siteVerification)->plot_size_possession ?? '...................' }}</span> oxZQhV
            </td>
        </tr>
        <tr>
            <td class="td-num">7-</td>
            <td class="td-label">xokg dk gLrk{kj ,oa iwjk irk %&</td>
            <td class="td-value" style="padding-top: 15px;">
                -----------------------------------------------------------------<br>
                <div style="margin-top: 10px;">-----------------------------------------------------------------</div>
            </td>
        </tr>
        <tr>
            <td class="td-num">8-</td>
            <td class="td-label">vfèkdkj ysus dh frfFk %&</td>
            <td class="td-value">
                -----------------------------------------------------------------
            </td>
        </tr>
    </table>

    <table style="margin-top: 60px; text-align: center; width: 100%;">
        <tr>
            <td style="width: 50%;">
                vfèkdkj nsusokys O;fä dk gLrk{kj ,oa inukeA
            </td>
            <td style="width: 50%;">
                vfèkdkj ysusokys O;fä dk gLrk{kjA
            </td>
        </tr>
    </table>

</body>

</html>
