<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class MoveBookmarkRule implements Rule
{
    protected $postId;
    /**
     * Create a new rule instance.
     *
     * @param $postId
     *
     * @return void
     */
    public function __construct($postId)
    {
        $this->postId = $postId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $validId = Auth::user()->bookmarks()
            ->wherePostId($this->postId)
            ->value('collection_id');

        return $validId != $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('bookmark.conflict');
    }
}
