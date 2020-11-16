<?php

namespace App\Repositories\Follow;

use App\Repositories\BaseRepositoryInterface;

/**
 * Interface FollowRepositoryInterface
 *
 * @package App\Repositories\Follow
 */
interface FollowRepositoryInterface extends BaseRepositoryInterface
{
    public function followUser($user);

    public function getByFollowerIds($ids);
}
