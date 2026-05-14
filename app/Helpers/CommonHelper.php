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
        return DB::table('districts')->where('state_id', $stateId)->get();
    }
}

if (!function_exists('getStates')) {
    function getStates()
    {
        return DB::table('states')
            ->orderByRaw("
                CASE 
                    WHEN name_en = 'Bihar (Now Jharkhand)' THEN 1
                    WHEN name_en = 'Jharkhand' THEN 2
                    WHEN name_en = 'Bihar' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('name_en', 'ASC') // optional: sort remaining states
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
        return DB::table('districts')->where('state_id', $stateId)->get();
    }
}

if (!function_exists('getStateName')) {
    function getStateName($stateId)
    {
        return DB::table('states')->where('id', $stateId)->value('name_en');
    }
}

if (!function_exists('getAllotteeName')) {
    function getAllotteeName($allotteeId)
    {
        $allottee = DB::table('allottees')->select('prefix', 'allottee_name', 'allottee_middle_name', 'allottee_surname')->where('id', $allotteeId)->first();
        if ($allottee) {
            return trim($allottee->prefix . ' ' . $allottee->allottee_name . ' ' . $allottee->allottee_middle_name . ' ' . $allottee->allottee_surname);
        }
        return null;
    }
}

if (!function_exists('getDistrictName')) {
    function getDistrictName($distId)
    {
        return DB::table('districts')->where('id', $distId)->value('name_en');
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

if (!function_exists('getDebugIndex')) {
    function getDebugIndex($data)
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
