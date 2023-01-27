<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class StoreCustomerRequest extends FormRequest
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
            'first_name' => 'required|string|min:3' ,
            'last_name' => 'required|string|min:2' ,
            'email' => 'required|email|unique:users' ,
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()] ,
            'profile_image' => ['required' , Rule::imageFile()->max(2048)] ,
            'address' => 'required|string|min:8' ,
            'phone' => 'required|unique:users,phone|regex:/[6-9][0-9]{9}/'
        ];
    }
}
