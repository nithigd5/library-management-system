<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreBookRequest extends FormRequest
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
            'name' => 'required|min:5|unique:books,name',
            'author' => 'required|',
            'price' => 'required|decimal:0,2',
            'version' => 'required|int',
            'mode' => 'required|in:online,offline',
            'book' => ['required_if:mode,online', File::types('application/pdf')],
            'image' => [ 'required', File::image()->max(2048) ],
            'is_download_allowed' => 'required_if:mode,online|boolean',
        ];
    }
}
