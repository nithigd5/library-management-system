<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('pages.admin.books.index' , ['type_menu' => 'books' , 'books' => Book::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admin.books.create' , ['type_menu' => 'books']);
    }

    /**
     * Store a newly created valid book in books table.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request)
    {
        $input = $request->validated();

        if ($input['mode'] === 'online') {
            $book = Book::create([
                'name' => $input['name'] ,
                'author' => $input['author'] ,
                'price' => $input['price'] ,
                'version' => $input['version'] ,
                'mode' => $input['mode'] ,
                'is_download_allowed' => $input['is_download_allowed'] ,
                'book_path' => $input['book']->store('books') ,
                'image' => $input['image']->store('data/books/front-covers' , ['disk' => 'public'])
            ]);
        } else {
            $book = Book::create([
                'name' => $input['name'] ,
                'author' => $input['author'] ,
                'price' => $input['price'] ,
                'version' => $input['version'] ,
                'mode' => $input['mode'] ,
                'image' => $input['image']->store('data/books/front-covers' , ['disk' => 'public'])]);
        }

        return to_route('books.index');
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
        return view('pages.admin.books.edit' , ['type_menu' => 'books']);
    }

    /**
     * Update the specified valid Book in books table.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookRequest $request , Book $book)
    {

        $book->name = $request->name;
        $book->author = $request->author;
        $book->price = $request->price;
        $book->version = $request->version;
        $book->mode = $request->mode;

        if (isset($request->image)) {
            $book->image = $request->image->store('data/books/front-covers' , ['disk' => 'public']);
        }

        if ($request->file('book_file') !== null && $request->mode === 'online') {

            $book->book_path = $request->file('book_file')->store('books');
            $book->is_download_allowed = $request->is_download_allowed;
        }

        $book->save();

        return $book;
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
