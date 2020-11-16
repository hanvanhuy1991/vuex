<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Auth;

class NotificationSettingResource extends BaseResource
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
            'user_id' => $this->user_id,
            'like' => (boolean)$this->like,
            'comment' => (boolean)$this->comment,
            'follow' => (boolean)$this->follow,
            'message' => (boolean)$this->message,
        ];
    }
}
