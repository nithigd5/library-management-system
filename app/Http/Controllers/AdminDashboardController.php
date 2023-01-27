<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Purchase;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AdminDashboardController extends Controller
{

    /**
     * Admin Dashboard View
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $rentedBooksCount = Purchase::rentedLastMonth()->count();
        $returnedBooksCount = Purchase::returnedLastMonth()->count();
        $overDueBooksCount = Purchase::bookOverDue()->count();

        $overDuePaymentsSum = Purchase::paymentOverDue()->sum('pending_amount');

        $ownedLastMonth = Purchase::ownedLastMonth()->count();

        $latestPurchases = Purchase::with('book' , 'user')->latestPurchases()->limit(5)->get();

        $lastMonthRevenueSum = Purchase::revenueSumBetween(now()->subMonth(), now())->first()->revenue;

        $lastMonthRentedRevenueSum = Purchase::revenueSumBetween(now()->subMonth(), now(), true)->first()->revenue;

        $topBooks = Book::orderByMostPurchased()->limit(5)->get();

        return view('pages.admin.dashboard' ,
            compact('rentedBooksCount' , 'ownedLastMonth' , 'latestPurchases' ,
                    'topBooks' , 'returnedBooksCount' , 'overDueBooksCount' , 'overDuePaymentsSum', 'lastMonthRevenueSum', 'lastMonthRentedRevenueSum') ,
            ['type_menu' => 'dashboard']);
    }
}
