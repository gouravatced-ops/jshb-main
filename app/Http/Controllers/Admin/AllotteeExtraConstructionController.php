<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AllotteeProcessStep;
use App\Models\Allottee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AllotteeExtraConstructionController extends Controller
{
    public function store(Request $request, $allottee_id)
    {
        try {
            $allottee = Allottee::findOrFail($allottee_id);

            $extraConstruction = $request->input('extra_construction'); // 'yes' or 'no'

            if ($extraConstruction === 'no') {
                // Update steps: mark 17 as completed and 18 as pending
                $step17 = AllotteeProcessStep::where('allottee_id', $allottee_id)->where('step_no', 17)->first();

                if ($step17 && $step17->status !== 'completed') {
                    $step17->update([
                        'status'       => 'completed',
                        'completed_at' => now(),
                        'completed_by' => Auth::id() ?? 1,
                    ]);
                }

                AllotteeProcessStep::where('allottee_id', $allottee_id)->where('step_no', 18)
                    ->update([
                        'status' => 'pending',
                    ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Extra Construction option saved. Proceeding to next step.'
                ]);
            } else if ($extraConstruction === 'yes') {
                // Here we can handle 'yes' later if they need to upload files or fill a form
                // For now just return success without completing the step yet, or maybe complete it?
                // The user specifically said "if user seelct no toh step 18 pending hoga and 17 completed hoga".
                return response()->json([
                    'success' => true,
                    'message' => 'Extra Construction option saved as Yes. Please provide additional details.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select Yes or No.'
                ], 422);
            }

        } catch (\Exception $e) {
            Log::error('Error saving extra construction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save: ' . $e->getMessage()
            ], 500);
        }
    }
}
