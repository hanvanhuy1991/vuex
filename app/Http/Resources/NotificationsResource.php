<?php

namespace App\Http\Resources;

class NotificationsResource extends BaseResource
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
            'id' => $this->uuid,
            'type' => $this->type,
            'data' => json_decode($this->data),
            'read_at' => $this->read_at,
            'view_at' => $this->view_at,
            'create_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
