<?php

namespace App\Http\Resources;

class ImageTagResource extends BaseResource
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
            'tag_name' => $this->user->name,
            'ratio_width' => $this->ratio_width,
            'ratio_height' => $this->ratio_height,
        ];
    }
}
