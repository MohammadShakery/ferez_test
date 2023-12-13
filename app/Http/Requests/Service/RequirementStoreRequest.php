<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class RequirementStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required','max:240'] ,
            'image' => ['required','mimes:png,gif,jpeg,jpg'] ,
            'description' => ['required','max:2000'] ,
            'contact' => ['required','max:2000'] ,
            'requirement_category_id' => ['required','exists:requirement_categories,id']
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
