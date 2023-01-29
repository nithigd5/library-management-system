<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequestUpdateValidation;
use App\Models\BookRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminBookRequestController extends Controller
{
    /**
     * Display a listing of the Books.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $query = BookRequest::query();

        $query = $this->sortAndDateQueryFilter($query , $request->date_range , $request->sort);

        $query = $this->filterByStatus($query, $request->status);

        $bookrequest = $query->paginate(10);

        return view('pages.admin.bookrequest.index' , ['type_menu' => 'books ' , 'books' => $bookrequest]);
    }

    /**
     *
     * Update a book request as a ajax
     * @param BookRequestUpdateValidation $request
     * @param BookRequest $bookRequest
     * @return JsonResponse
     */
    public function update(BookRequestUpdateValidation $request , $id)
    {
        $bookRequest = BookRequest::findOrFail($id);

        $bookRequest->status = $request->status;
        $bookRequest->comment = $request->comment;

        if (!$bookRequest->save()) {
            return response()->json([
                'message' => 'failed' ,
                'errors' => [
                    'amount' => ['Status cannot be updated. Please try again later.']
                ]
            ] , 400);
        }

        return response()->json([
            'message' => 'success' ,
            'data' => [
                'book_request' => $bookRequest
            ]
        ]);
    }

    public function show($id)
    {
        $book = BookRequest::findOrFail($id);
        return view('pages.admin.bookrequest.show' , ['type_menu' => '' , 'book' => $book]);
    }

    /**
     *
     * Filter the result by status
     * @param $query
     * @param $status
     * @return mixed
     */
    public function filterByStatus($query , $status)
    {
       $query = match($status){
           BookRequest::STATUS_REJECTED => $query->where('status', BookRequest::STATUS_REJECTED),
           BookRequest::STATUS_PENDING => $query->where('status', BookRequest::STATUS_PENDING),
           BookRequest::STATUS_ACCEPTED => $query->where('status', BookRequest::STATUS_ACCEPTED),
           default => $query
       };

       return $query;
    }
}
