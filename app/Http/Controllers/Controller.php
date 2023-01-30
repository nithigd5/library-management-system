<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests , DispatchesJobs , ValidatesRequests;

    /**
     * Sort and filter the result in given date range
     * @param $date_range
     * @param $sort
     * @param Builder $query
     * @return Builder
     */
    public function sortAndDateQueryFilter(Builder $query , $date_range , $sort = null , $column = 'updated_at'): Builder
    {
        //Sort the result
        if ($sort == 'oldest') {
            $query = $query->orderBy($column);
        } else {
            $query = $query->orderByDesc($column);
        }

        $date_range = explode(' - ' , $date_range);

        //Handle Invalid Date Format Error and query between given date ranges
        try {
            $start = Carbon::createFromFormat('m/d/Y' , $date_range[0]);
            $end = Carbon::createFromFormat('m/d/Y' , $date_range[1]);

            if ($date_range) {
                $query->whereBetween($column , [$start , $end]);
            }
        } catch (\Exception $e) {

        }
        return $query;
    }

    /**
     * Check if User has already rented the same book and not returned
     * @param $book
     * @param $user
     * @return bool
     */
    public function checkIfAlreadyRented($book , $user): bool
    {
        return Purchase::where(function ($query) use ($user , $book) {
            $query
                ->where('user_id' , $user->id)
                ->where('book_id' , $book->id);
        })->where(function ($query) {
            $query
                ->where('for_rent' , 1)
                ->whereNull('book_returned_at')
                ->where('payment_due', '>', now())
                ->where('book_return_due', '>', now());
        })->exists();
    }

}
