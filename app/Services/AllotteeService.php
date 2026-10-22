<?php

namespace App\Services;

use App\Mail\AllotteeCredentialMail;

use App\Models\Allottee;
use App\Models\AllotteeDocument;
use App\Models\AllotteeEmiAccount;
use App\Models\AllotteeEmiLedger;
use App\Models\AllotteeEmiSchedule;
use App\Models\AllotteeGeneratedDocument;
use App\Models\AllotteeInitialPayment;
use App\Models\AllotteeLedger;
use App\Models\AllotteeMonthlyDemand;
use App\Models\AllotteeNomineeBankDetail;
use App\Models\AllotteeNotification;
use App\Models\AllotteePaymentOrder;
use App\Models\AllotteePaymentTransaction;
use App\Models\AllotteeProcessStep;
use App\Models\AllotteePropertyFinDetail;
use App\Models\AllotteeSiteVerification;
use App\Models\AllotteeStageTracker;
use App\Models\AllotteeStepDuration;
use App\Models\AllotteeTransaction;
use App\Models\AllotteesContactDetail;
use App\Models\Application;
use App\Models\ApplicationAuditTrail;
use App\Models\ApplicationCorrespondence;
use App\Models\ApplicationDocument;
use App\Models\ApplicationExtensionRequest;
use App\Models\ApplicationMovement;
use App\Models\ApplicationNote;
use App\Models\BypassRequest;
use App\Models\CommunicationTrack;
use App\Models\Division;
use App\Models\DocumentGenerationQueue;
use App\Models\DocumentRequest;
use App\Models\FutureAllottee;
use App\Models\Notification;
use App\Models\QuarterType;
use App\Models\Scheme;
use App\Models\SubDivision;
use App\Models\User;
use App\Traits\DocumentUploadTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AllotteeService
{
    use DocumentUploadTrait;

    public function processStep0(array $data, $ipAddress, $applicantId = null)
    {
        DB::beginTransaction();

        try {
            $divisionId        = decryptId($data['division_id']);
            $subDivisionId     = decryptId($data['subdivision_id']);
            $pcategoryId       = decryptId($data['pcategory_id']);
            $propertyTypeId    = decryptId($data['property_type_id']);
            $quarterId         = decryptId($data['quarter_id']);

            $applicant = $applicantId ? Allottee::find($applicantId) : new Allottee();

            $rawSubType = $data['p_sub_type_id'] ?? null;
            if (!empty($rawSubType)) {
                $propertySubTypeId = is_numeric($rawSubType) ? (int) $rawSubType : decryptId($rawSubType);
            } else {
                $scheme = isset($data['scheme_id']) ? Scheme::find($data['scheme_id']) : null;
                $propertySubTypeId = $applicant->p_sub_type_id ?: ($scheme?->p_sub_type_id ?? null);
            }

            $isDraftLogin = !$applicant->exists || Str::startsWith((string) $applicant->username, 'DRAFT_');
            $plainPassword = null;

            if ($isDraftLogin) {
                $usersname = $this->generateUniqueUsername($divisionId, $quarterId, $subDivisionId, $data['payment_year']);
                $plainPassword = $this->generatePassword();
                $applicant->username = $usersname;
                $applicant->password = Hash::make($plainPassword);
                $applicant->create_ip_address = $ipAddress;
                $applicant->created_by = Auth::id();
                $applicant->is_synced = 0;
            }

            if (empty($applicant->property_number)) {
                $generatedNumber = Allottee::generateUniquePropertyNumber();

                $applicant->property_number = $generatedNumber;
                $applicant->property_number_hindi = Allottee::convertPropertyNumberToHindi($generatedNumber);
            }

            $applicant->fill([
                'division_id'       => $divisionId,
                'subdivision_id'    => $subDivisionId,
                'pcategory_id'      => $pcategoryId,
                'property_type_id'  => $propertyTypeId,
                'p_sub_type_id'     => $propertySubTypeId,
                'quarter_id'        => $quarterId,
                'scheme_id'         => $data['scheme_id'],
                'current_step'      => 1,
                'update_ip_address' => $ipAddress,
                'updated_by'        => Auth::id(),
            ]);

            $transaction = AllotteeTransaction::where([
                'allottee_id'      => $applicant->id,
                'transaction_type' => 'lottery_payment',
                'payment_stage'    => 'application',
            ])->first();

            $receiptFile = $transaction?->receipt_file;
            $receiptPath = $transaction?->receipt_path;

            if (isset($data['payment_receipt'])) {
                $file = $data['payment_receipt'];
                $scheme = Scheme::find($data['scheme_id']);

                $uploadResult = $this->uploadToDocumentApi(
                    $file,
                    'LOTTERY',
                    $scheme->scheme_code ?? 'SCH',
                    $applicant->property_number ?? 'PROP',
                    $data['payment_year'],
                    $data['payment_month'],
                    $data['payment_day'],
                    $receiptPath
                );

                $receiptPath = $uploadResult['file_path'];
                $receiptFile = $uploadResult['file_name'];
            }

            $user = User::on('adms_allottees')->where('username', $applicant->username)->first();

            if (!$user) {
                $fullName = trim(implode(' ', array_filter([
                    $applicant->allottee_name,
                    $applicant->allottee_middle_name,
                    $applicant->allottee_surname,
                ])));

                if (empty($fullName)) {
                    $fullName = 'Applicant';
                }

                $user = new User();
                $user->setConnection('adms_allottees');
                $user->name = $fullName;
                $user->username = $applicant->username;
                $user->email = $data['email'];
                $user->login_with_otp = false;
                $user->password_created_at = now();
                $user->status = true;
                $user->password = $applicant->password;
                $user->save();
            } else {
                $user->email = $data['email'];
                $user->save();
            }

            $applicant->user_id = $user->id;
            $applicant->save();

            $amount = str_replace(',', '', $data['payment_amount']);
            $paidAt = Carbon::create(
                $data['payment_year'],
                $data['payment_month'],
                $data['payment_day'],
                now()->hour,
                now()->minute,
                now()->second
            );

            $payment = AllotteeTransaction::updateOrCreate(
                [
                    'allottee_id'      => $applicant->id,
                    'transaction_type' => 'lottery_payment',
                    'payment_stage'    => 'application',
                ],
                [
                    'amount'           => $amount,
                    'principal_amount' => $amount,
                    'total_amount'     => $amount,
                    'payment_mode'     => 'cheque',
                    'payment_status'   => 'success',
                    'utr_no'           => $data['payment_utr_no'] ?? null,
                    'receipt_file'     => $receiptFile,
                    'receipt_path'     => $receiptPath,
                    'remarks'          => 'Lottery Payment',
                    'payment_day'      => $data['payment_day'],
                    'payment_month'    => $data['payment_month'],
                    'payment_year'     => $data['payment_year'],
                    'paid_at'          => $paidAt,
                    'created_by'       => Auth::id(),
                ]
            );

            // UPDATE ALLOTTEE LEDGER
            $lastLedger = AllotteeLedger::where('allottee_id', $applicant->id)->orderBy('id', 'desc')->first();
            $runningBalance = ($lastLedger->running_balance ?? 0) - $amount;

            AllotteeLedger::create([
                'allottee_id'      => $applicant->id,
                'payment_id'       => $payment->id,
                'order_id'         => $payment->id,
                'transaction_date' => now(),
                'transaction_type' => 'lottery_payment',
                'transaction_mode' => 'cheque',
                'description'      => 'Lottery Payment Received',
                'debit_amount'     => 0,
                'credit_amount'    => $amount,
                'running_principal' => 0,
                'running_balance'  => $runningBalance,
                'reference_no'     => $data['payment_utr_no'] ?? null,
                'remarks'          => 'Initial lottery payment',
                'created_by'       => Auth::id()
            ]);

            DB::commit();

            AllotteeStageTracker::create([
                'allottee_id'    => $applicant->id,
                'application_no' => $applicant->application_no,
                'stage_type'     => 'lottery_payment',
                'status'         => 'completed',
                'action_by'      => Auth::id(),
            ]);

            return [
                'applicant_id' => $applicant->id,
                'credentials'  => $plainPassword ? [
                    'username' => $applicant->username,
                    'password' => $plainPassword
                ] : null,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Process Step0 Failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);
            throw $e;
        }
    }

    public function processStep1(array $data, $ipAddress, $applicantId = null, $allotteeId = null)
    {
        $existingId = $applicantId ?? $allotteeId;

        $applicant = $existingId ? Allottee::find($existingId) : new Allottee();

        if ($existingId && !$applicant) {
            throw new \Exception('Application not found.');
        }

        if (!empty($applicant->username)) {
            $user = User::on('adms_allottees')->where('username', $applicant->username)->first();
            if ($user) {
                $fullName = trim(implode(' ', array_filter([
                    $data['allottee_name'] ?? '',
                    $data['allottee_middle_name'] ?? '',
                    $data['allottee_surname'] ?? '',
                ])));
                if (!empty($fullName) && $user->name !== $fullName) {
                    $user->name = $fullName;
                    $user->save();
                }
            }
        }

        $applicant->fill([
            'application_no' => $data['application_no'],
            'application_day' => $data['application_day'],
            'application_month' => $data['application_month'],
            'application_year' => $data['application_year'],
            'prefix' => $data['prefix'],
            'allottee_name' => $data['allottee_name'],
            'allottee_middle_name' => $data['allottee_middle_name'] ?? null,
            'allottee_surname' => $data['allottee_surname'] ?? null,
            'allottee_relation_type' => $data['relation_prefix'],
            'allottee_prefix_hindi' => $data['allottee_prefix_hindi'] ?? null,
            'allottee_name_hindi' => $data['allottee_name_hindi'],
            'allottee_middle_hindi' => $data['allottee_middle_hindi'] ?? null,
            'allottee_surname_hindi' => $data['allottee_surname_hindi'] ?? null,
            'relation_prefix_hindi' => $data['relation_prefix_hindi'],
            'relation_name_hindi' => $data['relation_name_hindi'],
            'relation_name' => $data['relation_name'],
            'marital_status' => $data['marital_status'] ?? null,
            'allottee_gender' => $data['allottee_gender'] ?? null,
            'pan_card_number' => $data['pan_card_number'] ?? null,
            'aadhar_card_number' => $data['aadhar_card_number'] ?? null,
            'allottee_category' => $data['allottee_category'] ?? null,
            'allottee_category_hindi' => $data['allottee_category_hindi'] ?? null,
            'allottee_religion' => $data['allottee_religion'] ?? null,
            'allottee_nationality' => $data['allottee_nationality'] ?? null,
            'date_of_birth_day' => $data['date_of_birth_day'],
            'date_of_birth_month' => $data['date_of_birth_month'],
            'date_of_birth_year' => $data['date_of_birth_year'],
            'allottee_remarks' => $data['allottee_remarks'] ?? null,
            'current_step' => 2,
        ]);

        if (!$applicant->exists) {
            $applicant->allottee_create_date = now();
            $applicant->create_ip_address = $ipAddress;
            $applicant->created_by = Auth::id();
        } else {
            $applicant->update_ip_address = $ipAddress;
            $applicant->updated_by = Auth::id();
        }

        $applicant->save();

        return $applicant;
    }

    public function processStep2(array $data, $ipAddress)
    {
        $applicantId = $data['applicant_id'];
        $data['update_ip_address'] = $ipAddress;

        if (empty($data['id'])) {
            $data['create_ip_address'] = $ipAddress;
            $data['created_by'] = Auth::id();
        }
        $data['updated_by'] = Auth::id();

        $record = AllotteesContactDetail::updateOrCreate(
            ['allottee_id' => $applicantId],
            $data
        );

        $applicant = Allottee::find($applicantId);
        if ($applicant) {
            $applicant->current_step = 3;
            $applicant->save();
        }

        return $record;
    }

    public function processStep3(array $data, $ipAddress, $userAgent)
    {
        DB::beginTransaction();

        try {
            $allottee = Allottee::find($data['applicant_id']);
            if (!$allottee) {
                throw new \Exception('Applicant not found');
            }

            $divCode = $allottee->division ? strtoupper($allottee->division->division_code) : 'XXX';
            $rand2 = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
            $rand6 = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            $allottee->is_step_completed = 1;
            $allottee->allotment_no = 'ALT/JSHB/' . date('Y') . '/' . date('m') . '/' . $rand2 . date('d') . '/' . $divCode . '/' . $rand6;
            $allottee->allotment_day = date('d');
            $allottee->allotment_month = date('m');
            $allottee->allotment_year = date('Y');
            $allottee->save();

            $applicationService = app(ApplicationService::class);
            $application = $applicationService->createApplication(
                $allottee,
                'allotment',
                $ipAddress,
                $userAgent
            );

            DB::commit();

            // Send Emails only for new applications
            if ($application) {
                $this->sendCompletionEmails($allottee, $application);
            }

            return $allottee;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to submit application: ' . $e->getMessage());
            throw $e;
        }
    }

    private function sendCompletionEmails($allottee, $application)
    {
        try {
            $user = User::on('adms_allottees')->where('username', $allottee->username)->first();
            if ($user && $user->email) {
                $plainPassword = $this->generatePassword();
                $user->password = Hash::make($plainPassword);
                $user->save();

                Log::channel('user_credentials')->info('New User Created', [
                    'username' => $user->username,
                    'password' => $plainPassword
                ]);

                // ----------------------------------------------------
                // PROFESSIONAL ARCHITECTURE: FIRE EVENT INSTEAD OF DIRECT MAIL
                // ----------------------------------------------------
                event(new \App\Events\AllotteeCreated($user, $allottee, $application ?? null, $plainPassword));

                // Send Welcome Email
                if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    $allotteeSubject = "Welcome to JSHB - Complete Your Process";
                    $allotteeMessage = "Dear {$user->name},\n\nYour account has been successfully created in the JSHB Portal.\n\nPlease log in to complete the future process and track your application.\n\nUsername: {$user->username}\n\nRegards,\nJSHB Administration";

                    $notificationService->send([
                        'user_id' => $user->id,
                        'is_allottee' => true,
                        'email_id' => $user->email,
                        'subject' => $allotteeSubject,
                        'message' => $allotteeMessage,
                        'send_email' => true,
                        'send_sms' => false
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send credential/welcome mails in saveStep3: ' . $e->getMessage());
        }
    }

    public function generateUniqueUsername($division, $incomeTypeId, $subDivision, $date)
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

    public function generatePassword($length = 12)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers   = '0123456789';
        $special   = '!@#$%^&*()_+-=';
        $password  = $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];
        $password .= str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $allChars = $uppercase . $lowercase . $numbers . $special;
        while (strlen($password) < $length) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        return str_shuffle($password);
    }

    /**
     * Completely delete an allottee and all its related records across adms_allottees and adms_jshb.
     * If the allottee was synced from Citizen Portal, revert the staging record in future_allottees to 'pending'.
     *
     * @param Allottee $allottee
     * @return array
     */
    public function deleteAllottee(Allottee $allottee): array
    {
        $allotteeId = $allottee->id;
        $isSynced   = (int) ($allottee->is_synced ?? 0) === 1;

        DB::connection('adms_allottees')->beginTransaction();
        DB::connection('adms_jshb')->beginTransaction();

        try {
            // 1. Revert Citizen Portal FutureAllottee record if this was a synced allottee
            $revertedFuture = FutureAllottee::where('synced_allottee_id', $allotteeId)->first();
            if (!$revertedFuture && !empty($allottee->application_no)) {
                $revertedFuture = FutureAllottee::where('application_no', $allottee->application_no)
                    ->where('sync_status', 'synced')
                    ->first();
            }

            if ($revertedFuture) {
                $revertedFuture->update([
                    'sync_status'           => 'pending',
                    'synced_allottee_id'    => null,
                    'synced_application_id' => null,
                    'synced_at'             => null,
                    'error_log'             => null,
                ]);
            }

            // 2. Delete Workflow Applications & movements, notes, documents in adms_jshb
            $applications = Application::where('allottee_id', $allotteeId)->get();
            foreach ($applications as $app) {
                // Delete movements
                ApplicationMovement::where('application_id', $app->id)->delete();

                // Delete notes
                ApplicationNote::where('application_id', $app->id)->delete();

                // Delete application documents (and physical files)
                $appDocs = ApplicationDocument::where('application_id', $app->id)->get();
                foreach ($appDocs as $ad) {
                    if (!empty($ad->file_path) && file_exists(public_path($ad->file_path))) {
                        @unlink(public_path($ad->file_path));
                    }
                }
                ApplicationDocument::where('application_id', $app->id)->delete();

                // Delete audit trails
                ApplicationAuditTrail::where('application_id', $app->id)->delete();

                // Delete notifications in adms_jshb
                Notification::where('application_id', $app->id)->delete();

                // Delete communication tracks
                CommunicationTrack::where('application_id', $app->id)->delete();

                // Delete correspondences
                ApplicationCorrespondence::where('application_id', $app->id)->delete();

                // Delete bypass & extension requests
                BypassRequest::where('application_id', $app->id)->delete();
                ApplicationExtensionRequest::where('application_id', $app->id)->delete();

                // Clean optional application child tables if they exist
                if (Schema::connection('adms_jshb')->hasTable('application_status_history')) {
                    DB::connection('adms_jshb')->table('application_status_history')->where('application_id', $app->id)->delete();
                }
                if (Schema::connection('adms_jshb')->hasTable('application_pdf_history')) {
                    DB::connection('adms_jshb')->table('application_pdf_history')->where('application_id', $app->id)->delete();
                }

                // Delete the application itself
                if (method_exists($app, 'forceDelete')) {
                    $app->forceDelete();
                } else {
                    $app->delete();
                }
            }

            // Clean up adms_jshb records referencing allottee_id directly
            DocumentRequest::where('allottee_id', $allotteeId)->delete();
            CommunicationTrack::where('allottee_id', $allotteeId)->delete();
            DocumentGenerationQueue::where('allottee_id', $allotteeId)->delete();

            // 3. Delete Generated Documents in adms_allottees (and unlink physical files)
            $genDocs = AllotteeGeneratedDocument::where('allottee_id', $allotteeId)->get();
            foreach ($genDocs as $doc) {
                if (!empty($doc->file_path) && file_exists(public_path($doc->file_path))) {
                    @unlink(public_path($doc->file_path));
                }
                if (!empty($doc->signed_file_path) && file_exists(public_path($doc->signed_file_path))) {
                    @unlink(public_path($doc->signed_file_path));
                }
            }
            AllotteeGeneratedDocument::where('allottee_id', $allotteeId)->delete();

            // 4. Delete Transactions in adms_allottees (and unlink receipt files)
            $txns = AllotteeTransaction::where('allottee_id', $allotteeId)->get();
            foreach ($txns as $txn) {
                if (!empty($txn->receipt_path) && file_exists(public_path($txn->receipt_path))) {
                    @unlink(public_path($txn->receipt_path));
                }
            }
            AllotteeTransaction::where('allottee_id', $allotteeId)->delete();

            // 5. Delete Uploaded Allottee Documents in adms_allottees (and unlink physical files)
            $docs = AllotteeDocument::where('allottee_id', $allotteeId)->get();
            foreach ($docs as $doc) {
                if (!empty($doc->file_path) && file_exists(public_path($doc->file_path))) {
                    @unlink(public_path($doc->file_path));
                }
            }
            AllotteeDocument::where('allottee_id', $allotteeId)->delete();

            // 6. Delete all child records in adms_allottees
            $allotteeChildTables = [
                'allottee_ledger',
                'allottee_stage_trackers',
                'allottees_contact_details',
                'allottee_process_steps',
                'allottee_payment_orders',
                'allottee_payment_transactions',
                'allottee_initial_payments',
                'allottee_emi_accounts',
                'allottee_emi_schedules',
                'allottee_monthly_demands',
                'allottee_property_fin_details',
                'allottee_nominee_bank_details',
                'allottee_emi_ledger',
                'allottee_step_durations',
                'allottee_site_verifications',
                'allottee_emi_demands',
            ];

            foreach ($allotteeChildTables as $table) {
                if (Schema::connection('adms_allottees')->hasTable($table)) {
                    DB::connection('adms_allottees')->table($table)->where('allottee_id', $allotteeId)->delete();
                }
            }

            // 7. Delete Citizen Login User in adms_allottees
            $citizenUserId = $allottee->user_id;
            $username      = $allottee->username;

            if ($citizenUserId) {
                if (Schema::connection('adms_allottees')->hasTable('notifications')) {
                    AllotteeNotification::where('user_id', $citizenUserId)->delete();
                }
                User::on('adms_allottees')->where('id', $citizenUserId)->delete();
            }
            if ($username) {
                User::on('adms_allottees')->where('username', $username)->delete();
            }

            // 8. Delete Allottee document if path exists
            if (!empty($allottee->allottee_document_path) && file_exists(public_path($allottee->allottee_document_path))) {
                @unlink(public_path($allottee->allottee_document_path));
            }

            // 9. Delete the Allottee record itself from adms_allottees
            DB::connection('adms_allottees')->table('allottees')->where('id', $allotteeId)->delete();

            DB::connection('adms_allottees')->commit();
            DB::connection('adms_jshb')->commit();

            return [
                'success'   => true,
                'message'   => ($isSynced || $revertedFuture)
                    ? 'Allottee and all related components deleted successfully. The Citizen Portal staging record has been reverted to pending and can be re-synced anytime.'
                    : 'Allottee and all related components deleted successfully.',
                'is_synced' => ($isSynced || $revertedFuture ? true : false),
            ];
        } catch (\Throwable $e) {
            DB::connection('adms_allottees')->rollBack();
            DB::connection('adms_jshb')->rollBack();

            Log::error('Failed to delete allottee: ' . $e->getMessage(), [
                'allottee_id' => $allotteeId,
                'file'        => $e->getFile(),
                'line'        => $e->getLine(),
            ]);

            throw $e;
        }
    }
}
