<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FollowService;

class FollowController extends BaseController
{
    /**
     * @var FollowService
     */
    private $followService;

    /**
     * FollowController constructor.
     *
     * @param FollowService $followService
     */
    public function __construct(FollowService $followService)
    {
        $this->followService = $followService;
    }

    /**
     * Follow/UnFollow user.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function followUser(User $user)
    {
        $response = $this->followService->followUser($user);
        $statusCode = $response['status_code'];
        unset($response['status_code']);

        return $this->responseSuccess($response, $statusCode, $statusCode);
    }

    /**
     * Remove user follow
     *
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFollower(User $user)
    {
        return $this->responseSuccess([
            'model_id' => $this->followService->removeFollower($user)
        ]);
    }
}
