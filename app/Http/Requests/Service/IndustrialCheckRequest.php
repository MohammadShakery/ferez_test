<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class IndustrialCheckRequest extends FormRequest
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
            'brand'   => ['required','string','max:50'] ,
            'image'   => ['required','file','image','mimes:png,jpg,jpeg,gif','max:3000'] ,
            'category_id' => ['required','exists:categories,id']
        ];
    }
}
