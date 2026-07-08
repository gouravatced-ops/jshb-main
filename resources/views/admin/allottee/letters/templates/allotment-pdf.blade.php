<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>आवंटन आदेश - झारखण्ड राज्य आवास बोर्ड</title>
    <style>
        @font-face {
            font-family: 'KrutiDev';
            src: url("{{ public_path('font/KrutiDev011.ttf') }}") format('truetype');
        }

        body {
            font-family: 'KrutiDev';
            margin: 12px 18px;
            font-size: 16px;
            line-height: 1.1;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        p {
            margin: 0;
            padding: 0;
        }
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
                    >kj[k.M jkT; vkokl cksMZ
                </div>

                <div style="font-size:18px; line-height:1;">
                    ¼uxj fodkl ,oa vkokl foHkkx½
                </div>

                <div
                    style="
                    font-size:14px;
                    font-family: Arial, sans-serif;
                ">
                    E-mail : md.jshb@gmail.com
                </div>

            </td>

            <td style="width:15%; text-align:right;">
                <img src="{{ public_path('img/logo.png') }}" style="width:72px;">
            </td>

        </tr>

    </table>


    <!-- TOP DETAILS -->

    <table style="margin-top:6px;">

        <tr>

            <td style="font-size:17px;">
                vkoaVu vkns’k la[;k % <span
                    style="
                    font-size:12px;
                    font-family: Arial, sans-serif;
                ">
                    {{ $allottee->allotment_no ?? '--------' }}
                </span>
            </td>

            <td style="text-align:right; font-size:17px;">
                fnukad %& <span
                    style="
                    font-size:12px;
                    font-family: Arial, sans-serif;
                ">
                    {{ date('d/m/Y') }}
                </span>
            </td>

        </tr>

    </table>


    <!-- PROPERTY -->

    <table style="margin-top:14px;">

        <tr>

            <td style="font-size:17px;">
                e/;e vk; oxhZ; ¶ySV la[;k %& <span
                    style="
                    font-size:12px;
                    font-family: Arial, sans-serif;
                ">
                    {{ $allottee->property_number ?? '------' }}
                </span>
            </td>

            <td style="text-align:right; font-size:17px;">
                dksfV %& lkekU;
            </td>

        </tr>

    </table>


    <!-- PARA -->

    <div
        style="
        margin-top:10px;
        font-size:17px;
        text-align:justify;
        line-height:1.35;
    ">

        >kj[k.M jkT; vkokl cksMZ ¼vkoklh; Hkw&lEink dk
        izcU/ku ,oa fuLrkj½ fofu;ekoyh 2004 ds micU/kksa ds
        rgr~ fnukad <span
            style="
                    font-size:12px;
                    font-family: Arial, sans-serif;
                ">
            <strong>
                {{ $allottee->allotment_day && $allottee->allotment_month && $allottee->allotment_year
                    ? "{$allottee->allotment_day}.{$allottee->allotment_month}.{$allottee->allotment_year}"
                    : '28.11.2026' }}
            </strong>
        </span> dks cksMZ eq[;ky; ds lHkk d{k esa
        fudkyh xbZ ykWVjh ds vkyksd esa Jh fot; dqekj flag] firk Jh
        cynso dqekj flag] lk0&822123] eks0 % 9934100038
        ----- vkosnu la[;k& <span
            style="
                    font-size:12px;
                    font-family: Arial, sans-serif;
                ">
            <strong>{{ $allottee->application_no ?: '-' }}</strong>
        </span>
        lkekU;] vkoafVr fd;k tk jkrk gSA ;g vkoaVu vkns’k
        izFke o varfje gS vkSj bldh vkf[kjh Lohd`fr ds mijkUr
        cksMZ }kjk iznku dh tk;sxhA

    </div>


    <!-- DIRECTION -->

    <table style="margin-top:10px; font-size:17px;">

        <tr>

            <td style="width:50%; padding-bottom:2px;">
                mRrj
                ---------------------------
            </td>

            <td style="width:50%; padding-bottom:2px;">
                nf{k.k
                ---------------------------
            </td>

        </tr>

        <tr>

            <td>
                iwoZ
                ---------------------------
            </td>

            <td>
                if’pe
                ---------------------------
            </td>

        </tr>

    </table>


    <!-- AREA -->

    <table style="
        margin-top:8px;
        font-size:17px;
        width:85%;
    ">

        <tr>
            <td>iwoZ ls if’pe mRrj rjQ</td>
            <td>--------------------</td>
            <td>QhV</td>
        </tr>

        <tr>
            <td>iwoZ ls if’pe nf{k.k rjQ</td>
            <td>--------------------</td>
            <td>QhV</td>
        </tr>

        <tr>
            <td>mRrj ls nf{k.k iwoZ rjQ</td>
            <td>--------------------</td>
            <td>QhV</td>
        </tr>

        <tr>
            <td>mRrj ls nf{k.k if’pe rjQ</td>
            <td>--------------------</td>
            <td>QhV</td>
        </tr>

    </table>


    <!-- PARA 2 -->

    <div
        style="
        margin-top:12px;
        font-size:16px;
        line-height:1.35;
        text-align:justify;
    ">

        2- mDr ¶ySV dk mÙkj of.kZr Hkqtkvksa dh eki ds lkFk
        tSlk gS] og <span
            style="
                    font-size:12px;
                    font-family: Arial, sans-serif;
                ">
            ¼AS IS WHERE IS½
        </span> dh ’krZ ij vkoafVr
        fd;k tk jgk gSA ;fn mDr ¶ySV esa fdlh izdkj dh
        =qfV ;k vUrj ik;k tkrk gS rks cksMZ }kjk vko’;d
        la’kks/ku fd;k tk ldrk gSA

    </div>

</body>

</html>
