<?php

namespace App\Http\Resources;

use App\Enums\PostEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class CreatedPostResource extends JsonResource
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
            $this->mergeWhen($this->status == PostEnum::STATUS_PENDING && $this->draftPost, [
                'time_public' => $this->draftPost->time_public ?? null
            ]),
            'created_by' => [
                'id' => $this->created_by,
                'name' => $this->user->name ?? null,
                'role' => $this->user->roles->first()->name ?? null,
            ],
        ];
    }
}
