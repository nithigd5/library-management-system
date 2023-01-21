<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateCustomerRequest extends FormRequest
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
            'email' => ['required' , 'email' , Rule::unique('users' , 'email')->ignore($this->customer->id)] ,
            'profile_image' => [Rule::imageFile()->max(2048)] ,
            'address' => 'required|string|min:8' ,
            'phone' => ['required' , 'regex:/[6-9][0-9]{9}/' , Rule::unique('users' , 'phone')->ignore($this->customer->id)]
        ];
    }
}
