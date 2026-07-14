<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/login/resend-otp', [AuthController::class, 'resendLoginOtp'])->name('login.resend-otp');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
    Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify-otp');
    Route::post('/forgot-password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('password.resend-otp');
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.store');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/lock-screen', [AuthController::class, 'showLockScreen'])->name('lock.screen');
    Route::post('/lock-screen/lock', [AuthController::class, 'lockScreen'])->name('lock.lock');
    Route::post('/lock-screen/unlock', [AuthController::class, 'unlockScreen'])->name('lock.unlock');

    Route::post('/first-setup/internal-password', [App\Http\Controllers\FirstLoginSetupController::class, 'setupInternalPassword'])->name('first-setup.internal-password');
    Route::post('/first-setup/quick-pin', [App\Http\Controllers\FirstLoginSetupController::class, 'setupQuickPin'])->name('first-setup.quick-pin');
    Route::post('/first-setup/skip-pin', [App\Http\Controllers\FirstLoginSetupController::class, 'skipQuickPin'])->name('first-setup.skip-pin');

    Route::get('/password/check-expiry', [PasswordController::class, 'checkPasswordExpiry'])->name('password.check-expiry');
    Route::post('/password/update', [PasswordController::class, 'update'])->name('password.update');
    Route::post('/password/update-quick-pin', [PasswordController::class, 'updateQuickPin'])->name('password.update-quick-pin');
    Route::post('/password/update-internal-password', [PasswordController::class, 'updateInternalPassword'])->name('password.update-internal-password');
    Route::post('/password/generate-captcha', [PasswordController::class, 'generateCaptcha'])->name('password.captcha');

    // Global OTP routes
    Route::post('/global-otp/send', [App\Http\Controllers\GlobalOtpController::class, 'sendOtp'])->name('global-otp.send');
    Route::post('/global-otp/verify', [App\Http\Controllers\GlobalOtpController::class, 'verifyOtp'])->name('global-otp.verify');
    
    // My Activity
    Route::get('/my-activity', [App\Http\Controllers\AdminController::class, 'myActivity'])->name('my-activity');

    // common Routes for retrive condition response of data
    Route::get('/get-sub-divisions/{division}', [CommonController::class, 'getDivision']);
    Route::get('/get-property-types/{category}', [CommonController::class, 'getPropertyType']);
    Route::get('/get-property-sub-types/{typeId}', [CommonController::class, 'getPropertySubType']);
    Route::get('/districts/{stateId}', [CommonController::class, 'getDistrict']);
    Route::post('/scheme-list', [CommonController::class, 'getSchemeList']);
    Route::get('/get-scheme-details/{id}',[CommonController::class, 'getSchemeDetails']
);
});

// Photo Capture Routes
Route::post('/api/photo-session/generate', [App\Http\Controllers\PhotoCaptureController::class, 'generateToken'])->name('photo-session.generate');
Route::get('/mobile/capture/{token}', [App\Http\Controllers\PhotoCaptureController::class, 'captureForm'])->name('mobile.capture');
Route::post('/mobile/capture/{token}/upload', [App\Http\Controllers\PhotoCaptureController::class, 'upload'])->name('mobile.capture.upload');

require __DIR__ . '/user-routes.php';
require __DIR__ . '/admin-routes.php';
require __DIR__ . '/staff-routes.php';
require __DIR__ . '/division-routes.php';
require __DIR__ . '/subdivision-routes.php';
require __DIR__ . '/engineer-routes.php';
require __DIR__ . '/accountant-routes.php';
require __DIR__ . '/managing-routes.php';
require __DIR__ . '/operator-routes.php';
