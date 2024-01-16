<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateBrandAttribute extends FormRequest
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
            'name'=> 'required|between:1,255|regex:/^[\w\s.]+$/',
            'icon_image_url'=> 'sometimes|between:1,355|url',
            'banner_image_url'=> 'sometimes|between:1,355|url',
            'description'=> 'required|string|between:1,355',
            'founded_year'=> 'required|date_format:Y',
            'social_platform'=> 'required|array|min:1',
            'social_platform.website_url'=> 'sometimes|url',
            'social_platform.facebook_url'=> 'sometimes|url',
            'social_platform.instagram_url'=> 'sometimes|url',
            'social_platform.twitter_url'=> 'sometimes|url',
            'main_brand'=> 'sometimes|integer'
        ];
    }
}
