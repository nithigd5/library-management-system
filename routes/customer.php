<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\OfflineEntryController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    //Admin Dashboard Routes
    Route::name('customer.')->group(function () {

        Route::get('/customer/dashboard' , [CustomerDashboardController::class , 'index'])->name('dashboard');

    });

    Route::name('offline.')->group(function () {

        Route::post('/offline-entry/{user}' , [OfflineEntryController::class , 'setUserEntry'])->name('entry')->can('entry.offline.create');

        Route::patch('/offline-entry/{offlineEntry}' , [OfflineEntryController::class , 'setUserExit'])->name('exit')->can('entry.offline.update');
    });

    Route::name('purchases.')->group(function (){
        Route::get('/purchases', [PurchaseController::class, 'index'])->name('index')->can('books.purchases.viewAny');

        Route::get('/purchases/open', [PurchaseController::class, 'open'])->name('open')->can('books.purchases.viewAny');

        Route::get('/purchases/closed', [PurchaseController::class, 'closed'])->name('closed')->can('books.purchases.viewAny');

        Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('show')->can('books.purchases.viewAny');
    });
});
