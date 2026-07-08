<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\SubDivisionController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\SchemeController;
use App\Http\Controllers\Admin\AllotteeController;
use App\Http\Controllers\Admin\AllotteePaymentController;
use App\Http\Controllers\Admin\AllotteeEmiController;
use App\Http\Controllers\Admin\PropertyTypeController;
use App\Http\Controllers\Admin\PropertyMainTypeController;
use App\Http\Controllers\Admin\QuarterTypeController;
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
        Route::post('/divisions/{division}/toggle-status', [DivisionController::class, 'toggleStatus'])->name('divisions.toggle-status');
        Route::delete('/divisions/{division}', [DivisionController::class, 'destroy'])->name('divisions.destroy');

        // Sub Division
        Route::get('/sub-divisions', [SubDivisionController::class, 'index'])->name('sub-divisions.index');
        Route::get('/sub-divisions/search', [SubDivisionController::class, 'search'])->name('sub-divisions.search');
        Route::get('/sub-divisions/create', [SubDivisionController::class, 'create'])->name('sub-divisions.create');
        Route::post('/sub-divisions', [SubDivisionController::class, 'store'])->name('sub-divisions.store');
        Route::get('/sub-divisions/{subDivision}/edit', [SubDivisionController::class, 'edit'])->name('sub-divisions.edit');
        Route::put('/sub-divisions/{subDivision}', [SubDivisionController::class, 'update'])->name('sub-divisions.update');
        Route::post('/sub-divisions/{subDivision}/toggle-status', [SubDivisionController::class, 'toggleStatus'])->name('sub-divisions.toggle-status');
        Route::delete('/sub-divisions/{subDivision}', [SubDivisionController::class, 'destroy'])->name('sub-divisions.destroy');

        // Property Category
        Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
        Route::get('/categories/search', [CategoriesController::class, 'search'])->name('categories.search');
        Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
        Route::get('/categories/{categories}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{categories}', [CategoriesController::class, 'update'])->name('categories.update');
        Route::post('/categories/{categories}/toggle-status', [CategoriesController::class, 'toggleStatus'])->name('categories.toggle-status');
        Route::delete('/categories/{categories}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

        // Property Type
        Route::get('/property-types', [PropertyTypeController::class, 'index'])->name('property-types.index');
        Route::get('/property-types/search', [PropertyTypeController::class, 'search'])->name('property-types.search');
        Route::get('/property-types/create', [PropertyTypeController::class, 'create'])->name('property-types.create');
        Route::post('/property-types', [PropertyTypeController::class, 'store'])->name('property-types.store');
        Route::get('/property-types/{propertyType}/edit', [PropertyTypeController::class, 'edit'])->name('property-types.edit');
        Route::put('/property-types/{propertyType}', [PropertyTypeController::class, 'update'])->name('property-types.update');
        Route::post('/property-types/{propertyType}/toggle-status', [PropertyTypeController::class, 'toggleStatus'])->name('property-types.toggle-status');
        Route::delete('/property-types/{propertyType}', [PropertyTypeController::class, 'destroy'])->name('property-types.destroy');

        // Property Sub Type
        Route::get('/property-sub-types', [PropertyMainTypeController::class, 'index'])->name('property-sub-types.index');
        Route::get('/property-sub-types/search', [PropertyMainTypeController::class, 'search'])->name('property-sub-types.search');
        Route::get('/property-sub-types/create', [PropertyMainTypeController::class, 'create'])->name('property-sub-types.create');
        Route::post('/property-sub-types', [PropertyMainTypeController::class, 'store'])->name('property-sub-types.store');
        Route::get('/property-sub-types/{propertySubType}/edit', [PropertyMainTypeController::class, 'edit'])->name('property-sub-types.edit');
        Route::put('/property-sub-types/{propertySubType}', [PropertyMainTypeController::class, 'update'])->name('property-sub-types.update');
        Route::post('/property-sub-types/{propertySubType}/toggle-status', [PropertyMainTypeController::class, 'toggleStatus'])->name('property-sub-types.toggle-status');
        Route::delete('/property-sub-types/{propertySubType}', [PropertyMainTypeController::class, 'destroy'])->name('property-sub-types.destroy');

        // Quarter Type
        Route::get('/quarter-types', [QuarterTypeController::class, 'index'])->name('quarter-types.index');
        Route::get('/quarter-types/search', [QuarterTypeController::class, 'search'])->name('quarter-types.search');
        Route::get('/quarter-types/create', [QuarterTypeController::class, 'create'])->name('quarter-types.create');
        Route::post('/quarter-types', [QuarterTypeController::class, 'store'])->name('quarter-types.store');
        Route::get('/quarter-types/{quarterType}/edit', [QuarterTypeController::class, 'edit'])->name('quarter-types.edit');
        Route::put('/quarter-types/{quarterType}', [QuarterTypeController::class, 'update'])->name('quarter-types.update');
        Route::post('/quarter-types/{quarterType}/toggle-status', [QuarterTypeController::class, 'toggleStatus'])->name('quarter-types.toggle-status');
        Route::delete('/quarter-types/{quarterType}', [QuarterTypeController::class, 'destroy'])->name('quarter-types.destroy');

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
        //create
        Route::get('/allottees/process/start', [AllotteeController::class, 'indexStart'])->name('apply.index');
        // edit
        Route::get('/allottees/edit/start/{allottee}', [AllotteeController::class, 'indexEditStart'])->name('edit.apply.index');


        Route::get('/allottees/step/{step}/{applicantId?}', [AllotteeController::class, 'getStep'])->name('apply.step');
        Route::post('/apply/step0/save', [AllotteeController::class, 'saveStep0'])->name('apply.step0.save');
        Route::post('/apply/step1/save', [AllotteeController::class, 'saveStep1'])->name('apply.step1.save');
        Route::post('/apply/step2/save', [AllotteeController::class, 'saveStep2'])->name('apply.step2.save');
        Route::post('/apply/step3/save', [AllotteeController::class, 'saveStep3'])->name('apply.step3.save');
        Route::get('/allottees/{allottee}/section/{section}', [AllotteeController::class, 'section'])->name('allottees.section');
        Route::get('/allottees/{allottee}/process/{stepNo}', [AllotteeController::class, 'processStep'])->name('allottees.process.step');
        Route::post('/allottees/{allottee}/process/{stepNo}/complete', [AllotteeController::class, 'completeProcessStep'])->name('allottees.process.complete');
        Route::post('/allottees/{allottee}/payment-plan', [AllotteeController::class, 'choosePaymentPlan'])->name('allottees.payment-plan');
        Route::post('/allottees/{allottee}/payment-option', [AllotteeController::class, 'updatePaymentOption'])->name('allottees.payment-option');
        Route::get('/allottees/{allottee}/letters/allotment', [AllotteeController::class, 'allotmentLetter'])->name('allottees.letters.allotment');
        Route::get('/allottees/{allottee}/letters/allotment/pdf', [AllotteeController::class, 'allotmentLetterPdf'])->name('allottees.letters.allotment.pdf');
        Route::get('/allottees/{allottee}/letters/possession', [AllotteeController::class, 'possessionLetter'])->name('allottees.letters.possession');
        Route::get('/allottees/{allottee}/letters/possession/pdf', [AllotteeController::class, 'possessionLetterPdf'])->name('allottees.letters.possession.pdf');
        Route::get('/allottees/{allottee}', [AllotteeController::class, 'show'])->name('allottees.show');
        Route::post('/allottees/signed/document/uploads', [AllotteeController::class, 'signedDocumentUploads'])->name('allottees.signed.document.uploads');
        Route::post('/allottees/{allottee}/site-verification', [App\Http\Controllers\Admin\AllotteeSiteVerificationController::class, 'store'])->name('allottees.site-verification.store');
        Route::post('/allottees/{allottee}/extra-construction-calculation', [App\Http\Controllers\Admin\AllotteeExtraConstructionController::class, 'store'])->name('allottees.extra-construction.store');

        // Delete Allottee Components
        Route::get('/allottees/delete/{allottee}', [AllotteeController::class, 'deleteAllotteeComponents'])->name('allottee.delete.components');
        Route::get('/allottees/delete/emi/{allottee}', [AllotteeController::class, 'deleteEMISetup'])->name('allottee.delete.emi.setup');

        // Initial Payment
        Route::post('/allottees/initial-payment/pay', [AllotteePaymentController::class, 'payInitialPayment'])->name('allottees.initial.payment.pay');
        Route::get('/allottees/payment-success/{payment}', [AllotteePaymentController::class, 'paymentSuccess'])->name('allottees.payment.success');

        // One Time Payment
        Route::post('/allottees/one-time-pay', [AllotteePaymentController::class, 'payOnetimePayment'])->name('allottees.one-time-payment.pay');

        // emi payment
        // Allottee EMI Routes
        Route::prefix('allottee')->name('allottee.')->group(function () {

            // EMI Dashboard
            Route::get('{allottee}/emi-dashboard', [AllotteeEmiController::class, 'dashboard'])
                ->name('emi.dashboard');

            // EMI Schedule
            Route::get('{allottee}/emi-schedule', [AllotteeEmiController::class, 'schedule'])
                ->name('emi.schedule');

            // Pay EMI Page
            Route::get('{allottee}/pay-emi', [AllotteeEmiController::class, 'payEmi'])
                ->name('emi.pay');

            // EMI History
            Route::get('{allottee}/emi-history', [AllotteeEmiController::class, 'history'])
                ->name('emi.history');

            // Process EMI Payment
            Route::post('{allottee}/process-emi-payment', [AllotteeEmiController::class, 'processPayment'])
                ->name('emi.process-payment');

            // Pre Payment (Extra Payment)
            Route::post('{allottee}/pre-payment', [AllotteeEmiController::class, 'prePayment'])
                ->name('emi.pre-payment');

            // Close Loan
            Route::post('{allottee}/close-loan', [AllotteeEmiController::class, 'closeLoan'])
                ->name('emi.close');

            // Download EMI Statement
            Route::get('{allottee}/emi-statement', [AllotteeEmiController::class, 'downloadStatement'])
                ->name('emi.statement');

            // Refresh Penalties (AJAX)
            Route::post('refresh-penalties', [AllotteeEmiController::class, 'refreshPenalties'])
                ->name('emi.refresh-penalties');
        });

        // Members Management (Only accessible to super-admin)
        Route::middleware('can:super-admin')->group(function () {
            Route::get('/members', [\App\Http\Controllers\Admin\MemberController::class, 'index'])->name('members.index');
            Route::get('/members/create', [\App\Http\Controllers\Admin\MemberController::class, 'create'])->name('members.create');
            Route::post('/members', [\App\Http\Controllers\Admin\MemberController::class, 'store'])->name('members.store');
            Route::get('/members/{member}/edit', [\App\Http\Controllers\Admin\MemberController::class, 'edit'])->name('members.edit');
            Route::put('/members/{member}', [\App\Http\Controllers\Admin\MemberController::class, 'update'])->name('members.update');
            Route::delete('/members/{member}', [\App\Http\Controllers\Admin\MemberController::class, 'destroy'])->name('members.destroy');
            Route::post('/members/{member}/toggle-status', [\App\Http\Controllers\Admin\MemberController::class, 'toggleStatus'])->name('members.toggle-status');
        });
    });