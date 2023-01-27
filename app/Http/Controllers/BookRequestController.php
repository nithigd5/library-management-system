<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class BookRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('pages.admin.request.index' , ['type_menu' => 'books' , 'books' => Book::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admin.bookrequest.request' , ['type_menu' => 'books']);
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

        return to_route('requests.index');
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
     * Show the form for editing the specified book.
     *
     * @param Book $book
     * @return Application|Factory|View
     */
    public function edit(Book $book): Application|Factory|View
    {
        //
    }

    /**
     * Update the specified valid Book in books table.
     *
     * @param UpdateBookRequest $request
     * @param Book $book
     * @return RedirectResponse
     */
    public function update(UpdateBookRequest $request , Book $book): RedirectResponse
    {

        //
    }

    /**
     * Remove the specified book from database and associated files from storage.
     *
     * @param Book $book
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function destroy(Book $book): RedirectResponse
    {
        //Delete a Book Front Image from Storage if Present
        if ($book->image)
            Storage::disk('public')->delete($book->image);

        //Delete a Book PDF File from Storage if present
        if ($book->book_path)
            Storage::delete($book->book_path);

        $book->delete();

        return to_route('books.index');
    }
}
