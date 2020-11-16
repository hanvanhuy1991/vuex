<?php

namespace App\Http\Resources;

use App\Enums\FollowEnum;
use App\Models\Role;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

class DetailPostResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $followings = $user->following()->whereStatus(FollowEnum::STATUS_ACTIVE)->pluck('is_followed_id')->toArray();
        $likes = $this->whenLoaded('likes');
        $bookmarks = $this->whenLoaded('bookmarks');
        $userIdsLiked = $likes->pluck('user_id')->toArray();
        $userIdsBookmarked = $bookmarks->pluck('user_id')->toArray();
        $isLiked = in_array($userId, $userIdsLiked);
        $isBookmarked = in_array($userId, $userIdsBookmarked);
        $roles = $this->user->roles->pluck('name')->toArray();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'hash_tags' => HashTagResource::collection($this->whenLoaded('hashTags')),
            'title' => $this->title,
            'content' => $this->content,
            'location_name' => $this->location_name,
            'location_lat' => $this->location_lat,
            'location_long' => $this->location_long,
            'users' => [
                'id' => $this->created_by,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar,
                'is_business_account' => $this->checkBusinessAccount($this->user),
                'is_followed' => in_array($this->created_by, $followings)
            ],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'total_views' => $this->view,
            'total_likes' => $likes->count(),
            'total_bookmarks' => $bookmarks->count(),
            'total_shares' => $this->shares_count,
            'total_comments' => $this->comments_count,
            'is_liked' => $isLiked,
            'is_bookmarked' => $isBookmarked,
            'current_language' => $this->current_language,
            'media_type' => $this->media_type,
            'thumbnail' => $this->thumbnail,
            'thumbnail_width' => $this->thumbnail_width,
            'thumbnail_height' => $this->thumbnail_height,
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'videos' => VideoResource::collection($this->whenLoaded('videos'))
        ];
    }
}
