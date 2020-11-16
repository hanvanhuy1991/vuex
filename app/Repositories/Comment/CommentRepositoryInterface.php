<?php

namespace App\Repositories\Comment;

use App\Repositories\BaseRepositoryInterface;

/**
 * Interface CommentRepositoryInterface
 * @package App\Repositories\Comment
 */
interface CommentRepositoryInterface extends BaseRepositoryInterface
{
    public function getCommentForPost($postId);
    public function store($postId, $data);
    public function updateBasic($model, $request);
    public function getByPostIds($ids);
}
