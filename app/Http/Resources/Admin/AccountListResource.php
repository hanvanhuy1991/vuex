<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use App\Http\Resources\BaseResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\Admin\BusinessCategoryResource;

class AccountListResource extends BaseResource
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

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'role' =>  RoleResource::collection($this->whenLoaded('roles')),
            'categories' => BusinessCategoryResource::collection($this->whenLoaded('businessCategories')),
            'created_at' => $created->diffForHumans($now),
            'total_followers' => $followers->count(),
            'total_following' => $following->count(),
            'status' => $this->status
        ];
    }
}
