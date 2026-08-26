<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('operator')
    ->as('operator.')
    ->middleware('role:operator')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'operator'])->name('dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');

        Route::prefix('applications')->as('applications.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Shared\ApplicationController::class, 'index'])->name('index');
            Route::get('/history', [\App\Http\Controllers\Shared\ApplicationController::class, 'history'])->name('history');
            Route::get('/{application}', [\App\Http\Controllers\Shared\ApplicationController::class, 'show'])->name('show');
            Route::get('/{application}/action/{action_type}', [\App\Http\Controllers\Shared\ApplicationController::class, 'action'])->name('action.form');
            Route::post('/{application}/action', [\App\Http\Controllers\Shared\ApplicationController::class, 'processAction'])->name('action');
            Route::get('/{application}/notes/pdf', [\App\Http\Controllers\Shared\ApplicationController::class, 'downloadNotesPdf'])->name('notes.pdf');
            Route::post('/{application}/upload-document', [\App\Http\Controllers\Shared\ApplicationController::class, 'uploadApplicationDocument'])->name('upload-document');
            Route::post('/{application}/delete-document/{document}', [\App\Http\Controllers\Shared\ApplicationController::class, 'deleteApplicationDocument'])->name('delete-document');
            Route::post('/{application}/update-applicant', [\App\Http\Controllers\Shared\ApplicationController::class, 'updateApplicantDetails'])->name('update-applicant');
            Route::post('/{application}/assign-property', [\App\Http\Controllers\Shared\ApplicationController::class, 'assignProperty'])->name('assign-property');
        });
    });
