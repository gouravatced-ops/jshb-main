<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\District;
use App\Models\Division;
use App\Models\SubDivision;
use App\Models\PropertyCategory;
use App\Models\PropertyType;
use App\Models\PropertyMainType;
use App\Models\QuarterType;
use App\Models\Scheme;
use App\Models\State;
use App\Models\Allottee;

if (!function_exists('getDivisions')) {
    function getDivisions()
    {
        return Division::where('status', 1)
            ->orderBy('name', 'asc')
            ->get();
    }
}

if (!function_exists('getPropertyCategory')) {
    function getPropertyCategory()
    {
        return PropertyCategory::where('status', 1)
            ->get();
    }
}

if (!function_exists('getSubDivisions')) {
    function getSubDivisions($divisionId)
    {
        $id = decryptId($divisionId);
        return SubDivision::where('division_id', $id)
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();
    }
}

if (!function_exists('getSubDivisionById')) {
    function getSubDivisionById($subDivisionId)
    {
        return SubDivision::where('id', $subDivisionId)
            ->where('status', 1)->first();
    }
}

if (!function_exists('getQuarterType')) {
    function getQuarterType()
    {
        return QuarterType::where('status', 1)
            ->get();
    }
}

if (!function_exists('getSchemeName')) {
    function getSchemeName($schemeId)
    {
        return Scheme::where('id', $schemeId)
            ->value('scheme_name');
    }
}

if (!function_exists('getPropertyType')) {
    function getPropertyType($category_id)
    {
        $id = decryptId($category_id);
        return PropertyType::where('category_id', $id)
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();
    }
}

if (!function_exists('getPropertySubType')) {
    function getPropertySubType($typeId)
    {
        $id = decryptId($typeId);
        return PropertyMainType::where('ptype_id', $id)
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();
    }
}

if (!function_exists('getDistrictsByStateId')) {
    function getDistrictsByStateId($stateId)
    {
        return District::where('state_id', $stateId)->get();
    }
}

if (!function_exists('getdistrictNameById')) {
    function getdistrictNameById($districtId)
    {
        $district = District::find($districtId);
        return $district ? $district->name_en : null;
    }
}

if (!function_exists('getDistrict')) {
    function getDistrict($stateId)
    {
        return District::where('state_id', $stateId)->get();
    }
}

