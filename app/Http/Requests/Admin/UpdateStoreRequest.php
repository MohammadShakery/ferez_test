<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest
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
            'category_id' => ['exists:categories,id'] ,
            'name'        => ['max:50'] ,
            'image'       => ['file','image','max:3000','mimes:png,jpg,jpeg'] ,
            'tell'        => ['max:50'],
            'priority'    => ['integer','lte:10','gte:0']
        ];
    }
}
