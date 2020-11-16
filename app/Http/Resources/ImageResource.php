<?php

namespace App\Http\Resources;

class ImageResource extends BaseResource
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
            'image_path' => $this->image_path,
            'width' => $this->width,
            'height' => $this->height,
            'image_tags' => ImageTagResource::collection($this->whenLoaded('imageTags'))
        ];
    }
}
