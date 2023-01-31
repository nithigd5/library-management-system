<?php

namespace App\Http\Requests;

use App\Models\BookRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequestUpdateValidation extends FormRequest
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
            'status' => ['required' ,
                Rule::in([BookRequest::STATUS_ACCEPTED , BookRequest::STATUS_PENDING , BookRequest::STATUS_REJECTED])] ,
        ];
    }
}
