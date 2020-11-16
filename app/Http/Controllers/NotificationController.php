<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationSettingResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\LikePost;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\NotificationsResource;

class NotificationController extends BaseController
{
    /**
     * @var $notificationService
     */
    private $notificationService;

    /**
     * NotificationController constructor.
     *
     * @param NotificationService $notificationService
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get notifications with type
     *
     * @param request $request
     *
     * @return \Illuminate\Http\Response|Json
     */
    public function index(Request $request)
    {
        return $this->responseSuccess(NotificationsResource::apiPaginate($this->notificationService->getListNotification($request->type), $request));
    }

    /**
     * Test notifications
     *
     * @return \Illuminate\Http\Response|Json
     */
    public function testNotification()
    {
        try {
            $user = User::find(1);
            $post = Post::find(2);
            $deviceList = [
                'f9DKPqKTT5u6nigpSq0bnb:APA91bF-vzkSg3u6XJuEPrguE5zDGaTah0DCdh5iASzLVXsCc8AXHFChH6zLNM-TYKpbg5E5XTTH9GsOqtGmzRxDbU4EZDou1cQrOA5UVX3j3zlu14uDOw8x9CbAwep-Xulyqk-2DTqN',
            ];

            $user->notify((new LikePost($deviceList, $user, $post, $this->notificationService)));

            return $this->responseSuccess([
                'message' => trans('user.send_notification_successfully')
            ]);
        } catch (\Exception $ex) {
            report($ex);

            return false;
        }
    }

    /**
     * View notification
     *
     * @param string $id
     *
     * @return \Illuminate\Http\Response|Json
     */
    public function show($id)
    {
        if ($this->notificationService->markAsReadNotification($id)) {
            return $this->responseSuccess([
                "message" => trans('notification.read_notification_successfully')
            ]);
        }

        return $this->responseError([
            "message" => trans('notification.notification_not_found')
        ], Response::HTTP_NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    /**
     * Count notification
     *
     * @return \Illuminate\Http\Response|Json
     */
    public function countNotifications()
    {
        return $this->notificationService->countNotifications();
        dd($this->notificationService->countNotifications());
    }

    /**
     * Get Notification Setting
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSetting()
    {
        return $this->responseSuccess(new NotificationSettingResource(Auth::user()->notificationSettings));
    }

    /**
     * Post Notification Setting
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSetting(Request $request)
    {
        $data = $request->only(['like', 'comment', 'follow', 'message']);

        return $this->responseSuccess(new NotificationSettingResource($this->notificationService->postSetting($data)));
    }
}
