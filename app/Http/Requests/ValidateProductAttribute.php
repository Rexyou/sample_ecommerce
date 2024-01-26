<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateProductAttribute extends FormRequest
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
            'name'=> 'required|between:1,255|regex:/^[\w\d\s\.\[\]\{\}\,]+$/',
            'icon_image_url'=> 'nullable|url',
            'banner_image_url'=> 'nullable|url',
            'brand_id'=> 'required|integer',
            'type'=> 'required|integer',
            'sizing'=> 'required|array',
            'sizing.width'=> 'required|numeric',
            'sizing.height'=> 'required|numeric',
            'sizing.depth'=> 'required|numeric',
            'description'=> 'required|between:1,255',
            'created_by'=> 'required|integer',
            'selling_status'=> 'required|integer',
            'display_order'=> 'required|integer',
        ];
    }
}
