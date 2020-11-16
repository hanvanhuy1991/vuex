<?php

namespace App\Http\Resources;

use App\Enums\StatisticEnum;

class EngagementResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $views = (int)$this->view;
        $type = in_array($request->type, StatisticEnum::types()) ? $request->type : StatisticEnum::TYPE_ALL;
        switch ($type) {
            case StatisticEnum::TYPE_LIKE:
                $engagements = [$this->likes_count];
                break;
            case StatisticEnum::TYPE_COMMENT:
                $engagements = [$this->comments_count];
                break;
            case StatisticEnum::TYPE_SHARE:
                $engagements = [$this->shares_count];
                break;
            case StatisticEnum::TYPE_VIEW:
                $engagements = [$views];
                break;
            default:
                $engagements = [$this->likes_count, $this->comments_count, $this->shares_count, $views];
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
            'engagement' => array_sum($engagements)
        ];
    }
}
