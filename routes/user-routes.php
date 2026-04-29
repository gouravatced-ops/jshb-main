<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestHouseRequisitionController;
use App\Http\Controllers\PasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('user')
    ->middleware('role:user')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
        Route::get('/requisitions', [GuestHouseRequisitionController::class, 'userIndex'])->name('requisitions.index');
        Route::get('/requisitions/create', [GuestHouseRequisitionController::class, 'create'])->name('requisitions.create');
        Route::post('/requisitions', [GuestHouseRequisitionController::class, 'store'])->name('requisitions.store');

        Route::get('/lock-screen', [AuthController::class, 'showLockScreen'])->name('lock.screen');
        Route::post('/lock-screen/lock', [AuthController::class, 'lockScreen'])->name('lock.lock');
        Route::post('/lock-screen/unlock', [AuthController::class, 'unlockScreen'])->name('lock.unlock');
    });
