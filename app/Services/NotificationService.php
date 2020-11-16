<?php

namespace App\Services;

use App\Enums\NotificationEnum;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use App\Jobs\PushNotificationJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\GuzzleException;
use App\Repositories\Notification\NotificationRepositoryInterface;

class NotificationService
{
    /**
     *
     * @var NotificationRepositoryInterface
     */
    private $notificationRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Get notifications with type
     *
     * @param string $type
     *
     * @return builder
     */
    public function getListNotification($type)
    {
        return $this->notificationRepository->getNotificationsForUser($type);
    }
    /**
     * @param [array] $deviceList
     * @param [string] $topicName
     * @param [string] $title
     * @param [string] $body
     *
     * @return true
     */
    public function makeQueueJobNotification($deviceList, $data)
    {
        PushNotificationJob::dispatch('sendBatchNotification', [
            $deviceList,
            $data
        ]);

        return true;
    }

    /**
     * View notification
     *
     * @param string $id
     *
     * @return model
     */
    public function markAsReadNotification($id)
    {
        return $this->notificationRepository->markAsReadNotification($id);
    }

    /**
     * Count notifications
     *
     * @return model
     */
    public function countNotifications()
    {
        $count = $this->notificationRepository->countNotifications();

        return response()->json([
            "status" => Response::HTTP_OK,
            "data" => [
                "like" => $count['like'],
                "comment" => $count['comment'],
                "follow_user" => $count['followUser'],
                "total" => $count['total'],
            ]
        ]);
    }

    /**
     * @param $deviceTokens
     * @param $data
     * @throws GuzzleException
     */
    public function sendBatchNotification($deviceTokens, $data = [])
    {
        self::subscribeTopic($deviceTokens, $data['topicName']);
        self::sendNotification($data, $data['topicName']);
        self::unsubscribeTopic($deviceTokens, $data['topicName']);
    }

    /**
     * @param $data
     * @param $topicName
     * @throws GuzzleException
     */
    public function sendNotification($data, $topicName = null)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $data = [
            'to' => '/topics/' . $topicName,
            'notification' => [
                'body' => $data['body'] ?? 'Something',
                'title' => $data['title'] ?? 'Something',
                'image' => $data['image'] ?? null,
            ],
            'data' => [
                'url' => $data['url'] ?? null,
                'redirect_to' => $data['redirect_to'] ?? null,
            ],
            'apns' => [
                'payload' => [
                    'aps' => [
                        'mutable-content' => 1,
                    ],
                ],
                'fcm_options' => [
                    'image' => $data['image'] ?? null,
                ],
            ],
        ];

        $this->execute($url, $data);
    }

    /**
     * @param $deviceTokens
     * @param $topicName
     * @throws GuzzleException
     */
    public function subscribeTopic($deviceTokens, $topicName = null)
    {
        $url = 'https://iid.googleapis.com/iid/v1:batchAdd';
        $data = [
            'to' => '/topics/' . $topicName,
            'registration_tokens' => $deviceTokens,
        ];

        $this->execute($url, $data);
    }

    /**
     * @param $deviceTokens
     * @param $topicName
     * @throws GuzzleException
     */
    public function unsubscribeTopic($deviceTokens, $topicName = null)
    {
        $url = 'https://iid.googleapis.com/iid/v1:batchRemove';
        $data = [
            'to' => '/topics/' . $topicName,
            'registration_tokens' => $deviceTokens,
        ];
        $this->execute($url, $data);
    }

    /**
     * @param $url
     * @param array $dataPost
     * @param string $method
     * @return bool
     * @throws GuzzleException
     */
    private function execute($url, $dataPost = [], $method = 'POST')
    {
        $result = false;
        try {
            $client = new Client();
            $result = $client->request($method, $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=' . config('key.fcm_server_key'),
                ],
                'json' => $dataPost,
                'timeout' => 300,
            ]);

            $result = $result->getStatusCode() == Response::HTTP_OK;
        } catch (Exception $e) {
            Log::debug($e);
        }

        return $result;
    }

    /**
     * Notification Setting
     *
     * @param $data
     *
     * @return mixed
     */
    public function postSetting($data)
    {
        return Auth::user()->notificationSettings()->updateOrCreate([], [
            'like' => isset($data['like']) ? (int)$data['like'] : NotificationEnum::STATUS_ACTIVE,
            'comment' => isset($data['comment']) ? (int)$data['comment'] : NotificationEnum::STATUS_ACTIVE,
            'follow' => isset($data['follow']) ? (int)$data['follow'] : NotificationEnum::STATUS_ACTIVE,
            'message' => isset($data['message']) ? (int)$data['message'] : NotificationEnum::STATUS_ACTIVE,
        ]);
    }
}
