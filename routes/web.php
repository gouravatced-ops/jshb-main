<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/grievance', function () {
    return view('grievance');
})->name('grievance');

Route::get('/schemes', function () {
    return view('schemes');
})->name('schemes');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/tenders', function () {
    return view('tenders');
})->name('tenders');

Route::get('/run-storage-link', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return 'Storage link created successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/run-storage-unlink', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:unlink');
        return 'Storage link removed successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/fix-storage', function () {
    try {
        $publicStorage = public_path('storage');
        $appStorage = storage_path('app/public');
        
        $messages = [];
        
        if (!file_exists($appStorage)) {
            mkdir($appStorage, 0775, true);
            $messages[] = "Created storage/app/public directory.";
        }
        
        if (file_exists($publicStorage) && !is_link($publicStorage)) {
            \Illuminate\Support\Facades\File::deleteDirectory($publicStorage);
            $messages[] = "Deleted invalid physical 'storage' folder in public.";
        }
        
        if (is_link($publicStorage)) {
            unlink($publicStorage);
            $messages[] = "Removed broken symlink.";
        }
        
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $messages[] = "Created fresh storage symlink successfully!";
        
        return implode("<br><br>", $messages);
    } catch (\Exception $e) {
        return "Error fixing storage: " . $e->getMessage();
    }
});

Route::get('/clear-cache', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return 'All caches (config, route, view, application) have been cleared successfully!';
    } catch (\Exception $e) {
        return 'Error clearing cache: ' . $e->getMessage();
    }
});

Route::get('/run-python', function () {
    try {
        $path = base_path('hello.py');
        $command = "python " . escapeshellarg($path) . " 2>&1";
        
        // Use python3 if we are on linux/mac and python is not aliased
        if (DIRECTORY_SEPARATOR === '/') {
            $command = "python3 " . escapeshellarg($path) . " 2>&1";
        }
        
        $output = shell_exec($command);
        return 'Python Output:<br><strong>' . nl2br(htmlspecialchars($output)) . '</strong>';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
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

    // Notifications
    Route::post('/notifications/mark-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.mark-read');
    Route::get('/my-notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('my-notifications.index');

    // common Routes for retrive condition response of data
    Route::get('/get-sub-divisions/{division}', [CommonController::class, 'getDivision']);
    Route::get('/get-property-types/{category}', [CommonController::class, 'getPropertyType']);
    Route::get('/get-property-sub-types/{typeId}', [CommonController::class, 'getPropertySubType']);
    Route::get('/districts/{stateId}', [CommonController::class, 'getDistrict']);
    Route::post('/scheme-list', [CommonController::class, 'getSchemeList']);
    Route::get('/get-scheme-details/{id}',[CommonController::class, 'getSchemeDetails']
);
});

// Development Routes
if (file_exists(base_path('routes/dev.php'))) {
    require base_path('routes/dev.php');
}

// Photo Capture Routes
Route::post('/api/photo-session/generate', [App\Http\Controllers\PhotoCaptureController::class, 'generateToken'])->name('photo-session.generate');
Route::get('/api/photo-session/check/{token}', [App\Http\Controllers\PhotoCaptureController::class, 'checkSession'])->name('photo-session.check');
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
require __DIR__ . '/coassistant-routes.php';

 / /   M e d i a   F a l l b a c k   R o u t e s 
 R o u t e : : g e t ( ' / m e d i a / p r o f i l e / { f i l e n a m e } ' ,   [ A p p \ H t t p \ C o n t r o l l e r s \ M e d i a C o n t r o l l e r : : c l a s s ,   ' p r o f i l e I m a g e ' ] ) - > n a m e ( ' m e d i a . p r o f i l e ' ) ; 
 R o u t e : : g e t ( ' / m e d i a / d o c u m e n t ' ,   [ A p p \ H t t p \ C o n t r o l l e r s \ M e d i a C o n t r o l l e r : : c l a s s ,   ' d o c u m e n t ' ] ) - > n a m e ( ' m e d i a . d o c u m e n t ' ) ; 
 R o u t e : : g e t ( ' / m e d i a / i m a g e ' ,   [ A p p \ H t t p \ C o n t r o l l e r s \ M e d i a C o n t r o l l e r : : c l a s s ,   ' g e n e r i c I m a g e ' ] ) - > n a m e ( ' m e d i a . i m a g e ' ) ; 
  
 