<?php

namespace App\Http\Requests;

use App\Rules\BookmarkRule;
use App\Rules\MoveBookmarkRule;
use Illuminate\Foundation\Http\FormRequest;

class MoveBookmarkRequest extends FormRequest
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
            'collection_id' => ['required', 'integer', 'exists:collections,id', new BookmarkRule(), new MoveBookmarkRule($this->post->id)]
        ];
    }
}
