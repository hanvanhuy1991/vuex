<?php

namespace App\Rules;

use App\Enums\PostEnum;
use Illuminate\Contracts\Validation\Rule;

class TypeShareRule implements Rule
{
    private $type;
    /**
     * Create a new rule instance.
     *
     * @param $type
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
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
        return in_array($this->type, PostEnum::shareTypes());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('post.error.type_share_invalid');
    }
}
