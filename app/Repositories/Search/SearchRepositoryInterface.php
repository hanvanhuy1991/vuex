<?php

namespace App\Repositories\Search;

use App\Repositories\BaseRepositoryInterface;

interface SearchRepositoryInterface extends BaseRepositoryInterface
{
    public function histories();

    public function clearHistory();

    public function getBySearchLike($keywords);
}
