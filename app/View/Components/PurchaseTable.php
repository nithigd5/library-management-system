<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PurchaseTable extends Component
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

    public function getPaymentStatusBadge($paymentStatus)
    {
        return match ($paymentStatus) {
            'pending' => 'danger',
            'half-paid' => 'warning',
            'completed' => 'success'
        };
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.purchase-table');
    }
}
