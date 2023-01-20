<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OfflineEntryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    //Admin Dashboard Routes
    Route::name('admin.')->group(function () {

        Route::get('/admin' , [AdminDashboardController::class , 'index'])->name('dashboard');
    });

    Route::name('customers.')->group(function () {

        Route::get('/customers' , [CustomerController::class , 'index'])->name('index')->can('users.customer.*');

        Route::get('/customers/create' , [CustomerController::class , 'create'])->name('create')->can('users.customer.create');

        Route::post('/customers' , [CustomerController::class , 'store'])->name('store')->can('users.customer.create');

        Route::put('/customers/{customer:users}' , [CustomerController::class , 'update'])->name('update')->can('users.customer.updateAny');

        Route::get('/customers/edit/{customer:users}' , [CustomerController::class , 'edit'])->name('edit')->can('users.customer.updateAny');

        Route::delete('/customers/{customer:users}' , [CustomerController::class , 'destroy'])->name('destroy')->can('users.customer.deleteAny');
    });

    Route::name('offline.')->group(function () {

        Route::post('/offline-entry/{user}' , [OfflineEntryController::class , 'setUserEntry'])->name('entry')->can('entry.offline.create');

        Route::patch('/offline-entry/{offlineEntry}' , [OfflineEntryController::class , 'setUserExit'])->name('exit')->can('entry.offline.update');
    });
});
