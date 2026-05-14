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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AllotteeController extends Controller
{
    private function processStepBlueprint(): array
    {
        return [

            1 => [
                'title'       => 'Payment Details',
                'description' => 'Initial payment and receipt details',
                'blade'       => 'payment-details',
            ],

            2 => [
                'title'       => 'Allottee Details',
                'description' => 'Personal details and communication setup',
                'blade'       => 'allottee-details',
            ],

            3 => [
                'title'       => 'Allotment Letter',
                'description' => 'Allotment letter will be generated after lottery',
                'blade'       => 'allotment-letter',
            ],

            4 => [
                'title'       => '25% Initial Payment',
                'description' => '25% payment to be made within 30 days',
                'blade'       => 'initial-payment',
            ],

            5 => [
                'title'       => 'Late Fine Conditions',
                'description' => 'After 30 days additional time allowed with fine',
                'blade'       => 'late-fine-conditions',
            ],

            6 => [
                'title'       => 'Agreement Letter',
                'description' => 'Agreement letter will be issued',
                'blade'       => 'agreement-letter',
            ],

            7 => [
                'title'       => 'Possession Letter',
                'description' => 'Possession letter will be issued',
                'blade'       => 'possession-letter',
            ],

            8 => [
                'title'       => 'Payment Option',
                'description' => 'Choose EMI on remaining amount or one-time payment',
                'blade'       => 'payment-option',
            ],

            9 => [
                'title'       => 'Monthly Payment',
                'description' => 'Monthly payment to be made before 7th',
                'blade'       => 'monthly-payment',
            ],

            10 => [
                'title'       => 'Application For Final Calculation',
                'description' => 'Applicant submits final calculation request',
                'blade'       => 'final-calculation-application',
            ],

            11 => [
                'title'       => 'Final Calculation Sheet',
                'description' => 'Final calculation sheet generated',
                'blade'       => 'final-calculation-sheet',
            ],

            12 => [
                'title'       => 'Remaining Amount',
                'description' => 'Remaining payable amount determined',
                'blade'       => 'remaining-amount',
            ],

            13 => [
                'title'       => 'Re-Calculation',
                'description' => 'Re-calculation if final payment not made on time',
                'blade'       => 're-calculation',
            ],

            14 => [
                'title'       => 'Payment Receipt',
                'description' => 'Receipt generated after final amount payment',
                'blade'       => 'payment-receipt',
            ],

            15 => [
                'title'       => 'Site Verification Order',
                'description' => 'Site verification order issued',
                'blade'       => 'site-verification-order',
            ],

            16 => [
                'title'       => 'Verification Report Upload',
                'description' => 'Division official uploads report',
                'blade'       => 'verification-report-upload',
            ],

            17 => [
                'title'       => 'Extra Construction Check',
                'description' => 'Check if extra construction exists',
                'blade'       => 'extra-construction-check',
            ],

            18 => [
                'title'       => 'Due Amount Determination',
                'description' => 'Recalculated due amount generated',
                'blade'       => 'due-amount-determination',
            ],

            19 => [
                'title'       => 'Demand Note Payment',
                'description' => 'Pay demand note amount and generate receipt',
                'blade'       => 'demand-note-payment',
            ],

            20 => [
                'title'       => 'NOC Issuance',
                'description' => 'NOC issued from division',
                'blade'       => 'noc-issuance',
            ],

            21 => [
                'title'       => 'Apply For Registry',
                'description' => 'Applicant applies for registry',
                'blade'       => 'apply-for-registry',
            ],

            22 => [
                'title'       => 'Registry Date Scheduling',
                'description' => 'Registry date scheduled by division',
                'blade'       => 'registry-date-scheduling',
            ],

            23 => [
                'title'       => 'Registry Deed Upload',
                'description' => 'Registry deed uploaded',
                'blade'       => 'registry-deed-upload',
            ],

        ];
    }

    private function ensureProcessSteps(Allottee $allottee): void
    {
        $steps = $this->processStepBlueprint();
        foreach ($steps as $stepNo => $meta) {
            AllotteeProcessStep::firstOrCreate(
                ['allottee_id' => $allottee->id, 'step_no' => $stepNo],
                ['title' => $meta['title'], 'description' => $meta['description'], 'blade' => $meta['blade'], 'status' => $stepNo <= 2 ? 'completed' : ($stepNo === 3 ? 'pending' : 'locked')]
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

    private function saveGeneratedPdf(
        Allottee $allottee,
        string $type,
        string $content
    ): string {

        $folder = implode('/', [
            'document',
            'allotment-letter',
            'generated',
            $allottee->allotment_year,
            $allottee->allotment_month,
            $allottee->allotment_day,
        ]);

        $directory = public_path($folder);

        File::ensureDirectoryExists($directory, 0755, true);

        $fileName =
            $type . '-' .
            $allottee->allotment_year .
            $allottee->allotment_month .
            $allottee->allotment_day .
            now()->format('His') . '-' .
            rand(1000, 9999) .
            '.pdf';

        file_put_contents(
            $directory . '/' . $fileName,
            $content
        );

        AllotteeGeneratedDocument::create([
            'allottee_id' => $allottee->id,
            'document_type' => $type,
            'file_name' => $fileName,
            'file_path' => $folder . '/' . $fileName,
            'generated_by' => Auth::id(),
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

    public function indexEditStart(Allottee $allottee)
    {
        $applicant = $allottee;
        return view('admin.allottee.edit.index', compact('applicant'));
    }

    public function show(Allottee $allottee)
    {
        $allottee->load([
            'division:id,name',
            'subDivision:id,name',
            'propertyCategory:id,name',
            'propertyType:id,name',
            'quarterType',
            'quarterType',
            'scheme',
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

        return view('admin.allottee.sections.' . $step->blade, compact('allottee', 'step'));
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
        $step->completed_by = Auth::id();
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
            $step8->completed_by = Auth::id();
            $step8->save();
        }

        if ($validated['payment_option'] === 'one_time') {
            $step11 = AllotteeProcessStep::where('allottee_id', $allottee->id)->where('step_no', 11)->first();
            if ($step11 && $step11->status !== 'completed') {
                $step11->status = 'completed';
                $step11->completed_at = now();
                $step11->completed_by = Auth::id();
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
        $allottee->updated_by = Auth::id();
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

        // Set PDF options for proper Unicode rendering
        $pdf = Pdf::loadView('admin.allottee.letters.templates.allotment-pdf', compact('allottee'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'KrutiDev',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'chroot' => public_path(),
            ]);

        $fileName = 'allotment-letter-' . $allottee->id . '.pdf';

        if ($request->boolean('download')) {

            $document = AllotteeGeneratedDocument::where([
                'allottee_id'   => $allottee->id,
                'document_type' => 'allotment-letter',
            ])->latest()->first();

            if (
                $document &&
                File::exists(public_path($document->file_path))
            ) {

                return response()->download(
                    public_path($document->file_path),
                    $document->file_name
                );
            }
        } else {

            $this->saveGeneratedPdf(
                $allottee,
                'allotment-letter',
                $pdf->output()
            );
        }

        return $pdf->stream($fileName);
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
            'applicant_id'     => 'nullable|integer|exists:allottees,id',
            'payment_amount'   => 'required|numeric|min:0.01',
            'payment_day'      => 'required|string|between:1,31',
            'payment_month'    => 'required|string|between:1,12',
            'payment_year'     => 'required|string|max:' . date('Y'),
            'payment_utr_no'   => 'nullable|string|max:255',
            'payment_receipt'  => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        // Receipt Required Validation
        $validator->after(function ($validator) use ($request) {

            if ($request->hasFile('payment_receipt')) {
                return;
            }

            $applicantId = $request->applicant_id;

            $hasExistingReceipt = $applicantId
                ? Allottee::where('id', $applicantId)
                ->whereNotNull('payment_receipt_path')
                ->exists()
                : false;

            if (!$hasExistingReceipt) {
                $validator->errors()->add(
                    'payment_receipt',
                    'Please upload payment receipt.'
                );
            }
        });

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Find Or Create Applicant
        $applicant = $request->filled('applicant_id')
            ? Allottee::find($request->applicant_id)
            : new Allottee();

        if (!$applicant->exists) {

            $applicant->username          = 'DRAFT_' . strtoupper(Str::random(12));
            $applicant->password          = Hash::make(Str::random(40));
            $applicant->create_ip_address = $request->ip();
            $applicant->created_by        = Auth::id();
        }

        // Save Payment Details
        $applicant->payment_amount = str_replace(',', '', $request->payment_amount);

        $applicant->payment_day    = $request->payment_day;
        $applicant->payment_month  = $request->payment_month;
        $applicant->payment_year   = $request->payment_year;
        $applicant->payment_utr_no = $request->payment_utr_no;

        // Upload Receipt
        if ($request->hasFile('payment_receipt')) {

            // Delete old file
            if (!empty($applicant->payment_receipt_path)) {

                $oldFile = public_path($applicant->payment_receipt_path);

                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }

            $folder = implode('/', [
                'uploads',
                'payments',
                $request->payment_year,
                str_pad($request->payment_month, 2, '0', STR_PAD_LEFT),
                str_pad($request->payment_day, 2, '0', STR_PAD_LEFT),
            ]);

            $year  = substr($request->payment_year, -2); // 2026 => 26
            $month = str_pad($request->payment_month, 2, '0', STR_PAD_LEFT);
            $day   = str_pad($request->payment_day, 2, '0', STR_PAD_LEFT);

            $directory = public_path($folder);

            File::ensureDirectoryExists($directory, 0755, true);

            $file = $request->file('payment_receipt');

            $fileName = 'payment-receipt-' .
                $year . $month . $day . now()->format('His') . '-' .
                rand(1000, 9999) . '.' .
                $file->getClientOriginalExtension();

            $file->move($directory, $fileName);

            $applicant->payment_receipt_path = $folder . '/' . $fileName;
        }

        // Final Save
        $applicant->current_step      = 1;
        $applicant->update_ip_address = $request->ip();
        $applicant->updated_by        = Auth::id();

        $applicant->save();

        return response()->json([
            'success'      => true,
            'message'      => 'Payment details saved successfully.',
            'applicant_id' => $applicant->id,
            'next_step'    => 1,
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
            // return [1];
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
                // return $view;
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

        // return $applicant->division_id;
        $subdivisions = getSubDivisions(encryptId($applicant->division_id)) ?? [];
        $propertyTypes = getPropertyType(encryptId($applicant->pcategory_id)) ?? [];
        $propertySubTypes = getPropertySubType(encryptId($applicant->property_type_id)) ?? [];

        // return [$subdivisions , $propertyTypes , $propertySubTypes];
        return view($view, compact('applicant', 'getSchemeList', 'subdivisions', 'propertyTypes', 'propertySubTypes'));
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
        $applicant->prefix = $request->prefix;
        $applicant->allottee_name = $request->allottee_name;
        $applicant->allottee_middle_name = $request->allottee_middle_name;
        $applicant->allottee_surname = $request->allottee_surname;
        $applicant->allottee_relation_type = $request->relation_prefix;
        $applicant->allottee_prefix_hindi = $request->allottee_prefix_hindi;
        $applicant->allottee_name_hindi = $request->allottee_name_hindi;
        $applicant->allottee_middle_hindi = $request->allottee_middle_hindi;
        $applicant->allottee_surname_hindi = $request->allottee_surname_hindi;
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
            $applicant->created_by = Auth::id();
            $applicant->created_at = now();
        } else {
            $applicant->update_ip_address = $request->ip() ?? null;
            $applicant->updated_by = Auth::id();
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
            $data['created_by'] = Auth::id();
        }

        $data['updated_by'] = Auth::id();

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

    public function saveStep3(Request $request)
    {
        // return $request;
        if (!$request->final_submission) {
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong',
            ]);
        }

        try {

            DB::beginTransaction();

            $allottee = Allottee::find($request->applicant_id);

            if (!$allottee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Applicant not found',
                ]);
            }

            // Step Completed
            $allottee->is_step_completed = 1;
            $allottee->allotment_no = str_pad($allottee->id, 3, '0', STR_PAD_LEFT) . '/' . strtoupper(Str::random(3)) . '/' . rand(111, 999) . '/' . date('Y');
            $allottee->allotment_day = date('d');
            $allottee->allotment_month = date('m');
            $allottee->allotment_year = date('Y');
            $allottee->save();

            DB::commit();

            $this->allotmentLetterPdf(
                new Request(),
                $allottee
            );

            return response()->json([
                'success' => true,
                'message' => 'Application Submit Successfully',
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application',
                'error' => $e->getMessage()
            ]);
        }
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
