<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Purchase;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class CustomerDashboardController extends Controller
{

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $rentedBooksCount = Purchase::rentedLastMonth()->count();
        $returnedBooksCount = Purchase::returnedLastMonth()->count();
        $overDueBooksCount = Purchase::bookOverDue()->count();

        $overDuePaymentsSum = Purchase::paymentOverDue()->sum('pending_amount');

        $ownedLastMonth = Purchase::ownedLastMonth()->count();

        $latestPurchases = Purchase::with('book' , 'user')->latestPurchases()->limit(5)->get();

        $topBooks = Book::orderByMostPurchased()->limit(5)->get();

        return view('pages.customer.customerDashboard' ,
            compact('rentedBooksCount' , 'ownedLastMonth' , 'latestPurchases' ,
                'topBooks' , 'returnedBooksCount' , 'overDueBooksCount' , 'overDuePaymentsSum') ,
            ['type_menu' => 'dashboard']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
