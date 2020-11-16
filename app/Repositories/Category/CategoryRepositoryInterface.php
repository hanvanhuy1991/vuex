<?php

namespace App\Repositories\Category;

use App\Repositories\BaseRepositoryInterface;

/**
 * Interface CategoryRepositoryInterface
 *
 * @package App\Repositories\Category
 */
interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get All Category with Topic
     *
     * @param string $hotest
     *
     * @return collection
     *
     */
    public function getCategoryWithTopic($hotest);

     /**
     * Search Category with keyword
     *
     * @param string $keyword
     *
     * @return $model
     */
    public function search($keyword);
}
