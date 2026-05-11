<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allottee;
use App\Models\Division;
use App\Models\Scheme;
use App\Models\AllotteesContactDetail;
use App\Models\RegistrationFile;
use App\Models\RegisterAllottee;
use App\Models\AllotteeMasterDocument;
use App\Models\SubDivision;
use App\Models\StepSkip;
use App\Models\AllotteePropertyFinDetail;
use App\Models\AllotteeNomineeBankDetail;
use App\Models\AllotteeEmiLedger;
use App\Models\AllotteeDocument;
use App\Models\AllotteeStepDuration;
use App\Models\DocumentMaster;
use App\Models\QuarterType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AllotteeController extends Controller
{
    public function index()
    {
        $allottees = Allottee::with('division')->latest()->paginate(10);
        return view('admin.allottee.index', compact('allottees'));
    }

    public function indexStart()
    {
        return view('admin.allottee.index');
    }

    // In your controller
    public function getStep($step, $applicantId = null)
    {
        $view = "admin.allottee.step{$step}";
        $baseRelations = [
            'division',
            'subDivision',
            'propertyCategory',
            'propertyType',
        ];
        // STEP 2
        if ($step == 2) {

            $applicant = AllotteesContactDetail::where('allottee_id', $applicantId)->first();

            if ($applicant) {

                $relationMap = [
                    'father'  => 'पिता',
                    'husband' => 'पति'
                ];

                $applicant->relation_type_hindi = $relationMap[$applicant->relation_type] ?? null;

                $districtFields = [
                    'relation_district',
                    'present_district',
                    'permanent_district',
                    'correspondence_district'
                ];

                foreach ($districtFields as $field) {
                    $applicant->{$field . '_hindi'} = $applicant->$field ?? '';
                }

                $applicant->id = $applicant->allottee_id;

                return view($view, compact('applicant'));
            }
            $applicant = Allottee::with($baseRelations)->findOrFail($applicantId);
            return view($view, compact('applicant'));
        }

        // DEFAULT (STEP 1)
        $applicant = Allottee::with($baseRelations)->findOrFail($applicantId);

        $getSchemeList = Scheme::Select('scheme_code','scheme_name')->where('id', $applicant->id)->first();
        return view($view, compact('applicant', 'getSchemeList'));
    }

    public function create()
    {
        $divisions = Division::where('status', 1)->get();
        $allottee = Allottee::where('id', 1)->first();
        return view('admin.allottee.add', compact('allottee', 'divisions'));
    }

    private function generateUniqueUsername($division, $incomeTypeId, $subDivision, $date)
    {
        $divisionCode = Division::where('id', $division)->value('division_code');
        $subDivisionCode = SubDivision::where('id', $subDivision)->value('subdivision_code');
        $incomeCode = QuarterType::where('quarter_id', $incomeTypeId)->value('quarter_code');
        $code = preg_replace('/[^A-Za-z]/', '', $incomeCode);
        $quarterCode = strtoupper(substr($code, 0, 2));
        $dateYear = $date;
        $randomString = substr(str_shuffle('0123456789'), 0, 5);
        return "{$divisionCode}{$quarterCode}{$dateYear}{$subDivisionCode}{$randomString}";
    }

    private function generatePassword($length = 12)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers   = '0123456789';
        $special   = '!@#$%^&*()_+-=';

        // Ensure at least one from each required category
        $password  = $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];
        $password .= str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        // Remaining random characters
        $allChars = $uppercase . $lowercase . $numbers . $special;

        while (strlen($password) < $length) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Shuffle to remove pattern
        return str_shuffle($password);
    }


    public function saveStep1(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'application_no' => [
                'required',
                'string',
                'max:255'
            ],
            'application_day' => [
                'required',
                'string',
                'between:1,31'
            ],
            'application_month' => [
                'required',
                'string',
                'between:1,12'
            ],
            'application_year' => [
                'required',
                'integer',
                'digits:4',
                'min:1970',
                'max:' . date('Y')
            ],
            'year' => [
                'nullable',
                'integer',
                'digits:4',
                'min:1970',
                'max:' . date('Y')
            ],
            'allotment_no' => [
                'required',
                'string',
                'max:255'
            ],
            'prefix' => [
                'required',
                'string',
                'max:255'
            ],
            'allottee_name' => [
                'required',
                'string',
                'max:255'
            ],
            'allottee_middle_name' => [
                'nullable',
                'string',
                'max:255'
            ],
            'allottee_surname' => [
                'nullable',
                'string',
                'max:255'
            ],
            'allottee_name_hindi' => [
                'nullable',
                'string',
                'max:255'
            ],
            'allottee_middle_hindi' => [
                'nullable',
                'string',
                'max:255'
            ],
            'allottee_surname_hindi' => [
                'nullable',
                'string',
                'max:255'
            ],
            'relation_prefix' => [
                'required',
                'string',
                'max:100'
            ],
            'relation_name' => [
                'required',
                'string',
                'max:100'
            ],
            'marital_status' => [
                'nullable',
                'string',
                'max:50'
            ],
            'allottee_gender' => [
                'nullable',
                'string',
                'max:20'
            ],
            'allottee_category' => [
                'nullable',
                'string',
                'max:100'
            ],
            'allottee_religion' => [
                'nullable',
                'string',
                'max:100'
            ],
            'allottee_nationality' => [
                'nullable',
                'string',
                'max:100'
            ],
            'date_of_birth_day' => [
                'required',
                'string',
                'between:1,31'
            ],
            'date_of_birth_month' => [
                'required',
                'string',
                'between:1,12'
            ],
            'date_of_birth_year' => [
                'required',
                'integer',
                'digits:4',
            ],
            'current_age' => [
                'nullable',
                'string'
            ],
            'division_id' => [
                'required',
                'string'
            ],
            'subdivision_id' => [
                'required',
                'string'
            ],
            'pcategory_id' => [
                'required',
                'string'
            ],
            'property_type_id' => [
                'required',
                'string'
            ],
            'quarter_id' => [
                'required',
                'string'
            ],
            'scheme_id' => [
                'required',
                'string',
                'exists:schemes,id'
            ],

        ], [
            'scheme_id.exists' =>
            'Selected scheme is invalid.',
            'application_year.max' =>
            'Application year cannot be greater than current year.',
            'year.max' =>
            'Year cannot be greater than current year.',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $divisionId = decryptId($request->division_id);
        $subDivisionId = decryptId($request->subdivision_id);
        $pcategoryId = decryptId($request->pcategory_id);
        $propertyTypeId = decryptId($request->property_type_id);
        $propertySubTypeId = decryptId($request->p_sub_type_id);
        $quaterId = decryptId($request->quarter_id);

        $usersname = $this->generateUniqueUsername($divisionId, $subDivisionId, $pcategoryId, $request->allotment_year);
        $password = $this->generatePassword();

        $applicant = new Allottee();
        $applicant->division_id = $divisionId;
        $applicant->subdivision_id = $subDivisionId;
        $applicant->pcategory_id = $pcategoryId;
        $applicant->property_type_id = $propertyTypeId;
        $applicant->p_sub_type_id = $propertySubTypeId;
        $applicant->quarter_id = $quaterId;
        $applicant->username = $usersname;
        $applicant->password = Hash::make($password);
        $applicant->scheme_id = $request->scheme_id;
        $applicant->application_no = $request->application_no;
        $applicant->application_day = $request->application_day;
        $applicant->application_month = $request->application_month;
        $applicant->application_year = $request->application_year;
        $applicant->allotment_no = $request->allotment_no . '/' . $request->year;
        $applicant->allotment_day = $request->allotment_day;
        $applicant->allotment_month = $request->allotment_month;
        $applicant->allotment_year = $request->allotment_year;
        $applicant->prefix = $request->prefix;
        $applicant->allottee_name = $request->allottee_name;
        $applicant->allottee_middle_name = $request->allottee_middle_name;
        $applicant->allottee_surname = $request->allottee_surname;
        $applicant->allottee_relation_type = $request->relation_prefix;
        $applicant->relation_name = $request->relation_name;
        $applicant->marital_status = $request->marital_status;
        $applicant->allottee_gender = $request->allottee_gender;
        $applicant->pan_card_number = $request->pan_card_number;
        $applicant->aadhar_card_number = $request->aadhar_card_number;
        $applicant->allottee_category = $request->allottee_category;
        $applicant->allottee_religion = $request->allottee_religion;
        $applicant->allottee_nationality = $request->allottee_nationality;
        $applicant->date_of_birth_day = $request->date_of_birth_day;
        $applicant->date_of_birth_month = $request->date_of_birth_month;
        $applicant->date_of_birth_year = $request->date_of_birth_year;
        $applicant->allottee_remarks = $request->allottee_remarks;
        $applicant->allottee_create_date  = now();
        $applicant->current_step = 2;
        $applicant->create_ip_address  = $request->ip() ?? NULL;
        $applicant->created_by = auth()->id();
        $applicant->created_at = now();
        $applicant->save();

        return response()->json([
            'success' => true,
            'message' => 'Allottee Details saved successfully',
            'applicant_id' => $applicant->id,
            'next_step' => 2
        ]);
    }

    public function saveStep2(Request $request)
    {
        $applicantId = $request->applicant_id;
        $data = $request->all();
        $data['update_ip_address'] = $request->ip();

        if (!$request->filled('id')) {
            $data['create_ip_address'] = $request->ip();
            $data['created_by'] = auth()->id();
        }

        $data['updated_by'] = auth()->id();

        $record = AllotteesContactDetail::updateOrCreate(
            ['allottee_id' => $applicantId],
            $data
        );

        // Update applicant's current step (optional)
        $applicant = Allottee::find($applicantId);
        if ($applicant) {
            $applicant->current_step = 3; // Move to next step
            $applicant->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Address Details saved successfully',
            'data' => $record,
            'next_step' => 3
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $allottees = Allottee::with('division')
            ->where('application_no', 'like', "%{$search}%")
            ->orWhere('allotment_no', 'like', "%{$search}%")
            ->orWhere('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->orWhere('primary_mobile', 'like', "%{$search}%")
            ->paginate(10);

        return view('admin.allottee.index', compact('allottees'));
    }
}
