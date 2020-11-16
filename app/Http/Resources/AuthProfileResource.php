<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthProfileResource extends JsonResource
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
            'name' => $this->name,
            'avatar' => $this->avatar,
            'cover_image' => $this->cover_image,
            'date_of_birth' => $this->birthday,
            'gender' => $this->gender,
            'email' => $this->email,
            'bio' => $this->bio,
            'country_code' => $this->country_code,
            'phone' => $this->phone,
            'address' => $this->address,
            'website' => $this->website,
            'introduction' => $this->description,
            'roles' => RoleResource::collection($this->roles),
            'location_lat' => $this->location_lat,
            'location_long' => $this->location_long
        ];
    }
}
