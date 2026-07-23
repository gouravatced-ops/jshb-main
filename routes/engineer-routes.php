<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('engineer')
    ->as('engineer.')
    ->middleware('role:engineer')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'engineer'])->name('dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
        
        // Applications
        Route::get('/applications', [App\Http\Controllers\Engineer\ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/history', [App\Http\Controllers\Engineer\ApplicationController::class, 'history'])->name('applications.history');
        Route::get('/applications/{application}', [App\Http\Controllers\Engineer\ApplicationController::class, 'show'])->name('applications.show');
        Route::get('/applications/{application}/action/{action_type}', [App\Http\Controllers\Engineer\ApplicationController::class, 'actionForm'])->name('applications.action.form');
        Route::post('/applications/{application}/action', [App\Http\Controllers\Engineer\ApplicationController::class, 'processAction'])->name('applications.action');
        Route::post('/applications/{application}/reset', [App\Http\Controllers\Engineer\ApplicationController::class, 'resetWorkflow'])->name('applications.reset');
        Route::post('/applications/{application}/upload-document', [App\Http\Controllers\Engineer\ApplicationController::class, 'uploadDocument'])->name('applications.upload-document');
        Route::get('/applications/{application}/notes-pdf', [App\Http\Controllers\Engineer\ApplicationController::class, 'previewNotesPdf'])->name('applications.notes.pdf');
        Route::post('/document-requests/store', [App\Http\Controllers\Engineer\ApplicationController::class, 'requestDocument'])->name('document-requests.store');

        // Assets (Signatures, Stamps)
        Route::get('/assets', [App\Http\Controllers\Engineer\AssetController::class, 'index'])->name('assets.index');
        Route::post('/assets', [App\Http\Controllers\Engineer\AssetController::class, 'store'])->name('assets.store');
        Route::delete('/assets/{id}', [App\Http\Controllers\Engineer\AssetController::class, 'destroy'])->name('assets.destroy');
        Route::get('/api/assets', [App\Http\Controllers\Engineer\AssetController::class, 'getAssetsForEditor'])->name('api.assets');
    });
