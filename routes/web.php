<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
    Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify-otp');
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.store');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/lock-screen', [AuthController::class, 'showLockScreen'])->name('lock.screen');
    Route::post('/lock-screen/lock', [AuthController::class, 'lockScreen'])->name('lock.lock');
    Route::post('/lock-screen/unlock', [AuthController::class, 'unlockScreen'])->name('lock.unlock');

    Route::get('/password/check-expiry', [PasswordController::class, 'checkPasswordExpiry'])->name('password.check-expiry');
    Route::post('/password/update', [PasswordController::class, 'update'])->name('password.update');
    Route::post('/password/generate-captcha', [PasswordController::class, 'generateCaptcha'])->name('password.captcha');
});

require __DIR__ . '/user-routes.php';
require __DIR__ . '/admin-routes.php';
