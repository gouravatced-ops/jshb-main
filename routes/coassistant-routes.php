<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoAssistant\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('co-assistant')
    ->as('coassistant.')
    ->middleware('role:coassistant')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
        
        // Applications
        Route::get('/applications', [App\Http\Controllers\CoAssistant\ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/history', [App\Http\Controllers\CoAssistant\ApplicationController::class, 'history'])->name('applications.history');
        Route::get('/applications/{application}', [App\Http\Controllers\CoAssistant\ApplicationController::class, 'show'])->name('applications.show');
        Route::get('/applications/{application}/action/{action_type}', [App\Http\Controllers\CoAssistant\ApplicationController::class, 'actionForm'])->name('applications.action.form');
        Route::post('/applications/{application}/action', [App\Http\Controllers\CoAssistant\ApplicationController::class, 'processAction'])->name('applications.action');
        Route::post('/applications/{application}/reset', [App\Http\Controllers\CoAssistant\ApplicationController::class, 'resetWorkflow'])->name('applications.reset');
        Route::post('/applications/{application}/upload-document', [App\Http\Controllers\CoAssistant\ApplicationController::class, 'uploadDocument'])->name('applications.upload-document');
        Route::get('/applications/{application}/notes-pdf', [App\Http\Controllers\CoAssistant\ApplicationController::class, 'previewNotesPdf'])->name('applications.notes.pdf');
        Route::post('/document-requests/store', [App\Http\Controllers\CoAssistant\ApplicationController::class, 'requestDocument'])->name('document-requests.store');

        // Assets (Signatures, Stamps)
        Route::get('/assets', [App\Http\Controllers\CoAssistant\AssetController::class, 'index'])->name('assets.index');
        Route::post('/assets', [App\Http\Controllers\CoAssistant\AssetController::class, 'store'])->name('assets.store');
        Route::delete('/assets/{id}', [App\Http\Controllers\CoAssistant\AssetController::class, 'destroy'])->name('assets.destroy');
        Route::get('/api/assets', [App\Http\Controllers\CoAssistant\AssetController::class, 'getAssetsForEditor'])->name('api.assets');
    });
