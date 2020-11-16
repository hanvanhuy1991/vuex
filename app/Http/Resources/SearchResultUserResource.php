<?php

namespace App\Http\Resources;

use App\Enums\FollowEnum;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

class SearchResultUserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $authUser = Auth::user();
        $followings = $authUser->following()->whereStatus(FollowEnum::STATUS_ACTIVE)->pluck('is_followed_id')->toArray();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'is_business_account' => $this->checkBusinessAccount($this),
            'total_followers' => (int)$this->followers_count,
            'is_followed' => in_array($this->id, $followings)
        ];
    }
}
