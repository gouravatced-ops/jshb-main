<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\SubDivisionController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\SchemeController;
use App\Http\Controllers\Admin\AllotteeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('admin')
    ->as('admin.')
    ->middleware('role:admin')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');

        // Division
        Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions.index');
        Route::get('/divisions/search', [DivisionController::class, 'search'])->name('divisions.search');
        Route::get('/divisions/create', [DivisionController::class, 'create'])->name('divisions.create');
        Route::post('/divisions', [DivisionController::class, 'store'])->name('divisions.store');
        Route::get('/divisions/{division}/edit', [DivisionController::class, 'edit'])->name('divisions.edit');
        Route::put('/divisions/{division}', [DivisionController::class, 'update'])->name('divisions.update');
        Route::delete('/divisions/{division}', [DivisionController::class, 'destroy'])->name('divisions.destroy');

        // Sub Division
        Route::get('/sub-divisions', [SubDivisionController::class, 'index'])->name('sub-divisions.index');
        Route::get('/sub-divisions/search', [SubDivisionController::class, 'search'])->name('sub-divisions.search');
        Route::get('/sub-divisions/create', [SubDivisionController::class, 'create'])->name('sub-divisions.create');
        Route::post('/sub-divisions', [SubDivisionController::class, 'store'])->name('sub-divisions.store');
        Route::get('/sub-divisions/{subDivision}/edit', [SubDivisionController::class, 'edit'])->name('sub-divisions.edit');
        Route::put('/sub-divisions/{subDivision}', [SubDivisionController::class, 'update'])->name('sub-divisions.update');
        Route::delete('/sub-divisions/{subDivision}', [SubDivisionController::class, 'destroy'])->name('sub-divisions.destroy');

        // Property Category
        Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
        Route::get('/categories/search', [CategoriesController::class, 'search'])->name('categories.search');
        Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
        Route::get('/categories/{categories}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{categories}', [CategoriesController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{categories}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

        // Scheme
        Route::get('/schemes', [SchemeController::class, 'index'])->name('schemes.index');
        Route::get('/schemes/search', [SchemeController::class, 'search'])->name('schemes.search');
        Route::get('/schemes/create', [SchemeController::class, 'create'])->name('schemes.create');
        Route::post('/schemes', [SchemeController::class, 'store'])->name('schemes.store');
        Route::get('/schemes/{scheme}/edit', [SchemeController::class, 'edit'])->name('schemes.edit');
        Route::put('/schemes/{scheme}', [SchemeController::class, 'update'])->name('schemes.update');
        Route::delete('/schemes/{scheme}', [SchemeController::class, 'destroy'])->name('schemes.destroy');

        // scheme blocks
        Route::get('/schemes/blocks/{scheme}', [SchemeController::class, 'blocksIndex'])->name('schemes.blocks.index');
        Route::get('/schemes/blocks/search/{scheme}', [SchemeController::class, 'blocksSearch'])->name('schemes.blocks.search');
        Route::get('/schemes/blocks/create/{scheme}', [SchemeController::class, 'blocksCreate'])->name('schemes.blocks.create');
        Route::post('/schemes/blocks/{scheme}', [SchemeController::class, 'blocksStore'])->name('schemes.blocks.store');
        Route::get('/schemes/blocks/{scheme}/{block}/edit', [SchemeController::class, 'blocksEdit'])->name('schemes.blocks.edit');
        Route::post('/schemes/blocks/{scheme}/{block}', [SchemeController::class, 'blocksUpdate'])->name('schemes.blocks.update');
        Route::post('/schemes/blocks/{scheme}/{block}', [SchemeController::class, 'blocksDestroy'])->name('schemes.blocks.destroy');

        // Scheme Quota
        Route::get('/schemes/{scheme}/quotas', [SchemeController::class, 'quotasIndex'])->name('schemes.quotas.index');
        Route::put('/schemes/{scheme}/quotas/bulk-update', [SchemeController::class, 'quotasBulkUpdate'])->name('schemes.quotas.bulk-update');

        // Allottee
        Route::get('/allottees/list', [AllotteeController::class, 'index'])->name('allottees.index');
        Route::get('/allottees/process/start', [AllotteeController::class, 'indexStart'])->name('apply.index');
        Route::get('/allottees/step/{step}/{applicantId?}', [AllotteeController::class, 'getStep'])->name('apply.step');
        Route::post('/apply/step1/save', [AllotteeController::class, 'saveStep1'])->name('apply.step1.save');
        Route::post('/apply/step2/save', [AllotteeController::class, 'saveStep2'])->name('apply.step2.save');
        Route::post('/apply/step3/save', [AllotteeController::class, 'saveStep3'])->name('apply.step3.save');

    });
