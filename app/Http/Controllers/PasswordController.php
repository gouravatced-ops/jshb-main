<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $passwordExpiryDays = config('panel.password_expiry_days', 30);
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
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'captcha' => 'required|string',
            'captcha_answer' => 'required|numeric',
        ], [
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
            'captcha_answer.required' => 'Please answer the security question.',
        ]);

        $user = Auth::user();

        // Verify old password
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Current password is incorrect.']);
        }

        // Verify new password is different from old
        if (Hash::check($request->new_password, $user->password)) {
            return back()->withErrors(['new_password' => 'New password must be different from current password.']);
        }

        // Verify captcha
        $captchaData = session('captcha_' . $user->id);
        if (!$captchaData || $captchaData['answer'] != $request->captcha_answer) {
            return back()->withErrors(['captcha_answer' => 'Incorrect security answer. Please try again.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
            'password_created_at' => now(),
        ]);

        // Clear captcha from session
        session()->forget('captcha_' . $user->id);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * Generate captcha for password reset
     */
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
