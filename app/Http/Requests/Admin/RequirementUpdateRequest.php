<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RequirementUpdateRequest extends FormRequest
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
            'title' => ['max:240'] ,
            'image' => ['mimes:png,gif,jpeg,jpg'] ,
            'description' => ['max:2000'] ,
            'contact' => ['max:2000'] ,
            'requirement_category_id' => ['exists:requirement_categories,id'],
            'status' => ['in:0,1']

        ];
    }
}
