<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoAssistant\DashboardController;
use App\Http\Controllers\Shared\ApplicationController;
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
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/history', [ApplicationController::class, 'history'])->name('applications.history');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::get('/applications/{application}/action/{action_type}', [ApplicationController::class, 'actionForm'])->name('applications.action.form');
        Route::post('/applications/{application}/action', [ApplicationController::class, 'processAction'])->name('applications.action');
        Route::post('/applications/{application}/reset', [ApplicationController::class, 'resetWorkflow'])->name('applications.reset');
        Route::post('/applications/{application}/upload-document', [ApplicationController::class, 'uploadDocument'])->name('applications.upload-document');
        Route::get('/applications/{application}/notes-pdf', [ApplicationController::class, 'previewNotesPdf'])->name('applications.notes.pdf');
        Route::post('/document-requests/store', [ApplicationController::class, 'requestDocument'])->name('document-requests.store');

        // Assets (Signatures, Stamps)
        Route::get('/assets', [App\Http\Controllers\CoAssistant\AssetController::class, 'index'])->name('assets.index');
        Route::post('/assets', [App\Http\Controllers\CoAssistant\AssetController::class, 'store'])->name('assets.store');
        Route::delete('/assets/{id}', [App\Http\Controllers\CoAssistant\AssetController::class, 'destroy'])->name('assets.destroy');
        Route::get('/api/assets', [App\Http\Controllers\CoAssistant\AssetController::class, 'getAssetsForEditor'])->name('api.assets');
    });
