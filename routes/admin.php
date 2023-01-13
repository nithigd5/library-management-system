<?php

use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->group(function (){
    Route::get('/admin' , [AdminDashboardController::class, 'index']);
});
