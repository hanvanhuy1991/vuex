<?php

namespace App\Repositories\BusinessCategory;

use App\Repositories\BaseRepositoryInterface;

/**
 * Interface CategoryRepositoryInterface
 *
 * @package App\Repositories\Category
 */
interface BusinessCategoryRepositoryInterface extends BaseRepositoryInterface
{
     /**
     *
     * List business category no paginate
     *
     * @return $collection
     */
    public function getListBusinessCategory();
}
