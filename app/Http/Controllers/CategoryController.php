<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Resources\CategoryResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\CategoryWithTopicResource;

class CategoryController extends BaseController
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @param CategoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        parent::__construct();

        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing Category with topic.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->responseSuccess(CategoryResource::collection($this->categoryService->getCategoryWithTopic($request->hotest)));
    }

    /**
     * Show detail category with topic.
     *
     * @param string id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->categoryService->showCategoryWithId($id);
        if (isset($response)) {
            return $this->responseSuccess(new CategoryResource($response));
        }

        return $this->responseError([
            "message" => trans('common.category_not_found')
        ], Response::HTTP_NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    /**
     *
     * Search Category.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $response = $this->categoryService->search($request->keyword);
        if (!$response->isEmpty()) {
            return $this->responseSuccess(CategoryWithTopicResource::collection($response));
        }

        return $this->responseError([
            "message" => trans('common.category_not_found')
        ], Response::HTTP_NOT_FOUND, Response::HTTP_NOT_FOUND);
    }
}
