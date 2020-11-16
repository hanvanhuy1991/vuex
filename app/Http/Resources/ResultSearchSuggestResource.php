<?php

namespace App\Http\Resources;

class ResultSearchSuggestResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $isUser = array_key_exists('avatar', $this->resource);

        return [
            'id' => $this['id'],
            'content' => $this['content'],
            'is_user' => $isUser,
            'avatar' => $isUser ? $this['avatar'] : null
        ];
    }
}
