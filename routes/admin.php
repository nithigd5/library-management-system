<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OfflineEntryController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    //Admin Dashboard Routes
    Route::name('admin.')->group(function () {

        Route::get('/admin' , [AdminDashboardController::class , 'index'])->name('dashboard');
    });

    Route::name('customers.')->prefix('customers')->group(function () {

        Route::get('/' , [CustomerController::class , 'index'])->name('index')->can('users.customer.*');

        Route::get('/create' , [CustomerController::class , 'create'])->name('create')->can('users.customer.create');

        Route::post('' , [CustomerController::class , 'store'])->name('store')->can('users.customer.create');

        Route::put('/{customer:users}' , [CustomerController::class , 'update'])->name('update')->can('users.customer.updateAny');

        Route::get('/edit/{customer:users}' , [CustomerController::class , 'edit'])->name('edit')->can('users.customer.updateAny');

        Route::delete('/{customer:users}' , [CustomerController::class , 'destroy'])->name('destroy')->can('users.customer.deleteAny');

        Route::get('/invite', [CustomerController::class, 'invite'])->name('invite')->can('users.invite.create');

    });

    Route::name('offline.')->group(function () {

        Route::post('/offline-entry/{user}' , [OfflineEntryController::class , 'setUserEntry'])->name('entry')->can('entry.offline.create');

        Route::patch('/offline-entry/{offlineEntry}' , [OfflineEntryController::class , 'setUserExit'])->name('exit')->can('entry.offline.update');
    });

    Route::name('purchases.')->prefix('purchases')->group(function (){
        Route::get('/', [PurchaseController::class, 'index'])->name('index')->can('books.purchases.viewAny');

        Route::get('/open', [PurchaseController::class, 'open'])->name('open')->can('books.purchases.viewAny');

        Route::get('/closed', [PurchaseController::class, 'closed'])->name('closed')->can('books.purchases.viewAny');

        Route::get('/overdue', [PurchaseController::class, 'overdue'])->name('overdue')->can('books.purchases.viewAny');

        Route::get('/{purchase}', [PurchaseController::class, 'show'])->name('show')->can('books.purchases.viewAny');

        Route::put('/{purchase}/return-book', [PurchaseController::class, 'returnBook'])->name('return-book')->can('books.purchases.updateAny');
    });


});

Route::middleware('signed')->group(function () {
    Route::get('register' , [CustomerController::class , 'signedCreate'])->name('customers.invitations.create-customer');
    Route::post('register' , [CustomerController::class , 'signedStore'])->name('customers.invitations.store-customer');
});
