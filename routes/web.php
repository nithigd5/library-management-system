<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::redirect('/' , '/login');

// auth
Route::get('/auth-forgot-password' , function () {
    return view('pages.auth-forgot-password' , ['type_menu' => 'auth']);
});

Route::get('/auth-reset-password' , function () {
    return view('pages.auth-reset-password' , ['type_menu' => 'auth']);
});

require 'auth.php';
require 'book.php';
require 'admin.php';
require 'bookRequest.php';
require 'customer.php';
require 'customerBook.php';
