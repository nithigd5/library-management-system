<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function profile()
    {
        $user=Auth::user();
        return view('pages.customer.userProfile' ,['type_menu'=>'','user'=>$user]);
    }
}
