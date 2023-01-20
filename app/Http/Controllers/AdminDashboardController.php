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
        $rentedBooks = Purchase::rentedLastMonth()->count();
        $latestPurchases = Purchase::with('book' , 'user')->latestPurchases()->limit(5)->get();
        $topBooks = Book::limit(5)->get();

        return view('pages.admin.dashboard' , ['type_menu' => 'dashboard' ,
            'rentedBooks' => $rentedBooks ,
            'latestPurchases' => $latestPurchases ,
            'topBooks' => $topBooks
        ]);
    }
}
