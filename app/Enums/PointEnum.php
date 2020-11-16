<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PointEnum extends Enum
{
    const
        POINT_LIKE = 1,
        POINT_VIEW = 1,
        POINT_BOOKMARK = 5,
        POINT_SHARE = 5,
        POINT_FOLLOW = 10,
        POINT_REPORT_COMMENT = -3,
        POINT_REPORT_POST = -5,
        POINT_REPORT_ACCOUNT = -20,
        POINT_HASH_TAG = 1;
}
