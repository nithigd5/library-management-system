<?php

namespace App\Traits;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Builder;

trait PurchasableTrait
{
    /**
     *
     * Get all books by date range with optional is_rent (default month: current)
     * @param Builder $query
     * @param $startDate
     * @param $endDate
     * @return Builder
     */
    public function scopePurchasedBetween(Builder $query , $startDate , $endDate , $is_rent): Builder
    {
        return $query->where('for_rent' , $is_rent)->whereBetween('created_at' , [$startDate , $endDate]);
    }

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
        return $this->scopePurchasedBetween($query , $startDate , $endDate , true);
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
     *
     * Get all rented books in last month
     * @param Builder $query
     * @return Builder
     */
    public function scopeOwnedLastMonth(Builder $query): Builder
    {
        return $this->scopePurchasedBetween($query , now()->subMonth() , now() , false);
    }

    /**
     * get all latest purchases
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatestPurchases(Builder $query, $status = Purchase::STATUS_OPEN): Builder
    {
        return $query->latest('created_at')->where('status' , $status);
    }
}
