<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required'] ,
            'image'       => ['required','file','max:3000','mimes:jpg,png,jpeg'] ,
            'parent_id'   => ['exists:categories,id'] ,
            'sort'        => ['gt:0','lte:50']
        ];
    }
}
