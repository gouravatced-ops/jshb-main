<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Allottee\Step0Request;
use App\Services\AllotteeService;
use App\Http\Requests\Allottee\Step1Request;
use App\Http\Requests\Allottee\Step2Request;
use App\Http\Requests\Allottee\Step3Request;
use App\Services\NotificationService;
use App\Mail\AllotteeCredentialMail;
use App\Http\Controllers\Controller;
use App\Models\Allottee;
use App\Models\Division;
use App\Models\Scheme;
use App\Models\AllotteesContactDetail;
use App\Models\SubDivision;
use App\Models\QuarterType;
use App\Models\PropertyCategory;
use App\Models\AllotteeProcessStep;
use App\Models\AllotteeGeneratedDocument;
use App\Models\AllotteePaymentOrder;
use App\Models\AllotteeTransaction;
use App\Models\AllotteeEmiAccount;
use App\Models\AllotteeEmiSchedule;
use App\Models\AllotteeMonthlyDemand;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use App\Models\Application;
use App\Models\ApplicationMovement;
use App\Models\ApplicationDocument;
use App\Models\ApplicationAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\EmiCalculatorService;
use App\Services\ApplicationService;
use App\Models\User;
use App\Models\Role;
use App\Traits\DocumentUploadTrait;

class AllotteeController extends Controller
{
    use DocumentUploadTrait;

