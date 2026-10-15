<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\FutureAllottee;
use App\Models\FutureAllotteeContactDetail;
use App\Models\FutureAllotteeTransaction;
use App\Models\Allottee;
use App\Models\AllotteesContactDetail;
use App\Models\AllotteeTransaction;
use App\Models\AllotteeLedger;
use App\Models\AllotteeStageTracker;
use App\Models\Application;
use App\Models\Division;
use App\Models\SubDivision;
use App\Models\QuarterType;
use App\Models\User;
use App\Models\State;
use App\Models\District;
use App\Services\AllotteeService;
use App\Services\ApplicationService;
use Exception;

class FutureAllotteeSyncService
{
    protected AllotteeService $allotteeService;
    protected ApplicationService $applicationService;

    public function __construct(AllotteeService $allotteeService, ApplicationService $applicationService)
    {
        $this->allotteeService = $allotteeService;
        $this->applicationService = $applicationService;
    }

    /**
     * Migrate a single FutureAllottee record to the live system
     */
    public function syncSingle(FutureAllottee $futureAllottee, ?string $ipAddress = null, ?string $userAgent = null): array
    {
        if ($futureAllottee->sync_status === 'synced') {
            return [
                'status'  => 'already_synced',
                'message' => "Record {$futureAllottee->reference_portal_id} is already migrated.",
                'allottee_id' => $futureAllottee->synced_allottee_id,
                'application_id' => $futureAllottee->synced_application_id,
            ];
        }

        $ip = $ipAddress ?? (request()->ip() ?? '127.0.0.1');
        $agent = $userAgent ?? (request()->userAgent() ?? 'System Sync Engine');
        $actingUser = Auth::user() ?? User::on('adms_jshb')->whereIn('role_id', [8, 9])->first() ?? User::on('adms_jshb')->first();
        $actingUserId = $actingUser ? $actingUser->id : 6;
        if (!Auth::check() && $actingUser) {
            Auth::login($actingUser);
        }

        DB::connection('adms_allottees')->beginTransaction();
        DB::connection('adms_jshb')->beginTransaction();

        try {
            // 1. Generate unique username & password
            $paymentYear = $futureAllottee->transaction?->payment_year ?? date('Y');
            do {
                $username = $this->allotteeService->generateUniqueUsername(
                    $futureAllottee->division_id,
                    $futureAllottee->quarter_id,
                    $futureAllottee->subdivision_id,
                    $paymentYear
                );
            } while (Allottee::where('username', $username)->exists());

            $plainPassword = $this->allotteeService->generatePassword();

            // 2. Create or update User on adms_allottees
            $fullName = $futureAllottee->full_name ?: 'Applicant';
            $user = User::on('adms_allottees')
                ->where('username', $username)
                ->when($futureAllottee->email, function ($q) use ($futureAllottee) {
                    return $q->orWhere('email', $futureAllottee->email);
                })
                ->first();

            if (!$user) {
                $user = new User();
                $user->setConnection('adms_allottees');
                $user->name = $fullName;
                $user->username = $username;
                $user->email = $futureAllottee->email;
                $user->login_with_otp = false;
                $user->password_created_at = now();
                $user->status = true;
                $user->password = Hash::make($plainPassword);
                $user->save();
            } else {
                $user->name = $fullName;
                $user->username = $username;
                $user->password = Hash::make($plainPassword);
                $user->password_created_at = now();
                $user->save();
            }

            // 3. Generate Unique Property Number
            $propertyNumber = Allottee::generateUniquePropertyNumber();
            $propertyNumberHindi = Allottee::convertPropertyNumberToHindi($propertyNumber);

            // 4. Create Live Allottee
            $allottee = new Allottee();
            $allottee->setConnection('adms_allottees');
            $allottee->division_id       = $futureAllottee->division_id;
            $allottee->subdivision_id    = $futureAllottee->subdivision_id;
            $allottee->pcategory_id      = $futureAllottee->pcategory_id;
            $allottee->property_type_id  = $futureAllottee->property_type_id;
            $allottee->p_sub_type_id     = $futureAllottee->p_sub_type_id;
            $allottee->quarter_id        = $futureAllottee->quarter_id;
            $allottee->scheme_id         = $futureAllottee->scheme_id;
            $allottee->property_number   = $propertyNumber;
            $allottee->property_number_hindi = $propertyNumberHindi;
            $allottee->username          = $username;
            $allottee->password          = Hash::make($plainPassword);
            $allottee->user_id           = $user->id;
            $allottee->current_step      = 2;
            $allottee->is_synced         = 1;
            $allottee->allottee_create_date = now();
            $allottee->create_ip_address = $ip;
            $allottee->created_by        = $actingUserId;

            // Personal Information
            $allottee->prefix                = $futureAllottee->prefix;
            $allottee->allottee_name          = $futureAllottee->allottee_name;
            $allottee->allottee_middle_name   = $futureAllottee->allottee_middle_name;
            $allottee->allottee_surname       = $futureAllottee->allottee_surname;
            $allottee->allottee_prefix_hindi  = $futureAllottee->allottee_prefix_hindi;
            $allottee->allottee_name_hindi    = $futureAllottee->allottee_name_hindi;
            $allottee->allottee_middle_hindi  = $futureAllottee->allottee_middle_hindi;
            $allottee->allottee_surname_hindi = $futureAllottee->allottee_surname_hindi;
            $allottee->allottee_relation_type = $futureAllottee->allottee_relation_type;
            $allottee->relation_name         = $futureAllottee->relation_name;
            $allottee->relation_prefix_hindi = $futureAllottee->relation_prefix_hindi;
            $allottee->relation_name_hindi   = $futureAllottee->relation_name_hindi;
            $allottee->marital_status        = $futureAllottee->marital_status;
            $allottee->allottee_gender       = $futureAllottee->allottee_gender;
            $allottee->pan_card_number       = substr(strtoupper(trim((string)$futureAllottee->pan_card_number)), 0, 10);
            $cleanAadhar                     = preg_replace('/\D/', '', (string)$futureAllottee->aadhar_card_number);
            $allottee->aadhar_card_number    = substr($cleanAadhar, 0, 12);
            $allottee->allottee_category     = $futureAllottee->allottee_category;
            $allottee->allottee_category_hindi = $futureAllottee->allottee_category_hindi;
            $allottee->allottee_religion     = $futureAllottee->allottee_religion;
            $allottee->allottee_nationality  = $futureAllottee->allottee_nationality;
            $allottee->date_of_birth_day     = $futureAllottee->date_of_birth_day;
            $allottee->date_of_birth_month   = $futureAllottee->date_of_birth_month;
            $allottee->date_of_birth_year    = $futureAllottee->date_of_birth_year;
            $allottee->allottee_remarks      = $futureAllottee->remarks;
            $allottee->save();

            // 5. Create Live Lottery Transaction
            $fTxn = $futureAllottee->transaction;
            $amount = $fTxn ? $fTxn->amount : 25000.00;
            $paidAt = $fTxn && $fTxn->paid_at ? $fTxn->paid_at : now();
            $paymentDay   = $fTxn?->payment_day ?? date('d');
            $paymentMonth = $fTxn?->payment_month ?? date('m');
            $paymentYear  = $fTxn?->payment_year ?? date('Y');

            $rawMode = strtolower(trim((string)($fTxn?->payment_mode ?? 'cheque')));
            $allowedModes = ['cash', 'cheque', 'dd', 'upi', 'netbanking', 'gateway', 'adjustment', 'new'];
            if (!in_array($rawMode, $allowedModes)) {
                $rawMode = ($rawMode === 'online') ? 'netbanking' : 'cheque';
            }

            $txnNo = $fTxn?->transaction_no;
            if (!$txnNo || AllotteeTransaction::where('transaction_no', $txnNo)->exists()) {
                $txnNo = 'TXN' . date('YmdHis') . rand(100, 999);
            }

            $utrNo = $fTxn?->utr_no;
            if (!$utrNo || AllotteeTransaction::where('utr_no', $utrNo)->exists()) {
                $utrNo = 'UTR' . date('Ymd') . rand(10000000, 99999999);
            }

            $payment = AllotteeTransaction::create([
                'allottee_id'      => $allottee->id,
                'transaction_type' => 'lottery_payment',
                'payment_stage'    => 'application',
                'amount'           => $amount,
                'principal_amount' => $amount,
                'total_amount'     => $amount,
                'payment_mode'     => $rawMode,
                'payment_status'   => 'success',
                'transaction_no'   => $txnNo,
                'utr_no'           => $utrNo,
                'receipt_file'     => $fTxn?->receipt_file ?? 'sample_lottery_receipt.pdf',
                'receipt_path'     => $fTxn?->receipt_path ?? 'uploads/future_allottees/sample_lottery_receipt.pdf',
                'remarks'          => $fTxn?->remarks ?? 'Lottery Payment from Citizen Portal Sync',
                'payment_day'      => $paymentDay,
                'payment_month'    => $paymentMonth,
                'payment_year'     => $paymentYear,
                'paid_at'          => $paidAt,
                'created_by'       => $actingUserId,
            ]);

            // 6. Update Allottee Ledger
            $lastLedger = AllotteeLedger::where('allottee_id', $allottee->id)->orderBy('id', 'desc')->first();
            $runningBalance = ($lastLedger->running_balance ?? 0) - $amount;

            AllotteeLedger::create([
                'allottee_id'       => $allottee->id,
                'payment_id'        => $payment->id,
                'order_id'          => $payment->id,
                'transaction_date'  => now(),
                'transaction_type'  => 'lottery_payment',
                'transaction_mode'  => $payment->payment_mode,
                'description'       => 'Lottery Payment Received (Citizen Portal Sync)',
                'debit_amount'      => 0,
                'credit_amount'     => $amount,
                'running_principal' => 0,
                'running_balance'   => $runningBalance,
                'reference_no'      => $payment->utr_no,
                'remarks'           => 'Initial lottery payment migrated from future portal',
                'created_by'        => $actingUserId
            ]);

            // 7. Record Stage Tracker
            AllotteeStageTracker::create([
                'allottee_id'    => $allottee->id,
                'application_no' => $allottee->application_no,
                'stage_type'     => 'lottery_payment',
                'status'         => 'completed',
                'action_by'      => $actingUserId,
            ]);

            // 8. Create Live Contact Details
            $fContact = $futureAllottee->contactDetail;
            if ($fContact) {
                $resolveStateId = function ($stateVal) {
                    if (is_numeric($stateVal) && (int)$stateVal > 0) {
                        return (int)$stateVal;
                    }
                    if (!empty($stateVal)) {
                        $state = State::where('name_en', 'like', "%{$stateVal}%")
                            ->orWhere('display_name', 'like', "%{$stateVal}%")
                            ->first();
                        if ($state) return $state->id;
                    }
                    return 15; // default Jharkhand
                };

                $resolveDistrictId = function ($districtVal) {
                    if (is_numeric($districtVal) && (int)$districtVal > 0) {
                        return (int)$districtVal;
                    }
                    if (!empty($districtVal)) {
                        $cleanDist = str_replace(['-', '_'], ' ', $districtVal);
                        $dist = District::where('name_en', 'like', "%{$cleanDist}%")
                            ->orWhere('name_en', 'like', '%' . explode(' ', $cleanDist)[0] . '%')
                            ->first();
                        if ($dist) return $dist->id;
                    }
                    return 281; // default Ranchi
                };

                $relStateId  = $resolveStateId($fContact->relation_state);
                $relDistId   = $resolveDistrictId($fContact->relation_district);
                $presStateId = $resolveStateId($fContact->present_state);
                $presDistId  = $resolveDistrictId($fContact->present_district);
                $permStateId = $resolveStateId($fContact->permanent_state);
                $permDistId  = $resolveDistrictId($fContact->permanent_district);

                AllotteesContactDetail::create([
                    'allottee_id'                   => $allottee->id,
                    'mobile_number'                 => $futureAllottee->mobile,
                    'email'                         => $futureAllottee->email,
                    'relation_type'                 => $fContact->relation_type,
                    'prefix_relation_eng'           => $fContact->prefix_relation_eng,
                    'relation_name'                 => $fContact->relation_name,
                    'prefix_relation_hindi'         => $fContact->prefix_relation_hindi,
                    'relation_name_hindi'           => $fContact->relation_name_hindi,
                    'relation_address'              => $fContact->relation_address,
                    'relation_address_hindi'        => $fContact->relation_address_hindi,
                    'relation_state'                => $relStateId,
                    'relation_district'             => $relDistId,
                    'relation_pincode'              => substr((string)$fContact->relation_pincode, 0, 10),
                    'relation_post_office'          => $fContact->relation_post_office,
                    'relation_post_office_hindi'    => $fContact->relation_post_office_hindi,
                    'relation_police_station'       => $fContact->relation_police_station,
                    'relation_police_station_hindi' => $fContact->relation_police_station_hindi,
                    'same_as_relation_copy'         => $fContact->same_as_relation_copy ? '1' : '0',
                    'present_address'               => $fContact->present_address,
                    'present_address_hindi'         => $fContact->present_address_hindi,
                    'present_state'                 => $presStateId,
                    'present_district'              => $presDistId,
                    'present_pincode'               => substr((string)$fContact->present_pincode, 0, 10),
                    'present_post_office'           => $fContact->present_post_office,
                    'present_post_office_hindi'     => $fContact->present_post_office_hindi,
                    'present_police_station'        => $fContact->present_police_station,
                    'present_police_station_hindi'  => $fContact->present_police_station_hindi,
                    'same_as_present_place_residance' => $fContact->same_as_present_place_residance ? '1' : '0',
                    'permanent_address'             => $fContact->permanent_address,
                    'permanent_address_hindi'       => $fContact->permanent_address_hindi,
                    'permanent_state'               => $permStateId,
                    'permanent_district'            => $permDistId,
                    'permanent_pincode'             => substr((string)$fContact->permanent_pincode, 0, 10),
                    'permanent_post_office'         => $fContact->permanent_post_office,
                    'permanent_post_office_hindi'   => $fContact->permanent_post_office_hindi,
                    'permanent_police_station'      => $fContact->permanent_police_station,
                    'permanent_police_station_hindi' => $fContact->permanent_police_station_hindi,
                    'same_as_permanent_address'     => $fContact->same_as_permanent_address ? '1' : '0',
                    'create_ip_address'             => $ip,
                    'created_by'                    => $actingUserId,
                    'updated_by'                    => $actingUserId,
                ]);
            }

            // 9. Step 3 Finalization: Generate Allotment Number
            $divCode = $allottee->division ? strtoupper($allottee->division->division_code) : 'XXX';
            $rand2   = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
            $rand6   = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            $allottee->is_step_completed = 1;
            $allottee->is_synced         = 1;
            $allottee->allotment_no      = 'ALT/JSHB/' . date('Y') . '/' . date('m') . '/' . $rand2 . date('d') . '/' . $divCode . '/' . $rand6;
            $allottee->application_no    = $futureAllottee->application_no ?: ('JSHB' . date('Y') . date('m') . $rand6);
            $allottee->application_day   = $futureAllottee->application_day ?: (int) date('d');
            $allottee->application_month = $futureAllottee->application_month ?: (int) date('m');
            $allottee->application_year  = $futureAllottee->application_year ?: (int) date('Y');
            $allottee->allotment_day     = date('d');
            $allottee->allotment_month   = date('m');
            $allottee->allotment_year    = date('Y');
            $allottee->current_step      = 3;
            $allottee->save();

            // 10. Automatically create the official Workflow Application
            $application = $this->applicationService->createApplication(
                $allottee,
                'allotment',
                $ip,
                $agent
            );

            // 11. Update Staging Record
            $futureAllottee->sync_status           = 'synced';
            $futureAllottee->synced_allottee_id     = $allottee->id;
            $futureAllottee->synced_application_id  = $application?->id;
            $futureAllottee->synced_at              = now();
            $futureAllottee->error_log              = null;
            $futureAllottee->save();

            DB::connection('adms_allottees')->commit();
            DB::connection('adms_jshb')->commit();

            // Log credentials to user_credentials.log
            Log::channel('user_credentials')->info('New User Created', [
                'username' => $username,
                'password' => $plainPassword
            ]);

            return [
                'status'         => 'success',
                'message'        => "Applicant {$futureAllottee->reference_portal_id} ({$fullName}) synced successfully!",
                'allottee_id'    => $allottee->id,
                'application_id' => $application?->id,
                'application_no' => $application?->application_no,
                'allotment_no'   => $allottee->allotment_no,
                'username'       => $username,
                'password'       => $plainPassword,
            ];
        } catch (Exception $e) {
            DB::connection('adms_allottees')->rollBack();
            DB::connection('adms_jshb')->rollBack();

            Log::error('Future Allottee Sync Failed', [
                'future_allottee_id' => $futureAllottee->id,
                'ref'                => $futureAllottee->reference_portal_id,
                'error'              => $e->getMessage(),
                'trace'              => $e->getTraceAsString(),
            ]);

            $futureAllottee->sync_status = 'failed';
            $futureAllottee->error_log   = $e->getMessage();
            $futureAllottee->save();

            return [
                'status'  => 'error',
                'message' => 'Sync failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Batch Sync multiple records
     */
    public function syncBatch(array $ids = []): array
    {
        $query = FutureAllottee::where('sync_status', 'pending');
        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        $records = $query->get();
        $results = [
            'total'   => $records->count(),
            'synced'  => 0,
            'failed'  => 0,
            'details' => [],
        ];

        foreach ($records as $item) {
            $res = $this->syncSingle($item);
            if ($res['status'] === 'success') {
                $results['synced']++;
            } else {
                $results['failed']++;
            }
            $results['details'][] = [
                'id'      => $item->id,
                'ref'     => $item->reference_portal_id,
                'status'  => $res['status'],
                'message' => $res['message'],
            ];
        }

        return $results;
    }
}
