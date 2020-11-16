<?php

namespace App\Http\Resources;

class TopicResource extends BaseResource
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
            'category_id' => $this->topic_id,
            'topic_name' => $this->hash_tag_name
        ];
    }
}
