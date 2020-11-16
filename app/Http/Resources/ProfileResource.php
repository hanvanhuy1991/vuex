<?php

namespace App\Http\Resources;

use App\Enums\FollowEnum;
use App\Enums\UserRole;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class ProfileResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $followings = Auth::user()->following()->whereStatus(FollowEnum::STATUS_ACTIVE)->pluck('is_followed_id')->toArray();

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
            'posts_count' => $this->posts_count,
            'followers_count' => $this->followers_count,
            'liked_count' => $this->liked_count,
            'is_business_account' => $this->checkBusinessAccount($this),
            'is_followed' => in_array($this->id, $followings)
        ];
    }
}
