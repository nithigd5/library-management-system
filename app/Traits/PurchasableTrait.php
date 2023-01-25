<?php

namespace App\Traits;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
    public function scopeLatestPurchases(Builder $query , $status = Purchase::STATUS_OPEN): Builder
    {
        return $query->latest('created_at')->where('status' , $status);
    }

    /**
     * get all latest purchases
     * @param Builder $query
     * @return Builder
     */
    public function scopeBookOverDuePurchases(Builder $query): Builder
    {
        return $query->oldest('created_at')
            ->where('book_return_due' , '<' , now())
            ->whereNull('book_returned_at')
            ->where('for_rent' , true);
    }

    /**
     * Get all books which are bought highest in order.
     * @param Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeOrderByMostPurchased(Builder $query): \Illuminate\Database\Query\Builder
    {
        return DB::table('books')
            ->joinSub($query->select('book_id' , DB::raw('count(book_id) as sales'))
                ->groupBy('book_id') , 'purchases' , function ($join) {
                $join->on('purchases.book_id' , '=' , 'books.id');
            })->orderBy('sales', 'Desc');
    }

    /**
     * Check if book should be returned based on if it is rented
     * @return boolean
     */
    public function toReturn()
    {
        return $this->for_rent && is_null($this->book_returned_at);
    }

    /**
     *
     * Check if amount need to pay for this purchase
     * @return bool
     */
    public function toPay()
    {
        return $this->pending_amount > 0;
    }

    /**
     * get payment status
     * @return string
     */
    public function getPaymentStatus()
    {
        return match ($this->pending_amount) {
            0.0 => Purchase::PAYMENT_COMPLETED ,
            $this->price => Purchase::PAYMENT_PENDING ,
            default => Purchase::PAYMENT_HALF_PAID
        };
    }

    /**
     * get purchase status
     * @return string
     */
    public function getPurchaseStatus()
    {
        return $this->isOpen() ? Purchase::STATUS_OPEN : Purchase::STATUS_CLOSE;
    }

    /**
     *
     * Check if purchase is active
     * @return bool
     */
    public function isOpen()
    {
        return $this->toPay() || $this->toReturn();
    }
}
