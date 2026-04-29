<?php
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use App\Models\District;

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

if (!function_exists('getDebugIndex')) {
    function getDebugIndex($data)
    {
        echo '<pre>';
        print_r($data->toArray());
        echo '</pre>';
        die();
    }
}

/**
 * Encrypt an ID for use in URLs
 * @param int|string $id
 * @return string
 */
if (!function_exists('encryptId')) {
    function encryptId($id)
    {
        try {
            $encrypted = Crypt::encrypt($id);
            return base64_encode($encrypted);
        } catch (\Exception $e) {
            return $id;
        }
    }
}

/**
 * Decrypt an ID from URL
 * @param string $encryptedId
 * @return int|string|null
 */
if (!function_exists('decryptId')) {
    function decryptId($encryptedId)
    {
        try {
            $encrypted = base64_decode($encryptedId);
            return Crypt::decrypt($encrypted);
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