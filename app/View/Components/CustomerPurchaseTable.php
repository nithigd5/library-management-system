<?php

namespace App\View\Components;

use App\Models\Purchase;
use Illuminate\View\Component;

class CustomerPurchaseTable extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public $purchases)
    {
        //
    }

    /**
     * get a payment status badge for bootstrap class
     * @param $purchase
     * @return string
     */
    public function getPaymentStatusBadge($purchase)
    {
        return match ($purchase->getPaymentStatus()) {
            Purchase::PAYMENT_PENDING => 'danger',
            Purchase::PAYMENT_HALF_PAID => 'warning',
            Purchase::PAYMENT_COMPLETED => 'success'
        };
    }

    /**
     * get a purchase status badge for bootstrap class
     * @param $purchase
     * @return string
     */
    public function getPurchaseStatusBadge($purchase)
    {
        return $purchase->isOpen() ? 'info' : 'success';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.customer-purchase-table');
    }
}
