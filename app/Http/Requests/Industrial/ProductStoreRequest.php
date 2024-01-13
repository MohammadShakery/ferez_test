<?php

namespace App\Http\Requests\Industrial;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','max:50'] ,
            'brand_category_id' => ['required','exists:brand_categories,id'] ,
            'price' => ['required'] ,
            'link' => ['max:200'] ,
            'images' => ['required'] ,
            'images.*' => ['required','file','image','max:3000','mimes:png,jpg,jpeg'] ,
            'multidimensional_view' => ['max:30000']
        ];
    }
}
