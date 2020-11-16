<?php

namespace App\Repositories\Notification;

use Carbon\Carbon;
use App\Models\Notification;
use App\Enums\NotificationEnum;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\DatabaseManager;

/**
 * Class CategoryRepository
 *
 * @package App\Repositories\Topic
 */
class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    /**
     * @var Notification
     */
    protected $model;

    /**
     * @var DatabaseManager
     */
    private $db;

    /**
     * NotificationRepository constructor.
     *
     * @param Notification $model
     * @param DatabaseManager $db
     */
    public function __construct(Notification $model, DatabaseManager $db)
    {
        parent::__construct($model);

        $this->db = $db;
    }

    /**
     * Get notifications with type
     *
     * @param string $type
     *
     * @return builder
     */
    public function getNotificationsForUser($type)
    {
        $type == 'like' ? $typeNotification = NotificationEnum::LIKE_POST : ($type == 'comment' ? $typeNotification = NotificationEnum::COMMENT_POST : $typeNotification = NotificationEnum::FOLLOW_USER);
        $column = $this->model->fillable;
        $column[] = 'id as uuid';
        $notifications = $this->model->select($column)->whereNotifiableId(Auth::user()->id)->whereNotifiableType(NotificationEnum::MODEL_USER)->whereType($typeNotification);
        $updatenotifications = $notifications->update([
            'view_at' => Carbon::now()
        ]);

        return $notifications->orderBy('created_at', 'DESC');
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
        $noti = $this->model->find($id);
        if ($noti) {
            return $noti->update([
                'read_at' => Carbon::now(),
                'view_at' => Carbon::now()
            ]);
        }

        return false;
    }

    /**
     * Count notification
     *
     * @return array
     */
    public function countNotifications()
    {
        $query = $this->model->whereNotifiableId(Auth::user()->id)->whereNotifiableType(NotificationEnum::MODEL_USER)->whereNull('view_at');
        $countNotification['like'] = $query->whereType(NotificationEnum::LIKE_POST)->get()->count();
        $countNotification['comment'] = $query->whereType(NotificationEnum::COMMENT_POST)->get()->count();
        $countNotification['followUser'] = $query->whereType(NotificationEnum::FOLLOW_USER)->get()->count();
        $countNotification['total'] = $countNotification['like'] + $countNotification['comment'] + $countNotification['followUser'];

        return $countNotification;
    }
}
