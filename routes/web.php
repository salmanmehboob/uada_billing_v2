<?php

use App\Http\Controllers\AlloteeController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\CombineBillController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Authentication Routes
Auth::routes();

// Redirect to log in if installation is completed


Route::get('/', static function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});


Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('admin.dashboard');

    // Warehouses
    Route::prefix('admin/users')->name('admin.users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('.index');
        Route::post('/store', [UserController::class, 'store'])->name('.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('.edit');
        Route::put('/{user}/update', [UserController::class, 'update'])->name('.update');
        Route::delete('/{user}/delete', [UserController::class, 'destroy'])->name('.destroy');
    });

    Route::prefix('admin/allotees')->name('admin.allotees')->group(function () {
        Route::get('/', [AlloteeController::class, 'index'])->name('.index');
        Route::get('/data', [AlloteeController::class, 'data'])->name('.data');
        Route::post('/store', [AlloteeController::class, 'store'])->name('.store');
        Route::get('/{allotee}/edit', [AlloteeController::class, 'edit'])->name('.edit');
        Route::put('/{allotee}/update', [AlloteeController::class, 'update'])->name('.update');
        Route::delete('/{allotee}/delete', [AlloteeController::class, 'destroy'])->name('.destroy');
    });

    Route::prefix('admin/bills')->name('admin.bills')->group(function () {
        Route::get('/', [BillController::class, 'index'])->name('.index');
        Route::get('/create', [BillController::class, 'create'])->name('.create');
        Route::post('/store', [BillController::class, 'store'])->name('.store');
    
        // Show single bill
        Route::get('/{bill}', [BillController::class, 'show'])->name('.show');
        Route::post('/calculate', [BillController::class, 'calculate'])->name('.calculate');
        Route::post('/check-period', [BillController::class, 'checkPeriod'])->name('.check-period');
        Route::post('/{bill}/pay', [BillController::class, 'pay'])->name('.pay');
        Route::get('/{bill}/transactions', [BillController::class, 'transactions'])->name('.transactions');
    
        Route::get('/{bill}/edit', [BillController::class, 'edit'])->name('.edit');
        Route::put('/{bill}/update', [BillController::class, 'update'])->name('.update');
        Route::delete('/{bill}/delete', [BillController::class, 'destroy'])->name('.destroy');
        Route::delete('/bulk-delete', [BillController::class, 'bulkDestroy'])->name('.bulk-destroy');
    });

    Route::prefix('admin/bills-combine')->name('admin.bills.combine')->group(function () {
        CombineBillController::routes();
    });


    Route::prefix('admin/dropdown')->name('admin.dropdown')->group(function () {

        Route::prefix('sectors')->name('.sectors')->group(function () {
            Route::get('/', [SectorController::class, 'index'])->name('.index');
            Route::post('/store', [SectorController::class, 'store'])->name('.store');
            Route::get('/{sector}/edit', [SectorController::class, 'edit'])->name('.edit');
            Route::put('/{sector}/update', [SectorController::class, 'update'])->name('.update');
            Route::delete('/{sector}/delete', [SectorController::class, 'destroy'])->name('.destroy');
        });

        Route::prefix('charges')->name('.charges')->group(function () {
            Route::get('/', [ChargeController::class, 'index'])->name('.index');
            Route::post('/store', [ChargeController::class, 'store'])->name('.store');
            Route::get('/{charge}/edit', [ChargeController::class, 'edit'])->name('.edit');
            Route::put('/{charge}/update', [ChargeController::class, 'update'])->name('.update');
            Route::delete('/{charge}/delete', [ChargeController::class, 'destroy'])->name('.destroy');
        });


        Route::prefix('sizes')->name('.sizes')->group(function () {
            Route::get('/', [SizeController::class, 'index'])->name('.index');
            Route::post('/store', [SizeController::class, 'store'])->name('.store');
            Route::get('/{size}/edit', [SizeController::class, 'edit'])->name('.edit');
            Route::put('/{size}/update', [SizeController::class, 'update'])->name('.update');
            Route::delete('/{size}/delete', [SizeController::class, 'destroy'])->name('.destroy');
        });

        Route::prefix('types')->name('.types')->group(function () {
            Route::get('/', [TypeController::class, 'index'])->name('.index');
            Route::post('/store', [TypeController::class, 'store'])->name('.store');
            Route::get('/{type}/edit', [TypeController::class, 'edit'])->name('.edit');
            Route::put('/{type}/update', [TypeController::class, 'update'])->name('.update');
            Route::delete('/{type}/delete', [TypeController::class, 'destroy'])->name('.destroy');
        });

        Route::prefix('banks')->name('.banks')->group(function () {
            Route::get('/', [BankController::class, 'index'])->name('.index');
            Route::post('/store', [BankController::class, 'store'])->name('.store');
            Route::get('/{bank}/edit', [BankController::class, 'edit'])->name('.edit');
            Route::put('/{bank}/update', [BankController::class, 'update'])->name('.update');
            Route::delete('/{bank}/delete', [BankController::class, 'destroy'])->name('.destroy');
        });
    });

    Route::prefix('admin/settings')->name('admin.settings')->group(function () {
        Route::get('/', [SettingController::class, 'settings'])->name('');
        Route::put('/update', [SettingController::class, 'updateSettings'])->name('.update');
    });
});
