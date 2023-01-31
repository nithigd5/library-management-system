<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentUpdateRequest;
use App\Http\Requests\PurchaseStoreRequest;
use App\Models\Book;
use App\Models\Purchase;
use App\Models\User;
use App\Traits\PurchaseControllableTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    use PurchaseControllableTrait;

    /**
     * View all Purchases ordered by recent
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $purchases = $this->getPurchases($request->due , $request->type , $request->date_range ,
            $request->status , $request->sort , $request->returned , $request->payment)->paginate(10)->withQueryString();

        return view('pages.admin.purchases.index' , compact('purchases') , ['type_menu' => 'purchases' , 'status' => 'all']);
    }

    /**
     * Show a particular book view page
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id): View|Factory|Application
    {
        $purchase = Purchase::with('user' , 'book')->findOrFail($id);
        return view('pages.admin.purchases.show' , compact('purchase') , ['type_menu' => 'purchases']);
    }

    /**
     * Show a view for creating a new offline Purchase
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('pages.admin.purchases.create' , ['type_menu' => 'purchases']);
    }

    /**
     * store new offline Purchase
     * @param PurchaseStoreRequest $request
     * @return JsonResponse
     */
    public function store(PurchaseStoreRequest $request): JsonResponse
    {
        //Get book and user
        $book = Book::find($request->book);
        $user = User::find($request->user);

        //convert for_rent to boolean
        $request->for_rent = (bool)$request->for_rent;

        //Check if book is online and user already purchased this book
        if ($this->checkIfAnyDue($user->id)) {
            return response()->json([
                'message' => 'failed' ,
                'errors' => [
                    'user' => ['User has pending dues.']
                ]
            ] , 409);
        }

        //Check if book is online and user already purchased this book or book is rented already offline
        if (!$this->isPurchasable($book->id , $user->id)) {
            return response()->json([
                'message' => 'failed' ,
                'errors' => [
                    'book' => ['User has already rented the same book and not returned.']
                ]
            ] , 409);
        }


        $purchase = [
            'user_id' => $user->id ,
            'book_id' => $book->id ,
            'for_rent' => $request->for_rent ,
            'book_issued_at' => now() ,
            'mode' => Purchase::MODE_OFFLINE ,
        ];

        //find maximum amount
        if ($request->for_rent) {
            $maxAmount = round($book->price * config('book.rent_percentage') / 100);
            $purchase['book_return_due'] = now()->addDays(config('book.book_return_due_days'));
        } else {
            $maxAmount = round($book->price);
        }


        //check if given data is less than maximum amount
        if ($request->amount > $maxAmount) {
            return response()->json([
                'message' => 'failed' ,
                'errors' => [
                    'amount' => ['Amount cannot be greater than actual price or rent % amount']
                ]
            ] , 406);
        }

        $purchase['price'] = $maxAmount;

        //check if user has permission to pay later for purchase if amount is less than maxAmount
        if ($request->amount < $maxAmount && !$user->hasPermissionTo('books.purchase.pay.later')) {
            return response()->json([
                'message' => 'failed' ,
                'errors' => [
                    'user' => ['User don"t have permission to pay later']
                ]
            ] , 402);
        }

        $purchase['pending_amount'] = $maxAmount - $request->amount;

        if ($purchase['pending_amount'] > 0) {
            $purchase['payment_due'] = now()->addDays(config('book.purchase_due_days'));
        }

        if (!Purchase::create($purchase)) {
            return response()->json([
                'message' => 'failed' ,
                'errors' => [
                    'purchase' => ['Cannot create a purchase. please try again later']
                ]
            ] , 500);
        }

        return response()->json([
            'message' => 'success' ,
            'data' => [
                'purchase' => $purchase
            ]
        ]);
    }

    /**
     * store updated purchase offline Purchase as ajax
     * @return JsonResponse
     */
    public function update(Purchase $purchase , PaymentUpdateRequest $request): JsonResponse
    {
        //Check if given amount is not greater than pending amount

        if ($purchase->pending_amount < $request->amount)
            return response()->json([
                'message' => 'failed' ,
                'errors' => [
                    'amount' => ['Payment Amount cannot be greater than pending amount: ' . $purchase->pending_amount]
                ]
            ] , 422);

        $purchase->pending_amount = $purchase->pending_amount - $request->amount;

        if (!$purchase->save()) {
            return response()->json([
                'message' => 'failed' ,
                'errors' => [
                    'amount' => ['Payment Amount cannot be updated. Please try again later.']
                ]
            ] , 400);
        }

        return response()->json([
            'message' => 'success' ,
            'data' => [
                'pending_amount' => $purchase->pending_amount
            ]
        ] , 200);
    }

    /**
     * return a book if not returned
     * @param Purchase $purchase
     * @return RedirectResponse
     */
    public function returnBook(Purchase $purchase): RedirectResponse
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

}
