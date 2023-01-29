<?php

use App\Http\Controllers\AdminBookRequestController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OfflineEntryController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/' , [AdminDashboardController::class , 'index'])->name('dashboard')->middleware('role:admin');

    Route::name('customers.')->prefix('customers')->group(function () {

        Route::get('' , [CustomerController::class , 'index'])->name('index')->can('users.customer.*');

        Route::get('invite' , [CustomerController::class , 'invite'])->name('invite')->can('users.invite.create');

        Route::get('create' , [CustomerController::class , 'create'])->name('create')->can('users.customer.create');

        Route::get('{customer:users}/edit' , [CustomerController::class , 'edit'])->name('edit')->can('users.customer.updateAny');

        Route::get('{customers:users}' , [CustomerController::class , 'show'])->name('show')->can('users.customer.viewAny');

        Route::post('' , [CustomerController::class , 'store'])->name('store')->can('users.customer.create');

        Route::put('{customer:users}' , [CustomerController::class , 'update'])->name('update')->can('users.customer.updateAny');

        Route::delete('{customer:users}' , [CustomerController::class , 'destroy'])->name('destroy')->can('users.customer.deleteAny');

    });

    Route::name('offline.')->group(function () {

        Route::post('/offline-entry/{user}' , [OfflineEntryController::class , 'setUserEntry'])->name('entry')->can('entry.offline.create');

        Route::patch('/offline-entry/{offlineEntry}' , [OfflineEntryController::class , 'setUserExit'])->name('exit')->can('entry.offline.update');
    });

    Route::name('purchases.')->prefix('purchases')->group(function () {
        Route::get('' , [PurchaseController::class , 'index'])->name('index')->can('books.purchases.viewAny');

        Route::get('create' , [PurchaseController::class , 'create'])->name('create')->can('books.purchases.createAny');

        Route::get('{purchase}' , [PurchaseController::class , 'show'])->name('show')->can('books.purchases.viewAny');

        Route::post('store' , [PurchaseController::class , 'store'])->name('store')->can('books.purchases.createAny');

        Route::put('{purchase}/update' , [PurchaseController::class , 'update'])->name('update')->can('books.purchases.updateAny');

        Route::put('{purchase}/return-book' , [PurchaseController::class , 'returnBook'])->name('return-book')->can('books.purchases.updateAny');
    });

    Route::name('books.')->prefix('books')->group(function () {
        Route::get('/' , [BookController::class , 'index'])->name('index')->can('books.*');

        Route::post('/' , [BookController::class , 'store'])->name('store')->can('books.create');

        Route::get('/create' , [BookController::class , 'create'])->name('create')->can('books.create');

        Route::get('/search' , [BookController::class , 'search'])->name('search')->can('books.*');

        Route::get('/{id}' , [BookController::class , 'show'])->name('show')->can('books.*');

        Route::get('/edit/{book}' , [BookController::class , 'edit'])->name('edit')->can('books.update');

        Route::put('/{book}' , [BookController::class , 'update'])->name('update')->can('books.update');

        Route::delete('/{book}' , [BookController::class , 'destroy'])->name('destroy')->can('books.delete');
    });

    Route::name('book-requests.')->prefix('book-requests')->group(function (){
        Route::get('/' , [AdminBookRequestController::class , 'index'])->name('index')->can('books.request.viewAny');

        Route::get('/{BookRequest}' , [AdminBookRequestController::class , 'show'])->name('show')->can('books.request.viewAny');

        Route::put('/{BookRequest}' , [AdminBookRequestController::class , 'update'])->name('update')->can('books.request.update');
    });

    Route::name('offline.')->group(function () {

        Route::post('/offline-entry/{user}' , [OfflineEntryController::class , 'setUserEntry'])->name('entry')->can('entry.offline.create');

        Route::patch('/offline-entry/{offlineEntry}' , [OfflineEntryController::class , 'setUserExit'])->name('exit')->can('entry.offline.update');
    });


});

Route::middleware('signed')->name('admin.')->group(function () {
    Route::get('register' , [CustomerController::class , 'signedCreate'])->name('customers.invitations.create-customer');

    Route::post('register' , [CustomerController::class , 'signedStore'])->name('customers.invitations.store-customer');
});

