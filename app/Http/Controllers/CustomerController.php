<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of all Customers.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        return view('pages.admin.users.index' , ['type_menu' => 'customers' , 'users' => User::where('type' , 'customer')->orderByDesc('updated_at')->get()]);

    }

    /**
     * Show the form for creating a new customer.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('pages.admin.users.create' , ['type_menu' => 'customers']);
    }

    /**
     * Store a newly created customer in database and profile image is stored in storage correctly.
     *
     * @param \Illuminate\Http\Request $request
     * @return RedirectResponse
     */
    public function store(StoreCustomerRequest $request)
    {
        $input = $request->validated();
        $input['password'] = Hash::make($input['password']);
        $input['profile_image'] = $input['profile_image']->store(config('filesystems.profile_images') , ['disk' => 'public']);
        $user = User::create($input);
        $user->activate();
        $user->makeCustomer();
        $user->save();

        return to_route('customers.index');
    }

    /**
     * Show the form for editing the customer.
     *
     * @param User $customer
     * @return Application|Factory|View
     */
    public function edit(User $customer)
    {
        return view('pages.admin.users.edit' , ['customer' => $customer , 'type_menu' => 'customers'] ,);
    }

    /**
     * Update the specified customer in database and profile image if present.
     *
     * @param UpdateCustomerRequest $request
     * @param User $customer
     * @return RedirectResponse
     */
    public function update(UpdateCustomerRequest $request , User $customer): RedirectResponse
    {
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->address = $request->address;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $this->saveProfileImage($request , $customer);
        $customer->save();

        return to_route('customers.index');
    }

    /**
     * Remove the specified Customer from database and remove his profile image.
     *
     * @param User $customer
     * @return RedirectResponse
     */
    public function destroy(User $customer)
    {
        $customer->deleteOrFail();
        Storage::disk('public')->delete($customer->profile_image);

        return to_route('customers.index');
    }

    /**
     * @param UpdateCustomerRequest $request
     * @param User $customer
     * @return void
     */
    public function saveProfileImage(UpdateCustomerRequest $request , User $customer): void
    {
        $profile_image = $request->profile_image;
        if (!is_null($profile_image)) {
            $customer->profile_image = $profile_image->store(config('filesystems.profile_images') , ['disk' => 'public']);
        }
    }
}
