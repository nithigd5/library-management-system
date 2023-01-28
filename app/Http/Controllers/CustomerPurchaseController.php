<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Book;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\NullableType;

class CustomerPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $book = Book::find($id);
        if ($book->mode == "online") {
            $purchased = Purchase::where('user_id', auth()->user()->id)
                ->where('book_id', $book->id)
                ->where('book_return_due', '>=', Carbon::now())
                ->orWhere('book_return_due', null)
                ->exists();

            if ($purchased) {
                return back()->with('status', 'Book Already Purchased');
            } else {
                //user has not purchased the book
                return view('pages.customer.customerPurchase.customerPurchase', ['type_menu' => '', 'book' => $book]);
            }
        }
        else{
            return back()->with('status', 'Book is available only in OFFLINE.');        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $book = Book::find($id);
        $validatedData = Validator::make($request->all(), (new PaymentRequest($book->price))->rules());
        if ($validatedData->fails()) {
            return redirect()->back()->withErrors($validatedData->errors());
        }

        if ($request->rentOrBuy == true) {
            $pendingAmount = ($book->price - ($request->paidPrice));
            $payDue = null;
            $rentOrBuy = 0;
            $book_return_due = null;
        } else {
            $pendingAmount = 0;
            $rentOrBuy = 1;
            $payDue = Carbon::now()->addDays(10);
            $book_return_due = Carbon::now()->addDays(30);
        }
        Purchase::create([
            'user_id' => auth()->user()->id,
            'book_id' => $book->id,
            'price' => $book->price,
            'for_rent' => $rentOrBuy,
            'pending_amount' => $pendingAmount,
            'payment_due' => Carbon::now()->addDays(15),
            'book_return_due' => $book_return_due,
            'book_issued_at' => Carbon::now(),
            'mode' => $book->mode,
        ]);
        return view('pages.customer.customerPurchase.paymentSuccess', ['type_menu' => '', 'book' => $book]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
