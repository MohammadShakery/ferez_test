<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RequirementCategoryStoreRequest extends FormRequest
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
            'title' => ['required','max:240']
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'عنوان دسته بندی را وارد نمایید',
            'title.max' => 'طول عنوان دسته بندی وارد شده بیش از حد مجاز می باشد',
        ];
    }
}
