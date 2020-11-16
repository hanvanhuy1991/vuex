<?php

namespace App\Repositories\Like;

use App\Repositories\BaseRepositoryInterface;

/**
 * Interface LikeRepositoryInterface
 *
 * @package App\Repositories\Like
 */
interface LikeRepositoryInterface extends BaseRepositoryInterface
{
    public function actionLike($id, $type);
    public function getByPostIds($ids);
}
