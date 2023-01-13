<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;


Route::name('books.')->group(function (){
    Route::post('/books', [BookController::class, 'store'])->name('store')->can('books.create');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('update')->can('books.update');
});
