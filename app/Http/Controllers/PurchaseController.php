<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class PurchaseController extends Controller
{
    /**
     * View all Purchases ordered by recent
     * @return Application|Factory|View
     */
    public function index()
    {
        $purchases = Purchase::with('book' , 'user')->orderBy('updated_at')->paginate(10);

        return view('pages.admin.purchases.index' , compact('purchases') , ['type_menu' => 'purchases' , 'status' => 'all']);
    }

    /**
     * View all open Purchases ordered by recent
     * @return Application|Factory|View
     */
    public function open()
    {
        $purchases = Purchase::with('book' , 'user')->latestPurchases()->paginate(10);

        return view('pages.admin.purchases.index' , compact('purchases') , ['type_menu' => 'purchases' , 'status' => 'open']);
    }

    /**
     * View all closed Purchases ordered by recent
     * @return Application|Factory|View
     */
    public function closed()
    {
        $purchases = Purchase::with('book' , 'user')->latestPurchases(Purchase::STATUS_CLOSE)->paginate(10);

        return view('pages.admin.purchases.index' , compact('purchases') , ['type_menu' => 'purchases' , 'status' => 'closed']);
    }

    /**
     * Show a particular book view page
     * @param $book
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $purchase = Purchase::with('user', 'book')->find($id);
        return view('pages.admin.purchases.show' , compact('purchase') , ['type_menu' => 'purchases']);
    }
}
