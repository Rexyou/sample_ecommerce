<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidatePageSettingAttribute extends FormRequest
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
            'page_name'=> 'required|between:1,255|regex:/^(\w)+$/',
            'component'=> 'required|between:1,255|regex:/^(\w)+$/',
            'image_url'=> 'required|between:1,355|url',
            'redirect_url'=> 'sometimes|between:1,355|url',
            'title'=> 'required|string|between:1,100',
            'description'=> 'sometimes|nullable|string|between:1,255',
            'display_order'=> 'required|integer',
        ];
    }
}
