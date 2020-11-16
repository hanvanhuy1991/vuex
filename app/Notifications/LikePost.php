<?php

namespace App\Notifications;

use App\Enums\LikeEnum;
use Illuminate\Bus\Queueable;
use App\Services\NotificationService;
use Illuminate\Notifications\Notification;

class LikePost extends Notification
{
    use Queueable;

    protected $user;
    protected $post;
    protected $deviceList;
    private $notificationService;

    /**
     * LikePost constructor.
     *
     * @param $deviceList
     * @param $post
     * @param $user
     * @param NotificationService $notificationService
     */
    public function __construct($deviceList, $user, $post, NotificationService $notificationService)
    {
        $this->user = $user;
        $this->post = $post;
        $this->deviceList = $deviceList;
        $this->notificationService = $notificationService;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase()
    {
        $deepLink = config('notifications.base_deeplink').'?type=like_post$post_id='.$this->post->id;
        $count = $this->post->likes()->whereIsLiked(LikeEnum::STATUS_LIKE)->count() - 1;
        $and = $count > 0 ? ' and '.$count.' other' : '';
        $text = $this->user->name.$and.' '.trans('notification.like_post_title');
        $data = [
            'topicName' => 'User',
            'title' => 'Like Post',
            'body' => $text,
            'redirect_to' => $deepLink
        ];
        $this->notificationService->makeQueueJobNotification($this->deviceList, $data);

        return [
            'title' => $text,
            'redirect_to' => $deepLink,
            'body' => [
                'image_user' => $this->user->avatar ?? "https://thuthuatnhanh.com/wp-content/uploads/2019/07/anh-girl-xinh-facebook-tuyet-dep.jpg",
                'thumnail' => $this->post->thumbnail,
                'time_like' => $this->user->url,
            ]
        ];
    }
}
