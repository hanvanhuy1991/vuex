<?php

namespace App\Http\Requests;

use App\Http\Traits\FailedValidation;
use App\Rules\SearchWithTypePriorityRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchResultRequest extends FormRequest
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
            'keyword' => ['required', 'string'],
            'priority' => ['required', 'string', new SearchWithTypePriorityRule()]
        ];
    }
}
