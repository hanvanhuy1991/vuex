<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use App\Models\Role;
use App\Enums\UserRole;
use App\Http\Resources\BaseResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\VideoResource;
use App\Http\Resources\HashTagResource;

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
        $now = Carbon::now();
        $created = $this->created_at;

        $likes = $this->whenLoaded('likes');
        $bookmarks = $this->whenLoaded('bookmarks');
        $comments = $this->whenLoaded('comments');
        $share = $this->whenLoaded('shares');

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
                'id' => $this->created_by ?? null,
                'name' => $this->user->name ?? null,
                'avatar' => $this->user->avatar ?? null,
                'is_business_account' => $this->checkBusinessAccount($this->user),
            ],
            'created_at' => $created->diffForHumans($now),
            'total_views' => $this->view,
            'total_likes' => $likes->count(),
            'total_bookmarks' => $bookmarks->count(),
            'total_shares' => $share->count(),
            'total_comments' => $comments->count(),
            'media_type' => $this->media_type,
            'thumbnail' => $this->thumbnail,
            'thumbnail_width' => $this->thumbnail_width,
            'thumbnail_height' => $this->thumbnail_height,
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'videos' => VideoResource::collection($this->whenLoaded('videos')),
            'status' => $this->status
        ];
    }
}
