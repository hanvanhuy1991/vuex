<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TopicService;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Resources\StoreTopicResource;
use App\Http\Resources\HashTagResource;
use Symfony\Component\HttpFoundation\Response;

class TopicController extends BaseController
{
    /**
     * @var topicService
     */
    private $topicService;

    /**
     * @param TopicService
     */
    public function __construct(TopicService $topicService)
    {
        parent::__construct();

        $this->topicService = $topicService;
    }

    /**
     * Display a hot Ranking of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  $this->responseSuccess(HashTagResource::collection($this->topicService->hotRanking()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTopicRequest $request)
    {
        $response = $this->topicService->store($request->all());

        if (isset($response)) {
            return  $this->responseSuccess(new StoreTopicResource($response));
        }

        return $this->responseError([
            "message" => trans('common.category_not_found')
        ], Response::HTTP_NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

     /**
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        return $this->responseSuccess(StoreTopicResource::apiPaginate($this->topicService->search($request->keyword), $request));
    }

    /**
     *
     * Search topic with categoryid and keyword
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $id
     *
     * @return \Illuminate\Http\Response
     */
    public function searchTopicWithCategory(Request $request, $categoryId)
    {
        return $this->responseSuccess(StoreTopicResource::apiPaginate($this->topicService->searchTopicWithCategory($request->keyword, $categoryId), $request));
    }
}
