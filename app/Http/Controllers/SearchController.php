<?php

namespace App\Http\Controllers;

use App\Enums\SearchEnum;
use App\Services\SearchService;
use App\Http\Resources\PostResource;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\SearchResultRequest;
use App\Http\Resources\SearchBrandResource;
use App\Http\Resources\SearchResultUserResource;
use App\Http\Resources\SearchResultTopicResource;
use App\Http\Resources\ResultSearchSuggestResource;

class SearchController extends BaseController
{
    /**
     * @var $searchService
     */
    protected $searchService;

    /**
     * SearchController constructor.
     *
     * @param SearchService $searchService
     */
    public function __construct(SearchService $searchService)
    {
        parent::__construct();

        $this->searchService = $searchService;
    }

    /**
     * Get search history maximum 8 items
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function histories()
    {
        return $this->responseSuccess($this->searchService->histories());
    }

    /**
     * Clear all search history of auth user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearHistory()
    {
        return $this->responseSuccess($this->searchService->clearHistory());
    }

    /**
     * Get paginate topic by keyword.
     *
     * @param SearchResultRequest $searchResultRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopic(SearchResultRequest $searchResultRequest)
    {
        $keyword = $searchResultRequest->get('keyword');
        $priority = $searchResultRequest->get('priority');
        $response = $this->searchService->getTopic($keyword, $priority);

        return $this->responseSuccess(SearchResultTopicResource::apiPaginate($response, $searchResultRequest));
    }

    /**
     * Get paginate people by keyword.
     *
     * @param SearchResultRequest $searchResultRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPeople(SearchResultRequest $searchResultRequest)
    {
        $keyword = $searchResultRequest->get('keyword');
        $priority = $searchResultRequest->get('priority');
        $response = $this->searchService->getPeople($keyword, $priority);

        return $this->responseSuccess(SearchResultUserResource::apiPaginate($response, $searchResultRequest));
    }

    /**
     * Get user by keyword.
     *
     * @param SearchResultRequest $searchResultRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(SearchResultRequest $searchResultRequest)
    {
        $keyword = $searchResultRequest->get('keyword');
        $priority = $searchResultRequest->get('priority');
        $response = $this->searchService->getUser($keyword, $priority);

        return $this->responseSuccess(SearchResultUserResource::apiPaginate($response, $searchResultRequest));
    }

    /**
     * Get suggestion by keyword.
     *
     * @param SearchRequest $searchRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuggest(SearchRequest $searchRequest)
    {
        $keyword = $searchRequest->get('keyword');
        $response = $this->searchService->getSuggest($keyword);

        return $this->responseSuccess(ResultSearchSuggestResource::collection($response));
    }

    /**
     * Get paginate post by keyword.
     *
     * @param SearchResultRequest $searchResultRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPost(SearchResultRequest $searchResultRequest)
    {
        $keyword = $searchResultRequest->get('keyword');
        $priority = $searchResultRequest->get('priority');
        $response = $this->searchService->getPost($keyword, $priority);

        return $this->responseSuccess(PostResource::apiPaginate($response, $searchResultRequest));
    }

    /**
     * Get brand by keyword.
     *
     * @param SearchRequest $searchRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBrand(SearchRequest $searchRequest)
    {
        $response = $this->searchService->getBrand($searchRequest->get('keyword'));

        return $this->responseSuccess(SearchBrandResource::collection($response));
    }
}
