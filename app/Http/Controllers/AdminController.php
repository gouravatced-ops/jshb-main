<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\LoginLog;
use App\Models\OtpLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function profile()
    {
        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();
        $userDetail = $user->detail ?? new UserDetail(['user_id' => $user->id]);

        $loginLogs = LoginLog::where('user_id', $user->id)
            ->latest()
            ->limit(50)
            ->get();

        $otpLogs = OtpLog::where('user_id', $user->id)
            ->latest()
            ->limit(50)
            ->get();

        $states = \App\Models\State::where('id', '!=', 37)->orderBy('name_en', 'asc')->get();

        return view('admin.profile', compact('user', 'userDetail', 'loginLogs', 'otpLogs', 'states'));
    }

    public function myActivity()
    {
        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        // Paginate logs for the dedicated activity page
        $loginLogs = LoginLog::where('user_id', $user->id)
            ->latest()
            ->paginate(15, ['*'], 'login_page');

        $otpLogs = OtpLog::where('user_id', $user->id)
            ->latest()
            ->paginate(15, ['*'], 'otp_page');

        return view('admin.my-activity', compact('loginLogs', 'otpLogs'));
    }

    public function updateProfile(Request $request)
    {
        if ($redirect = $this->redirectIfLocked()) {
            return $redirect;
        }

        $user = Auth::user();

        // Fix: Use the correct relationship name 'detail' instead of 'userDetail'
        $userDetail = $user->detail ?? new UserDetail(['user_id' => $user->id]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'additional_info' => 'nullable|string',
            'date_of_joining' => 'nullable|date',
            'date_of_retirement' => 'nullable|date',
            'date_of_contractual' => 'nullable|date',
            'date_of_deputation' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'secure_pin' => 'nullable|digits:5|confirmed',
        ]);

        // Update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($user->role === 'admin' && $request->filled('secure_pin')) {
            $user->secure_pin = $request->secure_pin;
            $user->save();
        }

        // Handle photo upload in the same public path used by the views.
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            $publicPhotoDirectory = public_path('storage/photos');
            $storagePhotoPath = storage_path('app/public/photos');

            if (! File::exists($publicPhotoDirectory)) {
                File::makeDirectory($publicPhotoDirectory, 0755, true);
            }

            // Delete the old photo from both legacy and current locations.
            if ($user->photo) {
                if (File::exists($publicPhotoDirectory . DIRECTORY_SEPARATOR . $user->photo)) {
                    File::delete($publicPhotoDirectory . DIRECTORY_SEPARATOR . $user->photo);
                }

                if (Storage::disk('public')->exists('photos/' . $user->photo)) {
                    Storage::disk('public')->delete('photos/' . $user->photo);
                }
            }

            // Generate unique filename
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();

            // Save the uploaded file directly in public/storage/photos.
            $photo->move($publicPhotoDirectory, $photoName);

            // Keep storage/app/public/photos in sync when that directory exists.
            if (File::exists($storagePhotoPath)) {
                File::copy(
                    $publicPhotoDirectory . DIRECTORY_SEPARATOR . $photoName,
                    $storagePhotoPath . DIRECTORY_SEPARATOR . $photoName
                );
            }

            if (File::exists($publicPhotoDirectory . DIRECTORY_SEPARATOR . $photoName)) {
                $user->photo = $photoName;
                $user->save();
            }
        }

        // Update or create user detail
        $userDetail->fill($request->only([
            'phone',
            'address_line1',
            'address_line2',
            'city',
            'state',
            'postal_code',
            'country',
            'designation',
            'additional_info',
            'date_of_joining',
            'date_of_retirement',
            'date_of_contractual',
            'date_of_deputation'
        ]));
        $userDetail->user_id = $user->id;
        $userDetail->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
