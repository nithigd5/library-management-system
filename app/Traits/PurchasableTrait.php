<?php

namespace App\Traits;

use App\Models\Book;
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
     * @param $is_rent
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
     * if purchase is open if book is not returned or payment is not completed
     * @param Builder $query
     * @param string $status
     * @return Builder
     */
    public function scopeByStatus(Builder $query , $status = Purchase::STATUS_OPEN): Builder
    {
        if ($status === Purchase::STATUS_OPEN) {
            return $query
                ->where('pending_amount' , '>' , 0)
                ->Orwhere(function (Builder $query) {
                    $query->where('for_rent' , true)
                        ->whereNull('book_returned_at');
                });
        }

        if ($status === Purchase::STATUS_CLOSE) {
            return $query
                ->where('pending_amount' , '=' , 0)
                ->where(function (Builder $query) {
                    $query->where('for_rent' , true)
                        ->whereNotNull('book_returned_at')
                        ->Orwhere('for_rent' , false);
                });
        }

        return $query;
    }

    /**
     * get all latest purchases
     * @param Builder $query
     * @param null $status
     * @return Builder
     */
    public function scopeLatestPurchases(Builder $query , $status = null): Builder
    {
        if ($status)
            $query = $this->scopeByStatus($query , $status);
        return $query->latest('created_at');
    }

    /**
     * get all purchases not returned and return book due is over.
     * @param Builder $query
     * @return Builder
     */
    public function scopeOfflineBookOverDue(Builder $query): Builder
    {
        return $query
            ->join('books' , 'books.id' , '=' , 'purchases.book_id')
            ->where('books.mode' , '=' , Book::MODE_OFFLINE)
            ->where('for_rent' , true)
            ->where('book_return_due' , '<' , now())
            ->whereNull('book_returned_at')
            ->select('purchases.*');
    }

    /**
     * get all purchases not returned if offline and book due is between given dates.
     * @param Builder $query
     * @param $start
     * @param $end
     * @return Builder
     */
    public function scopeBookDueBetween(Builder $query , $start = null , $end = null): Builder
    {
        if (is_null($start) || is_null($end)) {
            $start = now()->addDay();
            $end = now()->addDays(2);
        }

        return $query
            ->join('books' , 'books.id' , '=' , 'purchases.book_id')
            ->where(function (Builder $query) use ($end , $start) {
                return $query->where('books.mode' , '=' , Book::MODE_OFFLINE)
                    ->where('for_rent' , true)
                    ->whereBetween('book_return_due' , [$start , $end])
                    ->whereNull('book_returned_at');
            })
            ->Orwhere(function (Builder $query) use ($end , $start) {
                return $query
                    ->where('books.mode' , '=' , Book::MODE_ONLINE)
                    ->where('for_rent' , true)
                    ->whereBetween('book_return_due' , [$start , $end]);
            })->select('purchases.*');
    }

    /**
     * get all purchases where payments is not completed and payment due is over.
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
     * get all purchases where payments is not completed and payment due is over between given dates.
     * @param Builder $query
     * @param null $start
     * @param null $end
     * @return Builder
     */
    public function scopePaymentDueBetween(Builder $query , $start = null , $end = null): Builder
    {
        if (is_null($start) || is_null($end)) {
            $start = now()->addDay();
            $end = now()->addDays(2);
        }

        return $query
            ->whereBetween('payment_due' , [$start , $end])
            ->where('pending_amount' , '>' , 0);
    }

    /**
     * Get all payment and book overdue
     * @param Builder $query
     * @return Builder
     */
    public function scopeAllDue(Builder $query): Builder
    {
        return $query
            ->join('books' , 'books.id' , '=' , 'purchases.book_id')
            ->where(function (Builder $query) {
                return $query
                    ->where('books.mode' , '=' , Book::MODE_OFFLINE)
                    ->where('for_rent' , true)
                    ->where('book_return_due' , '<' , now())
                    ->whereNull('book_returned_at')
                    ->select('purchases.*');
            })->OrWhere(function ($query) {
                return $this->scopePaymentOverDue($query);
            });
    }

    /**
     * get all latest purchases
     * @param Builder $query
     * @return Builder
     */
    public function scopeUnpaidPayment(Builder $query): Builder
    {
        return $query
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
     * get all accessible online books for user
     * @param Builder $query
     * @param $userID
     * @return Builder
     */
    public function scopeAccessibleOnlineBooks(Builder $query , $userID): Builder
    {
        return $query
            ->join('books' , 'books.id' , '=' , 'purchases.book_id')
            ->where(function (Builder $query) use ($userID) {
                return $query
                    ->where('purchases.user_id' , $userID)
                    //Book should be online to be accessed
                    ->where('books.mode' , '=' , Book::MODE_ONLINE);
            })
            //Book is accessed through rent or owned and no dues
            ->where(function (Builder $query) {
                return
                    //Owned
                    $query->where(function (Builder $query) {
                        return $query
                            ->where('for_rent' , false)
                            ->where(function (Builder $query) {
                                return $query
                                    //Paid fully and book is owned
                                    ->where('pending_amount' , '=' , 0)
                                    //Owned book, not paid but payment due is not over
                                    ->Orwhere('payment_due' , '>' , now());
                            });
                    })
                        //Rented
                        ->Orwhere(function (Builder $query) {
                            return $query
                                ->where('for_rent' , true)
                                ->where(function (Builder $query) {
                                    return $query
                                        //Return due not over
                                        ->where('book_return_due' , '>' , now())
                                        ->where(function (Builder $query) {
                                            return $query
                                                //Paid fully
                                                ->where('pending_amount' , '=' , 0)
                                                //payment due is not over
                                                ->Orwhere('payment_due' , '>' , now());
                                        });
                                });
                        });
            })
            ->select('purchases.*');
    }

    /**
     * Check if online books is accessible
     * @param Builder $query
     * @param $userID
     * @return Builder
     */
    public function scopeAccessibleOfflineBooks(Builder $query , $userID): Builder
    {
        return $query
            ->join('books' , 'books.id' , '=' , 'purchases.book_id')
            ->where(function (Builder $query) use ($userID) {
                return $query
                    ->where('purchases.user_id' , $userID)
                    //Book should be online to be accessed
                    ->where('books.mode' , '=' , Book::MODE_OFFLINE);
            })
            //Book purchased is through rent or owned
            ->where(function (Builder $query) {
                return $query
                    ->where('for_rent' , false)
                    ->Orwhere(function (Builder $query) {
                        return $query
                            ->where('for_rent' , true)
                            ->whereNull('book_returned_at');
                    });
            })->select('purchases.*');
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
    public
    function toPay()
    {
        return $this->pending_amount > 0;
    }

    /**
     * get payment status
     * @return string
     */
    public
    function getPaymentStatus()
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
    public
    function getPurchaseStatus()
    {
        return $this->isOpen() ? Purchase::STATUS_OPEN : Purchase::STATUS_CLOSE;
    }

    /**
     *
     * Check if purchase is active
     * @return bool
     */
    public
    function isOpen()
    {
        return $this->toPay() || $this->toReturn();
    }
}
