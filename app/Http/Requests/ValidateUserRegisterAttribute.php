<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateUserRegisterAttribute extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username'=> 'required|alpha_num|between:8,12|unique:users,username',
            'email'=> 'required|email:rfc,dns|max:100|unique:users,email',
            'phone'=> 'required|between:10,14|regex:/\+[0-9]{10,14}$/|unique:users,phone',
            'password'=> 'required|string|between:8,16|confirmed',
        ];
    }
}
