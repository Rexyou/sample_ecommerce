<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateUserAttribute extends FormRequest
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
            'username'=> 'sometimes|alpha_num|between:8,12|unique:users,username',
            'email'=> 'sometimes|email:rfc,dns|max:100|unique:users,email',
            'phone'=> 'sometimes|between:10,14|regex:/\+[0-9]{10,14}$/|unique:users,phone',
            'current_password'=> 'required_with:password|string|between:8,16',
            'password'=> 'sometimes|string|between:8,16|confirmed',
            'preferences'=> 'sometimes|array|min:1',
            'preferences.remember_me'=> "sometimes|boolean",
            'preferences.receive_news'=> 'sometimes|boolean',
            'preferences.receive_recommandation'=> 'sometimes|boolean',
        ];
    }
}
