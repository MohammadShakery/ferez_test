<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class AuthStoreRequest extends FormRequest
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
            'phone' => ['required','regex:/^(0098|\+98)?(9\d{9})$/']
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'فرمت وارد شده تلفن همراه معتبر نمی باشد'
        ];
    }
}
