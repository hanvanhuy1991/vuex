<?php

namespace App\Http\Resources;

use App\Enums\LikeEnum;
use Illuminate\Support\Facades\Auth;

class CommentResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $likes = $this->likes()->whereIsLiked(LikeEnum::STATUS_LIKE);

        return [
            'id' => $this->id,
            'content' => $this->content,
            'users' => [
                'id' => $this->user_id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar,
            ],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'total_like' => $likes->count(),
            'is_liked' => $likes->whereUserId(Auth::id())->exists(),
            'images' => ImageResource::collection($this->images),
            'videos' => VideoResource::collection($this->videos),
            'mentions' => MentionResource::collection($this->mentions),
            'child_comments' => self::collection($this->childComments),
        ];
    }
}
