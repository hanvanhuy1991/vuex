<?php

namespace App\Repositories\Bookmark;

use App\Repositories\BaseRepositoryInterface;

interface BookmarkRepositoryInterface extends BaseRepositoryInterface
{
    public function store($model, $collectionId);
}
