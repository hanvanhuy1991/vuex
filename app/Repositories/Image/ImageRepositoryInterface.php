<?php

namespace App\Repositories\Image;

use App\Repositories\BaseRepositoryInterface;

/**
 * Interface ImageRepositoryInterface
 *
 * @package App\Repositories\Image
 */
interface ImageRepositoryInterface extends BaseRepositoryInterface
{
    public function whereImagePath($path);

    public function getByIds($ids);
}
