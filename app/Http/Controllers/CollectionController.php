<?php

namespace App\Http\Controllers;

use App\Enums\UniEnum;
use App\Http\Requests\UniRequest;
use App\Http\Resources\UniAllResource;
use App\Http\Resources\UniBookResource;
use App\Models\Collection;
use Illuminate\Http\Request;
use App\Services\CollectionService;
use App\Http\Requests\CollectionRequest;
use App\Http\Resources\CollectionResource;

class CollectionController extends BaseController
{
    /**
     * @var $collectionService
     */
    protected $collectionService;

    /**
     * CollectionController constructor.
     *
     * @param CollectionService $collectionService
     */
    public function __construct(CollectionService $collectionService)
    {
        parent::__construct();

        $this->collectionService = $collectionService;
    }

    /**
     * Get paginate collection
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $response = $this->collectionService->index();

        return $this->responseSuccess(CollectionResource::apiPaginate($response, $request));
    }

    /**
     * Create collection
     *
     * @param CollectionRequest $collectionRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CollectionRequest $collectionRequest)
    {
        $response = $this->collectionService->store($collectionRequest->input('name'));

        return $this->responseSuccess($response);
    }

    public function show($collection, Request $request)
    {
        return $this->responseSuccess(UniAllResource::apiPaginate($this->collectionService->show($collection), $request));
    }

    /**
     * Get uni all
     *
     * @param UniRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uniAll(UniRequest $request)
    {
        return $this->responseSuccess(UniAllResource::apiPaginate($this->collectionService->uniAll(), $request));
    }

    /**
     * Get uni book
     *
     * @param UniRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uniBook(UniRequest $request)
    {
        return $this->responseSuccess(UniBookResource::apiPaginate($this->collectionService->uniBook(), $request));
    }
}
