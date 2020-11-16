<?php

namespace App\Http\Resources;

class UniAllResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
            'width' => (int)$this->thumbnail_width,
            'height' => (int)$this->thumbnail_height,
            'collection_id' => $this->bookmarks->first()->collection_id
        ];
    }
}
