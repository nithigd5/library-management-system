<?php

namespace App\Books;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Builder;

trait Purchasable
{
    /**
     *
     * Get all rented books by date range (default month: current)
     * @param Builder $query
     * @param $startDate
     * @param $endDate
     * @return Builder
     */
    public function scopeRentedBetween(Builder $query , $startDate , $endDate): Builder
    {
        return $query->where('for_rent' , true)->whereBetween('created_at' , [$startDate , $endDate]);
    }

    /**
     *
     * Get all rented books in last month
     * @param Builder $query
     * @return Builder
     */
    public function scopeRentedLastMonth(Builder $query): Builder
    {
        return $this->scopeRentedBetween($query , now()->subMonth() , now());
    }

    /**
     * get all latest purchases
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatestPurchases(Builder $query): Builder
    {
        return $query->latest('created_at')->where('status', Purchase::STATUS_OPEN) ;
    }
}
