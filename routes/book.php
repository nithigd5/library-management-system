<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->name('books.')->group(function () {
    Route::get('/books' , [BookController::class , 'index'])->name('index')->can('books.*');
    Route::post('/books' , [BookController::class , 'store'])->name('store')->can('books.create');
    Route::get('/books/create' , [BookController::class , 'create'])->name('create')->can('books.create');
    Route::get('/books/edit/{book}' , [BookController::class , 'edit'])->name('edit')->can('books.update');
    Route::put('/books/{book}' , [BookController::class , 'update'])->name('update')->can('books.update');
    Route::delete('/books/{book}' , [BookController::class , 'destroy'])->name('destroy')->can('books.delete');
});
