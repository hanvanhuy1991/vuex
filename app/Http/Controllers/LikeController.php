<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Enums\LikeEnum;
use App\Models\Comment;
use App\Services\LikeService;

class LikeController extends BaseController
{
    /**
     * @var $likeService
     */
    private $likeService;

    /**
     * LikeController constructor.
     *
     * @param LikeService $likeService
     */
    public function __construct(LikeService $likeService)
    {
        parent::__construct();

        $this->likeService = $likeService;
    }

    /**
     * @param Post $post
     *
     * @return boolean
     */
    public function likePost(Post $post)
    {
        return $this->responseLike($this->likeService->actionLike($post, LikeEnum::MODEL_POST));
    }

    /**
     * @param Comment $comment
     *
     * @return boolean
     */
    public function likeComment(Comment $comment)
    {
        return $this->responseLike($this->likeService->actionLike($comment, LikeEnum::MODEL_COMMENT));
    }

    private function responseLike($response)
    {
        $statusCode = $response['status_code'];
        unset($response['status_code']);

        return $this->responseSuccess($response, $statusCode, $statusCode);
    }
}
