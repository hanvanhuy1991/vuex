<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class NotificationEnum extends Enum
{
    const
        SEND_NOTIFICATION = 1,
        DO_NOT_SEND_NOTIFICATION = 0,
        LIKE_POST = 'App\Notifications\LikePost',
        COMMENT_POST = 'App\Notifications\CommentPost',
        FOLLOW_USER = 'App\Notifications\FollowUser',
        MODEL_USER = 'App\Models\User';

    const
        STATUS_ACTIVE = 1,
        STATUS_INACTIVE = 0;
}
