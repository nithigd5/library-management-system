<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the Books.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('pages.admin.books.index' , ['type_menu' => 'books' , 'books' => Book::all()]);
    }

    /**
     * Show the form for creating a new book.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('pages.admin.books.create' , ['type_menu' => 'books']);
    }

    /**
     * Store a newly created valid book in books table.
     *
     * @param StoreBookRequest $request
     * @return RedirectResponse
     */
    public function store(StoreBookRequest $request)
    {
        $input = $request->validated();
        $input = $this->storeUploadedFilesByArray($input);
        Book::create($input);

        return to_route('books.index');
    }

    /**
     * Show the form for editing the specified book.
     *
     * @param Book $book
     * @return Application|Factory|View
     */
    public function edit(Book $book): Application|Factory|View
    {
        return view('pages.admin.books.edit' , ['type_menu' => 'books' , 'book' => $book]);
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

        $book->name = $request->name;
        $book->author = $request->author;
        $book->price = $request->price;
        $book->version = $request->version;
        $book->mode = $request->mode;
        $this->storeUploadedFiles($request , $book);
        $book->save();

        return to_route('books.index');
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
        $this->deleteBookFiles($book);
        $book->delete();

        return to_route('books.index');
    }

    /**
     * Store the uploaded image and pdf file
     * @param array $input
     * @return array
     */
    public function storeUploadedFilesByArray(array $input): array
    {
        $input['book_path'] = array_key_exists('book' , $input) ? $input['book']->store(config('filesystems.book_pdf_files')) : null;
        $input['image'] = $input['image']->store(config('filesystems.book_front_covers') , ['disk' => 'public']);
        return $input;
    }

    /**
     * @param UpdateBookRequest $request
     * @param Book $book
     * @return void
     */
    public function storeUploadedFiles(UpdateBookRequest $request , Book $book): void
    {
        if (isset($request->image)) {
            $book->image = $request->image->store(config('filesystems.book_front_covers') , ['disk' => 'public']);
        }

        if ($request->file('book_file') !== null && $request->mode === 'online') {
            $book->book_path = $request->file('book_file')->store(config('filesystems.book_pdf_files'));
            $book->is_download_allowed = $request->is_download_allowed;
        }
    }

    /**
     *
     * Delete Book PDF File and Front Image
     * @param Book $book
     * @return void
     */
    public function deleteBookFiles(Book $book): void
    {
        //Delete a Book Front Image from Storage if Present
        if ($book->image) Storage::disk('public')->delete($book->image);

        //Delete a Book PDF File from Storage if present
        if ($book->book_path) Storage::delete($book->book_path);
    }
}
