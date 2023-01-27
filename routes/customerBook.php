<?php

use App\Http\Controllers\CustomerBookController;
use App\Http\Controllers\CustomerPurchaseController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::name('book.')->group(function () {
        Route::get('book', [CustomerBookController::class, 'index'])->name('index');
        Route::get('book/search', [CustomerBookController::class, 'search'])->name('search');
        Route::get('book/{id}', [CustomerBookController::class, 'show'])->name('show');
    });

    Route::name('purchase.')->group(function () {
        Route::get('purchase/{id}', [CustomerPurchaseController::class, 'create'])->name('create');
        Route::post('purchase/{id}', [CustomerPurchaseController::class, 'store'])->name('store');
    });

});

