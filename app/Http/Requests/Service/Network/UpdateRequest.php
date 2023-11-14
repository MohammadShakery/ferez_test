<?php

namespace App\Http\Requests\Service\Network;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => ['required','max:240']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام شبکه را وارد نمایید' ,
            'name.max' => 'طول نام شما بیش از حد مجاز می باشد'
        ];
    }
}
