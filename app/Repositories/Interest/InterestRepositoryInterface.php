<?php

namespace App\Repositories\Interest;

use App\Repositories\BaseRepositoryInterface;

/**
 * Interface FollowRepositoryInterface
 *
 * @package App\Repositories\Follow
 */
interface InterestRepositoryInterface extends BaseRepositoryInterface
{
    public function getByInterestedInIds($ids);
}
