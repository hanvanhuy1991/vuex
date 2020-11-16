<?php

namespace App\Repositories\Video;

use App\Repositories\BaseRepositoryInterface;

/**
 * Interface VideoRepositoryInterface
 *
 * @package App\Repositories\Video
 */
interface VideoRepositoryInterface extends BaseRepositoryInterface
{
    public function whereVideoPath($path);
}
