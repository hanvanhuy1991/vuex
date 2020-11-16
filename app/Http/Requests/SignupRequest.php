<?php

namespace App\Http\Requests;

use App\Http\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'interested_in' => 'array',
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:6',
                'max:30'
            ],
            'password_confirmation' => 'required',
            'gender' => 'required|integer|min:0|max:2',
            'birthday' => 'nullable|date_format:Y-m-d|before:now',
            'phone' => ['required'],
            'country_code' => ['required', 'max:7'],
        ];
    }
}
