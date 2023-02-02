<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentUpdateRequest;
use App\Http\Requests\PurchaseStoreRequest;
use App\Models\Purchase;
use App\Traits\PurchaseControllableTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class PurchaseController extends Controller
{
    use PurchaseControllableTrait;

    /**
     * View all Purchases ordered by recent
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $purchases = $this->getPurchases(request('due') , request('type') , request('date_range') ,
            request('status') , request('sort') , request('returned') , request('payment'))->paginate(10)->withQueryString();

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
     * @throws ValidationException
     */
    public function store(PurchaseStoreRequest $request): JsonResponse
    {
        return response()->json(['message' => 'success' , 'data' => ['purchase' => $request->handle()]]);
    }

    /**
     * store updated purchase offline Purchase as ajax
     * @param Purchase $purchase
     * @param PaymentUpdateRequest $request
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
            ] , Response::HTTP_UNPROCESSABLE_ENTITY);

        $purchase->pending_amount = $purchase->pending_amount - $request->amount;

        if (!$purchase->save()) {
            return response()->json([
                'message' => 'failed' ,
                'errors' => [
                    'amount' => ['Payment Amount cannot be updated. Please try again later.']
                ]
            ] , Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'success' ,
            'data' => [
                'pending_amount' => $purchase->pending_amount
            ]
        ]);
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
