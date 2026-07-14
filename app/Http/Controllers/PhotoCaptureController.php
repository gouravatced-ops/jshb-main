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
    public function generateToken()
    {
        $token = Str::random(40);
        
        // Store in cache for 15 minutes with user_id
        Cache::put('photo_session_' . $token, [
            'status' => 'pending',
            'user_id' => auth()->id()
        ], now()->addMinutes(15));

        return response()->json([
            'token' => $token,
            'url' => route('mobile.capture', ['token' => $token]),
        ]);
    }

    /**
     * Display the mobile camera view.
     */
    public function captureForm($token)
    {
        if (!Cache::has('photo_session_' . $token)) {
            abort(404, 'Session expired or invalid token.');
        }

        return view('mobile.capture', compact('token'));
    }

    /**
     * Handle the uploaded photo from the mobile device.
     */
    public function upload(Request $request, $token)
    {
        $session = Cache::get('photo_session_' . $token);
        if (!$session) {
            return response()->json(['error' => 'Session expired or invalid token.'], 403);
        }

        $request->validate([
            'image' => 'required|string'
        ]);

        $imageData = $request->input('image');
        
        // Remove data URI scheme prefix
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, etc
            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                return response()->json(['error' => 'Invalid image type.'], 400);
            }
        } else {
            return response()->json(['error' => 'Did not match data URI with image data.'], 400);
        }

        $imageData = base64_decode($imageData);
        if ($imageData === false) {
            return response()->json(['error' => 'Base64 decode failed.'], 400);
        }

        // Generate filename and save to storage/app/public/engineer_assets
        $filename = 'engineer_' . ($session['user_id'] ?? 'unknown') . '_' . time() . '_' . Str::random(10) . '.' . $type;
        $path = 'engineer_assets/' . $filename;
        Storage::disk('public')->put($path, $imageData);

        $imageUrl = asset('storage/' . $path);

        // Save to Database so it shows up in "My Saved Assets"
        if (!empty($session['user_id'])) {
            \App\Models\EngineerAsset::create([
                'user_id' => $session['user_id'],
                'asset_type' => 'live_capture',
                'file_path' => 'storage/' . $path,
                'original_name' => 'Live Capture ' . date('Y-m-d H:i:s'),
            ]);
        }

        // Broadcast the event to the desktop
        broadcast(new PhotoCaptured($token, $imageUrl));

        // Mark cache as completed
        Cache::put('photo_session_' . $token, ['status' => 'completed', 'url' => $imageUrl], now()->addMinutes(5));

        return response()->json([
            'success' => true,
            'image_url' => $imageUrl
        ]);
    }
}
