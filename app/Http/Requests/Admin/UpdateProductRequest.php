<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => ['max:50'] ,
            'brand_category_id' => ['brand_categories,id'] ,
            'link' => ['max:200'] ,
            'images.*' => ['max:3000','mimes:png,jpg,jpeg','file','image']
        ];
    }
}
