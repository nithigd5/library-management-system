<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function PHPUnit\Framework\matches;

class PurchaseController extends Controller
{
    /**
     * View all Purchases ordered by recent
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {


        $purchases = $this->getPurchases($request->due, $request->type, $request->date_range);

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

    public function getPurchases($due, $type, $date_range)
    {
        $query = Purchase::with('book' , 'user')->orderBy('updated_at');

        $query = match($type){
            'rent' => $query->where('for_rent', true),
            'owned' => $query->where('for_rent', false),
            default => $query
        };

        $query = match ($due){
            'all' => $query->bookOverDue()->paymentOverDue(),
            'book_due' => $query->bookOverDue(),
            'payment_due' => $query->paymentOverDue(),
            default => $query
        };

        $date_range = explode(' - ', $date_range);

        //Handle Invalid Date Format Error
        try{
            $start = Carbon::createFromFormat('m/d/Y', $date_range[0]);
            $end = Carbon::createFromFormat('m/d/Y', $date_range[1]);

            if($date_range){
                $query->whereBetween('created_at', [$start, $end]);
            }
        }catch (\Exception $e){

        }

       return $query->paginate(10);
    }
}
