<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateCartAttribute extends FormRequest
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
            'user_id'=> 'required|integer|exists:users,id',
            'order_id'=> 'sometimes|integer|exists:orders,id',
            'product_id'=> 'required|integer|exists:products,id',
            'product_option_detail_id'=> 'required|integer|exists:product_option_details,id',
            'current_price'=> 'required|decimal:2,8',
            'quantity'=> 'required|integer',
        ];
    }
}