if (!function_exists('getStates')) {
    function getStates()
    {
        return State::orderByRaw("
                CASE
                    WHEN name_en = 'Bihar (Now Jharkhand)' THEN 1
                    WHEN name_en = 'Jharkhand' THEN 2
                    WHEN name_en = 'Bihar' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('name_en', 'ASC')
            ->get();
    }
}

if (!function_exists('getDivisionName')) {
    function getDivisionName($divisionId)
    {
        return Division::where('id', $divisionId)->value('name');
    }
}

if (!function_exists('getDistrict')) {
    function getDistrict($stateId)
    {
        return District::where('state_id', $stateId)->get();
    }
}

if (!function_exists('getStateName')) {
    function getStateName($stateId)
    {
        return State::where('id', $stateId)->value('name_en');
    }
}

if (!function_exists('getAllotteeName')) {
    function getAllotteeName($allotteeId)
    {
        $allottee = Allottee::select('prefix', 'allottee_name', 'allottee_middle_name', 'allottee_surname')->where('id', $allotteeId)->first();
        if ($allottee) {
            return trim($allottee->prefix . ' ' . $allottee->allottee_name . ' ' . $allottee->allottee_middle_name . ' ' . $allottee->allottee_surname);
        }
        return null;
    }
}

if (!function_exists('getDistrictName')) {
    function getDistrictName($distId)
    {
        return District::where('id', $distId)->value('name_en');
    }
}

if (!function_exists('formatDateTime')) {
    function formatDateTime($date, $format = 'd/m/Y H:i A')
    {
        if (!$date) {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($date)->format($format);
        } catch (\Exception $e) {
            return '-';
        }
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'd/m/Y')
    {
        if (!$date) {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($date)->format($format);
        } catch (\Exception $e) {
            return '-';
        }
    }
}

if (!function_exists('debug')) {
    function debug($data)
    {
        echo '<pre>';
        print_r($data->toArray());
        echo '</pre>';
        die();
    }
}

if (!function_exists('encryptId')) {
    function encryptId($id)
    {
        try {
            // $encrypted = base64_decode($encryptedId);
            return Crypt::encryptString((string) $id);
        } catch (\Exception $e) {
            return $id;
        }
    }
}

if (!function_exists('decryptId')) {
    function decryptId($encryptedId)
    {
        try {
            return Crypt::decryptString($encryptedId);
            // return base64_encode($encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }
}

/**
 * Encrypt multiple model instances for URL usage
 * @param $models
 * @return mixed
 */
if (!function_exists('encryptModels')) {
    function encryptModels($models)
    {
        if (is_null($models)) {
            return null;
        }

        if ($models instanceof \Illuminate\Database\Eloquent\Collection) {
            $models->each(function ($model) {
                $model->encrypted_id = encryptId($model->id);
            });
        } elseif ($models instanceof \Illuminate\Pagination\Paginator || $models instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $models->getCollection()->each(function ($model) {
                $model->encrypted_id = encryptId($model->id);
            });
        } else {
            $models->encrypted_id = encryptId($models->id);
        }

        return $models;
    }
}

if (!function_exists('amountToWords')) {

    function amountToWords($number, $lang = 'en')
    {
        $number = round($number, 2);

        $integerPart = floor($number);
        $decimalPart = round(($number - $integerPart) * 100);

        if ($lang == 'hi') {

            $words = [
                0 => 'शून्य',
                1 => 'एक',
                2 => 'दो',
                3 => 'तीन',
                4 => 'चार',
                5 => 'पांच',
                6 => 'छह',
                7 => 'सात',
                8 => 'आठ',
                9 => 'नौ',
                10 => 'दस',
                11 => 'ग्यारह',
                12 => 'बारह',
                13 => 'तेरह',
                14 => 'चौदह',
                15 => 'पंद्रह',
                16 => 'सोलह',
                17 => 'सत्रह',
                18 => 'अठारह',
                19 => 'उन्नीस',
                20 => 'बीस',
                21 => 'इक्कीस',
                22 => 'बाईस',
                23 => 'तेईस',
                24 => 'चौबीस',
                25 => 'पच्चीस',
                26 => 'छब्बीस',
                27 => 'सत्ताईस',
                28 => 'अट्ठाईस',
                29 => 'उनतीस',
                30 => 'तीस',
                31 => 'इकतीस',
                32 => 'बत्तीस',
                33 => 'तैंतीस',
                34 => 'चौंतीस',
                35 => 'पैंतीस',
                36 => 'छत्तीस',
                37 => 'सैंतीस',
                38 => 'अड़तीस',
                39 => 'उनतालीस',
                40 => 'चालीस',
                41 => 'इकतालीस',
                42 => 'बयालीस',
                43 => 'तैंतालीस',
                44 => 'चवालीस',
                45 => 'पैंतालीस',
                46 => 'छियालीस',
                47 => 'सैंतालीस',
                48 => 'अड़तालीस',
                49 => 'उनचास',
                50 => 'पचास',
                51 => 'इक्यावन',
                52 => 'बावन',
                53 => 'तिरेपन',
                54 => 'चौवन',
                55 => 'पचपन',
                56 => 'छप्पन',
                57 => 'सत्तावन',
                58 => 'अट्ठावन',
                59 => 'उनसठ',
                60 => 'साठ',
                61 => 'इकसठ',
                62 => 'बासठ',
                63 => 'तिरसठ',
                64 => 'चौंसठ',
                65 => 'पैंसठ',
                66 => 'छियासठ',
                67 => 'सड़सठ',
                68 => 'अड़सठ',
                69 => 'उनहत्तर',
                70 => 'सत्तर',
                71 => 'इकहत्तर',
                72 => 'बहत्तर',
                73 => 'तिहत्तर',
                74 => 'चौहत्तर',
                75 => 'पचहत्तर',
                76 => 'छिहत्तर',
                77 => 'सतहत्तर',
                78 => 'अठहत्तर',
                79 => 'उन्नासी',
                80 => 'अस्सी',
                81 => 'इक्यासी',
                82 => 'बयासी',
                83 => 'तिरासी',
                84 => 'चौरासी',
                85 => 'पचासी',
                86 => 'छियासी',
                87 => 'सत्तासी',
                88 => 'अट्ठासी',
                89 => 'नवासी',
                90 => 'नब्बे',
                91 => 'इक्यानवे',
                92 => 'बानवे',
                93 => 'तिरानवे',
                94 => 'चौरानवे',
                95 => 'पचानवे',
                96 => 'छियानवे',
                97 => 'सत्तानवे',
                98 => 'अट्ठानवे',
                99 => 'निन्यानवे'
            ];
        } else {

            $words = [
                0 => 'Zero',
                1 => 'One',
                2 => 'Two',
                3 => 'Three',
                4 => 'Four',
                5 => 'Five',
                6 => 'Six',
                7 => 'Seven',
                8 => 'Eight',
                9 => 'Nine',
                10 => 'Ten',
                11 => 'Eleven',
                12 => 'Twelve',
                13 => 'Thirteen',
                14 => 'Fourteen',
                15 => 'Fifteen',
                16 => 'Sixteen',
                17 => 'Seventeen',
                18 => 'Eighteen',
                19 => 'Nineteen',
                20 => 'Twenty',
                21 => 'Twenty One',
                22 => 'Twenty Two',
                23 => 'Twenty Three',
                24 => 'Twenty Four',
                25 => 'Twenty Five',
                26 => 'Twenty Six',
                27 => 'Twenty Seven',
                28 => 'Twenty Eight',
                29 => 'Twenty Nine',
                30 => 'Thirty',
                31 => 'Thirty One',
                32 => 'Thirty Two',
                33 => 'Thirty Three',
                34 => 'Thirty Four',
                35 => 'Thirty Five',
                36 => 'Thirty Six',
                37 => 'Thirty Seven',
                38 => 'Thirty Eight',
                39 => 'Thirty Nine',
                40 => 'Forty',
                41 => 'Forty One',
                42 => 'Forty Two',
                43 => 'Forty Three',
                44 => 'Forty Four',
                45 => 'Forty Five',
                46 => 'Forty Six',
                47 => 'Forty Seven',
                48 => 'Forty Eight',
                49 => 'Forty Nine',
                50 => 'Fifty',
                51 => 'Fifty One',
                52 => 'Fifty Two',
                53 => 'Fifty Three',
                54 => 'Fifty Four',
                55 => 'Fifty Five',
                56 => 'Fifty Six',
                57 => 'Fifty Seven',
                58 => 'Fifty Eight',
                59 => 'Fifty Nine',
                60 => 'Sixty',
                61 => 'Sixty One',
                62 => 'Sixty Two',
                63 => 'Sixty Three',
                64 => 'Sixty Four',
                65 => 'Sixty Five',
                66 => 'Sixty Six',
                67 => 'Sixty Seven',
                68 => 'Sixty Eight',
                69 => 'Sixty Nine',
                70 => 'Seventy',
                71 => 'Seventy One',
                72 => 'Seventy Two',
                73 => 'Seventy Three',
                74 => 'Seventy Four',
                75 => 'Seventy Five',
                76 => 'Seventy Six',
                77 => 'Seventy Seven',
                78 => 'Seventy Eight',
                79 => 'Seventy Nine',
                80 => 'Eighty',
                81 => 'Eighty One',
                82 => 'Eighty Two',
                83 => 'Eighty Three',
                84 => 'Eighty Four',
                85 => 'Eighty Five',
                86 => 'Eighty Six',
                87 => 'Eighty Seven',
                88 => 'Eighty Eight',
                89 => 'Eighty Nine',
                90 => 'Ninety',
                91 => 'Ninety One',
                92 => 'Ninety Two',
                93 => 'Ninety Three',
                94 => 'Ninety Four',
                95 => 'Ninety Five',
                96 => 'Ninety Six',
                97 => 'Ninety Seven',
                98 => 'Ninety Eight',
                99 => 'Ninety Nine'
            ];
        }

        $digits = [
            '',
            'Hundred',
            'Thousand',
            'Lakh',
            'Crore'
        ];

        if ($lang == 'hi') {
            $digits = [
                '',
                'सौ',
                'हजार',
                'लाख',
                'करोड़'
            ];
        }

        $result = convertNumber($integerPart, $words, $digits, $lang);

        if ($lang === 'hi') {

            $result .= ' रुपये';

            if ($decimalPart > 0) {
                $result .= ' ' .
                    convertNumber($decimalPart, $words, $digits, $lang) .
                    ' पैसे';
            }

            // Final Hindi Suffix
            $result .= ' मात्र';

            // Unicode Hindi → KrutiDev
            return unicodeToKruti(trim($result));
        } else {

            $result .= ' Rupees';

            if ($decimalPart > 0) {
                $result .= ' and ' . convertNumber($decimalPart, $words, $digits, $lang) . ' Paise';
            }

            $result .= ' Only';
        }

        return trim($result);
    }
}

if (!function_exists('convertNumber')) {

    function convertNumber($number, $words, $digits, $lang = 'en')
    {
        if ($number == 0) {
            return $lang == 'hi' ? 'शून्य' : 'Zero';
        }

        $result = '';

        // Crore
        if ($number >= 10000000) {

            $result .= convertNumber(
                floor($number / 10000000),
                $words,
                $digits,
                $lang
            ) . ' ' . $digits[4] . ' ';

            $number %= 10000000;
        }

        // Lakh
        if ($number >= 100000) {

            $result .= convertNumber(
                floor($number / 100000),
                $words,
                $digits,
                $lang
            ) . ' ' . $digits[3] . ' ';

            $number %= 100000;
        }

        // Thousand
        if ($number >= 1000) {

            $result .= convertNumber(
                floor($number / 1000),
                $words,
                $digits,
                $lang
            ) . ' ' . $digits[2] . ' ';

            $number %= 1000;
        }

        // Hundred
        if ($number >= 100) {

            $result .= convertNumber(
                floor($number / 100),
                $words,
                $digits,
                $lang
            ) . ' ' . $digits[1] . ' ';

            $number %= 100;
        }

        // Direct 0–99 Mapping
        if ($number > 0) {

            $result .= $words[$number];
        }

        return trim($result);
    }
}

if (!function_exists('unicodeToKruti')) {

    function unicodeToKruti($text)
    {

        $unicode = [
            'अ',
            'आ',
            'इ',
            'ई',
            'उ',
            'ऊ',
            'ए',
            'ऐ',
            'ओ',
            'औ',

            'क',
            'ख',
            'ग',
            'घ',
            'ङ',
            'च',
            'छ',
            'ज',
            'झ',
            'ञ',
            'ट',
            'ठ',
            'ड',
            'ढ',
            'ण',
            'त',
            'थ',
            'द',
            'ध',
            'न',
            'प',
            'फ',
            'ब',
            'भ',
            'म',
            'य',
            'र',
            'ल',
            'व',
            'श',
            'ष',
            'स',
            'ह',

            'ा',
            'ि',
            'ी',
            'ु',
            'ू',
            'े',
            'ै',
            'ो',
            'ौ',
            'ं',
            'ः',
            '्',

            '०',
            '१',
            '२',
            '३',
            '४',
            '५',
            '६',
            '७',
            '८',
            '९',

            'रुपये',
            'पैसे',
            'मात्र'
        ];

        $kruti = [
            'v',
            'vk',
            'b',
            'bZ',
            'm',
            'Å',
            ',',
            ',s',
            'vks',
            'vkS',

            'd',
            '[k',
            'x',
            '?k',
            '³',
            'p',
            'N',
            't',
            '>',
            '¥',
            'V',
            'B',
            'M',
            '<',
            '.',
            'r',
            'Fk',
            'n',
            '/k',
            'u',
            'i',
            'Q',
            'c',
            'Hk',
            'e',
            ';',
            'j',
            'y',
            'o',
            '\'k',
            '"k',
            'l',
            'g',

            'k',
            'f',
            'h',
            'q',
            'w',
            's',
            'S',
            'ks',
            'kS',
            'a',
            '%',
            '~',

            '0',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',

            ':i;s',
            'iSls',
            'ek='
        ];

        $text = str_replace($unicode, $kruti, $text);

        // Fix position of 'ि'
        $text = preg_replace('/f(.)/', '$1f', $text);

        return $text;
    }
}