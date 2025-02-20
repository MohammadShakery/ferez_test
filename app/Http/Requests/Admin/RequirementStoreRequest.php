<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RequirementStoreRequest extends FormRequest
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
            'title' => ['required','max:240'] ,
            'image' => ['required','mimes:png,gif,jpeg,jpg'] ,
            'description' => ['required','max:2000'] ,
            'contact' => ['required','max:2000'] ,
            'requirement_category_id' => ['required','exists:requirement_categories,id'] ,
            'status' => ['required','in:0,1']
        ];
    }

    public function messages()
    {
        return [
            'requirement_category_id.required' => 'لطفا دسته بندی این نیازمندی را وارد نمایید' ,
            'requirement_category_id.exists' => 'دسته بندی انتخاب شده معتبر نمی باشد'
        ];
    }
}
