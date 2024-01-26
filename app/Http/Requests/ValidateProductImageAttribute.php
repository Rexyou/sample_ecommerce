<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateProductImageAttribute extends FormRequest
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
            'product_images'=> 'required|array',
            'product_images.*.product_id'=> 'required|integer',
            'product_images.*.image_url'=> 'required|url|max:255',
            'product_images.*.created_by'=> 'required|integer',
            'product_images.*.display_order'=> 'required|integer',
        ];
    }
}
