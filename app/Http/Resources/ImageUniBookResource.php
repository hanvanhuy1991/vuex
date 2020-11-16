<?php

namespace App\Http\Resources;

class ImageUniBookResource extends BaseResource
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
            'id' => $this->post->id,
            'slug' => $this->post->slug,
            'thumbnail' => $this->post->thumbnail,
            'width' => (int)$this->post->thumbnail_width,
            'height' => (int)$this->post->thumbnail_height,
        ];
    }
}
