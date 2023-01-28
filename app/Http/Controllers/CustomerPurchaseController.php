<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Book;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\NullableType;

class CustomerPurchaseController extends Controller
{
    /**
     * View all Purchases ordered by recent
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $purchases = $this->getPurchases($request->due , $request->type , $request->date_range ,
            $request->status , $request->sort , $request->returned , $request->payment)->where('user_id',auth()->user()->id)->paginate(10);

        return view('pages.customer.purchases.index' , compact('purchases') , ['type_menu' => 'purchase' , 'status' => 'all']);
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
            $purchased=$this->checkIfPurchased($book);

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
     * View all closed Purchases ordered by recent
     * @return Application|Factory|View
     */
    public function overdue()
    {
        $purchases = Purchase::with('book' , 'user')->bookOverDue()->paginate(10);

        return view('pages.customer.purchase.index' , compact('purchases') , ['type_menu' => 'purchase' , 'status' => 'closed']);
    }

    /**
     * Show a particular book view page
     * @param $book
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $purchase = Purchase::with('user' , 'book')->findOrFail($id);
        return view('pages.admin.purchase.show' , compact('purchase') , ['type_menu' => 'purchase']);
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
            } catch (\Throwable $e) {
                return back()->with('message' , 'Book cannot be returned. Try again later')->with('status' , 'danger');
            }
            return back()->with('message' , 'Book has been returned successfully')->with('status' , 'success');
        } else {
            return back()->with('message' , 'Book is already returned')->with('status' , 'danger');
        }
    }

    public function getPurchases($due = null , $type = null , $date_range = null , $status = null , $sort = null , $isReturned = null , $isPaid = null)
    {
        $query = Purchase::with('book' , 'user');

        //Query By Type
        $query = match ($type) {
            'rented' => $query->where('for_rent' , true) ,
            'owned' => $query->where('for_rent' , false) ,
            default => $query
        };

        //Query By Due Date
        $query = match ($due) {
            'all' => $query->bookOverDue()->paymentOverDue() ,
            'book_due' => $query->bookOverDue() ,
            'payment_due' => $query->paymentOverDue() ,
            default => $query
        };

        //Query by Status
        $query = match ($status) {
            'active' => $query->byStatus(Purchase::STATUS_OPEN) ,
            'inactive' => $query->byStatus(Purchase::STATUS_CLOSE) ,
            default => $query
        };

        $date_range = explode(' - ' , $date_range);

        //Handle Invalid Date Format Error and query between given date ranges
        try {
            $start = Carbon::createFromFormat('m/d/Y' , $date_range[0]);
            $end = Carbon::createFromFormat('m/d/Y' , $date_range[1]);

            if ($date_range) {
                $query->whereBetween('created_at' , [$start , $end]);
            }
        } catch (\Exception $e) {

        }

        $query = match ($isReturned) {
            '1' => $query->where('for_rent' , true)->whereNotNull('book_returned_at') ,
            '0' => $query->where('for_rent' , true)->whereNull('book_returned_at') ,
            default => $query
        };

        $query = match ($isPaid) {
            '1' => $query->where('pending_amount' , '=' , 0) ,
            '0' => $query->whereColumn('pending_amount' , '=' , 'price') ,
            '2' => $query->where('pending_amount' , '>' , 0)
                ->whereColumn('pending_amount' , '<' , 'price') ,
            default => $query
        };

        //Sort the result
        if ($sort == 'oldest') {
            $query = $query->orderBy('updated_at');
        } else {
            $query = $query->orderByDesc('updated_at');
        }

        return $query;
    }

    function checkIfPurchased($book) {
        return Purchase::where(function ($query) use ($book) {
            $query->where('user_id', auth()->user()->id)
                ->where('book_id', $book->id);
        })->where(function ($query) {
            $query->where('book_return_due', '>=', Carbon::now())
                ->orWhere('book_return_due', null);
        })->exists();
    }
}
