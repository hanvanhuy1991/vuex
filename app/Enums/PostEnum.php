<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PostEnum extends Enum
{
    const
        QTY_RANDOM_BY_LAST_ACTION = 40,
        QTY_RANDOM_BY_LAST_SEARCH = 10;

    const
        STATUS_ACTIVE = 1,
        STATUS_INACTIVE = 0,
        STATUS_PENDING = 2,
        STATUS_DRAFT = 3;

    const LIMIT = 10;

    const PER_PAGE = 20;

    const
        MEDIA_TYPE_VIDEO = 'video',
        MEDIA_TYPE_IMAGE = 'image';

    const
        FOLLOWING = 'following',
        DISCOVERY = 'discovery',
        NEARBY = 'nearby';

    const
        FIRST = 'first',
        SECOND = 'second';

    const
        SORT_BY_DEFAULT = 'default',
        SORT_BY_LATEST = 'latest',
        SORT_BY_HOT = 'hot',
        SORT_BY_UNICER = 'unicer',
        SORT_BY_BUSINESS = 'business';

    const
        TIME_GIF = 5;

    const
        SHARE_TO_FACEBOOK = 'facebook',
        SHARE_TO_INSTAGRAM= 'instagram',
        SHARE_TO_TWITTER = 'twitter';

    public static function shareTypes()
    {
        return [
            self::SHARE_TO_FACEBOOK,
            self::SHARE_TO_INSTAGRAM,
            self::SHARE_TO_TWITTER,
        ];
    }

    public static function sorts()
    {
        return [
            self::SORT_BY_DEFAULT,
            self::SORT_BY_LATEST,
            self::SORT_BY_HOT,
            self::SORT_BY_UNICER,
            self::SORT_BY_BUSINESS
        ];
    }
}
