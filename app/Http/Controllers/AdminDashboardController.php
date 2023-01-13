<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AdminDashboardController extends Controller
{

    /**
     * Admin Dashboard View
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        return view('pages.admin.dashboard', ['type_menu' => 'dashboard']);
    }
}
