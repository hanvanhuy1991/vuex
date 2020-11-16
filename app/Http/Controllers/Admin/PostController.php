<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\PostService;
use Illuminate\Http\Response;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Admin\PostResource;
use App\Http\Resources\Admin\DetailPostResource;
use App\Http\Resources\Admin\AccountResource;
use App\Http\Requests\Admin\SearchPostsRequest;

class PostController extends BaseController
{

    protected $postService;

    public function __construct(PostService $postService)
    {
        parent::__construct();

        $this->postService = $postService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->responseSuccess(PostResource::apiPaginate($this->postService->getPostListCms(), $request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAccountForSearch(Request $request)
    {
        return $this->responseSuccess(AccountResource::collection($this->postService->getAccountForSearch()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchPosts(SearchPostsRequest $request)
    {
        return $this->responseSuccess(PostResource::apiPaginate($this->postService->searchPosts($request->all()), $request));
    }

     /**
     * Show Post Detail.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return $this->responseSuccess(new DetailPostResource($this->postService->showPostsCms($slug)));
    }
}
