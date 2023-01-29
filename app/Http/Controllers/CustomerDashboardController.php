<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Purchase;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{

    /**
     * Admin Dashboard View
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $rentedBooksCount = Purchase::rentedLastMonth()->where('user_id', Auth::id())->count();
        $returnedBooksCount = Purchase::returnedLastMonth()->where('user_id', Auth::id())->count();
        $overDueBooksCount = Purchase::bookOverDue()->where('user_id', Auth::id())->count();

        $unPaidSum = Purchase::unpaidPayment()->where('user_id', Auth::id())->sum('pending_amount');

        $ownedLastMonth = Purchase::ownedLastMonth()->where('user_id', Auth::id())->count();

        $latestPurchases = Purchase::with('book' , 'user')->latestPurchases()->where('user_id', Auth::id())->limit(5)->get();

        $lastMonthRevenueSum = Purchase::revenueSumBetween(now()->subMonth() , now())->where('user_id', Auth::id())->first()->revenue;

        $lastMonthRentedRevenueSum = Purchase::revenueSumBetween(now()->subMonth() , now() , true)->where('user_id', Auth::id())->first()->revenue;

        $topBooks = Book::orderByMostPurchased()->limit(5)->get();

        return view('pages.customer.customerDashboard' ,
            compact('rentedBooksCount' ,
                'ownedLastMonth' ,
                'latestPurchases' ,
                'topBooks' ,
                'returnedBooksCount' ,
                'overDueBooksCount' ,
                'unPaidSum' ,
                'lastMonthRevenueSum' ,
                'lastMonthRentedRevenueSum') ,
            ['type_menu' => 'dashboard']);
    }
}
