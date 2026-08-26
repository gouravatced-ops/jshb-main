<?php

namespace App\Http\Controllers\Engineer;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Application\VerifyAndUploadRequest;
use App\Models\ApplicationDocument;
use App\Models\AllotteeProcessStep;
use App\Models\ApplicationMovement;
use App\Mail\ApplicationForwardedMail;
use App\Services\NotificationService;
use App\Mail\OtpMail;
use App\Http\Requests\Application\StoreSiteVerificationRequest;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationNote;
use App\Models\Allottee;
use App\Models\User;
use App\Models\CommunicationTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\DocumentUploadTrait;
use App\Models\AllotteeSiteVerification;
use App\Models\AllotteeGeneratedDocument;
use App\Models\OtpLog;
use Illuminate\Support\Facades\Validator;

class SiteVerificationController extends Controller
{
    use DocumentUploadTrait;

    public function verifyAndUploadDocument(VerifyAndUploadRequest $request, Application $application)
    {

        $file     = $request->file('document_file');
        $allottee = $application->allottee;

        $schemeCode     = $allottee->scheme->scheme_code ?? 'SCH';
        $propertyNumber = $allottee->property_number ?? 'PROP';
        $yyyy = date('Y');
        $mm   = date('m');
        $dd   = date('d');

        $extraData = [
            'application_for'   => $application->application_type ?? '',
            'division_code'     => $allottee->division->division_code ?? '',
            'subdivision_code'  => $allottee->subDivision->subdivision_code ?? '',
            'property_category' => $allottee->propertyCategory->category_code ?? '',
            'property_type'     => $allottee->propertyType->type_code ?? '',
            'property_income'   => $allottee->quarterType->quarter_code ?? '',
            'username'          => $allottee->username ?? '',
        ];

        try {
            $uploadResult = $this->uploadToDocumentApi(
                $file,
                'FINAL',
                $schemeCode,
                $propertyNumber,
                $yyyy,
                $mm,
                $dd,
                null,
                $extraData
            );

            $path         = $uploadResult['file_path'];
            $originalName = $uploadResult['file_name'];
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload document to Document Store: ' . $e->getMessage());
        }

        $user           = Auth::user();
        $userType       = $user->user_type ?? 'engineer';
        $latestMovement = $application->movements()->latest()->first();

        ApplicationDocument::create([
            'application_id' => $application->id,
            'movement_id'    => $latestMovement ? $latestMovement->id : null,
            'document_type'  => 'engineer_verify_upload',
            'document_name'  => 'Engineer Verification Document',
            'file_name'      => $originalName,
            'file_path'      => $path,
            'file_size'      => $file->getSize(),
            'file_mime_type' => $file->getMimeType(),
            'uploaded_by'    => $user->id,
            'uploader_type'  => $userType,
            'uploaded_at'    => now(),
        ]);

        ApplicationNote::create([
            'application_id' => $application->id,
            'user_id'        => $user->id,
            'role_id'        => $user->role_id,
            'note_type'      => 'user_note',
            'remarks'        => $request->remarks,
            'font_family'    => $request->font_family ?? 'english',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // Special logic for the agreement DA verify-upload step
        if ($application->currentStep && $application->currentStep->step_code === 'agreement-da-verify-upload') {
            $agreementProcessStep = AllotteeProcessStep::where([
                'allottee_id' => $application->allottee_id,
                'menu_key'    => 'allotment',
                'sub_menu_key' => 'agreement-document-letter',
            ])->first();

            if ($agreementProcessStep) {
                AllotteeProcessStep::completeStep(
                    $application->allottee_id,
                    'allotment',
                    'agreement-document-letter',
                    $user->id
                );

                AllotteeProcessStep::unlockNextStep(
                    $application->allottee_id,
                    $agreementProcessStep->step_no
                );
            }

            AllotteeGeneratedDocument::create([
                'allottee_id'   => $application->allottee_id,
                'document_name' => 'Final Stamped Agreement',
                'document_type' => 'final-agreement-letter',
                'file_name'     => $originalName,
                'file_path'     => $path,
                'generated_by'  => $user->id,
                'generated_at'  => now(),
            ]);

            $application->status = 'completed';
            $application->save();

            ApplicationMovement::create([
                'application_id' => $application->id,
                'from_user_id'   => $user->id,
                'to_user_id'     => null,
                'from_role_id'   => $application->current_role_id,
                'to_role_id'     => null,
                'from_step_id'   => $application->current_step_id,
                'to_step_id'     => null,
                'action_type'    => 'completed',
                'status'         => 'completed',
                'remarks'        => 'Final Stamped Agreement Verified and Uploaded',
                'movement_date'  => now(),
            ]);

            // Send Notification to Allottee
            $allotteeUser = User::on('adms_allottees')->find($application->allottee->user_id);
            if ($allotteeUser) {
                $message = "Your agreement application ({$application->application_no}) has been successfully completed. The final stamped agreement has been uploaded. Please log in to your dashboard to download it.";

                $customMailableAllottee = new ApplicationForwardedMail(
                    $allotteeUser->name ?? 'Allottee',
                    $application->application_no,
                    $user->name ?? 'Engineer',
                    'completed',
                    config('jshb.allottee_app_url', config('app.url') . '/login'),
                    $message
                );

                app(NotificationService::class)->send([
                    'user_id'           => $allotteeUser->id,
                    'is_allottee'       => true,
                    'application_id'    => $application->id,
                    'notification_type' => 'application_movement',
                    'subject'           => "Application Completed: {$application->application_no}",
                    'message'           => $message,
                    'send_email'        => true,
                    'send_sms'          => true,
                    'send_whatsapp'     => true,
                    'link'              => '/login',
                    'mailable'          => $customMailableAllottee,
                ]);
            }

            return redirect()->route('engineer.applications.show', $application)
                ->with('success', 'Final Stamped Agreement uploaded. Application is now completed.');
        }

        return redirect()->back()->with('success', 'Document verified and uploaded successfully.');
    }

    public function siteVerificationForm($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
        } catch (\Exception $e) {
            abort(404);
        }
        $application = Application::findOrFail($id);
        $application->load('allottee');
        $allottee = $application->allottee;
        return view('engineer.applications.site-verification', compact('application', 'allottee', 'encryptedId'));
    }

    public function sendSiteVerificationOtp(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired URL parameter.'], 404);
        }
        $application = Application::findOrFail($id);
        $application->load('allottee');

        // Save as draft (pre-fill next time)
        $this->saveSiteVerificationData($request, $application->allottee);

        $otp = sprintf('%06d', mt_rand(100000, 999999));
        $user = Auth::user();

        OtpLog::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'otp_code' => Hash::make($otp),
            'purpose' => 'site_verification',
            'expires_at' => now()->addMinutes(10),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        $messageBody = "You have initiated the Site Verification process for Application ID: {$application->application_no}. Please use this OTP to verify and save the verification details.";

        try {
            $targetEmail = config('jshb.otp_dev_email') ?: $user->email;
            Mail::to($targetEmail)->send(new OtpMail($otp, $messageBody, [], 'site_verification', $user->name));

            // Log to communication_tracks
            CommunicationTrack::create([
                'application_id' => $application->id,
                'allottee_id' => $application->allottee_id,
                'sender_type' => 'system',
                'receiver_type' => 'jshb_user',
                'receiver_id' => $user->id,
                'role_id' => $user->role_id,
                'communication_type' => 'email',
                'subject' => 'Site Verification OTP',
                'content' => $messageBody,
                'ip_address' => $request->ip(),
                'browser_agent' => $request->userAgent(),
                'status' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'OTP sent to your registered email.']);
        } catch (\Exception $e) {
            Log::error('OTP Email failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send OTP email.'], 500);
        }
    }

    public function storeSiteVerification(StoreSiteVerificationRequest $request, $encryptedId)
    {
        try {
            try {
                $id = Crypt::decryptString($encryptedId);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Invalid or expired URL parameter.'], 404);
            }
            $application = Application::findOrFail($id);
            $application->load('allottee');
            $allottee = $application->allottee;
            $allottee_id = $allottee->id;

            $validatedData = $request->validated();

            // Verify OTP
            $otpLogs = OtpLog::where('user_id', Auth::id())
                ->where('purpose', 'site_verification')
                ->where('verified', 0)
                ->where('expires_at', '>=', now())
                ->orderBy('id', 'desc')
                ->get();

            $validOtpLog = null;
            foreach ($otpLogs as $log) {
                if (Hash::check($request->otp, $log->otp_code)) {
                    $validOtpLog = $log;
                    break;
                }
            }

            if (!$validOtpLog) {
                return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 400);
            }

            // Mark OTP as verified
            OtpLog::where('id', $validOtpLog->id)
                ->update(['verified' => 1]);

            $verification = $this->saveSiteVerificationData($request, $allottee);

            // Dynamically set map_image to base64 so dompdf can render it
            if ($request->filled('map_image_data')) {
                $verification->map_image = $request->map_image_data;
            }

            // Generate PDF
            $pdf = Pdf::loadView('admin.allottee.pdf.site-verification', compact('verification', 'allottee'));

            $pdfFileName =
                'site-verification-' .
                ($allottee->allotment_year ?? date('Y')) .
                ($allottee->allotment_month ?? date('m')) .
                ($allottee->allotment_day ?? date('d')) .
                now()->format('His') . '-' . rand(1000, 9999) . '.pdf';

            $year  = date('Y');
            $month = date('m');
            $day   = date('d');
            $scheme = $allottee->scheme ?? null;

            $extraData = [
                'application_for' => $application->application_type ?? '',
                'division_code' => $allottee->division->division_code ?? '',
                'subdivision_code' => $allottee->subDivision->subdivision_code ?? '',
                'property_category' => $allottee->propertyCategory->category_code ?? '',
                'property_type' => $allottee->propertyType->type_code ?? '',
                'property_income' => $allottee->quarterType->quarter_code ?? '',
                'username' => $allottee->username ?? ''
            ];

            // Upload PDF to Document API under FINAL category
            $pdfUploadResult = $this->uploadContentToDocumentApi(
                $pdf->output(),
                $pdfFileName,
                'FINAL',
                $scheme->scheme_code ?? 'SCH',
                $allottee->property_number ?? 'PROP',
                $year,
                $month,
                $day,
                $extraData
            );

            $pdfFilePath = $pdfUploadResult['file_path'];

            // Map Image Upload
            $mapUploadResult = null;
            $mapFilePath = null;
            $mapFileName = null;

            if ($request->filled('map_image_data')) {
                $base64Image = $request->map_image_data;
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                    $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
                    $ext = strtolower($type[1]);

                    $mapFileName = 'site-verification-map-' .
                        ($allottee->allotment_year ?? date('Y')) .
                        ($allottee->allotment_month ?? date('m')) .
                        ($allottee->allotment_day ?? date('d')) .
                        now()->format('His') . '-' . rand(1000, 9999) . '.' . $ext;

                    $mapUploadResult = $this->uploadContentToDocumentApi(
                        base64_decode($base64Image),
                        $mapFileName,
                        'FINAL',
                        $scheme->scheme_code ?? 'SCH',
                        $allottee->property_number ?? 'PROP',
                        $year,
                        $month,
                        $day,
                        $extraData
                    );

                    $mapFilePath = $mapUploadResult['file_path'];
                    $verification->update(['map_image' => $mapFilePath]);
                }
            }

            // PDF Document Entry
            $appDoc = ApplicationDocument::where('application_id', $application->id)
                ->where('document_type', 'Site Verification')->first();

            if ($appDoc) {
                $appDoc->update([
                    'file_name' => $pdfFileName,
                    'file_path' => $pdfFilePath,
                    'uploaded_by' => Auth::id() ?? 1,
                    'uploaded_at' => now(),
                    'is_verified' => 1,
                    'verified_by' => Auth::id() ?? 1,
                    'verified_at' => now(),
                ]);
            } else {
                ApplicationDocument::create([
                    'application_id' => $application->id,
                    'document_name' => 'Site Verification Report',
                    'document_type' => 'Site Verification',
                    'file_name' => $pdfFileName,
                    'file_path' => $pdfFilePath,
                    'uploaded_by' => Auth::id() ?? 1,
                    'uploader_type' => 'engineer',
                    'uploaded_at' => now(),
                    'is_verified' => 1,
                    'verified_by' => Auth::id() ?? 1,
                    'verified_at' => now(),
                    'version' => 1,
                    'is_original' => 1,
                ]);
            }

            // Map Image Document Entry
            if ($mapUploadResult) {
                $mapDoc = ApplicationDocument::where('application_id', $application->id)
                    ->where('document_type', 'Site Verification Map')->first();

                if ($mapDoc) {
                    $mapDoc->update([
                        'file_name' => $mapFileName,
                        'file_path' => $mapFilePath,
                        'uploaded_by' => Auth::id() ?? 1,
                        'uploaded_at' => now(),
                        'is_verified' => 1,
                        'verified_by' => Auth::id() ?? 1,
                        'verified_at' => now(),
                    ]);
                } else {
                    ApplicationDocument::create([
                        'application_id' => $application->id,
                        'document_name' => 'Site Verification Map',
                        'document_type' => 'Site Verification Map',
                        'file_name' => $mapFileName,
                        'file_path' => $mapFilePath,
                        'uploaded_by' => Auth::id() ?? 1,
                        'uploader_type' => 'engineer',
                        'uploaded_at' => now(),
                        'is_verified' => 1,
                        'verified_by' => Auth::id() ?? 1,
                        'verified_at' => now(),
                        'version' => 1,
                        'is_original' => 1,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Site verification details and PDF saved successfully.',
                'redirect_url' => route('engineer.applications.action.form', [
                    'application' => Crypt::encryptString($application->id),
                    'action_type' => 'forward'
                ])
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving site verification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save site verification: ' . $e->getMessage()
            ], 500);
        }
    }

    private function saveSiteVerificationData(Request $request, $allottee)
    {
        $allottee_id = $allottee->id;

        $data = $request->except([
            '_token',
            'otp',
            'map_image_data',
            'mapPlotNo',
            'mapNorth',
            'mapNorthLabel',
            'mapSouth',
            'mapSouthLabel',
            'mapEast',
            'mapEastLabel',
            'mapWest',
            'mapWestLabel'
        ]);

        // Collect map parameters as JSON
        $mapParameters = [
            'plotNo' => $request->mapPlotNo,
            'north' => $request->mapNorth,
            'northLabel' => $request->mapNorthLabel,
            'south' => $request->mapSouth,
            'southLabel' => $request->mapSouthLabel,
            'east' => $request->mapEast,
            'eastLabel' => $request->mapEastLabel,
            'west' => $request->mapWest,
            'westLabel' => $request->mapWestLabel,
        ];

        $data['map_parameters'] = json_encode($mapParameters);

        // Fix empty dates
        if (empty($data['approved_map_date'])) {
            $data['approved_map_date'] = null;
        }
        if (empty($data['alteration_map_date'])) {
            $data['alteration_map_date'] = null;
        }

        $year  = date('Y');
        $month = date('m');
        $day   = date('d');

        // Do not save map_image here to avoid public_path.
        // It will be uploaded to Document API in storeSiteVerification.

        return AllotteeSiteVerification::updateOrCreate(
            ['allottee_id' => $allottee_id],
            $data
        );
    }
}
