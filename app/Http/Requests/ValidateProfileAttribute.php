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
            'addresses'=> 'sometimes|array|max:3',
            'addresses.*.label'=> 'required|string|min:1|max:100|regex:/^[a-zA-Z]+$/',
            'addresses.*.receiver_name'=> 'required|string|between:1,255|regex:/^[\w\s.]+$/',
            'addresses.*.phone'=> 'required|between:10,14|regex:/\+[0-9]{10,14}$/',
            'addresses.*.line_1'=> 'required|string|between:1,155',
            'addresses.*.line_2'=> 'nullable|string|between:1,155',
            'addresses.*.line_3'=> 'nullable|string|between:1,155',
            'addresses.*.main_tag'=> 'nullable|boolean',
            'dob'=> 'sometimes|date',
        ];
    }
}
