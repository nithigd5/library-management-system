<?php

use App\Http\Controllers\CustomerDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookRequestController;
use App\Http\Controllers\CustomerBookController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\CustomerPurchaseController;
use App\Http\Controllers\pdfViewAndDownloadController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OfflineEntryController;
use App\Http\Controllers\PurchaseController;

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
    return redirect('login');
});

// auth
Route::get('/auth-forgot-password', function () {
    return view('pages.auth-forgot-password', ['type_menu' => 'auth']);
});

Route::get('/auth-reset-password', function () {
    return view('pages.auth-reset-password', ['type_menu' => 'auth']);
});

Route::get('/mail', function () {
    return new \App\Mail\BookDue();
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    Route::name('book.')->group(function () {
        Route::get('book', [CustomerBookController::class, 'index'])->name('index');
        Route::get('book/search', [CustomerBookController::class, 'search'])->name('search')->can('books.search');
        Route::get('book/{id}', [CustomerBookController::class, 'show'])->name('show')->can('books.view');
        Route::get('book/download/{id}', [pdfViewAndDownloadController::class, 'downloadPdf'])->name('download')
        ->can('books.link.download');
        Route::get('book/view/{id}', [pdfViewAndDownloadController::class, 'viewPDF'])->name('viewpdf')
        ->can('books.purchase.viewAny')
        ->can('books.purchase.view');
    });

    Route::name('purchase.')->group(function () {
        Route::post('purchase/{id}', [CustomerPurchaseController::class, 'store'])->name('store')
            ->can('books.purchase.create')
            ->can( 'books.purchase.online')
            ->can('books.purchase.pay.later')
            ->can('books.purchase.createAny')
            ->can('books.purchase.offline')
            ->can('books.purchase.rent')
            ->can('books.purchase.buy');
        Route::get('purchase/{id}/buy', [CustomerPurchaseController::class, 'create'])->name('create')
            ->can('books.purchase.create')
            ->can( 'books.purchase.online')
            ->can('books.purchase.pay.later')
            ->can('books.purchase.createAny')
            ->can('books.purchase.buy')
            ->can('books.purchase.offline');
        Route::get('purchase/{id}/pending-payment', [CustomerPurchaseController::class, 'pendingpayment'])->name('pending-payment');
        Route::post('purchase/{id}/pending-payment', [CustomerPurchaseController::class, 'updatePending'])->name('pending-payment');
        Route::get('/purchase', [CustomerPurchaseController::class, 'index'])->name('index');
        Route::get('/purchase/{id}', [CustomerPurchaseController::class, 'show'])->name('show');
    });

    Route::get('myprofile', [CustomerProfileController::class, 'profile'])->name('myprofile');

    Route::name('bookrequest.')->group(function () {
        Route::get('/bookrequest', [BookRequestController::class, 'index'])->name('index');
        Route::get('/bookrequest/create', [BookRequestController::class, 'create'])->name('create')->can('books.request.create');
        Route::post('/bookrequest/store', [BookRequestController::class, 'store'])->name('store')->can('books.request.create');
        Route::get('/bookrequest/{id}', [BookRequestController::class, 'show'])->name('show')
            ->can('books.request.view')
            ->can('books.request.viewAll');
    });
});

require 'auth.php';
require 'admin.php';
