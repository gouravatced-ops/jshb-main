<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class MediaController extends Controller
{
    /**
     * Serve a profile image, falling back to a role-based default if not found.
     */
    public function profileImage($filename)
    {
        $path = storage_path('app/public/photos/' . $filename);

        if (File::exists($path) && is_file($path)) {
            return response()->file($path);
        }

        // Determine fallback based on user role (if possible to deduce from filename)
        // Typically we would need the user ID, but we can search by photo filename.
        $user = User::where('photo', $filename)->first();

        $fallbackPath = public_path('img/user-profile.png'); // Default

        if ($user) {
            // Find role
            $roleSlug = $user->roleRelation ? $user->roleRelation->slug : null;
            if (!$roleSlug && $user->role_id) {
                $role = \App\Models\Role::find($user->role_id);
                $roleSlug = $role ? $role->slug : null;
            }

            if (in_array($roleSlug, ['admin', 'super-admin'])) {
                $fallbackPath = public_path('img/admin-profile.png');
            } elseif (in_array($roleSlug, [
                'executive-engineer', 'assistant-engineer', 'junior-engineer', 'secretary-chief-engineer'
            ])) {
                $fallbackPath = public_path('img/engineer-profile.png');
            }
        }

        return response()->file($fallbackPath);
    }

    /**
     * Serve a document, falling back to document-not-found image or PDF.
     */
    public function document(Request $request)
    {
        $path = $request->query('path');
        if (!$path) {
            return response()->file(public_path('img/document-not-found.png'));
        }
        
        // If the path is a full URL to jshb-doc or local storage
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            // Try fetching it
            try {
                $response = \Illuminate\Support\Facades\Http::get($path);
                if ($response->successful()) {
                    $contentType = $response->header('Content-Type');
                    return response($response->body(), 200, [
                        'Content-Type' => $contentType ?: 'application/octet-stream'
                    ]);
                }
            } catch (\Exception $e) {
                // Fetch failed, proceed to fallback
            }
        } else {
            // Local path
            $fullPath = public_path(ltrim($path, '/'));
            if (!File::exists($fullPath)) {
                $fullPath = storage_path('app/public/' . ltrim($path, '/'));
            }
            
            if (File::exists($fullPath) && is_file($fullPath)) {
                return response()->file($fullPath);
            }
        }

        // Determine fallback based on extension
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            return response()->file(public_path('img/document-pdf-not-found.pdf'));
        }

        return response()->file(public_path('img/document-not-found.png'));
    }

    /**
     * Generic fallback for any broken image.
     */
    public function genericImage(Request $request)
    {
        $path = $request->query('path');
        if ($path) {
            $fullPath = public_path(ltrim($path, '/'));
            if (File::exists($fullPath) && is_file($fullPath)) {
                return response()->file($fullPath);
            }
        }

        return response()->file(public_path('img/image-fake.png'));
    }
}
