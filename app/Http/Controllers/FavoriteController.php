<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Services\FavoriteService;
use App\Http\Resources\FavoriteResource;

class FavoriteController extends BaseController
{
    /**
     * @var FavoriteService
     */
    private $favoriteService;

    /**
     * @param TopicService
     */
    public function __construct(FavoriteService $favoriteService)
    {
        parent::__construct();

        $this->favoriteService = $favoriteService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->responseSuccess(FavoriteResource::collection($this->favoriteService->getFavorite()));
    }
}
