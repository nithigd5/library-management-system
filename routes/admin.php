<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OfflineEntryController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/' , [AdminDashboardController::class , 'index'])->name('dashboard');

    Route::name('customers.')->group(function () {

        Route::get('customers' , [CustomerController::class , 'index'])->name('index')->can('users.customer.*');

        Route::get('customers/create' , [CustomerController::class , 'create'])->name('create')->can('users.customer.create');

        Route::get('customers/{customers:users}' , [CustomerController::class , 'show'])->name('show')->can('users.customer.viewAny');

        Route::post('customers' , [CustomerController::class , 'store'])->name('store')->can('users.customer.create');

        Route::put('customers/{customer:users}' , [CustomerController::class , 'update'])->name('update')->can('users.customer.updateAny');

        Route::get('customers/edit/{customer:users}' , [CustomerController::class , 'edit'])->name('edit')->can('users.customer.updateAny');

        Route::delete('customers/{customer:users}' , [CustomerController::class , 'destroy'])->name('destroy')->can('users.customer.deleteAny');

        Route::get('invite/customers/' , [CustomerController::class , 'invite'])->name('invite')->can('users.invite.create');

    });

    Route::name('offline.')->group(function () {

        Route::post('/offline-entry/{user}' , [OfflineEntryController::class , 'setUserEntry'])->name('entry')->can('entry.offline.create');

        Route::patch('/offline-entry/{offlineEntry}' , [OfflineEntryController::class , 'setUserExit'])->name('exit')->can('entry.offline.update');
    });

    Route::name('purchases.')->prefix('purchases')->group(function () {
        Route::get('' , [PurchaseController::class , 'index'])->name('index')->can('books.purchases.viewAny');

        Route::get('{purchase}' , [PurchaseController::class , 'show'])->name('show')->can('books.purchases.viewAny');

        Route::get('create' , [PurchaseController::class , 'create'])->name('create')->can('books.purchases.createAny');

        Route::post('store' , [PurchaseController::class , 'store'])->name('store')->can('books.purchases.createAny');

        Route::put('{purchase}/update' , [PurchaseController::class , 'update'])->name('update')->can('books.purchases.updateAny');

        Route::put('{purchase}/return-book' , [PurchaseController::class , 'returnBook'])->name('return-book')->can('books.purchases.updateAny');
    });

    Route::middleware('auth')->name('books.')->group(function () {
        Route::get('/books' , [BookController::class , 'index'])->name('index')->can('books.*');

        Route::post('/books' , [BookController::class , 'store'])->name('store')->can('books.create');

        Route::get('/books/create' , [BookController::class , 'create'])->name('create')->can('books.create');

        Route::get('/books/search' , [BookController::class , 'search'])->name('search');

        Route::get('/books/{id}' , [BookController::class , 'show'])->name('show');

        Route::get('/books/edit/{book}' , [BookController::class , 'edit'])->name('edit')->can('books.update');

        Route::put('/books/{book}' , [BookController::class , 'update'])->name('update')->can('books.update');

        Route::delete('/books/{book}' , [BookController::class , 'destroy'])->name('destroy')->can('books.delete');
    });

    Route::name('offline.')->group(function () {

        Route::post('/offline-entry/{user}' , [OfflineEntryController::class , 'setUserEntry'])->name('entry')->can('entry.offline.create');

        Route::patch('/offline-entry/{offlineEntry}' , [OfflineEntryController::class , 'setUserExit'])->name('exit')->can('entry.offline.update');
    });

    Route::name('purchases.')->group(function () {
        Route::get('/purchases' , [PurchaseController::class , 'index'])->name('index')->can('books.purchases.viewAny');

        Route::get('/purchases/open' , [PurchaseController::class , 'open'])->name('open')->can('books.purchases.viewAny');

        Route::get('/purchases/closed' , [PurchaseController::class , 'closed'])->name('closed')->can('books.purchases.viewAny');

        Route::get('/purchases/{purchase}' , [PurchaseController::class , 'show'])->name('show')->can('books.purchases.viewAny');
    });

});

Route::middleware('signed')->name('admin.')->group(function () {
    Route::get('register' , [CustomerController::class , 'signedCreate'])->name('customers.invitations.create-customer');

    Route::post('register' , [CustomerController::class , 'signedStore'])->name('customers.invitations.store-customer');
});

