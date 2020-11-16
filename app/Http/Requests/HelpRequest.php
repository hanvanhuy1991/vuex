<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HelpRequest extends FormRequest
{
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
            'email' => ['required_without:phone', 'string', 'email', 'max:255'],
            'phone' => ['required_without:email', 'string', 'max:15'],
            'description' => ['required', 'string', 'max:1000'],
        ];
    }
}
