<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateProductOptionAttribute extends FormRequest
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
            'product_options'=> 'required|array',
            'product_options.*.product_id'=> 'required|integer',
            'product_options.*.category'=> 'required|string|between:1,100|regex:/^[a-z]+$/',
            'product_options.*.name'=> 'required|string|between:1,100|regex:/^[\d\w\s]+$/',
            'product_options.*.created_by'=> 'required|integer',
            'product_options.*.display_order'=> 'required|integer',
        ];
    }
}
