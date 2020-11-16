<?php

namespace App\Http\Resources;

use App\Enums\PostEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdatedPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'content' => $this->content,
            'current_language' => $this->current_language,
            'location_name' => $this->location_name,
            'location_lat' => $this->location_lat,
            'location_long' => $this->location_long,
            'thumbnail' => $this->thumbnail,
            'thumbnail_width' => $this->thumbnail_width,
            'thumbnail_height' => $this->thumbnail_height,
            'media_type' => $this->media_type,
            'hash_tags' => $this->hashTags->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'hash_tag_name' => $item->hash_tag_name,
                    'point' => $item->point,
                ];
            }),
            'status' => lowercaseStatus(PostEnum::getKey(intval($this->status))),
            'time_public' => $this->status == PostEnum::STATUS_PENDING && $this->draftPost
                ? $this->draftPost->time_public
                : null,
            'updated_by' => [
                'id' => $this->updated_by,
                'name' => $this->updater->name ?? null,
                'role' => $this->updater->roles->first()->name ?? null,
            ],
        ];
    }
}
