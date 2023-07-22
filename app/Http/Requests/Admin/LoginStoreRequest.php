<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LoginStoreRequest extends FormRequest
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
            'tell'     => ['required'] ,
            'password' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'tell.required'     => 'شماره همراه خود را وارد نمایید' ,
            'password.required' => 'رمز عبور خود را وارد نمایید'
        ];
    }
}
