<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class CommonController extends Controller
{
    public function getDivision($division)
    {
        return response()->json(getSubDivisions($division));
    }

    public function getPropertyType($category)
    {
        return response()->json(getPropertyType($category));
    }

    public function getPropertySubType($typeId)
    {
        return response()->json(getPropertySubType($typeId));
    }

    public function getDistrict($stateId)
    {
        return response()->json(getDistrict($stateId));
    }

    public function getSchemeList(Request $request)
    {
        $divisionId = decryptId($request->division_id);
        $subDivisionId = decryptId($request->subdivision_id);
        $pcategoryId = decryptId($request->pcategory_id);
        $propertyTypeId = decryptId($request->property_type_id);
        $propertySubTypeId = decryptId($request->property_sub_type_id);
        $quaterId = decryptId($request->quarter_id);
        // return [$divisionId , $subDivisionId , $pcategoryId , $propertyTypeId , $propertySubTypeId, $quaterId];
        $schemeList = Scheme::query()
            ->where([
                'is_active'       => 1,
                'division_id'     => $divisionId,
                'sub_division_id' => $subDivisionId,
                'pcategory_id'    => $pcategoryId,
                'p_type_id'       => $propertyTypeId,
                'quarter_type_id' => $quaterId,
            ])
            ->when(
                !empty($propertySubTypeId),
                fn($query) =>
                $query->where(
                    'p_sub_type_id',
                    $propertySubTypeId
                )
            )
            ->orderBy('scheme_name')
            ->get();

        return response()->json($schemeList);
    }

    public function getSchemeDetails($id)
    {
        $scheme = Scheme::with('financial')->find($id);

        if (!$scheme) {
            return response()->json([
                'status' => false,
                'message' => 'Scheme not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $scheme->id,
                'scheme_name' => $scheme->scheme_name,
                'scheme_code' => $scheme->scheme_code,
                'lottery_amount' => $scheme->financial->lottery_amount,
            ]
        ]);
    }
}