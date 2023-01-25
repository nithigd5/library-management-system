<?php

namespace App\View\Components;

use App\Constants;
use Illuminate\View\Component;

class SessionMessage extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public $message , public $status)
    {
        //
    }

    public function getStatusClass()
    {
        return match ($this->status) {
            Constants::SUCCESS_STATUS => 'success' ,
            Constants::FAILED_STATUS => 'danger' ,
            Constants::WARNING_STATUS => 'warning',
            Constants::INFO_STATUS => 'primary',
            default => 'light'
        };
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (!is_null($this->message))
            return view('components.session-message');
        return '';
    }
}
