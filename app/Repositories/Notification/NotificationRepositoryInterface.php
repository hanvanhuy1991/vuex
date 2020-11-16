<?php

namespace App\Repositories\Notification;

use App\Repositories\BaseRepositoryInterface;

/**
 * Interface NotificationRepositoryInterface
 *
 * @package App\Repositories\Notification
 */
interface NotificationRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get notifications with type
     *
     * @param string $type
     *
     * @return builder
     */
    public function getNotificationsForUser($type);

    /**
     * View notification
     *
     * @param string $id
     *
     * @return model
     */
    public function markAsReadNotification($id);

    /**
     * Count notification
     *
     * @return array
     */
    public function countNotifications();
}
