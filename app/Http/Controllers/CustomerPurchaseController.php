<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Book;
use App\Models\Purchase;
use App\Traits\PurchaseControllableTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerPurchaseController extends Controller
{
    use PurchaseControllableTrait;
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
     * @return Application|Factory|View|RedirectResponse
     */
    public function create($id)
    {
        $book = Book::find($id);
        if ($book->mode == "online") {
            if (!$this->isPurchasable($book->id , Auth::id())) {
                return back()->with('status' , 'Book is already purchased as rent or owned online.');
            } else {
                //user has not purchased the book
                return view('pages.customer.customerPurchase.customerPurchase' , ['type_menu' => '' , 'book' => $book]);
            }
        } else {
            return back()->with('status' , 'Book is available only in OFFLINE.');
        }
    }
    public function storePending(Request $request , $id)
    {
        $book = Purchase::find($id);
        $validatedData = Validator::make($request->all() , (new PaymentRequest($book->pending_amount))->rules());
        if ($validatedData->fails()) {
            return redirect()->back()->withErrors($validatedData->errors());
        }
        $pendingAmount = ($book->pending_amount - ($request->paidPrice));

        Purchase::create([
            'user_id' => auth()->user()->id ,
            'book_id' => $book->book_id ,
            'price' => $book->price ,
            'for_rent' => $book->for_rent,
            'pending_amount' => $pendingAmount ,
            'payment_due' => $book->payment_due ,
            'book_return_due' => $book->book_return_due ,
            'book_issued_at' => $book->book_issued_at ,
            'mode' => $book->mode ,
        ]);

        return view('pages.customer.customerPurchase.paymentSuccess' , ['type_menu' => '' , 'book' => $book]);
    }
    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function pendingpayment($id)
    {
        $purchase = Purchase::find($id);
        if ($purchase->pending_amount >0) {
                return view('pages.customer.customerPurchase.customerPurchasePending' , ['type_menu' => '' , 'book' => $purchase]);
            }
        else {
            return back()->with('status' , 'No Pending Due');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function store(Request $request , $id)
    {
        $book = Book::find($id);
        $validatedData = Validator::make($request->all() , (new PaymentRequest($book->price))->rules());
        if ($validatedData->fails()) {
            return redirect()->back()->withErrors($validatedData->errors());
        }

        if ($book->mode == 'offline') abort(403 , 'Book is only available offline');

        //Check if book is online and user already purchased this book
        if ($this->checkIfAnyDue(Auth::id())) {
            abort(403 , 'You have Dues. Please Clear it.');
        }

        //Check if book is online and user already purchased this book or book is rented already offline
        if (!$this->isPurchasable($book->id , Auth::id())) {
            abort(403 , 'You can access this book online.');
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
            'user_id' => auth()->user()->id ,
            'book_id' => $book->id ,
            'price' => $book->price ,
            'for_rent' => $rentOrBuy ,
            'pending_amount' => $pendingAmount ,
            'payment_due' => Carbon::now()->addDays(config('book.purchase_due_days')) ,
            'book_return_due' => $book_return_due ,
            'book_issued_at' => Carbon::now() ,
            'mode' => $book->mode ,
        ]);
        return view('pages.customer.customerPurchase.paymentSuccess' , ['type_menu' => '' , 'book' => $book]);
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
}
