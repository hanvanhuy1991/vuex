<?php

namespace App\Repositories\Share;

use App\Repositories\BaseRepositoryInterface;

interface ShareRepositoryInterface extends BaseRepositoryInterface
{
    public function getByPostIds($ids);
}
