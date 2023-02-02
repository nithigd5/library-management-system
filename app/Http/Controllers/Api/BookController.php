<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    /**
     * Get all books with paginations
     * @return BookCollection
     */
    public function index()
    {
        return new BookCollection($this->search(Book::query() , request()->q)->paginate(5));
    }

    /**
     * Search a given term in name and id column
     * @param Builder $query
     * @param string|null $term
     * @return Builder
     */
    public function search(Builder $query , ?string $term)
    {
        return $term ? $query->where('name' , 'LIKE' , "%{$term}%")
            ->orWhere('id' , $term) : $query;
    }

    /**
     * Return a book with given by ID.
     * @param $id
     * @return JsonResponse
     */
    public function getBook($id)
    {
        $codeWithRouteName = strtoupper(request()->route()->getName()) . '-BOOK_GET-';

        $book = Book::find($id);

        if ($book)
            return response()->json([
                'message' => 'success' ,
                'data' => [
                    'book' => new BookResource($book)
                ]
            ]);

        return response()->json([
            'message' => 'failed' ,
            'errors' => [
                'common_error' => [
                    'code' => $codeWithRouteName . ' ' . 'NOT_FOUND'
                ]
            ]
        ] , Response::HTTP_NOT_FOUND);
    }
}
