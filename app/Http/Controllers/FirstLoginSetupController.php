<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FirstLoginSetupController extends Controller
{
    public function setupInternalPassword(Request $request)
    {
        $request->validate([
            'internal_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
                'confirmed'
            ],
            'captcha_answer' => 'required|numeric',
        ], [
            'internal_password.regex' => 'Password must contain at least 1 uppercase, 1 lowercase, 1 number, and 1 special character.',
        ]);

        $user = Auth::user();

        // Verify captcha
        $captchaData = session('captcha_' . $user->id);
        if (!$captchaData || $captchaData['answer'] != $request->captcha_answer) {
            return response()->json(['success' => false, 'message' => 'Incorrect security answer. Please try again.']);
        }

        // Clear captcha from session
        session()->forget('captcha_' . $user->id);

        $user->internal_password = Hash::make($request->internal_password);
        $user->save();

        return response()->json(['success' => true, 'message' => 'Internal password set successfully.']);
    }

    public function setupQuickPin(Request $request)
    {
        $request->validate([
            'secure_pin' => 'required|numeric|digits:4',
        ]);

        $user = Auth::user();
        $user->secure_pin = Hash::make($request->secure_pin);
        $user->is_first_login = 1; // Mark as completed
        $user->save();

        return response()->json(['success' => true, 'message' => 'Quick PIN set successfully.']);
    }

    public function skipQuickPin(Request $request)
    {
        $user = Auth::user();
        $user->is_first_login = 1; // Mark as completed
        $user->save();

        return response()->json(['success' => true, 'message' => 'Quick PIN setup skipped.']);
    }
}
