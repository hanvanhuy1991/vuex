<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Admin\CommentResource;

class CommentController extends BaseController
{
    /**
     * @var CommentService
     */
    protected $commentService;

    /**
     * CommentController constructor.
     *
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService)
    {
        parent::__construct();

        $this->commentService = $commentService;
    }

    /**
     * Get comment of post in cms
     *
     * @param Post $post
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommentForPostCms(Post $post, Request $request)
    {
        $query = $this->commentService->getCommentForPost($post->id);

        return $this->responseSuccess(CommentResource::apiPaginate($query, $request));
    }
}
