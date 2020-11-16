<?php

namespace App\Http\Requests;

use App\Http\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangePasswordRequest extends FormRequest
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
            'current_password' => Rule::requiredIf($this->user()->password),
            'new_password' => [
                'required',
                'string',
                'confirmed',
                'min:6',
                'max:30'
            ],
            'new_password_confirmation' => ['required', 'string'],
        ];
    }
}
