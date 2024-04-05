<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateProfileAttribute extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'=> 'sometimes|between:1,255|regex:/^[\w\s.]+$/',
            'last_name'=> 'sometimes|between:1,255|regex:/^[\w\s.]+$/',
            'gender'=> 'sometimes|integer|in:1,2',
            'image_url'=> 'sometimes|url',
            'country'=> 'sometimes|string|regex:/^[A-Z]{2,3}$/',
            'state'=> 'sometimes|string|between:1,255',
            'city'=> 'sometimes|string|between:1,255',
            'addresses'=> 'sometimes|array|size:3',
            'addresses.*.remark'=> 'required|string|max:255',
            'addresses.*.address'=> 'required|string|max:255',
            'preferences'=> 'sometimes|array|min:1',
            'preferences.remember_me'=> 'sometimes|integer|in:0,1',
            'dob'=> 'sometimes|date',
        ];
    }
}
