<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateBookRequest extends FormRequest
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
            'name' => ['required' , 'min:5' , Rule::unique('books' , 'name')->ignore($this->book->id)] ,
            'author' => 'required' ,
            'price' => 'required|int' ,
            'version' => 'required|int' ,
            'mode' => 'required|in:online,offline' ,
            'book_file' => [
                Rule::requiredIf($this->mode === 'online' && $this->book->mode === 'offline') ,
                File::types('application/pdf') ,
            ] ,
            'image' => [File::image()->max(2048)] ,
            'is_download_allowed' => 'required_if:mode,online|boolean' ,
        ];
    }
}
