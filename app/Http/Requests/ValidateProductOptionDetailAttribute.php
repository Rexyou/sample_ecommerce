<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateProductOptionDetailAttribute extends FormRequest
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
            'product_option_details'=> 'required|array',
            'product_option_details.*.product_id'=> 'required|integer',
            'product_option_details.*.options'=> 'nullable|array',
            'product_option_details.*.quantity'=> 'required|integer|nullable',
            'product_option_details.*.original_price'=> 'required|decimal:2,8|nullable',
            'product_option_details.*.member_price'=> 'required|decimal:2,8|nullable',
            'product_option_details.*.sale_price'=> 'sometimes|decimal:2,8|nullable',
            'product_option_details.*.sale_member_price'=> 'sometimes|decimal:2,8|nullable',
        ];
    }
}
