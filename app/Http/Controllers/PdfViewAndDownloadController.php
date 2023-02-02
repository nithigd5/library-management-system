<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Purchase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfViewAndDownloadController extends Controller
{
    /**
     * @param $id
     * @return RedirectResponse|BinaryFileResponse
     */
    public function viewPDF($id)
    {
        $book = Book::find($id);
        $purchased = Purchase::accessibleOnlineBooks(Auth::id())->where('book_id' , $book->id);

        if ($purchased->exists()) {
            return Storage::download($book->book_path);
        } else {
            return back()->with('status' , "You haven't bought this book yet. Please, Buy the book!");
        }
    }

    /**
     * @return RedirectResponse|StreamedResponse
     */
    public function downloadPdf($id): StreamedResponse|RedirectResponse
    {
        $book = Book::find($id);
        if ($book->is_download_allowed) {
            $purchased = Purchase::accessibleOnlineBooks(Auth::id())->where('book_id' , $book->id);

            if ($purchased->exists()) {
                return Storage::download($book->book_path);
            } else {
                return back()->with('status' , "You haven't bought this book yet. Please, Buy the book!");
            }
        } else {
            return back()->with('status' , "Downloading is not allowed for this Book.");
        }
    }
}
