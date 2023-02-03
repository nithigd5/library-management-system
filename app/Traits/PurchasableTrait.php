<?php

namespace App\Traits;

use App\Models\Book;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait PurchasableTrait
{
    /**
     * Check if book should be returned based on if it is rented
     * @return boolean
     */
    public function toReturn()
    {
        return $this->book->mode == Book::MODE_OFFLINE && $this->for_rent && is_null($this->book_returned_at);
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
