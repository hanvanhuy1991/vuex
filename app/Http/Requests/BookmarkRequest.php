<?php

namespace App\Http\Requests;

use App\Enums\FavoriteEnum;
use App\Http\Traits\FailedValidation;
use App\Rules\BookmarkRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BookmarkRequest extends FormRequest
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
        $bookmarked = Auth::user()->bookmarks()->whereIsFavorited(FavoriteEnum::IS_FAVORITE)->pluck('post_id')->toArray();
        $ruleCollections = ['integer', 'exists:collections,id'];
        $isFavorite = !in_array($this->post->id, $bookmarked);
        if ($isFavorite) {
            $ruleCollections[] = 'required';
            $ruleCollections[] = new BookmarkRule();
        }
        $this->merge(['is_favorite' => $isFavorite]);

        return [
            'collection_id' => $ruleCollections
        ];
    }
}
