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
            font-weight: normal;
        }

        @font-face {
            font-family: 'KrutiDevBold';
            src: url("{{ public_path('font/KrutiDev_010b.ttf') }}") format('truetype');
        }

        body {
            font-family: 'KrutiDev';
            margin: 10px 15px;
            font-size: 15px;
            line-height: 1.25;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        p {
            margin: 0 0 6px 0;
            text-align: justify;
        }

        .indented-para {
            text-indent: 40px;
        }

        .list-item {
            display: flex;
            margin-bottom: 4px;
        }

        .list-number {
            width: 30px;
            flex-shrink: 0;
        }

        .list-content {
            text-align: justify;
        }

        .sub-list {
            padding-left: 30px;
            margin-top: 4px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <table style="margin-bottom:15px;">
        <tr>
            <td style="width:15%;">
                <img src="{{ public_path('img/jshb_logo.png') }}" style="width:70px;">
            </td>
            <td style="width:70%; text-align:center;">
                <div style="font-size:20px;">>kj[k.M ljdkj</div>
                <div style="font-size:28px; font-weight:bold; line-height:1;">>kj[k.M jkT; vkokl cksMZ</div>
                <div style="font-size:18px; line-height:1;">¼uxj fodkl ,oa vkokl foHkkx½</div>
                <div style="font-size:14px; font-family: Arial, sans-serif;">E-mail : md.jshb@gmail.com</div>
            </td>
            <td style="width:15%; text-align:right;">
                <img src="{{ public_path('img/logo.png') }}" style="width:72px;">
            </td>
        </tr>
    </table>

    <!-- TOP DETAILS -->
    <table style="margin-bottom:10px;">
        <tr>
            <td style="width:50%;">
                vkoaVu vkns’k la[;k %&
                <span style="font-size:13px; font-family: Arial, sans-serif; font-weight:bold;">
                    {{ $allottee->allotment_no ?? '-----------------------' }}
                </span>
            </td>
            <td style="width:50%; text-align:right;">
                fnukad %&
                <span style="font-size:13px; font-family: Arial, sans-serif; font-weight:bold;">
                    {{ date('d/m/Y') }}
                </span>
            </td>
        </tr>
        <tr>
            <td style="width:50%; padding-top:5px;">
                mPp vk; oxhZ; ¶ySV la[;k %&
                <span style="font-size:13px; font-family: Arial, sans-serif; font-weight:bold;">
                    {{ $allottee->property_number ?? '----------------------' }}
                </span>
            </td>
            <td style="width:50%; text-align:right; padding-top:5px;">
                dksfV %&
                <span>--------------------</span>
            </td>
        </tr>
    </table>

    <!-- PARAGRAPH 1 -->
    <p class="indented-para">
        >kj[k.M jkT; vkokl cksMZ ¼vkoklh; Hkw&lEink dk izcU/ku ,oa fuLrkj½ fofu;ekoyh 2004 ds micU/kksa ds rgr~ fnukad
        <span style="font-size:13px; font-family: Arial, sans-serif; font-weight:bold;">
            {{ $allottee->allotment_day && $allottee->allotment_month && $allottee->allotment_year
                ? "{$allottee->allotment_day}-{$allottee->allotment_month}-{$allottee->allotment_year}"
                : '------------------------' }}
        </span>
        dks cksMZ eq[;ky; ds lHkk d{k esa fudkyh xbZ ykWVjh ds vkyksd esa

        <span style="font-family: 'KrutiDevBold'; font-size:14px;">
            {{ $allottee->allottee_prefix_hindi ?? '' }} {{ trim(($allottee->allottee_name_hindi ?? '') . ' ' . ($allottee->allottee_middle_hindi ?? '') . ' ' . ($allottee->allottee_surname_hindi ?? '')) }}<span style="font-family: Arial, sans-serif;">,</span>
            {{ $allottee->relation_prefix_hindi ?? '' }} {{ $allottee->relation_name_hindi ?? '' }}<span style="font-family: Arial, sans-serif;">,</span>
            irk- {{ optional($allottee->alloteeAdresses)->present_address_hindi ?? '' }}<span style="font-family: Arial, sans-serif;">,</span>
            iks0- {{ optional($allottee->alloteeAdresses)->present_post_office_hindi ?? '' }}<span style="font-family: Arial, sans-serif;">,</span>
            Fkkuk- {{ optional($allottee->alloteeAdresses)->present_police_station_hindi ?? '' }}<span style="font-family: Arial, sans-serif;">,</span>
            ftyk-{{ unicodeToKruti(optional(optional($allottee->alloteeAdresses)->presentDistrict)->name_hi ?? '') }}<span style="font-family: Arial, sans-serif;">,</span>
            >kj[k.M<span style="font-family: Arial, sans-serif;">,</span>
        </span>
        fiu dksM<span style="font-family: Arial, sans-serif;">-</span>{{ optional($allottee->alloteeAdresses)->present_pincode ?? '' }}<span style="font-family: Arial, sans-serif;">,</span>

        eks0 %
        <span style="font-size:13px; font-family: Arial, sans-serif; font-weight:bold;">
            {{ optional($allottee->alloteeAdresses)->mobile_number ?? '---------------------' }}
        </span>
        vkosnu la[;k&
        <span style="font-size:13px; font-family: Arial, sans-serif; font-weight:bold;">
            {{ $allottee->application_no ?? '---------------------------' }}
        </span>
        dks gjew] jk¡ph fLFkr mPp vk; oxhZ; ¶ySV
        <span>-------------------------</span>
        esa ¶ySV la[;k&
        <span style="font-size:13px; font-family: Arial, sans-serif; font-weight:bold;">
            {{ $allottee->property_number ?? '----------------------' }}
        </span>
        ¼dkj ikfdZax lfgr½ vkoafVr djus gsrq p;u gqvk gS] ftldk {ks=kQy 1200 oxZQhV gS vkSj pkSgn~nh fuEufyf[kr gS %&
    </p>

    <!-- DIRECTION 1 -->
    <table style="margin-left:40px; margin-bottom:5px; width:80%;">
        <tr>
            <td style="width:50%;">mRrj &nbsp;&nbsp;&nbsp; ----------------------------------</td>
            <td style="width:50%;">nf{k.k &nbsp;&nbsp;&nbsp; -----------------------------------</td>
        </tr>
        <tr>
            <td>iwjc &nbsp;&nbsp;&nbsp; ----------------------------------</td>
            <td>if’pe &nbsp;&nbsp;&nbsp; ----------------------------------</td>
        </tr>
    </table>

    <p style="margin-bottom:2px;">Hkwtk,Wa %&</p>
    <table style="margin-left:40px; margin-bottom:10px; width:85%;">
        <tr>
            <td style="width:60%;">iwjc ls if’pe mÙkj rjQ</td>
            <td>-----------------------------------------</td>
            <td>QhV</td>
        </tr>
        <tr>
            <td>iwjc ls if’pe nf{k.k rjQ</td>
            <td>-----------------------------------------</td>
            <td>QhV</td>
        </tr>
        <tr>
            <td>mÙkj ls nf{k.k iwjc rjQ</td>
            <td>-----------------------------------------</td>
            <td>QhV</td>
        </tr>
        <tr>
            <td>mÙkj ls nf{k.k if’pe rjQ</td>
            <td>-----------------------------------------</td>
            <td>QhV</td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">2-</td>
            <td style="text-align:justify;">
                mDr ¶ySV vkSj Hkw[k.M dk mij vfdar Hkqtkvksa dh eki ds lkFk tSlk gS] tgkWa gS <span style="font-size:12px; font-family: Arial, sans-serif;">¼AS IS WHERE IS½</span> dh ’krZ ij vkoafVr fd;k tk jgk gSA blds vkdkj ;k fLFkfr ds ckjs esa fdlh izdkj dk nkok ;k f’kdk;r cksMZ dks ekU; ugha gksxkA ;fn tehu ds {ks=kQy esa okLrfod ukih ij deh ;k c<+ksÙkjh ik;h tk;sxh rks tehu dh dher esa vko’;d la’kks/ku fd;k tk;sxkA bl lEcU/k esa cksMZ dk fu.kZ; vafre vkSj loZekU; gksxkA
                    </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">3-</td>
            <td style="text-align:justify;">
                @php
                $financials = $allottee->schemeFinance ?? null;
                $propertyTotalCost = $financials->property_total_cost ?? 0;
                $lotteryAmount = $financials->lottery_amount ?? 0;
                $allotmentAmount = $financials->allotement_amount ?? 0;
                $balanceAmount = $financials->balance_amount ?? 0;
                $emiCount = $financials->emi_count ?? 120;
                $emiWithoutPenalty = $financials->emi_without_penalty ?? 0;
                $emiWithPenalty = $financials->emi_with_penalty ?? 0;
                @endphp
                ¶ySV dh varfje dher <span style="font-family: Arial, sans-serif; font-size: 13px;">{{ number_format($propertyTotalCost, 2) }}</span> :i;s ¼{{ trim(str_replace('jqi;s ekr~j', '', amountToWords($propertyTotalCost, 'hi'))) }}½ :i;s ek=k fnukad {{ date('d-m-Y') }} rd vkWadh xbZ gSA tehu ds vtZu rFkk fodkl [kpZ vkSj@vFkok ¶ySV ds fuekZ.k ij gq, [kpZ ds iquewZY;kadu@lwn dh nj esa c<+ksÙkjh@ekuoh; Hkwy@ewY;kadu esa c<+ksRrjh ds QyLo:i ;fn dher c<+sxh rks vfrfjDr dher cksMZ }kjk fu/kkZfjr le; ds vUnj vkoaVh dks tek djuh gksxh vkSj ewY;kadu esa deh gksus ij cksMZ }kjk vfrfjDr jkf’k lkeaftr ;k okil dj nh tk;sxhA ¶ySV dh dher ds lEcU/k esa cksMZ dk fu.kZ; vfUre vkSj loZekU; gksxk rFkk cksMZ blds fy;s dksbZ vkWadM+k ;k ys[kk ugha nsxkA
                    </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">4-</td>
            <td style="text-align:justify;">
                bl vkns’k ds fuxZr gksus dh frfFk ls 30¼rhl½ fnuksa ds vUnj ,djkjukek ds iwoZ ns; jkf’k dk fooj.k fuEuor gS %&

                <table style="width:90%; margin-top:5px; margin-left:15px;">
                    <tr>
                        <td valign="top" style="width:30px;">¼d½</td>
                        <td>¶ySV dh varfje <br>dher dk 25¼iPphl½ izfr’kr</td>
                        <td valign="bottom">:0-</td>
                        <td valign="bottom" style="font-family: Arial, sans-serif; font-size: 13px;">{{ number_format($lotteryAmount + $allotmentAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <td valign="top">¼[k½</td>
                        <td>vkoaVh }kjk vkosnu i=k ds <br>lkFk tek dh x;h jkf’k ¼&½</td>
                        <td valign="bottom">:0-</td>
                        <td valign="bottom" style="font-family: Arial, sans-serif; font-size: 13px;">{{ number_format($lotteryAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td style="border-top:1px solid #000; border-bottom:1px solid #000;">:0-</td>
                        <td style="border-top:1px solid #000; border-bottom:1px solid #000; font-family: Arial, sans-serif; font-size: 13px;">{{ number_format($allotmentAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <td valign="top">¼x½</td>
                        <td>fof/k ,oa vfHkys[ku ’kqYd ¼$½</td>
                        <td valign="bottom">:0-</td>
                        <td valign="bottom" style="font-family: Arial, sans-serif; font-size: 13px;">300.00</td>
                    </tr>
                    <tr>
                        <td valign="top">¼?k½</td>
                        <td>dqy Hkqxrs; jkf’k</td>
                        <td valign="bottom">:0-</td>
                        <td valign="bottom" style="font-family: Arial, sans-serif; font-size: 13px; font-weight:bold;">{{ number_format($allotmentAmount + 300, 2) }}</td>
                    </tr>
                </table>

                <div style="margin-top:8px; text-indent:40px;">
                    lHkh izdkj ds Hkqxrku ^>kj[k.M jkT; vkol cksMZ* ds i{k esa bafM;u cSad] gjew ’kk[kk] jk¡ph esa Hkqxrs; gksxkA
                </div>
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">5-</td>
            <td style="text-align:justify;">
                ¶ySV ds dher dk 25¼iPphl½ izfr’kr jkf’k vFkkZr~ <span style="font-family: Arial, sans-serif; font-size: 13px;">{{ number_format($lotteryAmount + $allotmentAmount, 2) }}</span> :i;k dk lek;kstu ¶ySV dh varfje dher <span style="font-family: Arial, sans-serif; font-size: 13px;">{{ number_format($propertyTotalCost, 2) }}</span> :i;s esa djus ds i’pkr~ ’ks"k jkf’k <span style="font-family: Arial, sans-serif; font-size: 13px;">{{ number_format($balanceAmount, 2) }}</span> :i;s dk Hkqxrku <span style="font-family: Arial, sans-serif; font-size: 13px;">{{ $emiCount }}</span>¼{{ trim(str_replace('jqi;s ekr~j', '', amountToWords($emiCount, 'hi'))) }}½ leku ekfld fdLrksa esa fuEu izdkj ls djuk gksxkA fdLrksa dk Hkqxrku ekg twu 2025 ls izkjEHk gksxkA

                <div class="sub-list">
                    <table style="width:100%; margin-bottom:4px;">
                        <tr>
                            <td valign="top" style="width:30px;">¼d½</td>
                            <td style="text-align:justify;">eghus dh lkroha frfFk rd Hkqxrku djus ij <span style="font-family: Arial, sans-serif; font-size: 13px;">13.5%</span> pdzo`f) C;kt lfgr <span style="font-family: Arial, sans-serif; font-size: 13px;">{{ number_format($emiWithoutPenalty, 2) }}</span> ¼{{ trim(str_replace('jqi;s ekr~j', '', amountToWords($emiWithoutPenalty, 'hi'))) }}½ :i;s ek=kA</td>
                        </tr>
                    </table>
                    <table style="width:100%; margin-bottom:4px;">
                        <tr>
                            <td valign="top" style="width:30px;">¼[k½</td>
                            <td style="text-align:justify;">eghus dh lkroha frfFk ds ckn Hkqxrku djus ij <span style="font-family: Arial, sans-serif; font-size: 13px;">2.5</span> izfr’kr vfrfjDr naM C;kt lfgr vFkkZr <span style="font-family: Arial, sans-serif; font-size: 13px;">16%</span> pdzo`f) C;kt lfgr <span style="font-family: Arial, sans-serif; font-size: 13px;">{{ number_format($emiWithPenalty, 2) }}</span> ¼{{ trim(str_replace('jqi;s ekr~j', '', amountToWords($emiWithPenalty, 'hi'))) }}½ :i;s ek=kA vkoaVh ds fcyEc ls Hkqxrku djus ij cksMZ }kjk fu/kkZfjr vU; fcyEc ’kqYd tSls <span style="font-family: Arial, sans-serif; font-size: 13px;">1%</span> n.M lwn izfr ekg izfr foyfEcr fdLr foyfEcr vof/k rd ,oa iz’kklfud ’kqYd foyfEcr fdLr ds fy, izfrekg fuEu izdkj ls Hkh ns; gksxk %&</td>
                        </tr>
                    </table>

                    <table style="width:90%; margin-left:30px; margin-bottom:4px;">
                        <tr>
                            <td style="width:40px;">¼i½</td>
                            <td>mPp@e/;e vk; oxhZ; edku@¶ySV ds fy,</td>
                            <td>&nbsp; &amp; &nbsp; <span style="font-family: Arial, sans-serif; font-size: 13px;">10/-</span> :0</td>
                        </tr>
                        <tr>
                            <td>¼ii½</td>
                            <td>vYi vk; oxhZ; edku@¶ySV ds fy,</td>
                            <td>&nbsp; &amp; &nbsp; <span style="font-family: Arial, sans-serif; font-size: 13px;">5/-</span> :0</td>
                        </tr>
                        <tr>
                            <td>¼iii½</td>
                            <td>vkfFkZd n`f"V ls detksj oxZ ds edku@¶ySV fy,</td>
                            <td>&nbsp; &amp; &nbsp; <span style="font-family: Arial, sans-serif; font-size: 13px;">2/-</span> :0</td>
                        </tr>
                    </table>
                </div>

                <div style="text-indent:40px;">
                    vkoaVh pkgsa rks ,d eqLr esa vfxze fdLrksa dh vnk;xh Hkh dj ldrs gSaA ,slk djus ij mUgsa HkkM+k&lg&dz; dh ’ks"k vof/k ds fy;s lwn dk Hkqxrku ugha djuk gksxkA
                </div>
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">6-</td>
            <td style="text-align:justify;">
                vkoaVh dks ;g i=k fuxZr gksus ds rhl fnuksa ds vUnj dafMdk 4 esa of.kZr jkf’k dk Hkqxrku djuk gksxk rFkk rhl fnuksa ds vUnj lEcfU/kr ize.Myh; dk;kZy; }kjk iznRr ,djkjukek dk fu"iknu djuk gksxkA fu/kZkfjr 30 fnuksa dh vof/k ds vUnj vFkkZr fnukad {{ now()->addDays(30)->format('d-m-Y') }} rd mi;qZDr jkf’k tek dj ,djkjukek fu"ikfnr ugha fd;s tkus ij ;g le>k tk;sxk fd vkoaVh dks edku@¶ySV dh vko’;drk ugha gS vkSj ;g vkoaVu vkns’k Lor% jn~n le>k tk;sxkA
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">7-</td>
            <td style="text-align:justify;">
                ,djkjukek fuca/ku [kpZ vkoaVh dks ogu djuk gksxkA ,djkjukek ds vxys ekg i’pkr~ fofgr ’krksZa ,oa cU/kstksa ds vUrxZr vUrxZr edku@¶ySV dk dCtk fn;k tk ldsxkA
                <div class="sub-list">
                    <table style="width:100%;">
                        <tr>
                            <td valign="top" style="width:30px;">¼d½</td>
                            <td style="text-align:justify;">vkoaVu vkns’k fuxZr ds ,d ekg ds vUnj ,djkjukek dk fu"iknu ;FkklEHko djk ysuk gksxk vU;Fkk vxys ekg ls foyfEcr ’kqYd v|ru x.kuk ds i’pkr~ gh ,djkjukek fd;k tk;sxk ,oa vkoaVu vkns’k rFkk ,djkjukek ds chp ds vof/k ij cksMZ }kjk fu/kkZfjr C;kt dh jkf’k vkoaVh dks Hkqxrku djuk gksxkA</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">8-</td>
            <td style="text-align:justify;">
                fdlh dkj.ko’k edku@¶ySV ij dCtk nsus esa dqN foyEc gks ldrk gSA ,Slh fLFkfr esa Hkh vkoaVh dks fd’rksa dk Hkqxrku fu;fer :i ls djuk gksxkA ;fn vkoaVh }kjk fu/kkZfjr vof/k ds vUnj n[ky dCtk ugha fy;k x;k rks ,slh fLFkfr esa Hkh vkoaVu ,oa ,djkjukek jí fd;k tk;sxkA
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">9-</td>
            <td style="text-align:justify;">
                fu;fer :i ls fdLrksa dk Hkqxrku ugha djus ij vkoaVu Lor% jn~n le>k tk;sxk rFkk dqy tek jkf’k dk 25¼iphl½ izfr’kr dVkSrh dj ’ks"k jkf’k okil dj nh tk;sxhA
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">10-</td>
            <td style="text-align:justify;">
                mi;qZDr edku@Q~ySV dsoy vkoklh; mi;ksx ds fy, vkoafVr fd;k tk jgk gS ftls fdlh Hkh ifjfLFkfr esa O;olkf;d mi;ksx ugha fd;k tk ldsxkA ;fn fdlh Hkh le; ;g ik;k x;k fd vkoafVr edku@¶ySV dk mi;ksx O;olkf;d vFkok vU; dk;Z ds fy, gks jgk gks rks ,slh ifjfLFkfr esa vkidk vkoaVu jí djrs gq, dafMdk 9 ds rgr vkidh jkf’k okil dj nh tk;sxhA
                <div class="sub-list">
                    <table style="width:100%;">
                        <tr>
                            <td valign="top" style="width:30px;">¼d½</td>
                            <td style="text-align:justify;">vkoafVr edku@¶ySV dsoy vkoklh; mi;ksx gsrq fd;k x;k gS] ;fn mDr lEink ij O;olkf;d mi;ksx fd;k x;k gks rks oSls ifjfLFkfr esa cksMZ }kjk O;olkf;d nj ij n[ky dCtk dh frfFk ls O;olkf;d mi;ksx ds cUn gksus rd olwyh dh tk;sxh ftldk Hkqxrku vkoaVh dks fdlh Hkh lwjr esa djuk iMs+xk vU;Fkk vkoaVu jí dj fn;k tk;sxkA</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">11-</td>
            <td style="text-align:justify;">
                izca/k funs’kd ;k muds }kjk izkf/kd`r inkf/kdkjh dh fyf[kr vuqefr ds fcuk vkoafVr edku@¶ySV dks foHkkftr djus] fdlh vU; Hkw[k.M ;k edku ds lkFk mls feykus ;k dksbZ fuekZ.kRed ifjoZRru@ifjo/kZu djus dk gd ugha gksxkA vU;Fkk >kj[k.M jkT; vkokl cksMZ vf/kfu;e dh /kkjk&78 ds rgr dkuwuh dkjZokbZ dh tk;sxhA
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">12-</td>
            <td style="text-align:justify;">
                izcU/k funs’kd ;k muds }kjk izkf/kd`r inkf/kdkjh dh fyf[kr lgefr ds fcuk vkidk edku@¶ySV ij iw.kZr% ;k vkaf’kd :i ls viuk n[ky dCtk gLrkarfjr djus] lkSaius ;k mls fdlh vU; izdkj ls R;kxus dk gd ugha gksxkA izcU/k funs’kd viuh LosPNk ls bldh fyf[kr lgefr nsus ls budkj Hkh dj ldrs gSaA
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">13-</td>
            <td style="text-align:justify;">
                ;fn dksbZ vkoaVh dk edku@¶ySV ds vkoaVu ds ckn vkoafVr edku@¶ySV ij dCtk ysus ds iwoZ e`R;q gks tkrh gS rks vkoaVh }kjk vfxze ;k tekur ds :i esa tek dh x;h jkf’k mlds mrjkf/kdkjh dks cksMZ dh izfdz;k ds rgr okil dj nh tk;sxhA vkoaVh ds mRrjkf/kdkjh }kjk vius uke ls edku@¶ySV vkoafVr djus ds fy, vuqjks/k fd, tkus ij cksMZ ds rRle; izo`r fu;e rFkk fofu;e ds v/khu izca/k funs’kd Lofoosd <span style="font-family: Arial, sans-serif; font-size: 13px;">(Discretion)</span> ls lE;d fu.kZ; ys ldsaxsA
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">14-</td>
            <td style="text-align:justify;">
                ;fn fdlh vkoaVh dh edku@¶ySV ij dCtk ysus ds i’pkr~ e`R;q gks tkrh gS vkSj ns; fd’r rFkk vU; jkf’k dk Hkqxrku fu;fer :i ls dj fn;k x;k gS rks mlds mRrjkf/kdkjh }kjk vius uke ls edku@¶ySV ds ukekarj.k ds fy, vuqjks/k fd, tkus ij cksMZ ds rRle; izo`r fu;eksa ,oa fofu;eksa ds v/khu izca/k funs’kd Lofoosd <span style="font-family: Arial, sans-serif; font-size: 13px;">(Discretion)</span> ls okafNr dkxtkr ds voyksduksijkUr ukekarj.k dk fu.kZ; ys ldsaxsA ,slk fu.kZ; djus ds igys vkoaVh dks bl vk’k; dk fyf[kr izfrKk i=k nsuk gksxk fd og ewy vkoaVh ds mij yxs izfrcU/k vkSj ,djkjukek dh lHkh ’krksZ ,oa cU/kstksaa dk vuqikyu djsxkA
                <div class="sub-list">
                    <table style="width:100%;">
                        <tr>
                            <td valign="top" style="width:30px;">¼d½</td>
                            <td style="text-align:justify;">vkoafVr lEink dk gLrkUrj.k vkoaVh }kjk fdlh vU; O;fä dks izca/k funs’kd ds vuqefr ds fcuk ugha fd;k tk ldsxkA</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">15-</td>
            <td style="text-align:justify;">
                cksMZ dks Hkqxrs; fdlh cdk;k jkf’k dh olwyh yksd ekWax ds :i esa fcgkj ,.M mM+hlk ifCyd fMekUM~l fjdHkjh ,sDV] <span style="font-family: Arial, sans-serif; font-size: 13px;">1914</span> ds vUrxZr dh tk;sxhA
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">16-</td>
            <td style="text-align:justify;">
                ,djkjukek esa fufgr fdlh ’krZ dk mYy?kau gksus ij vkoaVu vkns’k jn~n le>k tk;sxkA
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">17-</td>
            <td style="text-align:justify;">
                edku@¶ySV dh iwjh dher ¼C;kt lfgr½ rFkk vU; jkf’k dk lEiw.kZ Hkqxrku dj nsus ij rFkk bldh lEiqf"V gks tkus ij cksMZ }kjk fu/kkZfjr ’krksZa ,oa ca/kstksa ij Hkwfe rFkk lEifr ds LokfeRo dk gLrkUrj.k LFkk;h iV~Vk i)fr ds vk/kkj ij fd;k tk;sxkA
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">18-</td>
            <td style="text-align:justify;">
                edku@¶ySV dh dher ds vfrfjDr vkoaVh }kjk fuEufyf[kr nj ls tehu dk yxku <span style="font-family: Arial, sans-serif; font-size: 13px;">(Ground Rent)</span> ns; gksxkA cksMZ gj rhl o"kZ ij tehu ds yxku dks iqujhf{kr dj ldsxkA

                <table style="width:90%; margin-left:30px; margin-top:5px; margin-bottom:4px;">
                    <tr>
                        <td style="width:40px;">¼d½</td>
                        <td>mPp vk; oxhZ; edku@¶ySV ds fy,</td>
                        <td>&nbsp; &amp; &nbsp; <span style="font-family: Arial, sans-serif; font-size: 13px;">250/-</span> :0 izfro"kZ izfr bdkbZ</td>
                    </tr>
                    <tr>
                        <td>¼[k½</td>
                        <td>e/;e vk; oxhZ; edku@¶ySV ds fy,</td>
                        <td>&nbsp; &amp; &nbsp; <span style="font-family: Arial, sans-serif; font-size: 13px;">150/-</span> :0 izfro"kZ izfr bdkbZ</td>
                    </tr>
                    <tr>
                        <td>¼x½</td>
                        <td>vYi vk; oxhZ; edku@¶ySV ds fy,</td>
                        <td>&nbsp; &amp; &nbsp; <span style="font-family: Arial, sans-serif; font-size: 13px;">100/-</span> :0 izfro"kZ izfr bdkbZ</td>
                    </tr>
                    <tr>
                        <td valign="top">¼?k½</td>
                        <td>vkfFkZd n`f"V ls detksj vk; oxZ ds<br>edku@¶ySV ds fy,</td>
                        <td valign="top">&nbsp; &amp; &nbsp; <span style="font-family: Arial, sans-serif; font-size: 13px;">50/-</span> :0 izfro"kZ izfr bdkbZ</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">19-</td>
            <td style="text-align:justify;">
                uxj fuxe] uxjikfydk@vf/klwfpr {ks=k lfefr@ljdkjh foHkkx@vU; laLFkku dks ns; dj] mi dj vkfn dk Hkqxrku vkoaVh dks n[ky&dCts dh frfFk ls djuk gksxkA
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">20-</td>
            <td style="text-align:justify;">
                >kj[k.M jkT; vkokl cksMZ }kjk cuk;s lHkh fu;e] fofu;e] ftldk Li"Vhdj.k >kj[k.M jkT; vkokl cksMZ ¼vkoklh; Hkw&laEink dk izcU/ku ,oa fuLrkj½ fofu;ekoyh <span style="font-family: Arial, sans-serif; font-size: 13px;">2004</span> rFkk cksMZ }kjk le;&le; ij tkjh fd;s dk;kZYk; vkns’kksa esa fd;k x;k gS] vkoaVh ij iw.kZr% ykxw le>s tk;saxsA
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td valign="top" style="width:25px;">21-</td>
            <td style="text-align:justify;">
                vkoaVh ;k muds mÙkjkf/kdkjh vFkok fcdzh gLrkUr.k ls lacaf/kr fdlh Hkh okn@fookn ftlesa cksMZ dks izfroknh cuk;k x;k gks] mlij gksusokys fof/k ij O;; dh olwyh vkoaVh@oknh ls fd;k tk;sxkA
            </td>
        </tr>
    </table>


    <div style="page-break-before: always;">

        <div style="margin-top:40px; text-align:right; margin-right:40px; font-size:18px;">
            <strong>Hkw&lEink inkf/kdkjh</strong>
        </div>
        <!-- MEMO SECTION 1 -->
        <table style="width:100%; margin-top:20px;">
            <tr>
                <td style="width:50%;">Kki la[;k ----------------------------</td>
                <td style="width:50%; text-align:right;">jkWaph] fnukad -------------------------------</td>
            </tr>
        </table>

        <div style="margin-top:10px; padding-left:40px; text-align:justify;">
            <strong>izfrfyfi %</strong>
            <span style="font-family: 'KrutiDevBold'; font-size:14px;">
                {{ $allottee->allottee_prefix_hindi ?? '' }} {{ trim(($allottee->allottee_name_hindi ?? '') . ' ' . ($allottee->allottee_middle_hindi ?? '') . ' ' . ($allottee->allottee_surname_hindi ?? '')) }}<span style="font-family: Arial, sans-serif;">,</span>
                {{ $allottee->relation_prefix_hindi ?? '' }} {{ $allottee->relation_name_hindi ?? '' }}<span style="font-family: Arial, sans-serif;">,</span>
                irk- {{ optional($allottee->alloteeAdresses)->present_address_hindi ?? '' }}<span style="font-family: Arial, sans-serif;">,</span>
                iks0- {{ optional($allottee->alloteeAdresses)->present_post_office_hindi ?? '' }}<span style="font-family: Arial, sans-serif;">,</span>
                Fkkuk- {{ optional($allottee->alloteeAdresses)->present_police_station_hindi ?? '' }}<span style="font-family: Arial, sans-serif;">,</span>
                ftyk-{{ unicodeToKruti(optional(optional($allottee->alloteeAdresses)->presentDistrict)->name_hi ?? '') }}<span style="font-family: Arial, sans-serif;">,</span>
                >kj[k.M<span style="font-family: Arial, sans-serif;">,</span>
            </span>
            fiu dksM<span style="font-family: Arial, sans-serif;">-</span>{{ optional($allottee->alloteeAdresses)->present_pincode ?? '' }}<span style="font-family: Arial, sans-serif;">,</span>
            eks0- {{ optional($allottee->alloteeAdresses)->mobile_number ?? '' }}
            dks lwpukFkZ ,oa vko’;d dkjZokbZ gsrq izsf"krA
        </div>

        <div style="margin-top:40px; text-align:right; margin-right:40px; font-size:18px;">
            <strong>Hkw&lEink inkf/kdkjh</strong>
        </div>

        <!-- MEMO SECTION 2 -->
        <table style="width:100%; margin-top:20px;">
            <tr>
                <td style="width:50%;">Kki la[;k ----------------------------</td>
                <td style="width:50%; text-align:right;">jkWaph] fnukad --------------------------------</td>
            </tr>
        </table>

        <div style="margin-top:10px; padding-left:40px; text-align:justify;">
            <strong>izfrfyfi %</strong> dk;Zikyd vfHk;Urk] >kj[k.M jkT; vkokl cksMZ]
            <span style="font-size:13px; font-family: Arial, sans-serif; font-weight:bold;">
                {{ optional($allottee->division)->division_name ?? '..................' }}
            </span>
            ize.My dks lwpukFkZ ,oa vko’;d dkjZokbZ gsrq vxzlkfjrA
        </div>

        <div style="margin-top:40px; text-align:right; margin-right:40px; font-size:18px;">
            <strong>Hkw&lEink inkf/kdkjh</strong>
        </div>
    </div>

</body>

</html>
