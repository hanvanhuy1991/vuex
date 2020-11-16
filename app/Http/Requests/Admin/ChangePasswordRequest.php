<?php

namespace App\Http\Requests\Admin;

use App\Rules\SecurePasswordRule;
use App\Rules\CurrentPasswordRule;
use App\Http\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

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
            'current_password' => [
                'required',
                new CurrentPasswordRule(),
            ],
            'new_password' => [
                'required',
                new SecurePasswordRule(),
            ],
            'new_password_confirmation' => 'required|same:new_password',
        ];
    }
}
