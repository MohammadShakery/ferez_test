<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SpecialSaleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'title' => ['max:240'] ,
            'images.*' => ['max:3000','file','mimes:png,jpg,gif,jpeg'] ,
            'price' => ['integer'] ,
            'percent' => ['gte:1','lte:99'] ,
        ];
    }

    public function messages()
    {
        return [
            'title.max' => 'طول عنوان فروش ویژه بیش از حد مجاز می باشد',
            'images.*.max' => 'حجم تصویر وارد شده نباید بیش از 3 مگابایت باشد',
            'images.*.mimes' => 'فرمت تصویر وارد شده معتبر نمی باشد',
            'price.integer' => 'قیمت محصول باید عدد باشد',
            'percent.gte' => 'درصد تخفیف محصول باید بیشتر از 0 باشد',
            'percent.lte' => 'درصد تخفیف محصول باید کمتر از 100 باشد' ,
        ];
    }
}
