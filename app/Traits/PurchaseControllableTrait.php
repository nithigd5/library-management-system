<?php

namespace App\Traits;

use App\Models\Purchase;

trait PurchaseControllableTrait
{
    /**
     *
     * get all purchases based on given filters
     * @param $due
     * @param $type
     * @param $date_range
     * @param $status
     * @param $sort
     * @param $isReturned
     * @param $isPaid
     * @return mixed
     */
    public function getPurchases($due = null , $type = null , $date_range = null , $status = null , $sort = null , $isReturned = null , $isPaid = null): mixed
    {
        $query = Purchase ::with('book' , 'user');

        //Query By Type
        $query = match ($type) {
            'rented' => $query->where('for_rent' , true) ,
            'owned' => $query->where('for_rent' , false) ,
            default => $query
        };

        //Query By Due Date
        $query = match ($due) {
            'all' => $query->offlineBookOverDue()->paymentOverDue() ,
            'book_due' => $query->offlineBookOverDue() ,
            'payment_due' => $query->paymentOverDue() ,
            default => $query
        };

        //Query by Status
        $query = match ($status) {
            'active' => $query->byStatus(Purchase::STATUS_OPEN) ,
            'inactive' => $query->byStatus(Purchase::STATUS_CLOSE) ,
            default => $query
        };


        $query = match ($isReturned) {
            '1' => $query->where('for_rent' , true)->whereNotNull('book_returned_at') ,
            '0' => $query->where('for_rent' , true)->whereNull('book_returned_at') ,
            default => $query
        };

        $query = match ($isPaid) {
            '1' => $query->where('pending_amount' , '=' , 0) ,
            '0' => $query->whereColumn('pending_amount' , '=' , 'price') ,
            '2' => $query->where('pending_amount' , '>' , 0)
                ->whereColumn('pending_amount' , '<' , 'price') ,
            default => $query
        };

        //Sort and filter the result in given date range
        $this->sortAndDateQueryFilter($query , $date_range , $sort);

        return $query;
    }

}
