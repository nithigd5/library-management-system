<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Requests\PaymentUpdateRequest;
use App\Models\Book;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            $request->status , $request->sort , $request->returned , $request->payment)->paginate(10)->withQueryString();

        return view('pages.customer.purchases.index' , compact('purchases') , ['type_menu' => 'purchase' , 'status' => 'all']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($id)
    {
        $book = Book::find($id);
        if ($book->mode == "online") {
            $purchased=$this->checkIfAlreadyRented($book, Auth::id());

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
     * @param Request $request
     * @param $id
     * @return Response
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
            $book_return_due = Carbon::now()->addDays(config('book.book_return_due_days'));
        }
        Purchase::create([
            'user_id' => auth()->user()->id,
            'book_id' => $book->id,
            'price' => $book->price,
            'for_rent' => $rentOrBuy,
            'pending_amount' => $pendingAmount,
            'payment_due' => Carbon::now()->addDays(config('book.purchase_due_days')),
            'book_return_due' => $book_return_due,
            'book_issued_at' => Carbon::now(),
            'mode' => $book->mode,
        ]);
        return view('pages.customer.customerPurchase.paymentSuccess', ['type_menu' => '', 'book' => $book]);
    }

    /**
     * Show a particular book view page
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $purchase = Purchase::with('user' , 'book')->findOrFail($id);
        return view('pages.customer.purchases.show' , compact('purchase') , ['type_menu' => 'purchase']);
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

}
