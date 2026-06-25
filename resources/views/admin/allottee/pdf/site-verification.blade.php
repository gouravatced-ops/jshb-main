<!DOCTYPE html>
<html lang="hi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>झारखण्ड राज्य आवास बोर्ड – जाँच प्रपत्र</title>
    <style>
        /* ===== FONTS ===== */
        @font-face {
            font-family: 'KrutiDev';
            src: url("{{ public_path('font/KrutiDev011.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'KrutiDev';
            src: url("{{ public_path('font/KrutiDev011.ttf') }}") format('truetype');
            font-weight: 500;
            font-style: normal;
        }
        @font-face {
            font-family: 'KrutiDev';
            src: url("{{ public_path('font/KrutiDev011.ttf') }}") format('truetype');
            font-weight: 600;
            font-style: normal;
        }
        @font-face {
            font-family: 'KrutiDev';
            src: url("{{ public_path('font/KrutiDev011.ttf') }}") format('truetype');
            font-weight: 700;
            font-style: normal;
        }
        @font-face {
            font-family: 'KrutiDev';
            src: url("{{ public_path('font/KrutiDev011.ttf') }}") format('truetype');
            font-weight: 800;
            font-style: normal;
        }
        @font-face {
            font-family: 'KrutiDev';
            src: url("{{ public_path('font/KrutiDev011.ttf') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        /* minimal reset, all styling is inline except these helpers */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #ffffff;
            padding: 10px;
            font-family: 'KrutiDev', 'freeserif', sans-serif;
            font-size: 18px;
        }

        .page {
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
            background: white;
            padding: 10px 20px;
        }

        /* dotted underline pattern for fill-in fields */
        .dotted-field {
            display: inline-block;
            min-width: 140px;
            max-width: 100%;
            box-sizing: border-box;
            border-bottom: 2px dotted #1e2b4f;
            margin: 0 4px;
            height: 1.4em;
            line-height: 1.4;
            letter-spacing: 2px;
            font-size: 14px;
            font-family: 'freeserif', 'Helvetica', 'Arial', sans-serif !important;
        }

        .dotted-long { min-width: 240px; }
        .dotted-xl { min-width: 260px; }
        .dotted-medium { min-width: 160px; }
        .dotted-short { min-width: 80px; }

        .sub-item {
            margin-left: 28px;
            margin-top: 3px;
        }

        .checklist-row {
            margin-bottom: 10px;
            line-height: 1.7;
        }

        .en-slash {
            font-family: 'freeserif', 'Helvetica', 'Arial', sans-serif !important;
            font-weight: normal !important;
            margin: 0 2px;
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            table-layout: fixed;
            word-wrap: break-word;
        }
        .form-table td {
            padding: 2px 0;
            vertical-align: bottom;
            line-height: 1.3;
        }

        .label-bold { font-weight: 600; }

        .hr-divider {
            margin: 18px 0 12px;
            border: 0;
            border-top: 2px solid #1e2b4f;
        }

        /* Header Table */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1e2b4f;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-td {
            width: 80px;
            text-align: left;
        }
        .logo-td img {
            width: 60px;
            height: auto;
        }
        .header-content-td {
            text-align: center;
        }
        .header-sub {
            font-size: 16px;
            font-weight: 700;
            color: #1e2b4f;
            margin-top: 2px;
        }
        .header-sub2 {
            font-size: 14px;
            font-weight: 500;
            color: #1e2b4f;
            margin-top: 2px;
        }

        /* Map Placeholder */
        .map-placeholder {
            text-align: center;
            margin: 14px 0 20px;
        }

        .map-placeholder span {
            display: inline-block;
            padding: 8px 28px;
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 60px;
        }

        /* Signature Table */
        .signature-table {
            width: 100%;
            margin-top: 30px;
            border-top: 2px solid #1e2b4f;
            padding-top: 18px;
        }
        .signature-table td {
            vertical-align: top;
        }
        .signature-block {
            text-align: right;
            font-weight: 600;
        }
        .signature-line {
            display: inline-block;
            min-width: 260px;
            border-bottom: 2px solid #1e2b4f;
            height: 1.6em;
            margin-bottom: 4px;
        }
        .page-break {
            page-break-before: always;
            font-family: 'freeserif', 'Helvetica', 'Arial', sans-serif !important;
            padding: 10px 20px;
        }
    </style>
</head>

<body>
@php
function yesNo($val) {
    if($val == 'yes') return 'Yes';
    if($val == 'no') return 'No';
    return $val;
}
$mapParams = (isset($verification) && $verification) ? json_decode($verification->map_parameters ?? '{}', true) : [];
@endphp
    <div class="page">
        <!-- HEADER using Tables (DOMPDF friendly) -->
        <table class="header-table">
            <tr>
                <td class="logo-td">
                    <img src="{{ public_path('img/jshb_logo.png') }}" alt="Logo">
                </td>
                <td class="header-content-td">
                    <div style="font-size: 24px;font-weight: 800;color: #0b1a3a;line-height: 1.2;font-family: 'KrutiDev' !important;">>kj[k.M jkT; vkokl cksMZ] jk¡ph çe.My</div>
                    <div class="header-sub">tk¡p çi= ¼psd fyLV½</div>
                    <div class="header-sub2">vfUre gLrkUrj.k ls lEcfU/kr dk;Zikyd vfHk;ark dk LFky fujh{k.k çfrosnu</div>
                </td>
                <td style="width: 80px;"></td> <!-- Balance layout -->
            </tr>
        </table>

        <!-- CHECKLIST FORM – aligned perfectly with HTML table -->
        <table class="form-table">
            <!-- 1 -->
            <tr>
                <td style="width: 58%;"><span class="label-bold">1½ vkoklh; dkyksuh dk uke</span></td>
                <td style="width: 5%; text-align: center;">%&</td>
                <td style="width: 37%;"><span class="dotted-field dotted-xl">{{ $verification->colony_name ?? $allottee->scheme_name ?? '' }}</span></td>
            </tr>

            <!-- 2 -->
            <tr>
                <td><span class="label-bold">2½ vkoaVh dk uke</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ $verification->allottee_name ?? trim(($allottee->prefix ?? '') . ' ' . ($allottee->allottee_name ?? '') . ' ' . ($allottee->allottee_middle_name ?? '') . ' ' . ($allottee->allottee_surname ?? '')) }}</span></td>
            </tr>

            <!-- 3 -->
            <tr>
                <td><span class="label-bold">3½ vkoafVr bdkbZ dh la[;k</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ $verification->unit_number ?? $allottee->asset_number ?? '' }}</span></td>
            </tr>

            <!-- 4 -->
            <tr>
                <td><span class="label-bold">4½ vkoafVr bdkbZ dk ç;ksx ¼vkoklh; ;k O;olkf;d½</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ ucfirst($verification->unit_use ?? '') }}</span></td>
            </tr>

            <!-- 5 -->
            <tr>
                <td colspan="3"><span class="label-bold">5½ lM+d dk vkdkj</span></td>
            </tr>
            <tr>
                <td style="padding-left: 28px;"><span class="label-bold">¼d½ vkoafVr bdkbZ dh rjQ</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ $verification->road_front ?? '' }}</span></td>
            </tr>
            <tr>
                <td style="padding-left: 28px;"><span class="label-bold">¼[k½ vkoafVr bdkbZ dk fiNyk Hkkx</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ $verification->road_beside ?? '' }}</span></td>
            </tr>

            <!-- 6 -->
            <tr>
                <td colspan="3"><span class="label-bold">6½ Hkw[k.M dk vkdkj</span></td>
            </tr>
            <tr>
                <td style="padding-left: 28px;"><span class="label-bold">¼d½ vkoafVr bdkbZ ds vuqlkj</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ $verification->plot_size_allotment ?? '' }}</span></td>
            </tr>
            <tr>
                <td style="padding-left: 28px;"><span class="label-bold">¼[k½ fu/kkZfjr ,-,-jktukek ds vuqlkj</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ $verification->plot_size_agreement ?? '' }}</span></td>
            </tr>
            <tr>
                <td style="padding-left: 28px;"><span class="label-bold">¼x½ fn, x, n[ky dCtk ds vuqlkj</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ $verification->plot_size_possession ?? '' }}</span></td>
            </tr>
            <tr>
                <td style="padding-left: 28px;"><span class="label-bold">¼?k½ vxj dksbZ la[;k gks rks bldk dkj.k</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ $verification->plot_size_difference_reason ?? '' }}</span></td>
            </tr>

            <!-- 7 -->
            <tr>
                <td colspan="3"><span class="label-bold">7½ ;fn dksbZ vfrØe.k gks rks mldk O;ksjk</span></td>
            </tr>
            <tr>
                <td style="padding-left: 28px;"><span class="label-bold">¼d½ jdck</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ $verification->encroachment_area ?? '' }}</span></td>
            </tr>
            <tr>
                <td style="padding-left: 28px;"><span class="label-bold">¼[k½ vfrØfer Hkkx</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl"></span></td>
            </tr>
            <tr>
                <td style="padding-left: 50px;" colspan="3">
                    <span style="font-weight:500;">jksM <span class="en-slash">&#47;</span> ikdZ <span class="en-slash">&#47;</span> ukyh <span class="en-slash">&#47;</span> flojst</span>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 50px;">
                    <span style="margin-left:6px;">vU; lkoZtfud mi;ksx gsrq fpfUgr Fkk ¼gk¡ ;k ugha½</span>
                </td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ yesNo($verification->encroachment_public_use ?? '') }}</span></td>
            </tr>
            <tr>
                <td style="padding-left: 28px;" colspan="3">
                    <span class="label-bold">¼x½ bldk Lrjksa esa Hkw[k.M <span class="en-slash">&#47;</span> Iy‚V <span class="en-slash">&#47;</span> edku cukus gsrq</span>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 50px;">
                    <span style="font-weight:500;">mi;ksx esa fy;k tk ldrk gS\ ¼gk¡ ;k ugha½</span>
                </td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ yesNo($verification->encroachment_independent_use ?? '') }}</span></td>
            </tr>
            <tr>
                <td style="padding-left: 28px;">
                    <span class="label-bold">¼?k½ ;g vkoafVr bdkbZ ls fudVe fcUnq ij gS]</span><br>
                    <span style="font-weight:500; margin-left: 20px;">bldk dksbZ eq[; mi;ksx Hkfo"; esa gS ;k ugha\ ¼gk¡ ;k ugha½</span>
                </td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ yesNo($verification->encroachment_future_use ?? '') }}</span></td>
            </tr>
            <tr>
                <td style="padding-left: 28px;"><span class="label-bold">¼³½ vkoaVh ds lkFk cUnkscLrh dh tk ldrh gS ¼gk¡ ;k ugha½</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ yesNo($verification->encroachment_settlement ?? '') }}</span></td>
            </tr>

            <!-- 8 -->
            <tr>
                <td><span class="label-bold">8½ Hkw[k.M ij edku fufeZr gS ;k ugha</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-long">{{ yesNo($verification->is_house_constructed ?? '') }}</span></td>
            </tr>

            <!-- 9 -->
            <tr>
                <td colspan="3">
                    <div style="margin-bottom: 6px;"><span class="label-bold">9½ ;fn edku fufeZr gks rks l{ke çkf/kdkj }kjk Loh—r uD'kk dh vfHkçekf.kr çfr layXu djsa %&</span></div>
                    <div style="margin-left:28px;">
                        <span class="label-bold">çkf/kdkj dk uke</span> <span class="dotted-field dotted-medium" style="min-width:160px;">{{ $verification->approved_map_authority ?? '' }}</span>
                        <span style="margin-left:14px;"><span class="label-bold">ds'k la[;k</span> <span class="dotted-field dotted-medium" style="min-width:110px;">{{ $verification->approved_map_case ?? '' }}</span></span>
                        <span style="margin-left:14px;"><span class="label-bold">frfFk</span> <span class="dotted-field dotted-medium" style="min-width:100px;">{{ $verification->approved_map_date ?? '' }}</span></span>
                    </div>
                </td>
            </tr>

            <!-- 10 -->
            <tr>
                <td><span class="label-bold">10½ vkoafVr Hkw[k.M ij edku dk fuekZ.k Loh—r uD'kk vuqlkj gS ;k ugha\</span></td>
                <td style="text-align: center;">%&</td>
                <td><span class="dotted-field dotted-xl">{{ yesNo($verification->is_construction_as_per_map ?? '') }}</span></td>
            </tr>

            <!-- 11 -->
            <tr>
                <td colspan="3">
                    <div style="margin-bottom: 6px;"><span class="label-bold">11½ vkoafVr edku <span class="en-slash">&#47;</span> ¶ySV esa ifjorZu <span class="en-slash">&#47;</span> ifjonZ~/ku gqvk gS ;k ugha] ;fn gqvk gS rks l{ke çkf/kdkj  <br> }kjk Loh—r uD'kk dh çfr layXu djsa</span></div>
                    <div style="margin-left:28px;">
                        <span class="label-bold">çkf/kdkj dk uke</span> <span class="dotted-field dotted-medium" style="min-width:160px;">{{ $verification->alteration_map_authority ?? '' }}</span>
                        <span style="margin-left:14px;"><span class="label-bold">ds'k la[;k</span> <span class="dotted-field dotted-medium" style="min-width:110px;">{{ $verification->alteration_map_case ?? '' }}</span></span>
                        <span style="margin-left:14px;"><span class="label-bold">frfFk</span> <span class="dotted-field dotted-medium" style="min-width:100px;">{{ $verification->alteration_map_date ?? '' }}</span></span>
                    </div>
                </td>
            </tr>
        </table>

        <hr class="hr-divider">

        <!-- PAGE BREAK : MAP SECTION -->
        <div style="margin-top: 24px; padding-top: 26px;" class="page-break">
            <div
                style="text-align: center; font-weight: 700; font-size: 0.825rem; border-bottom: 2px solid #1e2b4f; padding-bottom: 8px; margin-bottom: 8px;">
                SITE PLAN OF {{ $allottee->quarterType->quarter_code }} PLOT NO. {{ $allottee->property_number }} HARMU HOUSING COLONY RANCHI.
            </div>
            
            <table style="width:100%; font-size: 0.725rem; font-weight: 500; margin-bottom: 4px;">
                <tr>
                    <td style="text-align: left;">SCALE - NOT TO SCALE</td>
                    <td style="text-align: right;">PLOT AREA - <span style="text-transform: uppercase;">{{ $verification->plot_size_agreement ?? '' }}</span></td>
                </tr>
            </table>

            <!-- Map Image placeholder -->
            <div class="map-placeholder">
                @if($verification->map_image)
                    @php
                        $src = $verification->map_image;
                        if (!str_starts_with($src, 'data:image')) {
                            $src = public_path($src);
                        }
                    @endphp
                    <img src="{{ $src }}" alt="Site Map Image" style="max-width:100%; max-height:300px;"/>
                @else
                    <span>Map Area</span>
                @endif
            </div>

            <!-- EXECUTIVE ENGINEER signature block -->
            <table class="signature-table">
                <tr>
                    <td></td> <!-- Left side empty -->
                    <td class="signature-block">
                        <div><span class="signature-line"></span></div>
                        <div style="font-size: 0.725rem;">EXECUTIVE ENGINEER</div>
                        <div style="font-weight: 600;font-size: 0.625rem;">JHARKHAND STATE HOUSING BOARD</div>
                        <div style="font-weight: 600;font-size: 0.625rem;">RANCHI DIVISION, RANCHI.</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>