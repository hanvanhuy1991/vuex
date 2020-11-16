<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\BaseResource;

class BusinessCategoryResource extends BaseResource
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
            'code' => $this->id,
            'name' => $this->name,
            'status' => $this->status
        ];
    }
}
