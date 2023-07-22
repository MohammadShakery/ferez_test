<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ModuleStoreRequest extends FormRequest
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
            'name' => ['required','max:50'] ,
            'start_color' => ['required'] ,
            'end_color' => ['required'] ,
            'icon' => ['required','file','mimes:ico,png,jpeg,jpg,svg,gif','max:3000'] ,
            'route' => ['required']
        ];
    }
}
