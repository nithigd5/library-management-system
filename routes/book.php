<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->name('books.')->group(function (){
    Route::get('/books', [BookController::class, 'index'])->name('index')->can('books.admin-view');
    Route::post('/books', [BookController::class, 'store'])->name('store')->can('books.create');
    Route::post('/books/create', [BookController::class, 'create'])->name('create')->can('books.create');
    Route::post('/books/edit', [BookController::class, 'edit'])->name('edit')->can('books.update');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('update')->can('books.update');
});
