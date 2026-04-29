<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlockListController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EngineerController;
use App\Http\Controllers\GuestHouseRequisitionController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ParentOrganizationController;
use App\Http\Controllers\PostTypeController;
use App\Http\Controllers\SubDivisionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('admin')
    ->as('admin.')
    ->middleware('role:admin')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
        Route::get('/requisitions', [GuestHouseRequisitionController::class, 'adminIndex'])->name('requisitions.index');
        Route::patch('/requisitions/{requisition}/status', [GuestHouseRequisitionController::class, 'updateStatus'])->name('requisitions.update-status');

        Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions.index');
        Route::get('/divisions/search', [DivisionController::class, 'search'])->name('divisions.search');
        Route::get('/divisions/create', [DivisionController::class, 'create'])->name('divisions.create');
        Route::post('/divisions', [DivisionController::class, 'store'])->name('divisions.store');
        Route::get('/divisions/{division}/edit', [DivisionController::class, 'edit'])->name('divisions.edit');
        Route::put('/divisions/{division}', [DivisionController::class, 'update'])->name('divisions.update');
        Route::delete('/divisions/{division}', [DivisionController::class, 'destroy'])->name('divisions.destroy');

        Route::get('/sub-divisions', [SubDivisionController::class, 'index'])->name('sub-divisions.index');
        Route::get('/sub-divisions/search', [SubDivisionController::class, 'search'])->name('sub-divisions.search');
        Route::get('/sub-divisions/create', [SubDivisionController::class, 'create'])->name('sub-divisions.create');
        Route::post('/sub-divisions', [SubDivisionController::class, 'store'])->name('sub-divisions.store');
        Route::get('/sub-divisions/{subDivision}/edit', [SubDivisionController::class, 'edit'])->name('sub-divisions.edit');
        Route::put('/sub-divisions/{subDivision}', [SubDivisionController::class, 'update'])->name('sub-divisions.update');
        Route::delete('/sub-divisions/{subDivision}', [SubDivisionController::class, 'destroy'])->name('sub-divisions.destroy');

        Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
        Route::get('/organizations/search', [OrganizationController::class, 'search'])->name('organizations.search');
        Route::get('/organizations/create', [OrganizationController::class, 'create'])->name('organizations.create');
        Route::post('/organizations', [OrganizationController::class, 'store'])->name('organizations.store');
        Route::get('/organizations/{organization}/edit', [OrganizationController::class, 'edit'])->name('organizations.edit');
        Route::put('/organizations/{organization}', [OrganizationController::class, 'update'])->name('organizations.update');
        Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');

        Route::get('/parent-organizations', [ParentOrganizationController::class, 'index'])->name('parent-organizations.index');
        Route::get('/parent-organizations/search', [ParentOrganizationController::class, 'search'])->name('parent-organizations.search');
        Route::get('/parent-organizations/create', [ParentOrganizationController::class, 'create'])->name('parent-organizations.create');
        Route::post('/parent-organizations', [ParentOrganizationController::class, 'store'])->name('parent-organizations.store');
        Route::get('/parent-organizations/{parentOrganization}/edit', [ParentOrganizationController::class, 'edit'])->name('parent-organizations.edit');
        Route::put('/parent-organizations/{parentOrganization}', [ParentOrganizationController::class, 'update'])->name('parent-organizations.update');
        Route::delete('/parent-organizations/{parentOrganization}', [ParentOrganizationController::class, 'destroy'])->name('parent-organizations.destroy');

        Route::get('/post-types', [PostTypeController::class, 'index'])->name('post-types.index');
        Route::get('/post-types/search', [PostTypeController::class, 'search'])->name('post-types.search');
        Route::get('/post-types/create', [PostTypeController::class, 'create'])->name('post-types.create');
        Route::post('/post-types', [PostTypeController::class, 'store'])->name('post-types.store');
        Route::get('/post-types/{postType}/edit', [PostTypeController::class, 'edit'])->name('post-types.edit');
        Route::put('/post-types/{postType}', [PostTypeController::class, 'update'])->name('post-types.update');
        Route::delete('/post-types/{postType}', [PostTypeController::class, 'destroy'])->name('post-types.destroy');

        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/search', [DepartmentController::class, 'search'])->name('departments.search');
        Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

        Route::get('/blocks', [BlockListController::class, 'index'])->name('blocks.index');
        Route::get('/blocks/search', [BlockListController::class, 'search'])->name('blocks.search');
        Route::get('/blocks/create', [BlockListController::class, 'create'])->name('blocks.create');
        Route::post('/blocks', [BlockListController::class, 'store'])->name('blocks.store');
        Route::get('/blocks/{block}/edit', [BlockListController::class, 'edit'])->name('blocks.edit');
        Route::put('/blocks/{block}', [BlockListController::class, 'update'])->name('blocks.update');
        Route::delete('/blocks/{block}', [BlockListController::class, 'destroy'])->name('blocks.destroy');

        Route::get('/engineers', [EngineerController::class, 'index'])->name('engineers.index');
        Route::get('/engineers/search', [EngineerController::class, 'search'])->name('engineers.search');
        Route::get('/engineers/create', [EngineerController::class, 'create'])->name('engineers.create');
        Route::post('/engineers', [EngineerController::class, 'store'])->name('engineers.store');
        Route::post('/engineers/{engineer}/verify-sensitive', [EngineerController::class, 'verifySensitive'])->name('engineers.verify-sensitive');
        Route::get('/engineers/{engineer}/edit', [EngineerController::class, 'edit'])->name('engineers.edit');
        Route::put('/engineers/{engineer}', [EngineerController::class, 'update'])->name('engineers.update');
    });
