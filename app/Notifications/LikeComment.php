<?php

namespace App\Notifications;

use App\Enums\LikeEnum;
use Illuminate\Bus\Queueable;
use App\Services\NotificationService;
use Illuminate\Notifications\Notification;

class LikeComment extends Notification
{
    use Queueable;

    protected $user;
    protected $comment;
    protected $deviceList;
    private $notificationService;

    /**
     * LikeComment constructor.
     *
     * @param $deviceList
     * @param $user
     * @param $comment
     * @param NotificationService $notificationService
     */
    public function __construct($deviceList, $user, $comment, NotificationService $notificationService)
    {
        $this->user = $user;
        $this->comment = $comment;
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
        $deepLink = config('notifications.base_deeplink').'?type=like_comment$comment_id='.$this->comment->id;
        $count = $this->comment->likes()->whereIsLiked(LikeEnum::STATUS_LIKE)->count() - 1;
        $and = $count > 0 ? ' and '.$count.' other' : '';
        $text = $this->user->name.$and.' '.trans('notification.like_comment_title');
        $data = [
            'topicName' => 'User',
            'title' => 'Like Comment',
            'body' => $text,
            'redirect_to' => $deepLink
        ];
        $this->notificationService->makeQueueJobNotification($this->deviceList, $data);

        return [
            'title' => $text,
            'redirect_to' => $deepLink,
            'body' => [
                'image_user' => $this->user->avatar ?? "https://thuthuatnhanh.com/wp-content/uploads/2019/07/anh-girl-xinh-facebook-tuyet-dep.jpg",
                'time_like' => $this->user->url,
            ]
        ];
    }
}
