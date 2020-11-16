<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PageService;
use App\Http\Resources\PageResource;

class PageController extends BaseController
{
    /**
     * @var PageService
     */
    private $pageService;

    /**
     * @param PageService
     */
    public function __construct(PageService $pageService)
    {
        parent::__construct();

        $this->pageService = $pageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = $this->pageService->getPage($request->type);

        return $this->responseSuccess(new PageResource($response));
    }
}
