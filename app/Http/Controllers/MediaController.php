<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Role;
use App\Models\Allottee;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\AllotteeDocument;
use App\Models\AllotteeGeneratedDocument;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    /**
     * Serve a profile image, falling back to a role-based default if not found.
     */
    public function profileImage(Request $request, $filename)
    {
        if ($filename && $filename !== 'default') {
            $path = storage_path('app/public/photos/' . $filename);
            if (File::exists($path) && is_file($path)) {
                return response()->file($path);
            }
        }

        $user = null;
        if ($request->has('user_id')) {
            $user = User::find($request->query('user_id'));
        }

        if (!$user && $filename !== 'default') {
            $user = User::where('photo', $filename)->first();
        }

        $fallbackPath = public_path('img/user-profile.png'); // Default

        if ($user) {
            // Find role
            $roleSlug = $user->roleRelation ? $user->roleRelation->slug : null;
            if (!$roleSlug && $user->role_id) {
                $role = Role::find($user->role_id);
                $roleSlug = $role ? $role->slug : null;
            }

            if (in_array($roleSlug, ['admin', 'super-admin'])) {
                $fallbackPath = public_path('img/admin-profile.png');
            } elseif (in_array($roleSlug, [
                'executive-engineer',
                'assistant-engineer',
                'junior-engineer',
                'secretary-chief-engineer'
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
        if (!Auth::check()) {
            abort(403, 'Unauthorized. Please login first.');
        }

        $path = $request->query('path');
        if (!$path) {
            return response()->file(public_path('img/document-not-found.png'));
        }

        $user = Auth::user();
        if ($user->user_type === 'allottee') {
            $allottee = Allottee::where('username', $user->username)->first();
            if (!$allottee) {
                abort(403, 'Allottee profile not found.');
            }

            $isAuthorized = false;

            // 1. Application documents
            $appIds = Application::where('allottee_id', $allottee->id)->pluck('id');
            $appDocs = ApplicationDocument::whereIn('application_id', $appIds)->get();
            foreach ($appDocs as $doc) {
                if (!empty($doc->file_path) && str_contains($path, ltrim($doc->file_path, '/'))) {
                    $isAuthorized = true;
                    break;
                }
            }

            // 2. Generated documents
            if (!$isAuthorized) {
                $genDocs = AllotteeGeneratedDocument::where('allottee_id', $allottee->id)->get();
                foreach ($genDocs as $doc) {
                    if ((!empty($doc->file_path) && str_contains($path, ltrim($doc->file_path, '/'))) ||
                        (!empty($doc->signed_file_path) && str_contains($path, ltrim($doc->signed_file_path, '/')))
                    ) {
                        $isAuthorized = true;
                        break;
                    }
                }
            }

            // 3. Allottee Documents
            if (!$isAuthorized) {
                $allDocs = AllotteeDocument::where('allottee_id', $allottee->id)->get();
                foreach ($allDocs as $doc) {
                    if (!empty($doc->file_path) && str_contains($path, ltrim($doc->file_path, '/'))) {
                        $isAuthorized = true;
                        break;
                    }
                }
            }
            
            // 4. Payment Receipts (Transactions)
            if (!$isAuthorized) {
                $transactions = \App\Models\AllotteeTransaction::where('allottee_id', $allottee->id)->get();
                foreach ($transactions as $txn) {
                    if (!empty($txn->receipt_path) && str_contains($path, ltrim($txn->receipt_path, '/'))) {
                        $isAuthorized = true;
                        break;
                    }
                }
            }

            if (!$isAuthorized) {
                $paymentTxns = \App\Models\AllotteePaymentTransaction::where('allottee_id', $allottee->id)->get();
                foreach ($paymentTxns as $txn) {
                    if (!empty($txn->receipt_path) && str_contains($path, ltrim($txn->receipt_path, '/'))) {
                        $isAuthorized = true;
                        break;
                    }
                }
            }

            // 5. Initial Payment Applicant Receipt
            if (!$isAuthorized) {
                // Applicants might have payment_receipt_path directly on their Application or Allottee record
                if (!empty($allottee->payment_receipt_path) && str_contains($path, ltrim($allottee->payment_receipt_path, '/'))) {
                    $isAuthorized = true;
                }
            }

            if (!$isAuthorized) {
                abort(403, 'Unauthorized access to this document.');
            }
        }

        // If the path is a full URL to jshb-doc or local storage
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $docApiUrl = rtrim(config('app.doc_api_url', 'http://localhost/jshb-doc'), '/');

            // Priority 1: Direct File System Access (Fastest & Most Secure for Local)
            if (str_starts_with($path, $docApiUrl)) {
                $relativePath = str_replace($docApiUrl, '', $path);
                // Since jshb and jshb-doc are siblings in C:\xampp\htdocs
                $jshbDocPath = dirname(base_path()) . '/jshb-doc' . $relativePath;
                if (\Illuminate\Support\Facades\File::exists($jshbDocPath) && is_file($jshbDocPath)) {
                    return response()->file($jshbDocPath);
                }
            }

            // Priority 2: HTTP Fetching (Fallback for Remote Servers)
            try {
                // Pass secret header to bypass .htaccess restrictions
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'X-Internal-Secret' => 'BElxKU996uTvAJ56WF9wiUDZyykUf376EKzeaccJSaVFHcwVcIUwMJ09e9Pl0bXY9HjvHiyIqpdfX'
                ])->get($path);

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

