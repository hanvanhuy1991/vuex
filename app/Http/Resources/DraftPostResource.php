<?php

namespace App\Http\Resources;

class DraftPostResource extends BaseResource
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
            'media_type' => $this->media_type,
            'thumbnail' => $this->thumbnail,
            'thumbnail_width' => (int)$this->thumbnail_width,
            'thumbnail_height' => (int)$this->thumbnail_height,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
