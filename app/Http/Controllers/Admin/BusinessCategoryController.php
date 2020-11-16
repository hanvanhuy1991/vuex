<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Services\BusinessCategoryService;
use App\Http\Resources\Admin\BusinessCategoryResource;

class BusinessCategoryController extends BaseController
{
    protected $businessCategoryService;

    public function __construct(BusinessCategoryService $businessCategoryService)
    {
        parent::__construct();

        $this->businessCategoryService = $businessCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getListBusinessCategory(Request $request)
    {
        return $this->responseSuccess(BusinessCategoryResource::collection($this->businessCategoryService->getListBusinessCategory()));
    }
}
