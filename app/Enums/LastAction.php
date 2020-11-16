<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LastAction extends Enum
{
    const
        TYPE_COMMENT = 1,
        TYPE_LIKE = 2,
        TYPE_SHARE = 3,
        TYPE_BOOKMARK = 4,
        TYPE_VIEW = 5;

    const
        MAX_ACTION = 10;
}
