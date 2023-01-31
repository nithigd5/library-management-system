<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\OfflineEntryController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard' , [CustomerDashboardController::class , 'index'])->name('dashboard');

    Route::name('offline.')->group(function () {

        Route::post('/offline-entry/{user}' , [OfflineEntryController::class , 'setUserEntry'])->name('entry')->can('entry.offline.create');

        Route::patch('/offline-entry/{offlineEntry}' , [OfflineEntryController::class , 'setUserExit'])->name('exit')->can('entry.offline.update');
    });
});
