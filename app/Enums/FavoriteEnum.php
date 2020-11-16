<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class FavoriteEnum extends Enum
{
    const
        IS_FAVORITE = 1,
        IS_NOT_FAVORITE = 0;

    const
        TYPE_LIKE = 'like',
        TYPE_BOOKMARK = 'bookmark';
}
