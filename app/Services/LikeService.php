<?php

namespace App\Services;

use App\Enums\LikeEnum;
use App\Notifications\LikePost;
use App\Notifications\LikeComment;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Like\LikeRepositoryInterface;

class LikeService
{
    /**
     * @var $likeRepository
     */
    private $likeRepository;
    private $notificationService;

    /**
     * LikeService constructor.
     * @param LikeRepositoryInterface $likeRepository
     * @param NotificationService $notificationService
     */
    public function __construct(
        LikeRepositoryInterface $likeRepository,
        NotificationService $notificationService
    ) {
        $this->likeRepository = $likeRepository;
        $this->notificationService = $notificationService;
    }

    /**
     * @param $model
     * @param $type
     *
     * @return array
     */
    public function actionLike($model, $type)
    {
        $authUser = Auth::user();
        $users = $model->user;
        $response = $this->likeRepository->actionLike($model, $type);
        if (!empty($response['is_liked']) &&
            $authUser->id != $model->user->id &&
            !empty($users->notificationSettings()->value('like'))
        ) {
            $devices = $users->tokenDevices->pluck('uuid')->toArray();
            if (!empty($devices)) {
                $type == LikeEnum::MODEL_POST ?
                    $users->notify(new LikePost($devices, $authUser, $model, $this->notificationService)) :
                    $users->notify(new LikeComment($devices, $authUser, $model, $this->notificationService));
            }
        }

        return $response;
    }
}
