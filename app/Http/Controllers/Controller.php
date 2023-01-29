<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Sort and filter the result in given date range
     * @param $date_range
     * @param $sort
     * @param Builder $query
     * @return Builder
     */
    public function sortAndDateQueryFilter(Builder $query , $date_range , $sort = null, $column = 'updated_at'): Builder
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

}
