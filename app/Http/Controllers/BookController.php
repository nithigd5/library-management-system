<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                'image' => $input['image']->store('public/books/front-covers')
            ]);
        } else {
            $book = Book::create([
                'name' => $input['name'] ,
                'author' => $input['author'] ,
                'price' => $input['price'] ,
                'version' => $input['version'] ,
                'mode' => $input['mode'] ,
                'image' => $input['image']->store('public/books/front-covers')]);
        }

        return response()->json($book);
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
        //
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

        if(isset($request->image)){
            $book->image = $request->image->store('public/books/front-covers');
        }

        if($request->file('book_file') !== null && $request->mode === 'online')
        {

            $book->book_path =  $request->file('book_file')->store('books');
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
