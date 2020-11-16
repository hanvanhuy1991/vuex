<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Http\Resources\LabelReasonResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;

class CommentController extends BaseController
{
    /**
     * @var CommentService
     */
    protected $commentService;

    /**
     * CommentController constructor.
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService)
    {
        parent::__construct();

        $this->commentService = $commentService;
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommentForPost(Post $post, Request $request)
    {
        $query = $this->commentService->getCommentForPost($post->id);

        return $this->responseSuccess(CommentResource::apiPaginate($query, $request));
    }

    /**
     * Create comment
     *
     * @param Post $post
     *
     * @param CommentRequest $commentRequest
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function store(Post $post, CommentRequest $commentRequest)
    {
        $response = $this->commentService->store($post, $commentRequest);

        return $this->responseSuccess(CommentResource::collection($response['data']), $response['status_code'], $response['status_code']);
    }

    public function update(CommentRequest $commentRequest, Comment $comment)
    {
        $response = $this->commentService->update($comment, $commentRequest);

        return $this->responseSuccess(CommentResource::collection($response['data']), $response['status_code'], $response['status_code']);
    }

    public function destroy(Comment $comment)
    {
        return $this->responseSuccess([
            'success' => $this->commentService->destroy($comment)
        ]);
    }

    /**
     * Get Reason Report
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReasonReport()
    {
        return $this->responseSuccess(LabelReasonResource::collection($this->commentService->getReasonReport()));
    }

    /**
     * Action report post
     *
     * @param $comment
     * @param ReportRequest $reportRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionReport(Comment $comment, ReportRequest $reportRequest)
    {
        return $this->responseSuccess([
            'model_id' => $this->commentService->actionReport($comment, $reportRequest->reason_id)
        ]);
    }
}
