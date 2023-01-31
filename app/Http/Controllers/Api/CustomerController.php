<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerCollection;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class CustomerController extends Controller
{
    /**
     * search and return result for select2 frontend
     * @return CustomerCollection
     */
    public function index()
    {
        $customers = $this->search(User::query() , request()->q)->where('type' , 'customer')->paginate(5);

        return new CustomerCollection($customers);
    }

    /**
     * search a term in name and id column
     * @param Builder $query
     * @param string $term
     * @return Builder
     */
    public function search(Builder $query , ?string $term)
    {
        return $term ? $query->where('first_name' , 'LIKE' , "%{$term}%")
            ->orWhere('last_name' , 'LIKE' , "%{$term}%")
            ->orWhere('email' , 'LIKE' , "%{$term}%")
            ->orWhere('id' , $term) : $query;
    }
}
