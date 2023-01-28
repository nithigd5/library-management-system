<?php

use App\Http\Controllers\BookRequestController;
use App\Http\Controllers\CustomerBookController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\CustomerPurchaseController;
use App\Http\Controllers\pdfViewAndDownloadController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::name('book.')->group(function () {
        Route::get('book', [CustomerBookController::class, 'index'])->name('index');
        Route::get('book/search', [CustomerBookController::class, 'search'])->name('search');
        Route::get('book/{id}', [CustomerBookController::class, 'show'])->name('show');
        Route::get('book/download/{id}', [pdfViewAndDownloadController::class, 'downloadPdf'])->name('download');
        Route::get('book/view/{id}', [pdfViewAndDownloadController::class, 'viewPDF'])->name('viewpdf');
    });

    Route::name('purchase.')->group(function () {
        Route::post('purchase/{id}', [CustomerPurchaseController::class, 'store'])->name('store');
        Route::get('purchase/{id}/buy', [CustomerPurchaseController::class, 'create'])->name('create');
        Route::get('/purchase', [CustomerPurchaseController::class, 'index'])->name('index');
        Route::get('/purchase/{id}', [CustomerPurchaseController::class, 'show'])->name('show');
    });

    Route::get('myprofile', [CustomerProfileController::class, 'profile'])->name('myprofile');

    Route::name('bookrequest.')->group(function () {
        Route::get('/bookrequest', [BookRequestController::class, 'index'])->name('index');
        Route::get('/bookrequest/create', [BookRequestController::class, 'create'])->name('create');
        Route::post('/bookrequest/store', [BookRequestController::class, 'store'])->name('store');
        Route::get('/bookrequest/{id}', [BookRequestController::class, 'show'])->name('show');
//        Route::get('/book/search', [BookRequestController::class, 'search'])->name('search');
//
//        Route::get('/book/edit/{book}', [BookRequestController::class, 'edit'])->name('edit');
//        Route::put('/book/{book}', [BookRequestController::class, 'update'])->name('update');
//        Route::delete('/book/{book}', [BookRequestController::class, 'destroy'])->name('destroy');
    });
});

