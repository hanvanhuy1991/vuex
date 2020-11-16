<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Services\NotificationService;
use Illuminate\Notifications\Notification;

class CommentPost extends Notification
{
    use Queueable;

    protected $user;
    protected $post;
    protected $deviceList;
    private $notificationService;
    private $title;

    /**
     * LikePost constructor.
     *
     * @param $deviceList
     * @param $user
     * @param $post
     * @param NotificationService $notificationService
     * @param $title
     */
    public function __construct($deviceList, $user, $post, NotificationService $notificationService, $title)
    {
        $this->user = $user;
        $this->post = $post;
        $this->deviceList = $deviceList;
        $this->notificationService = $notificationService;
        $this->title = $title;
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
        $deepLink = config('notifications.base_deeplink').'?type=comment_post$post_id='.$this->post->id;
        $data = [
            'topicName' => 'User',
            'title' => 'Comment Post',
            'body' => $this->title,
            'redirect_to' => $deepLink
        ];
        $this->notificationService->makeQueueJobNotification($this->deviceList, $data);

        return [
            'title' => $this->title,
            'redirect_to' => $deepLink,
            'body' => [
                'image_user' => $this->user->avatar ?? "https://thuthuatnhanh.com/wp-content/uploads/2019/07/anh-girl-xinh-facebook-tuyet-dep.jpg",
                'thumnail' => $this->post->thumbnail,
                'time_like' => $this->user->url,
            ]
        ];
    }
}