    private function processStepBlueprint(): array
    {
        return [

            // OVERVIEW
            [
                'order_key'   => 1,
                'menu_key'    => 'quick-overview',
                'title'       => 'Overview',
                'description' => 'Quick Overview',
                'icon'        => 'fa-solid fa-gauge-high',
                'blade'       => 'overview',
                'always_show' => true,
                'visible_if'  => [
                    'payment_option' => [
                        'emi',
                        'one_time',
                        'null',
                    ],
                ],
            ],

            // ALLOTTEE DETAILS
            [
                'order_key'   => 2,
                'menu_key'    => 'allottee-details',
                'title'       => 'Allottee Details',
                'description' => 'Allottee Details',
                'icon'        => 'fa-solid fa-user',
                'blade'       => 'allottee-details',
                'always_show' => true,
                'visible_if'  => [
                    'payment_option' => [
                        'emi',
                        'one_time',
                        'null',
                    ],
                ],
            ],

            // ALLOTTEE DOCUMENT
            [
                'order_key'   => 3,
                'menu_key'    => 'letter-order-issued',
                'title'       => 'Letter/Orders Issued',
                'description' => 'Letter/Orders Issued',
                'icon'        => 'fa-solid fa-file-signature',
                'blade'       => 'letter-order-issued',
                'always_show' => true,
                'visible_if'  => [
                    'payment_option' => [
                        'emi',
                        'one_time',
                        'null',
                    ],
                ],
            ],

            // LOTTERY
            [
                'order_key'   => 4,
                'menu_key'    => 'lottery',
                'title'       => 'Lottery',
                'description' => 'Lottery related activities',
                'icon'        => 'fa-solid fa-ticket',
                'always_show' => true,
                'visible_if'  => [
                    'payment_option' => [
                        'emi',
                        'one_time',
                        'null',
                    ],
                ],
                'submenus'    => [
                    [
                        'order_key'    => 1,
                        'sub_menu_key' => 'payment-details',
                        'title'        => 'Lottery Payment',
                        'icon'         => 'fa-solid fa-money-check-dollar',
                        'blade'        => 'payment-details',
                    ],
                ],
            ],

            // ALLOTMENT
            [
                'order_key'   => 5,
                'menu_key'    => 'allotment',
                'title'       => 'Allotment',
                'description' => 'Allotment related activities',
                'icon'        => 'fa-solid fa-file-signature',
                'always_show' => true,
                'visible_if'  => [
                    'payment_option' => [
                        'emi',
                        'one_time',
                        'null',
                    ],
                ],
                'submenus'    => [
                    [
                        'order_key'    => 1,
                        'sub_menu_key' => 'generate-allotment',
                        'title'        => 'Allotment Letter',
                        'icon'         => 'fa-solid fa-file-lines',
                        'blade'        => 'allotment-letter',
                    ],
                    [
                        'order_key'    => 2,
                        'sub_menu_key' => 'allotment-demand-note',
                        'title'        => '15% Demand Note',
                        'icon'         => 'fa-solid fa-file-invoice-dollar',
                        'blade'        => 'initial-payment',
                    ],
                    [
                        'order_key'    => 3,
                        'sub_menu_key' => 'agreement-document-letter',
                        'title'        => 'Agreement',
                        'icon'         => 'fa-solid fa-key',
                        'blade'        => 'allotment-agreement-letter',
                    ],
                    [
                        'order_key'    => 4,
                        'sub_menu_key' => 'allotment-possession-letter',
                        'title'        => 'Possession Letter',
                        'icon'         => 'fa-solid fa-key',
                        'blade'        => 'allotment-possession-letter',
                    ],
                ],
            ],

            // CHOOSE PAYMENT OPTION
            [
                'order_key'   => 6,
                'menu_key'    => 'choose-payment-option',
                'title'       => 'Choose Payment Option',
                'description' => 'Choose EMI or One Time Payment',
                'icon'        => 'fa-solid fa-wallet',
                'blade'       => 'choose-payment-option',
                'always_show' => false,
                'visible_if'  => [
                    'payment_option' => [
                        'null',
                    ],
                ],
            ],

            // PROPERTY PAYMENT
            [
                'order_key'   => 7,
                'menu_key'    => 'property-payment',
                'title'       => 'Property Payment',
                'description' => 'One time property payment',
                'icon'        => 'fa-solid fa-building-circle-check',
                'always_show' => false,
                'visible_if'  => [
                    'payment_option' => [
                        'one_time',
                    ],
                ],
                'submenus'    => [
                    [
                        'order_key'    => 1,
                        'sub_menu_key' => 'one-time-payment',
                        'title'        => 'One Time Payment',
                        'icon'         => 'fa-solid fa-indian-rupee-sign',
                        'blade'        => 'one-time-payment',
                    ],
                    [
                        'order_key'    => 2,
                        'sub_menu_key' => 'payment-history',
                        'title'        => 'Payment History',
                        'icon'         => 'fa-solid fa-clock-rotate-left',
                        'blade'        => 'payment-history',
                    ],
                ],
            ],

            // EMI MANAGEMENT
            [
                'order_key'   => 8,
                'menu_key'    => 'emi-management',
                'title'       => 'EMI Management',
                'description' => 'EMI Management',
                'icon'        => 'fa-solid fa-calendar-days',
                'always_show' => false,
                'visible_if'  => [
                    'payment_option' => [
                        'emi',
                    ],
                ],
                'submenus'    => [
                    [
                        'order_key'    => 1,
                        'sub_menu_key' => 'emi-dashboard',
                        'title'        => 'EMI Dashboard',
                        'icon'         => 'fa-solid fa-chart-line',
                        'blade'        => 'emi-dashboard',
                    ],
                    [
                        'order_key'    => 2,
                        'sub_menu_key' => 'monthly-emi',
                        'title'        => 'Pay EMI',
                        'icon'         => 'fa-solid fa-credit-card',
                        'blade'        => 'monthly-emi',
                    ],
                    [
                        'order_key'    => 3,
                        'sub_menu_key' => 'emi-schedule',
                        'title'        => 'EMI Schedule',
                        'icon'         => 'fa-solid fa-calendar-check',
                        'blade'        => 'emi-schedule',
                    ],
                    [
                        'order_key'    => 4,
                        'sub_menu_key' => 'emi-history',
                        'title'        => 'EMI History',
                        'icon'         => 'fa-solid fa-receipt',
                        'blade'        => 'emi-history',
                    ],
                ],
            ],

            // NOC
            [
                'order_key'   => 9,
                'menu_key'    => 'noc',
                'title'       => 'NOC',
                'description' => 'NOC related process',
                'icon'        => 'fa-solid fa-file-circle-check',
                'always_show' => true,
                'visible_if'  => [
                    'payment_option' => [
                        'emi',
                        'one_time',
                    ],
                ],
                'submenus'    => [
                    [
                        'order_key'    => 1,
                        'sub_menu_key' => 'site-verification',
                        'title'        => 'Site Verification',
                        'icon'         => 'fa-solid fa-location-dot',
                        'blade'        => 'site-verification',
                    ],
                    [
                        'order_key'    => 2,
                        'sub_menu_key' => 'extra-construction-calculation',
                        'title'        => 'Extra Construction',
                        'icon'         => 'fa-solid fa-ruler-combined',
                        'blade'        => 'extra-construction-calculation',
                    ],
                    [
                        'order_key'    => 3,
                        'sub_menu_key' => 'generate-noc',
                        'title'        => 'Generate NOC',
                        'icon'         => 'fa-solid fa-file-circle-plus',
                        'blade'        => 'generate-noc',
                    ],
                ],
            ],

            // FINAL CALCULATION
            [
                'order_key'   => 10,
                'menu_key'    => 'final-calculation',
                'title'       => 'Final Calculation',
                'description' => 'Final calculation process',
                'icon'        => 'fa-solid fa-calculator',
                'always_show' => true,
                'visible_if'  => [
                    'payment_option' => [
                        'emi',
                    ],
                ],
                'submenus'    => [
                    [
                        'order_key'    => 1,
                        'sub_menu_key' => 'final-calculate-value',
                        'title'        => 'Calculate Value',
                        'icon'         => 'fa-solid fa-calculator',
                        'blade'        => 'final-calculate-value',
                    ],
                    [
                        'order_key'    => 2,
                        'sub_menu_key' => 'final-payment-demand-note',
                        'title'        => 'Payment Demand Note',
                        'icon'         => 'fa-solid fa-file-invoice',
                        'blade'        => 'final-payment-demand-note',
                    ],
                    [
                        'order_key'    => 3,
                        'sub_menu_key' => 'final-generate-letter',
                        'title'        => 'Generate Letter',
                        'icon'         => 'fa-solid fa-envelope-open-text',
                        'blade'        => 'final-generate-letter',
                    ],
                ],
            ],

            // REGISTRY
            [
                'order_key'   => 11,
                'menu_key'    => 'registry',
                'title'       => 'Registry',
                'description' => 'Registry related process',
                'icon'        => 'fa-solid fa-book-open',
                'always_show' => true,
                'visible_if'  => [
                    'payment_option' => [
                        'emi',
                        'one_time',
                    ],
                ],
                'submenus'    => [
                    [
                        'order_key'    => 1,
                        'sub_menu_key' => 'request-for-documentation',
                        'title'        => 'Documentation Request',
                        'icon'         => 'fa-solid fa-folder-open',
                        'blade'        => 'request-for-documentation',
                    ],
                    [
                        'order_key'    => 2,
                        'sub_menu_key' => 'upload-registry-deed',
                        'title'        => 'Upload Registry Deed',
                        'icon'         => 'fa-solid fa-upload',
                        'blade'        => 'upload-registry-deed',
                    ],
                    [
                        'order_key'    => 3,
                        'sub_menu_key' => 'registry-generate-noc',
                        'title'        => 'Generate Registry NOC',
                        'icon'         => 'fa-solid fa-file-shield',
                        'blade'        => 'registry-generate-noc',
                    ],
                ],
            ],

            // NAME TRANSFER
            [
                'order_key'   => 12,
                'menu_key'    => 'name-transfer',
                'title'       => 'Name Transfer',
                'description' => 'Name transfer process',
                'icon'        => 'fa-solid fa-user-pen',
                'blade'       => 'name-transfer',
                'always_show' => true,
                'visible_if'  => [
                    'payment_option' => [
                        'emi',
                        'one_time',
                    ],
                ],
            ],

            // LEASE FREE HOLD
            [
                'order_key'   => 13,
                'menu_key'    => 'lease-free-hold',
                'title'       => 'Lease Free Hold',
                'description' => 'Lease free hold process',
                'icon'        => 'fa-solid fa-building-shield',
                'blade'       => 'lease-free-hold',
                'always_show' => true,
                'visible_if'  => [
                    'payment_option' => [
                        'emi',
                        'one_time',
                    ],
                ],
            ],

            // ALLOTMENT CANCELLATION
            [
                'order_key'   => 14,
                'menu_key'    => 'allotment-cancellation',
                'title'       => 'Allotment Cancellation',
                'description' => 'Allotment cancellation process',
                'icon'        => 'fa-solid fa-ban',
                'blade'       => 'allotment-cancellation',
                'always_show' => true,
                'visible_if'  => [
                    'payment_option' => [
                        'null',
                    ],
                ],
            ],
        ];
    }

