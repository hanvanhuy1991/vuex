<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\PostService;
use App\Services\AccountService;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Admin\PostResource;
use App\Http\Requests\Admin\SearchAccountRequest;
use App\Http\Resources\Admin\AccountListResource;
use App\Http\Resources\Admin\AccountProfileResource;

class AccountController extends BaseController
{
    protected $accountService;
    protected $postService;


    public function __construct(AccountService $accountService, PostService $postService)
    {
        parent::__construct();

        $this->accountService = $accountService;

        $this->postService = $postService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SearchAccountRequest $request)
    {
        return $this->responseSuccess(AccountListResource::apiPaginate($this->accountService->listAccountCms($request->all()), $request));
    }

    /**
     * Show detail account
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return $this->responseSuccess(new AccountProfileResource($this->accountService->getProfileAccount($id)));
    }

     /**
     * Get list post
     *
     * @param Post $post
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListPosts($id, Request $request)
    {
        return $this->responseSuccess(PostResource::apiPaginate($this->postService->getListPostWithAccountCms($id), $request));
    }
}
