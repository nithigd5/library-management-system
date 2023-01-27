<?php

use App\Http\Controllers\BookRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->name('request.')->group(function () {
    Route::get('/book' , [BookRequestController::class , 'index'])->name('index')->can('books.*');
    Route::post('/book' , [BookRequestController::class , 'store'])->name('store')->can('books.create');
    Route::get('/book/request' , [BookRequestController::class , 'create'])->name('create')->can('books.create');
    Route::delete('/book/request/{book}' , [BookRequestController::class , 'destroy'])->name('destroy')->can('books.delete');
});
