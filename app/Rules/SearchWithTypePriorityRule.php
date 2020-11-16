<?php

namespace App\Rules;

use App\Enums\SearchEnum;
use Illuminate\Contracts\Validation\Rule;

class SearchWithTypePriorityRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, SearchEnum::priorities());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('search.priority');
    }
}
