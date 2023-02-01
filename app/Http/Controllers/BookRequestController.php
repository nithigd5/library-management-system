<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequestValidation;
use App\Http\Requests\PaymentRequest;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookRequestController extends Controller
{
    /**
     * Display a listing of the Books.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $bookrequest = BookRequest::where('user_id', auth()->user()->id)->get();
        return view('pages.customer.bookrequest.index', ['type_menu' => 'books', 'books' => $bookrequest]);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('pages.customer.bookrequest.create', ['type_menu' => 'books']);
    }

    /**
     * @param BookRequestValidation $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(BookRequestValidation $request)
    {
        BookRequest::create([
            'book_name' => $request->book_name,
            'user_id' => auth()->user()->id,
            'book_author' => $request->book_author,
            'description' => $request->description,
        ]);
        return redirect(route('bookrequest.index'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $book=BookRequest::find($id);
        return view('pages.customer.bookrequest.show',[ 'type_menu'=> '','book'=>$book]);
    }
}
