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
     * @param Builder $query
     * @param $date_range
     * @param null $sort
     * @param string $column
     * @return Builder
     */
    public function sortAndDateQueryFilter(Builder $query , $date_range , $sort = null , string $column = 'updated_at'): Builder
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
     * User is allowed to purchase only if book is not rented already in offline
     * and not owned or rented in online and also no due
     * @param $bookId
     * @param $userId
     * @return bool
     */
    public function isPurchasable($bookId , $userId): bool
    {
        return  (!Purchase::accessibleOnlineBooks($userId)->where('book_id' , $bookId)->exists()
            && !Purchase::accessibleOfflineBooks($userId)->where('for_rent' , true)->where('book_id' , $bookId)->exists());
    }

    /**
     * User is allowed to purchase only if book is not rented already in offline
     * and not owned or rented in online
     * @param $userId
     * @return bool
     */
    public function checkIfAnyDue($userId): bool
    {
        return Purchase::allDue()->where('user_id' , $userId)->exists();
    }
}
