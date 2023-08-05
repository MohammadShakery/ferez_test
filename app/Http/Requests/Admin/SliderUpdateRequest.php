<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SliderUpdateRequest extends FormRequest
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
            'location' => ['in:main,brands','max:50'] ,
            'image' => ['file','max:3000','mimes:png,jpg,jpeg,gif'] ,
            'link' => ['max:255'] ,
            'priority' => ['in:0,1,2,3,4,5']
        ];
    }
}
