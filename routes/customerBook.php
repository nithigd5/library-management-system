<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CustomerBookController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->name('book.')->group(function () {
    Route::get('customer/book' , [CustomerBookController::class , 'index'])->name('index');
    Route::get('customer/book/search' , [CustomerBookController::class , 'search'])->name('search');
    Route::get('customer/book/{id}' , [CustomerBookController::class , 'show'])->name('show');
});

