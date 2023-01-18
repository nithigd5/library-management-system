<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function (){
    //Admin Dashboard Routes
    Route::name('admin.')->group(function (){
        Route::get('/admin' , [AdminDashboardController::class, 'index'])->name('dashboard');
    });

    Route::name('customers.')->group(function (){

        Route::get('/customers' , [CustomerController::class, 'index'])->name('index')->can('users.customer.*');
        Route::get('/customers/create' , [CustomerController::class, 'create'])->name('create')->can('users.customer.create');
        Route::get('/customers/edit/{customer}' , [CustomerController::class, 'edit'])->name('edit')->can('users.customer.edit');
        Route::delete('/customers/{customer}' , [CustomerController::class, 'destroy'])->name('destroy')->can('users.customer.edit');

    });
});