    private function ensureProcessSteps(Allottee $allottee): void
    {
        // ALREADY EXISTS

        if (
            AllotteeProcessStep::where(
                'allottee_id',
                $allottee->id
            )->exists()
        ) {
            return;
        }

        $now    = now();
        $userId = Auth::id();

        $rows   = [];
        $stepNo = 1;

        foreach ($this->processStepBlueprint() as $menu) {

            $submenus = $menu['submenus'] ?? [[
                'sub_menu_key' => null,
                'title'        => $menu['title'],
                'blade'        => $menu['blade'] ?? null,
                'icon'         => $menu['icon'] ?? null,
            ]];

            foreach ($submenus as $index => $submenu) {

                $rows[] = [

                    // BASIC

                    'allottee_id'   => $allottee->id,

                    'menu_order'    => $menu['order_key'],
                    'step_order'    => $index + 1,

                    'step_no'       => $stepNo,

                    // MENU

                    'menu_key'      => $menu['menu_key'],
                    'sub_menu_key'  => $submenu['sub_menu_key'],

                    'process_group' => $menu['menu_key'],

                    // UI

                    'icons'         => $submenu['icon']
                        ?? $menu['icon']
                        ?? 'fa-solid fa-circle',

                    'title'         => $submenu['title'],

                    'description'   => $menu['description'] ?? null,

                    'blade'         => $submenu['blade'] ?? null,

                    // STATUS

                    'status'        => $this->resolveStepStatus(
                        menuOrder: $menu['order_key'],
                        subMenuIndex: $index
                    ),

                    'is_active'     => true,

                    'started_at'    => $now,

                    // META

                    'meta' => json_encode([
                        'always_show' => $menu['always_show'] ?? false,
                        'visible_if'  => $menu['visible_if'] ?? [],
                    ], JSON_UNESCAPED_UNICODE),

                    // AUDIT

                    'created_by'    => $userId,
                    'updated_by'    => $userId,

                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];

                $stepNo++;
            }
        }

        AllotteeProcessStep::upsert(
            $rows,
            [
                'allottee_id',
                'menu_key',
                'sub_menu_key'
            ],
            [
                'menu_order',
                'step_order',
                'process_group',

                'icons',

                'step_no',

                'title',
                'description',
                'blade',

                'status',
                'is_active',

                'meta',

                'updated_by',
                'updated_at'
            ]
        );
    }

    // RESOLVE STEP STATUS
    private function resolveStepStatus(
        int $menuOrder,
        int $subMenuIndex = 0
    ): string {

        // COMPLETED

        if (
            in_array($menuOrder, [1, 2, 3, 4])
        ) {
            return AllotteeProcessStep::STATUS_COMPLETED;
        }

        // ALLOTMENT

        if ($menuOrder === 4) {

            return match ($subMenuIndex) {

                0       => AllotteeProcessStep::STATUS_COMPLETED,

                1       => AllotteeProcessStep::STATUS_PENDING,

                default => AllotteeProcessStep::STATUS_LOCKED,
            };
        }

        // PAYMENT OPTION

        if ($menuOrder === 5) {
            return AllotteeProcessStep::STATUS_PENDING;
        }

        // DEFAULT

        return AllotteeProcessStep::STATUS_LOCKED;
    }

