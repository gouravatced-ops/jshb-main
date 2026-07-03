<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    /**
     * Check if user's password needs reset
     */
    public function checkPasswordExpiry()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['expired' => false]);
        }
        $passwordExpiryDays = config('panel.password_expiry_days', 90);
        $passwordCreatedAt = $user->password_created_at;

        if (!$passwordCreatedAt) {
            return response()->json(['expired' => false]);
        }

        $daysOld = $passwordCreatedAt->diffInDays(now());
        $isExpired = $daysOld >= $passwordExpiryDays;

        return response()->json([
            'expired' => $isExpired,
            'daysOld' => $daysOld,
            'expiryDays' => $passwordExpiryDays,
            'daysRemaining' => max(0, $passwordExpiryDays - $daysOld),
        ]);
    }

    /**
     * Show password reset form
     */
    public function showResetForm()
    {
        return view('password.reset');
    }

    /**
     * Update password
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'captcha' => 'required|string',
            'captcha_answer' => 'required|numeric',
        ], [
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
            'captcha_answer.required' => 'Please answer the security question.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        if (!session('global_otp_verified_update_password')) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify OTP before updating your password.'
            ]);
        }

        $user = Auth::user();

        // Verify old password
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Current password is incorrect.']);
        }

        // Verify new password is different from old
        if (Hash::check($request->new_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'New password must be different from current password.']);
        }

        // Verify captcha
        $captchaData = session('captcha_' . $user->id);
        if (!$captchaData || $captchaData['answer'] != $request->captcha_answer) {
            return response()->json(['success' => false, 'message' => 'Incorrect security answer. Please try again.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
            'password_created_at' => now(),
        ]);

        // Clear session flags
        session()->forget('captcha_' . $user->id);
        session()->forget('global_otp_verified_update_password');

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * Update or Set Quick PIN
     */
    public function updateQuickPin(Request $request)
    {
        $user = Auth::user();
        $hasPin = !empty($user->secure_pin);

        $rules = [
            'captcha_answer' => 'required|numeric',
            'secure_pin' => 'required|numeric|digits:4',
        ];

        if ($hasPin) {
            $rules['current_pin'] = 'required|numeric|digits:4';
        }

        $request->validate($rules);

        if (!session('global_otp_verified_update_quick_pin')) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify OTP before updating Quick PIN.'
            ]);
        }

        // Verify captcha
        $captchaData = session('captcha_' . $user->id);
        if (!$captchaData || $captchaData['answer'] != $request->captcha_answer) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect security answer.'
            ]);
        }
        
        // Verify current PIN if updating
        if ($hasPin && !Hash::check($request->current_pin, $user->secure_pin)) {
            return response()->json([
                'success' => false,
                'message' => 'Current PIN is incorrect.'
            ]);
        }

        session()->forget('captcha_' . $user->id);
        session()->forget('global_otp_verified_update_quick_pin');

        $user->secure_pin = Hash::make($request->secure_pin);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $hasPin ? 'Quick PIN updated successfully.' : 'Quick PIN set successfully.'
        ]);
    }

    /**
     * Generate captcha for password reset
     */
    public function updateInternalPassword(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Not authenticated.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'current_internal_password' => 'required|string',
            'internal_password' => 'required|string|min:8|confirmed',
            'captcha_answer' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        if (!session('global_otp_verified_update_internal_password')) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify OTP before updating Internal Password.'
            ], 422);
        }

        // Validate Captcha
        $captchaData = session('captcha_' . $user->id);
        if (!$captchaData || 
            !isset($captchaData['answer']) || 
            (int)$request->captcha_answer !== (int)$captchaData['answer'] ||
            now()->diffInMinutes($captchaData['generated_at']) > 5) {
            
            return response()->json([
                'success' => false, 
                'message' => 'Invalid or expired CAPTCHA answer.'
            ], 422);
        }

        // Verify current internal password
        if (!Hash::check($request->current_internal_password, $user->internal_password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current internal password is incorrect.'
            ], 422);
        }

        // Update internal password
        $user->internal_password = Hash::make($request->internal_password);
        $user->save();

        // Clear captcha session
        session()->forget('captcha_' . $user->id);
        session()->forget('global_otp_verified_update_internal_password');

        return response()->json([
            'success' => true,
            'message' => 'Internal password updated successfully.'
        ]);
    }

    public function generateCaptcha()
    {
        $user = Auth::user();
        
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $operations = ['+', '-', '*'];
        $operation = $operations[array_rand($operations)];

        $answer = 0;
        $question = "$num1 $operation $num2";

        switch ($operation) {
            case '+':
                $answer = $num1 + $num2;
                break;
            case '-':
                $answer = $num1 - $num2;
                break;
            case '*':
                $answer = $num1 * $num2;
                break;
        }

        // Store answer in session with expiry
        session([
            'captcha_' . $user->id => [
                'question' => $question,
                'answer' => $answer,
                'generated_at' => now(),
            ],
        ]);

        return response()->json([
            'question' => $question,
        ]);
    }
}
