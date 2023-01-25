<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CustomerBookController extends Controller
{

    /**
     * Display a listing of the Books.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('pages.customer.customerBook.index' , ['type_menu' => 'books' , 'books' => Book::all()]);
    }
    public function search(Request $request)
    {
        // Get the search value from the request
        $search = $request->input('search');

        // Search in the title and body columns from the posts table
        $books = Book::query()
            ->where('name', 'LIKE', "%{$search}%")
            ->orWhere('author', 'LIKE', "%{$search}%")
            ->get();
        return view('pages.customer.customerBook.index' , ['type_menu' => 'books' , 'books' => $books]);
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $book=Book::find($id);
        return view('pages.customer.customerBook.showBooks',[ 'type_menu'=> '','book'=>$book]);
    }
}
