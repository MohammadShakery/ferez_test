<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ModuleUpdateRequest extends FormRequest
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
            'start_color' => ['string'] ,
            'end_color' => ['string'] ,
            'icon' => ['file','mimes:ico,png,jpeg,jpg,svg,gif','max:3000']
        ];
    }
}