    private function refreshStepFlow(Allottee $allottee): void
    {
        $rows = AllotteeProcessStep::where('allottee_id', $allottee->id)->orderBy('step_no')->get()->keyBy('step_no');
        if ($rows->isEmpty()) {
            return;
        }
        $sequence = $allottee->payment_option === 'one_time'
            ? [1, 2, 3, 4, 5, 6, 7, 9, 8, 10, 11, 12, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]
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

    private function saveGeneratedPdf(Allottee $allottee, string $docName, string $type, string $content): string
    {
        // CHECK EXISTING DOCUMENT
        $exists = $allottee->generatedDocument()
            ->where('document_type', $type)
            ->exists();

        // ALREADY GENERATE
        if ($exists) {
            return 'Document already exists.';
        }

        if ($type == 'allotment-letter') {
            $year  = $allottee->allotment_year;
            $month = $allottee->allotment_month;
            $day   = $allottee->allotment_day;
        } else {
            $year  = date('Y');
            $month = date('m');
            $day   = date('d');
        }

        $folder = implode('/', ['documents', $type, 'generated', $year, $month, $day]);

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
            'document_name' => $docName,
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
            'applications.currentStep',
            'applications.currentUser',
            'applications.currentRole',
            'applications.movements.fromUser',
            'applications.movements.toUser',
            'applications.movements.fromRole',
            'applications.movements.toRole',
            'applications.movements.fromStep',
            'applications.movements.toStep',
            'applications.communicationTracks',
            'applications.correspondences.generatedBy',
            'applications.bypassRequests.requestedBy',
            'applications.bypassRequests.targetUser',
            'applications.bypassRequests.targetRole',
            'applications.bypassRequests.targetStep'
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
        $allottees = $query->latest('id')->paginate(20)->appends($request->query());
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

    public function signedDocumentUploads(Request $request)
    {
        // VALIDATE REQUEST
        $validated = $request->validate([
            'document_id'     => 'required',
            'allottee_id'     => 'required|integer',
            'document_name'   => 'required|string',
            'document_type'   => 'required|string',
            'issue_date'      => 'required|date',
            'document_number' => 'required|string|max:255',
            'stepNo'          => 'required|integer',
            'file'            => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();

        try {

            $userId    = Auth::id();
            $now       = now();
            $stepNo    = (int) $validated['stepNo'];
            $issueDate = Carbon::parse($validated['issue_date']);

            // FETCH ALLOTTEE DETAILS
            $allottee = Allottee::with('scheme.schemeFinance')
                ->findOrFail($validated['allottee_id']);

            // PREPARE FILE STORAGE PATH
            $folder = sprintf(
                'documents/%s/signed/%s/%s/%s',
                $validated['document_type'],
                $issueDate->format('Y'),
                $issueDate->format('m'),
                $issueDate->format('d')
            );

            $directory = public_path($folder);

            File::ensureDirectoryExists($directory, 0755, true);

            // UPLOAD FILE
            $file      = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            $fileName = sprintf(
                'signed-%s-%s-%s.%s',
                Str::slug($validated['document_type']),
                $now->format('YmdHis'),
                Str::random(6),
                $extension
            );

            $file->move($directory, $fileName);

            $filePath = "{$folder}/{$fileName}";

            // CREATE OR UPDATE DOCUMENT
            if ($validated['document_id'] === 'up') {

                $document = AllotteeGeneratedDocument::create([
                    'allottee_id'   => $allottee->id,
                    'document_name' => $validated['document_name'],
                    'document_type' => $validated['document_type'],
                    'file_name'     => $fileName,
                    'file_path'     => $filePath,
                    'generated_by'  => $userId,
                    'generated_at'  => $now,
                ]);
            } else {

                $document = AllotteeGeneratedDocument::findOrFail(
                    $validated['document_id']
                );

                $document->update([
                    'issue_date'         => $validated['issue_date'],
                    'document_number'    => $validated['document_number'],
                    'signed_file_name'   => $fileName,
                    'signed_file_path'   => $filePath,
                    'signed_uploaded_by' => $userId,
                    'signed_uploaded_at' => $now,
                ]);
            }

            // GENERATE ALLOTMENT PAYMENT ORDER
            $paymentOrder = null;

            if ($validated['document_type'] === 'allotment-letter') {

                $finance = $allottee->scheme->schemeFinance;

                $propertyAmount = (float) ($finance->property_total_cost ?? 0);
                $percentage     = (float) ($finance->allotment_percentage ?? 15);
                $allotmentAmount = (float) ($finance->allotement_amount ?? 0);

                $paymentOrder = AllotteePaymentOrder::updateOrCreate(
                    [
                        'allottee_id' => $allottee->id,
                        'order_type'  => 'allotment',
                    ],
                    [
                        'order_no'         => AllotteePaymentOrder::generateOrderNo('ODR-ALT'),
                        'title'            => "{$percentage}% Allotment Payment Order",
                        'property_amount'  => $propertyAmount,
                        'percentage'       => $percentage,
                        'base_amount'      => $allotmentAmount,
                        'total_payable'    => $allotmentAmount,
                        'remaining_amount' => $allotmentAmount,
                        'due_date'         => $issueDate->copy()->addDays(30),
                        'issued_at'        => $now,
                        'order_status'     => 'issued',
                        'remarks'          => 'Auto generated after signed allotment letter upload',
                        'created_by'       => $userId,
                    ]
                );
            }

            // COMPLETE CURRENT STEP
            $step = AllotteeProcessStep::where([
                'allottee_id' => $allottee->id,
                'step_no'     => $stepNo,
            ])->firstOrFail();

            if ($step->status === 'locked') {
                return response()->json([
                    'success' => false,
                    'message' => 'Step is locked.',
                ], 422);
            }

            $step->update([
                'status'       => 'completed',
                'completed_at' => $now,
                'completed_by' => $userId,
            ]);

            // UNLOCK NEXT STEP
            AllotteeProcessStep::where([
                'allottee_id' => $allottee->id,
                'step_no'     => $stepNo + 1,
            ])->update([
                'status' => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Signed document uploaded successfully',
                'file_url' => asset($filePath),
                'order_id' => $paymentOrder?->id,
                'order_no' => $paymentOrder?->order_no,
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('SIGNED DOCUMENT UPLOAD FAILED', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload signed document',
            ], 500);
        }
    }

    public function show(Allottee $allottee)
    {
        $allottee->load([
            'division',
            'subDivision',
            'propertyCategory',
            'propertyType',
            'quarterType',
            'scheme',
            'alloteeAdresses',
        ]);

        // PROCESS FLOW
        $processStage = $allottee->processSteps()
            ->exists();
        if (!$processStage) {
            $this->ensureProcessSteps($allottee);
            $this->refreshStepFlow($allottee);
        } else {
            $this->ensureProcessSteps($allottee);
        }

        // DOCUMENTS
        $documents = AllotteeGeneratedDocument::query()
            ->where('allottee_id', $allottee->id)
            ->latest()
            ->get();

        // PROCESS STEPS

        $steps = AllotteeProcessStep::query()
            ->where('allottee_id', $allottee->id)
            ->orderBy('menu_order')
            ->orderBy('step_order')
            ->get();

        // PROGRESS CALCULATION

        $totalSteps = $steps->count();

        $completedSteps = $steps
            ->where('status', 'completed')
            ->count();

        $activeStep = $steps
            ->firstWhere('status', 'pending');

        $currentStepNo = $activeStep?->step_no
            ?? ($completedSteps + 1);

        $progressPercent = $totalSteps > 0
            ? (int) round(($completedSteps / $totalSteps) * 100)
            : 0;

        return view('admin.allottee.show', [
            'allottee'        => $allottee,
            'steps'           => $steps,
            'documents'       => $documents,
            'totalSteps'      => $totalSteps,
            'currentStepNo'   => $currentStepNo,
            'progressPercent' => $progressPercent,
        ]);
    }

    public function section(Allottee $allottee, string $section)
    {
        $allowed = ['overview', 'allottees'];
        abort_unless(in_array($section, $allowed, true), 404);
        $allottee->load([
            'division:id,name',
            'subDivision:id,name',
            'propertyCategory:id,name',
            'alloteeAdresses',
        ]);
        $documents = AllotteeGeneratedDocument::where('allottee_id', $allottee->id)->get();
        return view("admin.allottee.sections.{$section}", compact('allottee', 'documents'));
    }

    public function processStep(Allottee $allottee, int $stepNo)
    {
        $relationWith = [
            'division',
            'subDivision',
            'propertyCategory',
            'scheme.schemeFinance',
            'propertyType',
            'propertySubType',
            'quarterType',
            'alloteeAdresses',
            'generatedDocument',
            'allotteeOrders',
            'allotteeTransaction',
        ];
        $allottee->load($relationWith);
        $this->ensureProcessSteps($allottee);
        // $this->refreshStepFlow($allottee);
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
        // $this->refreshStepFlow($allottee);
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
        // $this->refreshStepFlow($allottee);
        return back()->with('success', 'Payment option saved successfully.');
    }

    public function updatePaymentOption(Request $request, Allottee $allottee)
    {
        $validated = $request->validate([
            'payment_option' => 'required|in:emi,one_time',
        ]);

        DB::transaction(function () use ($request, $allottee, $validated) {

            $allottee->update([
                'payment_option'   => $validated['payment_option'],
                'updated_by'       => Auth::id(),
                'update_ip_address' => $request->ip(),
            ]);

            $finance = $allottee->scheme->schemeFinance;

            if (!$finance) {
                throw new \Exception("Scheme financial details not found for the selected property.");
            }

            $propertyAmount = (float) ($finance->property_total_cost ?? 0);

            $lotteryPercentage   = (float) ($finance->lottery_percentage ?? 10);
            $allotmentPercentage = (float) ($finance->allotment_percentage ?? 15);

            $balancePercentage = 100 - ($lotteryPercentage + $allotmentPercentage);

            $balanceAmount = (float) ($finance->balance_amount ?? 0);
            $adminCharges  = (float) ($finance->admin_charges ?? 0);

            $emiCount = (float) ($finance->emi_count ?? 0);
            $normalInterest = (float) ($finance->normal_interest_rate ?? 0);
            $penaltyInterest = (float) ($finance->penalty_interest_rate ?? 0);

            // ONE TIME PAYMENT
            if ($validated['payment_option'] === 'one_time') {

                // Remove EMI setup if already created
                AllotteeEmiSchedule::where('allottee_id', $allottee->id)
                    ->delete();

                AllotteeEmiAccount::where('allottee_id', $allottee->id)
                    ->delete();

                AllotteePaymentOrder::updateOrCreate(
                    [
                        'allottee_id' => $allottee->id,
                        'order_type'  => 'final',
                    ],
                    [
                        'order_no'         => AllotteePaymentOrder::generateOrderNo('ORD-FINAL'),
                        'title'            => "{$balancePercentage}% Final One Time Settlement",
                        'property_amount'  => $propertyAmount,
                        'percentage'       => $balancePercentage,
                        'base_amount'      => $balanceAmount,
                        'penalty_amount'   => 0,
                        'admin_charge'     => $adminCharges,
                        'total_payable'    => $balanceAmount + $adminCharges,
                        'paid_amount'      => 0,
                        'remaining_amount' => $balanceAmount + $adminCharges,
                        'payment_option'   => 'one_time',
                        'due_date'         => now()->addDays(30),
                        'issued_at'        => now(),
                        'order_status'     => 'issued',
                        'remarks'          => 'Auto generated one time payment order',
                        'created_by'       => Auth::id(),
                    ]
                );

                // Remove EMI Order if exists
                AllotteePaymentOrder::where(
                    'allottee_id',
                    $allottee->id
                )->ofType('emi')->delete();

                $nextSteps = [10, 11];
            }

            // In your updatePaymentOption method, replace the EMI section with:

            // EMI PAYMENT
            else {
                // Remove one-time order if exists
                AllotteePaymentOrder::where('allottee_id', $allottee->id)
                    ->ofType('final')
                    ->delete();

                $emiOrder = AllotteePaymentOrder::updateOrCreate(
                    [
                        'allottee_id' => $allottee->id,
                        'order_type'  => 'emi',
                    ],
                    [
                        'order_no'         => AllotteePaymentOrder::generateOrderNo('ORD-EMI'),
                        'title'            => "{$balancePercentage}% EMI Payment Order",
                        'property_amount'  => $propertyAmount,
                        'percentage'       => $balancePercentage,
                        'base_amount'      => $balanceAmount,
                        'penalty_amount'   => 0,
                        'admin_charge'     => 0,
                        'total_payable'    => $balanceAmount,
                        'paid_amount'      => 0,
                        'remaining_amount' => $balanceAmount,
                        'payment_option'   => 'emi',
                        'issued_at'        => now(),
                        'order_status'     => 'issued',
                        'remarks'          => 'Auto generated EMI order',
                        'created_by'       => Auth::id(),
                    ]
                );

                // EMI ACCOUNT
                $tenureMonths = $emiCount;
                $interestRate = $normalInterest;
                $penaltyRate  = $penaltyInterest;

                $emiAccount = AllotteeEmiAccount::updateOrCreate(
                    [
                        'allottee_id' => $allottee->id,
                        'order_id'    => $emiOrder->id,
                    ],
                    [
                        'account_no'            => 'EMI-' . str_pad($allottee->id, 6, '0', STR_PAD_LEFT),
                        'principal_amount'      => $balanceAmount,
                        'annual_interest_rate'  => $interestRate,
                        'penalty_interest_rate' => $penaltyRate,
                        'admin_charge'          => 10,
                        'tenure_months'         => $tenureMonths,
                        'account_status'        => 'active',
                        'emi_start_date'        => now()->addMonth()->startOfMonth(),
                        'emi_end_date'          => Carbon::parse(now()->addMonth()->startOfMonth())->addMonths($tenureMonths - 1),
                        'created_by'            => Auth::id(),
                    ]
                );

                // Generate first demand only if no demands exist
                if (!$emiAccount->demands()->exists()) {
                    app(EmiCalculatorService::class)->generateFirstDemand($emiAccount);
                }

                $nextSteps = [12, 13, 14, 15];
            }

            // STEP MANAGEMENT
            $step7 = AllotteeProcessStep::where(
                'allottee_id',
                $allottee->id
            )->where('step_no', 7)->first();

            if ($step7 && $step7->status !== 'completed') {
                $step7->update([
                    'status'       => 'completed',
                    'completed_at' => now(),
                    'completed_by' => Auth::id(),
                ]);
            }

            AllotteeProcessStep::where(
                'allottee_id',
                $allottee->id
            )->whereIn('step_no', $nextSteps)
                ->update([
                    'status' => 'pending',
                ]);
        });

        return back()->with(
            'success',
            'Payment option updated successfully.'
        );
    }

    /**
     * Process EMI payment
     */
    public function processEmiPayment(Request $request, Allottee $allottee, $demandId)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required|in:cash,cheque,dd,upi,netbanking,gateway',
        ]);

        DB::transaction(function () use ($request, $allottee, $demandId, $validated) {
            $demand = AllotteeMonthlyDemand::where('allottee_id', $allottee->id)
                ->where('id', $demandId)
                ->firstOrFail();

            $emiService = app(EmiCalculatorService::class);

            // Refresh penalty before payment
            $emiService->refreshPenalty($demand);

            // Apply payment
            $emiService->applyPayment($demand, $validated['amount'], $validated['payment_mode']);

            // Create transaction record
            AllotteeTransaction::create([
                'allottee_id' => $allottee->id,
                'demand_id' => $demand->id,
                'transaction_type' => 'emi_payment',
                'payment_stage' => 'emi',
                'amount' => $validated['amount'],
                'principal_amount' => $demand->principle_amount,
                'interest_amount' => $demand->interest_amount,
                'penalty_amount' => $demand->late_fine_penalty + $demand->penalty_interest_amount,
                'admin_charge' => $demand->penalty_admin_charges,
                'total_amount' => $validated['amount'],
                'payment_mode' => $validated['payment_mode'],
                'payment_status' => 'success',
                'transaction_no' => 'TXN-' . uniqid(),
                'paid_at' => now(),
                'created_by' => Auth::id(),
            ]);
        });

        return redirect()->back()->with('success', 'Payment processed successfully.');
    }

    public function allotmentLetter(Allottee $allottee)
    {
        // return $allottee;
        $steps = AllotteeProcessStep::where('allottee_id', $allottee->id)
            ->orderBy('step_no')
            ->get();
        return view('admin.allottee.letters.allotment', compact('allottee', 'steps'));
    }

    public function possessionLetter(Allottee $allottee)
    {
        $steps = AllotteeProcessStep::query()
            ->where('allottee_id', $allottee->id)
            ->orderBy('menu_order')
            ->orderBy('step_order')
            ->get();

        // PROGRESS CALCULATION

        $totalSteps = $steps->count();

        $completedSteps = $steps
            ->where('status', 'completed')
            ->count();

        $activeStep = $steps
            ->firstWhere('status', 'pending');

        $currentStepNo = $activeStep?->step_no
            ?? ($completedSteps + 1);

        $progressPercent = $totalSteps > 0
            ? (int) round(($completedSteps / $totalSteps) * 100)
            : 0;

        return view('admin.allottee.letters.possession', compact('allottee', 'steps', 'totalSteps', 'completedSteps', 'activeStep', 'currentStepNo', 'progressPercent'));
    }

    public function allotmentLetterPdf(Request $request, Allottee $allottee)
    {
        try {
            $allottee->update([
                'property_number' =>  Allottee::generateUniquePropertyNumber(),
            ]);

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
                $fileName = $this->saveGeneratedPdf($allottee, 'Allotment Letter', 'allotment-letter', $pdf->output());
            }
            if (!$request->boolean('download')) {
                return $pdf->stream($fileName);
            } else {
                return back()->with('success', 'PDF generated successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Error generating Allotment Letter PDF: ' . $e->getMessage());
            return back()->with('error', 'Error generating PDF. Please check the logs.');
        }
    }

    public function possessionLetterPdf(Request $request, Allottee $allottee)
    {
        try {
            $allottee->load(['division:id,name', 'subDivision:id,name', 'propertyCategory:id,name']);
            $pdf = Pdf::loadView('admin.allottee.letters.templates.possession-pdf', compact('allottee'))->setPaper('a4');
            $fileName = 'possession-letter-' . $allottee->id . '.pdf';
            if ($request->boolean('download')) {
                $fileName = $this->saveGeneratedPdf($allottee, 'Possession Letter', 'possession-letter', $pdf->output());
            }
            if (!$request->boolean('download')) {
                return $pdf->stream($fileName);
            } else {
                return back()->with('success', 'PDF generated successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Error generating Possession Letter PDF: ' . $e->getMessage());
            return back()->with('error', 'Error generating PDF. Please check the logs.');
        }
    }

    public function saveStep0(Step0Request $request, AllotteeService $allotteeService)
    {
        try {
            $result = $allotteeService->processStep0(
                $request->validated(),
                $request->ip(),
                $request->applicant_id
            );

            return response()->json([
                'success'      => true,
                'message'      => 'Payment details saved successfully.',
                'applicant_id' => $result['applicant_id'],
                'next_step'    => 1,
                'credentials'  => $result['credentials'] ?? null,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function getStep($step, $applicantId = null)
    {
        $step = (int) $step;
        if ($step === 0) {

            $applicant = $applicantId ? Allottee::find($applicantId) : new Allottee();

            $getSchemeList = $applicant->scheme_id
                ? Scheme::select('scheme_code', 'scheme_name')->where('id', $applicant->scheme_id)->first()
                : null;
            // return $applicant->division_id;
            $subdivisions = getSubDivisions(encryptId($applicant->division_id)) ?? [];
            $propertyTypes = getPropertyType(encryptId($applicant->pcategory_id)) ?? [];
            $propertySubTypes = getPropertySubType(encryptId($applicant->property_type_id)) ?? [];

            $transaction = AllotteeTransaction::where([
                'allottee_id'     => $applicant->id,
                'transaction_type' => 'lottery_payment',
                'payment_stage'   => 'application',
            ])->first();

            $applicant->payment_amount = $transaction->total_amount ?? '';
            $applicant->payment_day = $transaction->payment_day ?? '';
            $applicant->payment_month = $transaction->payment_month ?? '';
            $applicant->payment_year = $transaction->payment_year ?? '';
            $applicant->payment_utr_no = $transaction->utr_no ?? '';
            $applicant->payment_receipt_path = $transaction->receipt_path ?? '';
            $applicant->user_email = $applicant->user_id ? User::on('adms_allottees')->where('id', $applicant->user_id)->value('email') : '';

            return view('admin.allottee.step0', compact('applicant', 'getSchemeList', 'subdivisions', 'propertyTypes', 'propertySubTypes'));
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
        // return [$subdivisions , $propertyTypes , $propertySubTypes];
        return view($view, compact('applicant'));
    }

    public function create()
    {
        $divisions = Division::where('status', 1)->get();
        $allottee = Allottee::where('id', 1)->first();
        return view('admin.allottee.add', compact('allottee', 'divisions'));
    }

    public function saveStep1(Step1Request $request, AllotteeService $allotteeService)
    {
        try {
            $applicant = $allotteeService->processStep1(
                $request->validated(),
                $request->ip(),
                $request->applicant_id,
                $request->allottee_id
            );

            return response()->json([
                'success' => true,
                'message' => 'Allottee Details saved successfully',
                'applicant_id' => $applicant->id,
                'next_step' => 2
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function saveStep2(Step2Request $request, AllotteeService $allotteeService)
    {
        try {
            $record = $allotteeService->processStep2(
                $request->all(), // We pass all data because contact details weren't explicitly validated in the original
                $request->ip()
            );

            return response()->json([
                'success' => true,
                'message' => 'Address Details saved successfully',
                'data' => $record,
                'next_step' => 3
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save Address Details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function saveStep3(Step3Request $request, AllotteeService $allotteeService)
    {
        try {
            $allotteeService->processStep3(
                $request->validated(),
                $request->ip(),
                $request->userAgent()
            );

            return response()->json([
                'success' => true,
                'message' => 'Application Submit Successfully',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application',
                'error' => $e->getMessage()
            ], 500);
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

    public function deleteAllotteeComponents(Allottee $allottee)
    {
        $allottee->update([
            'payment_option' => null,
            'property_number' => null,
        ]);

        $allottee->processSteps()->delete();
        $allottee->allotteeOrders()->delete();
        $allottee->emiAccount()->delete();
        $allottee->emiSchedule()->delete();

        $allottee->allotteeTransaction()
            ->excludeLottery()
            ->delete();

        $allottee->generatedDocument()->delete();

        return redirect()
            ->route('admin.allottees.index')
            ->with('success', 'Allottee components deleted successfully.');
    }

    public function deleteEMISetup(Allottee $allottee)
    {
        $allottee->update([
            'payment_option' => null,
        ]);

        $allottee->emiAccount()->delete();
        $allottee->emiDemand()->delete();
        $allottee->emiSchedule()->delete();
        $allottee->allotteeTransaction()
            ->emi()
            ->delete();
        $allottee->generatedDocument()
            ->where('document_type', 'emi-payment-receipt')
            ->delete();

        AllotteeProcessStep::where('allottee_id', $allottee->id)
            ->whereIn('step_no', [16, 17, 18])
            ->update([
                'status' => 'locked',
            ]);

        return redirect()
            ->route('admin.allottees.index')
            ->with('success', 'Allottee EMI Setup Revoke successfully.');
    }

    public function getAllotteeApplications($allotteeId)
    {
        $applications = Application::with([
            'currentStep',
            'movements.fromUser',
            'movements.toUser',
            'movements.fromRole',
            'movements.toRole',
            'movements.fromStep',
            'movements.toStep'
        ])
            ->where('allottee_id', $allotteeId)
            ->get();
        return response()->json([
            'success' => true,
            'applications' => $applications,
        ]);
    }

    public function deleteApplication($applicationId, ApplicationService $applicationService)
    {
        $application = Application::findOrFail($applicationId);

        $deleted = $applicationService->deleteApplication($application);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Application and all related data deleted successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete application. Please try again.',
        ], 500);
    }

    public function createApplicationDirect(Request $request, $allotteeId, ApplicationService $applicationService)
    {
        $allottee = Allottee::findOrFail($allotteeId);
        $applicationType = $request->input('application_type', 'allotment');
        // return $allottee->is_step_completed;
        if ($allottee->is_step_completed != 1 && $allottee->current_step != 3) {
            return response()->json([
                'success' => false,
                'message' => 'Please complete full step first.',
            ], 400);
        }

        // Prevent duplicate applications
        $existing = Application::where('allottee_id', $allotteeId)
            ->where('application_type', $applicationType)
            ->exists();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'A ' . ucfirst($applicationType) . ' application already exists for this allottee.',
            ], 400);
        }

        try {
            $application = $applicationService->createApplication(
                $allottee,
                $applicationType,
                $request->ip(),
                $request->userAgent()
            );

            if ($application) {
                return response()->json([
                    'success' => true,
                    'message' => ucfirst($applicationType) . ' application created successfully.',
                    'application' => $application
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Workflow not found or creation failed.',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the application.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sendCredentialMail($id)
    {
        try {
            $allottee = Allottee::findOrFail($id);
            $user = User::on('adms_allottees')->where('username', $allottee->username)->first();

            if (!$user || !$user->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid email address found for this allottee.',
                ]);
            }

            $plainPassword = $this->generatePassword();
            $user->password = Hash::make($plainPassword);
            $user->save();

            Log::channel('user_credentials')->info('Credentials updated manually via Admin', [
                'username' => $user->username,
                'password' => $plainPassword
            ]);

            $portalUrl = config('jshb.allottee_portal_url', 'http://localhost/jshb-allottees');

            $notificationService = app(NotificationService::class);
            $notificationService->send([
                'user_id' => $user->id,
                'allottee_id' => $allottee->id,
                'is_allottee' => true,
                'notification_type' => 'info',
                'subject' => 'Your JSHB Allottee Portal Login Credentials',
                'message' => 'Credentials sent manually by admin.',
                'email_id' => $user->email,
                'send_email' => true,
                'mailable' => new AllotteeCredentialMail($user->username, $plainPassword, $portalUrl)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Credential mail sent successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send credential mail manually: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send mail: ' . $e->getMessage()
            ], 500);
        }
    }
}
