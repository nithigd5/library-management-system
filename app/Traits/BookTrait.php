<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait BookTrait
{
    /**
     * Sales column will be added to result for a each book
     * Get all books which are bought highest in order.
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrderByMostPurchased(Builder $query): Builder
    {
        return $query
            ->joinSub(DB::table('purchases')->select('book_id' , DB::raw('count(book_id) as sales'))
                ->groupBy('book_id') , 'purchases' , function ($join) {
                $join->on('purchases.book_id' , '=' , 'books.id');
            })->orderBy('sales', 'Desc');
    }
}
