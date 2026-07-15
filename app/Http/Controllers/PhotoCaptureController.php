<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Events\PhotoCaptured;

class PhotoCaptureController extends Controller
{
    /**
     * Generate a unique token for the photo capture session.
     */
    public function generateToken(Request $request)
    {
        $token = Str::random(40);
        
        // Store in cache for 15 minutes with user_id
        Cache::put('photo_session_' . $token, [
            'status' => 'pending',
            'user_id' => auth()->id()
        ], now()->addMinutes(15));

        \Illuminate\Support\Facades\Log::info("Live Image Capture: QR Generated", [
            'ip' => $request->ip(),
            'user_id' => auth()->id(),
            'token' => $token
        ]);

        return response()->json([
            'token' => $token,
            'url' => route('mobile.capture', ['token' => $token]),
        ]);
    }

    /**
     * Check the status of the photo capture session (for polling)
     */
    public function checkSession($token)
    {
        $session = Cache::get('photo_session_' . $token);
        if (!$session) {
            return response()->json(['status' => 'expired']);
        }
        return response()->json($session);
    }

    /**
     * Display the mobile camera view.
     */
    public function captureForm(Request $request, $token)
    {
        if (!Cache::has('photo_session_' . $token)) {
            \Illuminate\Support\Facades\Log::warning("Live Image Capture: Invalid Token Accessed", ['ip' => $request->ip(), 'token' => $token]);
            abort(404, 'Session expired or invalid token.');
        }

        \Illuminate\Support\Facades\Log::info("Live Image Capture: Mobile View Opened", ['ip' => $request->ip(), 'token' => $token]);

        return view('mobile.capture', compact('token'));
    }

    /**
     * Handle the uploaded photo from the mobile device.
     */
    public function upload(Request $request, $token)
    {
        $session = Cache::get('photo_session_' . $token);
        if (!$session) {
            \Illuminate\Support\Facades\Log::error("Live Image Capture: Upload failed (Expired Token)", ['ip' => $request->ip(), 'token' => $token]);
            return response()->json(['error' => 'Session expired or invalid token.'], 403);
        }

        $request->validate([
            'image' => 'required|string'
        ]);

        try {
            $imageData = $request->input('image');
        
        // Remove data URI scheme prefix
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, etc
            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                \Illuminate\Support\Facades\Log::error("Live Image Capture: Upload failed (Invalid Type)", ['ip' => $request->ip(), 'type' => $type]);
                return response()->json(['error' => 'Invalid image type.'], 400);
            }
        } else {
            \Illuminate\Support\Facades\Log::error("Live Image Capture: Upload failed (Bad URI Match)", ['ip' => $request->ip()]);
            return response()->json(['error' => 'Did not match data URI with image data.'], 400);
        }

        $imageData = base64_decode($imageData);
        if ($imageData === false) {
            \Illuminate\Support\Facades\Log::error("Live Image Capture: Upload failed (Base64 Decode)", ['ip' => $request->ip()]);
            return response()->json(['error' => 'Base64 decode failed.'], 400);
        }

        // Generate filename and save to storage/app/public/engineer_assets
        $filename = 'engineer_' . ($session['user_id'] ?? 'unknown') . '_' . time() . '_' . Str::random(10) . '.' . $type;
        $path = 'engineer_assets/' . $filename;
        Storage::disk('public')->put($path, $imageData);

        // --- Python Image Processing Integration ---
        $absolutePath = storage_path('app/public/' . $path);
        $scriptPath = base_path('scripts/clean_image.py');
        
        // Execute python script
        $command = "python " . escapeshellarg($scriptPath) . " " . escapeshellarg($absolutePath) . " 2>&1";
        exec($command, $output, $returnCode);

        if ($returnCode == 2) {
            // No text or stamp found
            Storage::disk('public')->delete($path);
            \Illuminate\Support\Facades\Log::warning("Live Image Capture: Rejected by Python (No Text/Stamp)", ['ip' => $request->ip()]);
            return response()->json(['error' => 'Image must contain readable text or a stamp with a clean background.'], 400);
        } elseif ($returnCode !== 0) {
            // General python script failure
            Storage::disk('public')->delete($path);
            \Illuminate\Support\Facades\Log::error("Live Image Capture: Python Processing Failed", ['ip' => $request->ip(), 'output' => $output]);
            return response()->json(['error' => 'Image processing failed. Please try again.'], 500);
        }
        // -------------------------------------------

        $imageUrl = asset('storage/' . $path);

        // Save to Database so it shows up in "My Saved Assets"
        if (!empty($session['user_id'])) {
            \App\Models\EngineerAsset::create([
                'user_id' => $session['user_id'],
                // CRITICAL: asset_type MUST be 'other' because the DB enum only allows 'signature', 'stamp', or 'other'. Using 'live_capture' will cause a 500 SQL crash!
                'asset_type' => 'other',
                'file_path' => 'storage/' . $path,
                'original_name' => 'Live Capture ' . date('Y-m-d H:i:s'),
            ]);
        }

        // Broadcast the event to the desktop
        broadcast(new PhotoCaptured($token, $imageUrl));

        \Illuminate\Support\Facades\Log::info("Live Image Capture: Success", [
            'ip' => $request->ip(),
            'user_id' => $session['user_id'],
            'url' => $imageUrl
        ]);

            // Mark cache as completed
            Cache::put('photo_session_' . $token, ['status' => 'completed', 'url' => $imageUrl], now()->addMinutes(5));

            return response()->json([
                'success' => true,
                'image_url' => $imageUrl
            ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Live Image Capture: Unexpected Error", [
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'An unexpected error occurred during upload. Please try again.'], 500);
        }
    }
}
