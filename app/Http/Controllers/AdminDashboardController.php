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
        $ownedLastMonth = Purchase::ownedLastMonth()->count();

        $latestPurchases = Purchase::with('book' , 'user')->latestPurchases()->limit(5)->get();
        $topBooks = Purchase::orderByMostPurchased()->limit(5)->get();

        return view('pages.admin.dashboard' ,
            compact('rentedBooksCount' , 'ownedLastMonth' , 'latestPurchases' , 'topBooks') ,
            ['type_menu' => 'dashboard']);
    }
}
