<?php

namespace App\Notifications;

use App\Enums\LikeEnum;
use Illuminate\Bus\Queueable;
use App\Services\NotificationService;
use Illuminate\Notifications\Notification;

class Follow extends Notification
{
    use Queueable;

    protected $user;
    protected $deviceList;
    private $notificationService;

    /**
     * LikeComment constructor.
     *
     * @param $deviceList
     * @param $user
     * @param NotificationService $notificationService
     */
    public function __construct($deviceList, $user, NotificationService $notificationService)
    {
        $this->user = $user;
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
        $deepLink = config('notifications.base_deeplink').'?type=follow';
        $text = $this->user->name.' '.trans('notification.follow_title');
        $data = [
            'topicName' => 'User',
            'title' => 'Follow',
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
