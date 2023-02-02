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

Route::get('/', function () {
    return view('welcome');
});

// auth
Route::get('/auth-forgot-password' , function () {
    return view('pages.auth-forgot-password' , ['type_menu' => 'auth']);
});

Route::get('/auth-reset-password' , function () {
    return view('pages.auth-reset-password' , ['type_menu' => 'auth']);
});

Route::get('/mail', function (){
    return new \App\Mail\BookDue();
});

require 'auth.php';
require 'admin.php';
require 'customer.php';
require 'customerBook.php';
