<?php

namespace App\Http\Requests;

use App\Models\Book;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed $amount
 */
class PurchaseStoreRequest extends FormRequest
{
    public array $purchase;
    public bool $isRent;

    public User $customer;
    public Book $book;
    private float $maxAmount;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user' => ['required' , Rule::exists('users' , 'id')] ,
            'book' => ['required' , Rule::exists('books' , 'id')] ,
            'amount' => 'required|integer|gte:0'
        ];
    }

    /**
     * Handle the request
     * @throws ValidationException
     */
    public function handle()
    {
        $this->customer = User::find($this->input('user'));

        $this->book = Book::find($this->input('book'));

        $this->isRent = $this->boolean('for_rent');

        $this->purchase = ['user_id' => $this->customer->id , 'book_id' => $this->book->id ,
            'for_rent' => $this->isRent , 'book_issued_at' => now() , 'mode' => Purchase::MODE_OFFLINE ,];

        $this->checkPermissions();

        $this->checkDues();

        $this->checkExistingBook();

        $this->checkAmount();

        $this->checkAmount();

        $this->checkPayment();
    }

    /**
     * Check if permission has appropriate permissions to buy book
     * @throws ValidationException
     */
    public function checkPermissions()
    {
        if ($this->isRent) {

            if (!$this->customer->hasPermissionTo('books.purchase.rent'))

                throw ValidationException::withMessages(['user' => 'User don"t have permission to rent a book.']);

        } else if (!$this->customer->hasPermissionTo('books.purchase.buy')) {

            throw ValidationException::withMessages(['user' => 'User don"t have permission to buy a book.']);

        }
    }

    /**
     * Check if book is online and customer already purchased this book
     * @return void
     * @throws ValidationException
     */
    public function checkDues(): void
    {
        if ($this->customer->dues()->exists()) {
            throw ValidationException::withMessages(['user' => 'User has pending dues.']);
        }
    }

    /**
     * Check if book is online and customer already purchased this book or book is rented already offline
     * @return void
     * @throws ValidationException
     */
    public function checkExistingBook(): void
    {
        if (!Purchase::can($this->book , $this->customer)) {
            throw ValidationException::withMessages(['book' => 'User has already rented the same book and not returned.']);
        }
    }

    /**
     * find maximum amount
     * Check if given data is less than maximum amount
     * @return void
     * @throws ValidationException
     */
    public function checkAmount(): void
    {
        if ($this->isRent) {
            $this->maxAmount = round($this->book->price * config('book.rent_percentage') / 100);
            $this->purchase['book_return_due'] = now()->addDays(config('book.book_return_due_days'));
        } else {
            $this->maxAmount = round($this->book->price);
        }

        if ($this->amount > $this->maxAmount) {
            throw ValidationException::withMessages(['amount' => 'Amount cannot be greater than actual price or rent % amount']);
        }

        $this->purchase['price'] = $this->maxAmount;
    }

    /**
     * check if customer has permission to pay later for purchase if amount is less than maxAmount.
     * @return void
     * @throws ValidationException
     */
    public function checkPayment(): void
    {
        if ($this->amount < $this->maxAmount && !$this->customer->hasPermissionTo('books.purchase.pay.later')) {
            throw ValidationException::withMessages(['customer' => 'User don"t have permission to pay later']);
        }

        $this->purchase['pending_amount'] = $this->maxAmount - $this->amount;

        if ($this->purchase['pending_amount'] > 0) {
            $this->purchase['payment_due'] = now()->addDays(config('book.purchase_due_days'));
        }
    }
}
