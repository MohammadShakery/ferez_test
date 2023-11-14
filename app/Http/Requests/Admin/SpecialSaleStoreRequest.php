<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SpecialSaleStoreRequest extends FormRequest
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
            'images' => ['required'] ,
            'images.*' => ['max:3000','file','mimes:png,jpg,gif,jpeg'] ,
            'price' => ['integer','required'] ,
            'percent' => ['required','gte:1','lte:99'] ,
            'contact' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'عنوان فروش ویژه را وارد نمایید',
            'title.max' => 'طول عنوان فروش ویژه بیش از حد مجاز می باشد',
            'images.required' => 'تصویر یا تصویر های این فروش ویژه را وارد نمایید',
            'images.*.required' => 'تصویر یا تصویر های این فروش ویژه را وارد نمایید',
            'images.*.max' => 'حجم تصویر وارد شده نباید بیش از 3 مگابایت باشد',
            'images.*.mimes' => 'فرمت تصویر وارد شده معتبر نمی باشد',
            'price.required' => 'قیمت این محصول را وارد نمایید',
            'price.integer' => 'قیمت محصول باید عدد باشد',
            'percent.required' => 'درصد تخفیف این محصول را وارد نمایید' ,
            'percent.gte' => 'درصد تخفیف محصول باید بیشتر از 0 باشد',
            'percent.lte' => 'درصد تخفیف محصول باید کمتر از 100 باشد' ,
            'contact.required' => 'اطلاعات تماس خود را وارد نمایید'
        ];
    }
}
