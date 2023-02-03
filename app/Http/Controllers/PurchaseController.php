<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentUpdateRequest;
use App\Http\Requests\PurchaseStoreRequest;
use App\Models\Purchase;
use App\Traits\PurchaseControllerTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class PurchaseController extends Controller
{
    use PurchaseControllerTrait;

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
        // Do all the validations
        $request->handle();

        if (!$purchase = Purchase::create($request->purchase)) {
            return response()
                ->json(['message' => 'failed' , 'errors' => ['amount' => ['Cannot create a purchase. please try again later']]] ,
                    Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response()->json(['message' => 'success' , 'data' => ['purchase' => $purchase]]);
    }

    /**
     * store updated purchase offline Purchase as ajax
     * @param Purchase $purchase
     * @param PaymentUpdateRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Purchase $purchase , PaymentUpdateRequest $request): JsonResponse
    {
        $request->checkAmount();

        $purchase->pending_amount = $purchase->pending_amount - $request->amount;

        if (!$purchase->save()) {
            return response()
                ->json(['message' => 'failed' , 'errors' => ['amount' => ['Payment Amount cannot be updated. Please try again later.']]] ,
                    Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response()
            ->json(['message' => 'success' , 'data' => ['pending_amount' => $purchase->pending_amount]
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

            $purchase->book_returned_at = now();

            if (!$purchase->save())
                abort(503, 'Cannot return a book. Please Try again later.');
            else
                return back()->with('message' , 'Book has been returned successfully')->with('status' , 'success');

        } else {
            return back()->with('message' , 'Book is already returned')->with('status' , 'danger');
        }
    }

}
