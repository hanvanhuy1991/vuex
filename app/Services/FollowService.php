<?php

namespace App\Services;

use App\Notifications\Follow;
use App\Repositories\Follow\FollowRepository;
use Illuminate\Support\Facades\Auth;

class FollowService
{
    /**
     * @var FollowRepository
     */
    private $followRepository;

    /**
     * @var FollowRepository
     */
    private $notificationService;

    /**
     * FollowService constructor.
     *
     * @param FollowRepository $followRepository
     * @param NotificationService $notificationService
     */
    public function __construct(
        FollowRepository $followRepository,
        NotificationService $notificationService
    ) {
        $this->followRepository = $followRepository;
        $this->notificationService = $notificationService;
    }

    /**
     * Follow/UnFollow user.
     *
     * @param [model] $user
     *
     * @return array
     */
    public function followUser($user)
    {
        $response =  $this->followRepository->followUser($user);
        if (!empty($response['is_followed']) && !empty(Auth::user()->notificationSettings()->value('follow'))) {
            $devices = $user->tokenDevices->pluck('uuid')->toArray();
            if (!empty($devices)) {
                $user->notify(new Follow($devices, Auth::user(), $this->notificationService));
            }
        }

        return $response;
    }

    /**
     * Remove follow
     *
     * @param $user
     *
     * @return integer
     */
    public function removeFollower($user)
    {
        return $this->followRepository->removeFollower($user);
    }
}
