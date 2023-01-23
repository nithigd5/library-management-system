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
        Book::create($this->storeAndSetUploadedFiles($request->all()));

        return back()->with('message' , 'Book has been Successfully Created.');
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
        $book->update($this->storeAndSetUploadedFiles($request->all()));

        return back()->with('message' , 'Book has been Successfully updated.');
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
        $book->deleteOrFail();

        $this->deleteBookFiles($book);

        return back()->with('message' , 'Book has been Successfully deleted.');
    }


    /**
     *
     * Store and set Uploaded Book files
     * @param $validated
     * @return array
     */
    public function storeAndSetUploadedFiles($validated): array
    {
        if (array_key_exists('image' , $validated)) {
            $validated['image'] = $validated['image']->store(config('filesystems.book_front_covers') , ['disk' => 'public']);
        }

        if (array_key_exists('book_file' , $validated)) {
            $validated['book_path'] = $validated['book_file']->store(config('filesystems.book_pdf_files'));
        }

        return $validated;
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
