<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UploadFilePostRule implements Rule
{
    protected $image;
    protected $video;

    /**
     * UploadFilePostRule constructor.
     *
     * @param $image
     * @param $video
     */
    public function __construct($image, $video)
    {
        $this->image = $image;
        $this->video = $video;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !($this->image && $this->video);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('post.error.can_not_pass_both_images_and_videos');
    }
}
