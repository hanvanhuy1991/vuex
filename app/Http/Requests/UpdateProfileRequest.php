<?php

namespace App\Http\Requests;

use App\Http\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    use FailedValidation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:128'],
            'phone' => ['string', 'max:16'],
            'country_code' => ['string', 'max:7'],
            'address' => ['string', 'max:256'],
            'url' => ['string', 'max:2083'],
            'description' => ['string', 'max:600'],
            'bio' => ['string', 'max:150'],
            'website' => ['string', 'max:150'],
        ];
    }
}
