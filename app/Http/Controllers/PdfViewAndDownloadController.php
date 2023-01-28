<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PdfViewAndDownloadController extends Controller
{
    public function viewPDF($id)
    {
        $book = Book::find($id);
        $purchased=$this->checkIfPurchased($book);

        if ($purchased) {
            $path = public_path('/storage/data/bookPDF/dummy.pdf');
            return response()->file($path);
        } else {
            return back()->with('status', "You havn't bought this book yet. Please, Buy the book!");
        }
    }

    /**
     * @return BinaryFileResponse
     */
    public function downloadPdf($id)
    {
        $book = Book::find($id);
        if ($book->is_download_allowed) {
           $purchased=$this->checkIfPurchased($book);

            if ($purchased) {
                $file_path = public_path('/storage/data/bookPDF/dummy.pdf');
                return response()->download($file_path);
            } else {
                return back()->with('status', "You havn't bought this book yet. Please, Buy the book!");
            }
        }
    else {
        return back()->with('status', "Downloading is not allowed for this Book.");
    }
    }

    function checkIfPurchased($book) {
        return Purchase::where(function ($query) use ($book) {
            $query->where('user_id', auth()->user()->id)
                ->where('book_id', $book->id);
        })->where(function ($query) {
            $query->where('book_return_due', '>=', Carbon::now())
                ->orWhere('book_return_due', null);
        })->exists();
    }
}
