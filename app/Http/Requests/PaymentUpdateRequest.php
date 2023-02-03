<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PaymentUpdateRequest extends FormRequest
{
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
            'amount' => 'required|integer'
        ];
    }

    /**
     * Check if given amount is not greater than pending amount
     * @return void
     * @throws ValidationException
     */
    public function checkAmount()
    {
        if ($this->purchase->pending_amount < $this->amount)
            throw ValidationException::withMessages(['amount' => 'Payment Amount cannot be greater than pending amount: ' . $this->purchase->pending_amount]);
    }
}
