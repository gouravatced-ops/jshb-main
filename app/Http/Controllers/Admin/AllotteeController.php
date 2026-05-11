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
use App\Models\PropertyCategory;
use App\Models\AllotteeProcessStep;
use App\Models\AllotteeGeneratedDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AllotteeController extends Controller
{
    private function processStepBlueprint(): array
    {
        return [
            1 => ['title' => 'Payment Details', 'description' => 'Initial payment and receipt details'],
            2 => ['title' => 'Allottee Details', 'description' => 'Personal details and communication setup'],
            3 => ['title' => 'Allotment Letter Generation', 'description' => 'Allotment letter will be generated after lottery'],
            4 => ['title' => '25% Initial Payment', 'description' => '25% payment to be made within 30 days'],
            5 => ['title' => 'Late Fine Conditions', 'description' => 'After 30 days additional time allowed with fine'],
            6 => ['title' => 'Agreement Letter', 'description' => 'Agreement letter will be issued'],
            7 => ['title' => 'Possession Letter', 'description' => 'Possession letter will be issued'],
            8 => ['title' => 'Payment Option', 'description' => 'Choose EMI on remaining amount or one-time payment'],
            9 => ['title' => 'Monthly Payment', 'description' => 'Monthly payment to be made before 7th'],
            10 => ['title' => 'Application For Final Calculation', 'description' => 'Applicant submits final calculation request'],
            11 => ['title' => 'Final Calculation Sheet', 'description' => 'Final calculation sheet generated'],
            12 => ['title' => 'Remaining Amount', 'description' => 'Remaining payable amount determined'],
            13 => ['title' => 'Re-Calculation', 'description' => 'Re-calculation if final payment not made on time'],
            14 => ['title' => 'Payment Receipt', 'description' => 'Receipt generated after final amount payment'],
            15 => ['title' => 'Site Verification Order', 'description' => 'Site verification order issued'],
            16 => ['title' => 'Verification Report Upload', 'description' => 'Division official uploads report'],
            17 => ['title' => 'Extra Construction Check', 'description' => 'Check if extra construction exists'],
            18 => ['title' => 'Due Amount Determination', 'description' => 'Recalculated due amount generated'],
            19 => ['title' => 'Demand Note Payment', 'description' => 'Pay demand note amount and generate receipt'],
            20 => ['title' => 'NOC Issuance', 'description' => 'NOC issued from division'],
            21 => ['title' => 'Apply For Registry', 'description' => 'Applicant applies for registry'],
            22 => ['title' => 'Registry Date Scheduling', 'description' => 'Registry date scheduled by division'],
            23 => ['title' => 'Registry Deed Upload', 'description' => 'Registry deed uploaded'],
        ];
    }

    private function ensureProcessSteps(Allottee $allottee): void
    {
        $steps = $this->processStepBlueprint();
        foreach ($steps as $stepNo => $meta) {
            AllotteeProcessStep::firstOrCreate(
                ['allottee_id' => $allottee->id, 'step_no' => $stepNo],
                ['title' => $meta['title'], 'description' => $meta['description'], 'status' => $stepNo <= 2 ? 'completed' : ($stepNo === 3 ? 'pending' : 'locked')]
            );
        }
    }

    private function refreshStepFlow(Allottee $allottee): void
    {
        $rows = AllotteeProcessStep::where('allottee_id', $allottee->id)->orderBy('step_no')->get()->keyBy('step_no');
        if ($rows->isEmpty()) {
            return;
        }

        $sequence = $allottee->payment_option === 'one_time'
            ? [1, 2, 3, 4, 5, 6, 7, 8, 10, 11, 12, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]
            : range(1, 23);

        $nextPending = null;
        foreach ($sequence as $stepNo) {
            $row = $rows->get($stepNo);
            if (!$row) {
                continue;
            }
            if ($row->status !== 'completed') {
                $nextPending = $stepNo;
                break;
            }
        }

        foreach ($rows as $row) {
            if ($row->status === 'completed') {
                continue;
            }

            if (!in_array($row->step_no, $sequence, true)) {
                $row->status = 'locked';
            } else {
                $row->status = $row->step_no === $nextPending ? 'pending' : 'locked';
            }
            $row->save();
        }
    }

    private function saveGeneratedPdf(Allottee $allottee, string $type, string $content): string
    {
        $fileName = $type . '-' . $allottee->id . '-' . now()->format('YmdHis') . '.pdf';
        $path = 'allottee_letters/' . $fileName;
        Storage::disk('public')->put($path, $content);

        AllotteeGeneratedDocument::create([
            'allottee_id' => $allottee->id,
            'document_type' => $type,
            'file_name' => $fileName,
            'file_path' => $path,
            'generated_by' => auth()->id(),
            'generated_at' => now(),
        ]);

        return $fileName;
    }

    public function index(Request $request)
    {
        $query = Allottee::query()->with([
            'division:id,name',
            'subDivision:id,name',
            'propertyCategory:id,name',
        ]);

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('application_no', 'like', "%{$search}%")
                    ->orWhere('allotment_no', 'like', "%{$search}%")
                    ->orWhere('property_number', 'like', "%{$search}%")
                    ->orWhere('allottee_name', 'like', "%{$search}%")
                    ->orWhere('allottee_middle_name', 'like', "%{$search}%")
                    ->orWhere('allottee_surname', 'like', "%{$search}%");
            });
        }

        if ($request->filled('division_id')) {
            $query->where('division_id', (int) $request->division_id);
        }
        if ($request->filled('subdivision_id')) {
            $query->where('subdivision_id', (int) $request->subdivision_id);
        }
        if ($request->filled('pcategory_id')) {
            $query->where('pcategory_id', (int) $request->pcategory_id);
        }
        if ($request->filled('property_number')) {
            $propertyNumber = trim((string) $request->property_number);
            $query->where('property_number', 'like', "%{$propertyNumber}%");
        }
        if ($request->filled('flat')) {
            $flat = trim((string) $request->flat);
            $query->where('allotment_no', 'like', "%{$flat}%");
        }

        $allottees = $query->latest('id')->paginate(10)->appends($request->query());
        $divisions = Division::select('id', 'name')->where('status', 1)->orderBy('name')->get();
        $subDivisions = SubDivision::select('id', 'name')->where('status', 1)->orderBy('name')->get();
        $categories = PropertyCategory::select('id', 'name')->where('status', 1)->orderBy('name')->get();

        return view('admin.allottee.list', compact('allottees', 'divisions', 'subDivisions', 'categories'));
    }

    public function indexStart()
    {
        return view('admin.allottee.index');
    }

    public function show(Allottee $allottee)
    {
        $allottee->load([
            'division:id,name',
            'subDivision:id,name',
            'propertyCategory:id,name',
            'propertyType:id,name',
            'quarterType',
        ]);

        $this->ensureProcessSteps($allottee);
        $this->refreshStepFlow($allottee);

        $steps = AllotteeProcessStep::where('allottee_id', $allottee->id)->orderBy('step_no')->get();
        $completed = $steps->where('status', 'completed')->count();
        $progressPercent = $steps->count() > 0 ? (int) round(($completed / $steps->count()) * 100) : 0;

        return view('admin.allottee.show', compact('allottee', 'steps', 'progressPercent'));
    }

    public function section(Allottee $allottee, string $section)
    {
        $allowed = ['overview', 'payment', 'personal', 'communication', 'agreement', 'possession'];
        abort_unless(in_array($section, $allowed, true), 404);

        $allottee->load([
            'division:id,name',
            'subDivision:id,name',
            'propertyCategory:id,name',
            'alloteeAdresses',
        ]);

        return view("admin.allottee.sections.{$section}", compact('allottee'));
    }

    public function processStep(Allottee $allottee, int $stepNo)
    {
        $this->ensureProcessSteps($allottee);
        $this->refreshStepFlow($allottee);

        $step = AllotteeProcessStep::where('allottee_id', $allottee->id)->where('step_no', $stepNo)->firstOrFail();
        if ($step->status === 'locked') {
            return response('<div class="alert alert-warning">This step is locked. Complete previous step first.</div>');
        }

        return view('admin.allottee.sections.process-step', compact('allottee', 'step'));
    }

    public function completeProcessStep(Request $request, Allottee $allottee, int $stepNo)
    {
        $this->ensureProcessSteps($allottee);
        $step = AllotteeProcessStep::where('allottee_id', $allottee->id)->where('step_no', $stepNo)->firstOrFail();
        if ($step->status === 'locked') {
            return response()->json(['success' => false, 'message' => 'Step is locked.'], 422);
        }

        $step->status = 'completed';
        $step->completed_at = now();
        $step->completed_by = auth()->id();
        $step->save();

        if ($stepNo >= $allottee->current_step) {
            $allottee->current_step = $stepNo + 1;
            $allottee->save();
        }

        $this->refreshStepFlow($allottee);
        return response()->json(['success' => true, 'message' => 'Step marked completed.']);
    }

    public function choosePaymentPlan(Request $request, Allottee $allottee)
    {
        $validated = $request->validate([
            'payment_option' => 'required|in:emi_60,one_time',
        ]);

        $allottee->loadMissing('allotProFinDetail');
        $remaining = (float) ($allottee->allotProFinDetail->remaining_amount ?? $allottee->remaining_amount ?? 0);
        if ($remaining <= 0) {
            $remaining = max(0, (float) ($allottee->payment_amount ?? 0) * 3); // fallback estimate when real remaining amount is unavailable
        }

        $allottee->payment_option = $validated['payment_option'];
        $allottee->payment_option_selected_at = now();
        $allottee->remaining_amount = $remaining;

        if ($validated['payment_option'] === 'emi_60') {
            $allottee->emi_months = 60;
            $allottee->emi_monthly_amount = round($remaining / 60, 2);
            $allottee->final_calculation_generated = false;
            $allottee->recalculation_allowed = true;
        } else {
            $allottee->emi_months = 0;
            $allottee->emi_monthly_amount = null;
            $allottee->final_calculation_generated = true;
            $allottee->recalculation_allowed = false;
        }
        $allottee->save();

        $step8 = AllotteeProcessStep::where('allottee_id', $allottee->id)->where('step_no', 8)->first();
        if ($step8 && $step8->status !== 'completed') {
            $step8->status = 'completed';
            $step8->completed_at = now();
            $step8->completed_by = auth()->id();
            $step8->save();
        }

        if ($validated['payment_option'] === 'one_time') {
            $step11 = AllotteeProcessStep::where('allottee_id', $allottee->id)->where('step_no', 11)->first();
            if ($step11 && $step11->status !== 'completed') {
                $step11->status = 'completed';
                $step11->completed_at = now();
                $step11->completed_by = auth()->id();
                $step11->save();
            }
        }

        $this->refreshStepFlow($allottee);
        return back()->with('success', 'Payment option saved successfully.');
    }

    public function updatePaymentOption(Request $request, Allottee $allottee)
    {
        $validated = $request->validate([
            'payment_option' => 'required|in:emi,one_time',
        ]);

        $allottee->step_remarks = trim(($allottee->step_remarks ? $allottee->step_remarks . ' | ' : '') . 'Payment Option: ' . strtoupper($validated['payment_option']));
        $allottee->updated_by = auth()->id();
        $allottee->update_ip_address = $request->ip();
        $allottee->save();

        return back()->with('success', 'Payment option updated successfully.');
    }

    public function allotmentLetter(Allottee $allottee)
    {
        return view('admin.allottee.letters.allotment', compact('allottee'));
    }

    public function possessionLetter(Allottee $allottee)
    {
        return view('admin.allottee.letters.possession', compact('allottee'));
    }

    public function allotmentLetterPdf(Request $request, Allottee $allottee)
    {
        $allottee->load(['division:id,name', 'subDivision:id,name', 'propertyCategory:id,name']);
        $pdf = Pdf::loadView('admin.allottee.letters.templates.allotment-pdf', compact('allottee'))->setPaper('a4');
        $fileName = 'allotment-letter-' . $allottee->id . '.pdf';
        if ($request->boolean('download')) {
            $fileName = $this->saveGeneratedPdf($allottee, 'allotment-letter', $pdf->output());
        }
        return $request->boolean('download') ? $pdf->download($fileName) : $pdf->stream($fileName);
    }

    public function possessionLetterPdf(Request $request, Allottee $allottee)
    {
        $allottee->load(['division:id,name', 'subDivision:id,name', 'propertyCategory:id,name']);
        $pdf = Pdf::loadView('admin.allottee.letters.templates.possession-pdf', compact('allottee'))->setPaper('a4');
        $fileName = 'possession-letter-' . $allottee->id . '.pdf';
        if ($request->boolean('download')) {
            $fileName = $this->saveGeneratedPdf($allottee, 'possession-letter', $pdf->output());
        }
        return $request->boolean('download') ? $pdf->download($fileName) : $pdf->stream($fileName);
    }

    public function saveStep0(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'applicant_id' => 'nullable|integer|exists:allottees,id',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string|max:100',
            'payment_reference' => 'nullable|string|max:255',
            'payment_receipt' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->hasFile('payment_receipt')) {
                return;
            }
            $id = $request->input('applicant_id');
            $hasExisting = $id && Allottee::where('id', $id)->whereNotNull('payment_receipt_path')->exists();
            if (!$hasExisting) {
                $validator->errors()->add('payment_receipt', 'Please upload a payment receipt image (JPEG, PNG, or WebP).');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $applicantId = $request->input('applicant_id');
        $applicant = $applicantId ? Allottee::find($applicantId) : null;
        if ($applicantId && !$applicant) {
            return response()->json(['success' => false, 'message' => 'Application not found.'], 404);
        }

        if (!$applicant) {
            $applicant = new Allottee();
            $applicant->username = 'DRAFT_' . strtoupper(Str::random(12));
            $applicant->password = Hash::make(Str::random(40));
            $applicant->create_ip_address = $request->ip();
            $applicant->created_by = auth()->id();
            $applicant->created_at = now();
        }

        $applicant->payment_amount = $request->payment_amount;
        $applicant->payment_date = $request->payment_date;
        $applicant->payment_mode = $request->payment_mode;
        $applicant->payment_reference = $request->payment_reference;

        if ($request->hasFile('payment_receipt')) {
            if ($applicant->payment_receipt_path) {
                Storage::disk('public')->delete($applicant->payment_receipt_path);
            }
            $applicant->payment_receipt_path = $request->file('payment_receipt')->store('allottee_payments', 'public');
        }

        $applicant->current_step = 1;
        $applicant->update_ip_address = $request->ip();
        $applicant->updated_by = auth()->id();
        $applicant->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment details saved successfully.',
            'applicant_id' => $applicant->id,
            'next_step' => 1,
        ]);
    }

    public function getStep($step, $applicantId = null)
    {
        $step = (int) $step;

        if ($step === 0) {
            $applicant = $applicantId ? Allottee::find($applicantId) : null;

            return view('admin.allottee.step0', compact('applicant'));
        }

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

        $getSchemeList = $applicant->scheme_id
            ? Scheme::select('scheme_code', 'scheme_name')->where('id', $applicant->scheme_id)->first()
            : null;

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

        $existingId = $request->filled('applicant_id') ? (int) $request->applicant_id : null;
        if (!$existingId && $request->filled('allottee_id')) {
            $existingId = (int) $request->allottee_id;
        }

        if ($existingId) {
            $applicant = Allottee::find($existingId);
            if (!$applicant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application not found.',
                ], 404);
            }
        } else {
            $applicant = new Allottee();
        }

        $isDraftLogin = !$applicant->exists || Str::startsWith((string) $applicant->username, 'DRAFT_');
        if ($isDraftLogin) {
            $usersname = $this->generateUniqueUsername($divisionId, $subDivisionId, $pcategoryId, $request->allotment_year);
            $password = $this->generatePassword();
            $applicant->username = $usersname;
            $applicant->password = Hash::make($password);
        }

        $applicant->division_id = $divisionId;
        $applicant->subdivision_id = $subDivisionId;
        $applicant->pcategory_id = $pcategoryId;
        $applicant->property_type_id = $propertyTypeId;
        $applicant->p_sub_type_id = $propertySubTypeId;
        $applicant->quarter_id = $quaterId;
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
        $applicant->current_step = 2;

        if (!$applicant->exists) {
            $applicant->allottee_create_date = now();
            $applicant->create_ip_address = $request->ip() ?? null;
            $applicant->created_by = auth()->id();
            $applicant->created_at = now();
        } else {
            $applicant->update_ip_address = $request->ip() ?? null;
            $applicant->updated_by = auth()->id();
        }

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
