<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
        if (Book::create($this->storeAndSetUploadedFiles($request->all()))) {
            return back()->with('message' , __('book.store.success'))->with('status' , Constants::SUCCESS_STATUS);
        }
        return back()->with('message' , __('book.store.failed'))->with('status' , Constants::FAILED_STATUS);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function search(Request $request)
    {
        // Get the search value from the request
        $search = $request->input('search');

        // Search in the title and body columns from the posts table
        $books = Book::query()
            ->where('name', 'LIKE', "%{$search}%")
            ->orWhere('author', 'LIKE', "%{$search}%")
            ->get();
        return view('pages.admin.books.index' , ['type_menu' => 'books' , 'books' => $books]);
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $book=Book::findOrFail($id);
        return view('pages.admin.books.show',[ 'type_menu'=> '','book'=>$book]);
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
        if ($book->update($this->storeAndSetUploadedFiles($request->all()))) {
            return back()->with('message' , __('book.update.success'))->with('status' , Constants::SUCCESS_STATUS);
        }
        return back()->with('message' , __('book.update.failed'))->with('status' , Constants::FAILED_STATUS);
    }

    /**
     * Remove the specified book from database and associated files from storage.
     *
     * @param Book $book
     * @return RedirectResponse
     */
    public function destroy(Book $book): RedirectResponse
    {
        if ($book->delete()) {
            $this->deleteBookFiles($book);
            return back()->with('message' , __('book.delete.success'))->with('status' , Constants::SUCCESS_STATUS);
        }
        return back()->with('message' , __('book.delete.failed'))->with('status' , Constants::FAILED_STATUS);
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
