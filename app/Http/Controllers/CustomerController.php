<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

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
     * @param StoreCustomerRequest $request
     * @return RedirectResponse
     */
    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        if ($user = User::create($this->saveProfileImage($request->all()))) {
            $user->activateAndMakeCustomer();
            return back()->with('message' , __('customer.store.success'))->with('status' , Constants::SUCCESS_STATUS);
        }

        return back()->with('message' , __('customer.store.failed'))->with('status' , Constants::FAILED_STATUS);
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

        if ($customer->update($this->saveProfileImage($request->all()))) {
            return back()->with('message' , __('customer.update.failed'))->with('status' , Constants::SUCCESS_STATUS);
        }
        return back()->with('message' , __('customer.update.success'))->with('status' , Constants::FAILED_STATUS);

    }

    /**
     * Remove the specified Customer from database and remove his profile image.
     *
     * @param User $customer
     * @return RedirectResponse
     */
    public function destroy(User $customer)
    {
        if ($customer->delete()) {
            Storage::disk('public')->delete($customer->profile_image);
            return back()->with('message' , __('customer.delete.success'))->with('status' , Constants::SUCCESS_STATUS);
        }

        return back()->with('message' , __('customer.delete.failed'))->with('status' , Constants::FAILED_STATUS);

    }

    /**
     *
     * generate a invitation link for a customer to generate
     * @return string
     */
    public function invite()
    {
        return URL::temporarySignedRoute(
            'customers.invitations.create-customer' , now()->addMinutes(60)
        );
    }

    /**
     * Show a page for creating a customer if valid signature
     * @param Request $request
     * @return Application|Factory|View
     */
    public function signedCreate(Request $request)
    {
        return view('pages.auth-register', ['url' =>  URL::temporarySignedRoute('customers.invitations.store-customer', now()->addMinutes(15))]);
    }

    /**
     * Verify the signature and call store method
     * @param StoreCustomerRequest $request
     * @return RedirectResponse
     */
    public function signedStore(StoreCustomerRequest $request): RedirectResponse
    {
        if ($user = User::create($this->saveProfileImage($request->all()))) {
            $user->activateAndMakeCustomer();
            return to_route('login')->with('message' , __('customer.register.success'))->with('status' , Constants::SUCCESS_STATUS);
        }

        return back()->with('message' , __('customer.store.failed'))->with('status' , Constants::FAILED_STATUS);
    }

    /**
     * Save a profile image to disk and get a path and set in validated array
     * @param array $validated
     * @return array
     */
    public function saveProfileImage(array $validated): array
    {
        if (array_key_exists('profile_image' , $validated)) {
            $validated['profile_image'] = $validated['profile_image']->store(config('filesystems.profile_images') , ['disk' => 'public']);
        }

        return $validated;
    }
}
