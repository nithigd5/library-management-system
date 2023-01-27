<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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
     * View all closed Purchases ordered by recent
     * @return Application|Factory|View
     */
    public function overdue()
    {
        $purchases = Purchase::with('book' , 'user')->bookOverDue()->paginate(10);

        return view('pages.admin.purchases.index' , compact('purchases') , ['type_menu' => 'purchases' , 'status' => 'closed']);
    }

    /**
     * Show a particular book view page
     * @param $book
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $purchase = Purchase::with('user' , 'book')->findOrFail($id);
        return view('pages.admin.purchases.show' , compact('purchase') , ['type_menu' => 'purchases']);
    }

    /**
     * return a book if not returned
     * @param Purchase $purchase
     * @return RedirectResponse
     */
    public function returnBook(Purchase $purchase)
    {
        if ($purchase->toReturn()) {
            try {
                $purchase->book_returned_at = now();
                $purchase->saveOrFail();
            }catch (\Throwable $e){
                 return back()->with('message' , 'Book cannot be returned. Try again later')->with('status' , 'danger');
            }
            return back()->with('message' , 'Book has been returned successfully')->with('status' , 'success');
        } else {
            return back()->with('message' , 'Book is already returned')->with('status' , 'danger');
        }
    }
}
