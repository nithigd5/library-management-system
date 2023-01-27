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
    public function scopeReturnedLastMonth(Builder $query): Builder
    {
        return $this->scopeRentedBetween($query , now()->subMonth() , now())->whereNotNull('book_returned_at');
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
    public function scopeLatestPurchases(Builder $query , $status = null): Builder
    {
        if ($status === Purchase::STATUS_OPEN) {
            return $query->latest('created_at')
                ->where('pending_amount' , '>' , 0)
                ->Orwhere(function (Builder $query) {
                    $query->where('for_rent' , true)
                        ->whereNull('book_returned_at');
                });
        }
        if ($status === Purchase::STATUS_CLOSE) {
            return $query->latest('created_at')
                ->where('pending_amount' , '=' , 0)
                ->Orwhere(function (Builder $query) {
                    $query->where('for_rent' , true)
                        ->whereNotNull('book_returned_at');
                });
        }

        return $query->latest('created_at');
    }

    /**
     * get all latest purchases
     * @param Builder $query
     * @return Builder
     */
    public function scopeBookOverDue(Builder $query): Builder
    {
        return $query
            ->where('book_return_due' , '<' , now())
            ->whereNull('book_returned_at')
            ->where('for_rent' , true);
    }

    /**
     * get all latest purchases
     * @param Builder $query
     * @return Builder
     */
    public function scopePaymentOverDue(Builder $query): Builder
    {
        return $query
            ->where('payment_due' , '<' , now())
            ->where('pending_amount' , '>' , 0);
    }

    /**
     * get a revenue column = price - pending_amount between given dates
     * @param Builder $query
     * @param $start
     * @param $end
     * @param bool $for_rent
     * @return Builder
     */
    public function scopeRevenueBetween(Builder $query , $start , $end , $for_rent = true): Builder
    {
        $query = $query
            ->select(DB::raw('price - pending_amount as revenue'))
            ->whereBetween('created_at' , [$start , $end]);

        if ($for_rent) $query->where('for_rent' , $for_rent);

        return $query;
    }

    /**
     * get a total revenue between given dates
     * @param Builder $query
     * @param $start
     * @param $end
     * @param bool $for_rent
     * @return Builder
     */
    public function scopeRevenueSumBetween(Builder $query , $start , $end , $for_rent = false): Builder
    {
        $query = $query
            ->select(DB::raw('SUM(price - pending_amount) as revenue'))
            ->whereBetween('created_at' , [$start , $end]);

        if ($for_rent) $query->where('for_rent' , $for_rent);

        return $query;
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
