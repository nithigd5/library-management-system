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
});
