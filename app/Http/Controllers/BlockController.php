<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\BlockService;
use App\Http\Resources\BlockResource;

class BlockController extends BaseController
{
    /**
     * @var BlockService
     */
    protected $blockService;

    /**
     * BlockController constructor.
     *
     * @param BlockService $blockService
     */
    public function __construct(BlockService $blockService)
    {
        parent::__construct();

        $this->blockService = $blockService;
    }

    /**
     * Get paginate user block
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->responseSuccess(BlockResource::apiPaginate($this->blockService->index(), $request));
    }

    /**
     * Block / UnBlock user
     *
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(User $user)
    {
        return $this->responseSuccess(new BlockResource($this->blockService->store($user)));
    }
}
