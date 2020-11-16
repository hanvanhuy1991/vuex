<?php

namespace App\Http\Resources;

class StoreTopicResource extends BaseResource
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
            'title' => $this->hash_tag_name,
            'point' => $this->point,
            'category' =>  new CategoryWithTopicResource($this->whenLoaded('topic'))
        ];
    }
}
