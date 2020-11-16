<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookmarkRequest;
use App\Http\Requests\MoveBookmarkRequest;
use App\Http\Resources\UniBookResource;
use App\Models\Post;
use App\Services\BookmarkService;
use http\Env\Request;

class BookmarkController extends BaseController
{
    /**
     * @var $bookmarkService
     */
    protected $bookmarkService;

    /**
     * BookmarkController constructor.
     *
     * @param BookmarkService $bookmarkService
     */
    public function __construct(BookmarkService $bookmarkService)
    {
        parent::__construct();

        $this->bookmarkService = $bookmarkService;
    }

    /**
     * Action Bookmark / Unbookmark
     *
     * @param Post $post
     * @param BookmarkRequest $bookmarkRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Post $post, BookmarkRequest $bookmarkRequest)
    {
        return $this->responseSuccess($this->bookmarkService->store($post, $bookmarkRequest));
    }

    /**
     * Move Bookmark
     *
     * @param Post $post
     * @param MoveBookmarkRequest $moveBookmarkRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function move(Post $post, MoveBookmarkRequest $moveBookmarkRequest)
    {
        return $this->responseSuccess(UniBookResource::collection($this->bookmarkService->move($post, $moveBookmarkRequest->collection_id)));
    }
}
