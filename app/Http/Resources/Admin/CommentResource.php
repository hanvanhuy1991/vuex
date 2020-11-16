<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use App\Enums\LikeEnum;
use App\Http\Resources\BaseResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\VideoResource;
use App\Http\Resources\MentionResource;

class CommentResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $now = Carbon::now();
        $created = $this->created_at;
        $likes = $this->likes()->whereIsLiked(LikeEnum::STATUS_LIKE);

        return [
            'id' => $this->id,
            'content' => $this->content,
            'users' => [
                'id' => $this->user_id ?? null,
                'name' => $this->user->name ?? null,
                'avatar' => $this->user->avatar ?? null,
            ],
            'created_at' => $created->diffForHumans($now),
            'total_like' => $likes->count(),
            'images' => ImageResource::collection($this->images),
            'videos' => VideoResource::collection($this->videos),
            'mentions' => MentionResource::collection($this->mentions),
            'child_comments' => self::collection($this->childComments),
        ];
    }
}
