<?php

namespace App\Http\Requests\Admin;

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
            'name' => 'required|string|max:128',
            'phone' => 'max:16',
            'gender' => 'required|integer',
            'address' => 'max:10',
            'birthday' => 'date_format:Y-m-d',
        ];
    }
}
