<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('accountant')
    ->as('accountant.')
    ->middleware('role:accountant')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'accountant'])->name('dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
    });
