<?php

namespace App\Rules;

use App\Enums\CollectionEnum;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class BookmarkRule implements Rule
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
        $validIds = Auth::user()->collections()->whereType(CollectionEnum::BOOKMARK)->pluck('id')->toArray();

        return in_array((int)$value, $validIds);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('bookmark.invalid');
    }
}
