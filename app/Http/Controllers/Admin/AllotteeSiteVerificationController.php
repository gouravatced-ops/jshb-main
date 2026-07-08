<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AllotteeSiteVerification;
use App\Models\Allottee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\AllotteeGeneratedDocument;
use Barryvdh\DomPDF\Facade\Pdf;

class AllotteeSiteVerificationController extends Controller
{
    public function store(Request $request, $allottee_id)
    {
        try {
            $allottee = Allottee::findOrFail($allottee_id);

            // Validation can be added here if needed
            $validator = Validator::make($request->all(), [
                // rules
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->except([
                '_token', 
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
            
            $year  = date('Y');
            $month = date('m');
            $day   = date('d');

            $existingVerification = AllotteeSiteVerification::where('allottee_id', $allottee_id)->first();

            if ($request->filled('map_image_data')) {
                $base64Image = $request->map_image_data;
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                    $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
                    $type = strtolower($type[1]);

                    if ($existingVerification && $existingVerification->map_image && \Illuminate\Support\Facades\File::exists(public_path($existingVerification->map_image))) {
                        $photoPath = $existingVerification->map_image;
                    } else {
                        $imageName = 'site-verification-map-' .
                            ($allottee->allotment_year ?? date('Y')) .
                            ($allottee->allotment_month ?? date('m')) .
                            ($allottee->allotment_day ?? date('d')) .
                            now()->format('His') . '-' . rand(1000, 9999) . '.' . $type;

                        $photoFolder = implode('/', ['documents', 'site-verification', 'photo', $year, $month, $day]);
                        $photoDirectory = public_path($photoFolder);
                        \Illuminate\Support\Facades\File::ensureDirectoryExists($photoDirectory, 0755, true);

                        $photoPath = $photoFolder . '/' . $imageName;
                    }

                    \Illuminate\Support\Facades\File::put(public_path($photoPath), base64_decode($base64Image));
                    
                    $data['map_image'] = $photoPath;
                } else {
                    $data['map_image'] = $request->map_image_data;
                }
            }

            $verification = AllotteeSiteVerification::updateOrCreate(
                ['allottee_id' => $allottee_id],
                $data
            );

            // Generate PDF
            $pdf = Pdf::loadView('admin.allottee.pdf.site-verification', compact('verification', 'allottee'));
            
            $generatedDoc = AllotteeGeneratedDocument::where('allottee_id', $allottee_id)
                ->where('document_type', 'Site Verification')
                ->first();

            if ($generatedDoc) {
                $filePath = $generatedDoc->file_path;
                \Illuminate\Support\Facades\File::put(public_path($filePath), $pdf->output());
            } else {
                $fileName =
                    'site-verification-' .
                    ($allottee->allotment_year ?? date('Y')) .
                    ($allottee->allotment_month ?? date('m')) .
                    ($allottee->allotment_day ?? date('d')) .
                    now()->format('His') . '-' . rand(1000, 9999) . '.pdf';

                $folder = implode('/', ['documents', 'site-verification', 'pdf', $year, $month, $day]);
                $directory = public_path($folder);
                \Illuminate\Support\Facades\File::ensureDirectoryExists($directory, 0755, true);

                $filePath = $folder . '/' . $fileName;
                \Illuminate\Support\Facades\File::put(public_path($filePath), $pdf->output());

                AllotteeGeneratedDocument::create([
                    'allottee_id' => $allottee_id,
                    'document_name' => 'Site Verification Report',
                    'document_type' => 'Site Verification',
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'generated_by' => Auth::id() ?? 1,
                    'generated_at' => now(),
                    'issue_date' => now()->toDateString(),
                    'document_number' => 'SVR-' . date('Ymd') . '-' . rand(1000, 9999),
                ]);
            }

            // Update steps: mark 17 as completed and 18 as pending
            $step16 = \App\Models\AllotteeProcessStep::where('allottee_id', $allottee_id)
                ->where('step_no', 16)
                ->first();
                
            if ($step16 && !$step16->is_completed) {
                $step16->markAsCompleted(Auth::id() ?? 1, 'Site Verification completed');
            }
            
            $step17 = \App\Models\AllotteeProcessStep::where('allottee_id', $allottee_id)
                ->where('step_no', 17)
                ->first();
                
            if ($step17 && $step17->is_locked) {
                $step17->markAsPending();
                $step17->activate();
            }

            return response()->json([
                'success' => true,
                'message' => 'Site verification details and PDF saved successfully',
                'data' => $verification
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving site verification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save site verification: ' . $e->getMessage()
            ], 500);
        }
    }
}
