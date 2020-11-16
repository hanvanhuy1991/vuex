<?php

namespace App\Repositories\Favorite;

use App\Repositories\BaseRepositoryInterface;

/**
 * Interface FavoriteRepositoryInterface
 *
 * @package App\Repositories\Favorite
 */
interface FavoriteRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get All Favorite
     *
     * @return void
     */
    public function getFavorite();
}
