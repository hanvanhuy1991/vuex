<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use App\Http\Resources\BaseResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\Admin\InterestesResource;
use App\Http\Resources\Admin\BusinessCategoryResource;

class AccountProfileResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $now = Carbon::now();
        $created = $this->created_at;
        $followers = $this->whenLoaded('followers');
        $following = $this->whenLoaded('following');
        $bookmarks = $this->whenLoaded('bookmarkPosts');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'created_at' => $created->diffForHumans($now),
            'expiry_date' => $this->expiry_date,
            'point' => $this->point,
            'website' => $this->website,
            'bio' => $this->bio,
            'birthday' => $this->birthday,
            'age' => Carbon::parse($this->birthday)->diff($now)->format('%y'),
            'address' => $this->address,
            'location_long' => $this->location_long,
            'location_lat' => $this->location_lat,
            'description' => $this->description,
            'role' =>  RoleResource::collection($this->whenLoaded('roles')),
            'categories' => BusinessCategoryResource::collection($this->whenLoaded('businessCategories')),
            'interestes' => InterestesResource::collection($this->whenLoaded('interestsTopic')),
            'total_followers' => $followers->count(),
            'total_following' => $following->count(),
            'total_bookmarks' => $bookmarks->count(),
            'status' => $this->status
        ];
    }
}
